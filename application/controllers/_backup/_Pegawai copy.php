<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'pegawai') {
            redirect('auth/login'); // Hanya pegawai yang bisa mengakses
        }
    }

public function absen() {
    // Ambil data pegawai berdasarkan sesi login
    $pegawai = $this->db->get_where('pegawai', ['id' => $this->session->id])->row();

    if (!$pegawai) {
        $this->session->set_flashdata('error', 'Data pegawai tidak ditemukan.');
        redirect('pegawai/index');
    }

    $data['title'] = 'Absen Pegawai';
    $data['lokasi'] = $this->db->get_where('lokasi_absensi', ['status' => 1])->result();

    // Ambil shift pegawai berdasarkan jadwal_shift untuk hari ini
    $tanggal_hari_ini = date('Y-m-d');
    $this->load->model('Jadwal_model');
    $jadwal_shift = $this->Jadwal_model->get_shift_hari_ini($pegawai->id, $tanggal_hari_ini);

    $data['shift'] = $jadwal_shift; // Shift untuk hari ini

    // Jika form dikirim
if ($this->input->post()) {
    $latitude = $this->input->post('latitude');
    $longitude = $this->input->post('longitude');
    $foto = $this->upload_foto(); // Foto tidak wajib, sehingga bisa bernilai null
    $jenis_absen = $this->input->post('jenis_absen');
    $lokasi_id = $this->input->post('lokasi_id');

    if (!$jadwal_shift) {
        $this->session->set_flashdata('error', 'Shift Anda untuk hari ini belum diatur.');
        redirect('pegawai/absen');
    }

    // Validasi lokasi dan jarak
    $lokasi = $this->db->get_where('lokasi_absensi', ['id' => $lokasi_id])->row();
    if ($lokasi) {
        $jarak = $this->haversine($latitude, $longitude, $lokasi->latitude, $lokasi->longitude);

        if ($jarak <= $lokasi->range) {
            // Simpan data absensi tanpa memeriksa apakah foto diunggah atau tidak
            $data_absen = [
                'pegawai_id' => $pegawai->id,
                'shift_id' => $jadwal_shift->shift_id,
                'jenis_absen' => $jenis_absen,
                'tanggal' => $tanggal_hari_ini,
                'waktu' => date('H:i:s'),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'lokasi_id' => $lokasi_id,
                'foto' => $foto // Jika foto tidak diunggah, nilai akan null
            ];
            $this->db->insert('absensi', $data_absen);

            // Update rekap absensi
            $this->update_rekap_absensi($pegawai->id, $jadwal_shift->shift_id);

            $this->session->set_flashdata('success', 'Absen berhasil dilakukan!');
        } else {
            $this->session->set_flashdata('error', 'Anda berada di luar area absen! Jarak: ' . round($jarak, 2) . ' meter.');
        }
    } else {
        $this->session->set_flashdata('error', 'Lokasi absensi tidak valid.');
    }
    redirect('pegawai/absen');
}

    // Tampilkan halaman absen
    $this->load->view('templates/header', $data);
    $this->load->view('pegawai/absen', $data);
    $this->load->view('templates/footer');
}


private function update_rekap_absensi($pegawai_id, $shift_id) {
    // Ambil data shift
    $shift = $this->db->get_where('shift', ['id' => $shift_id])->row();

    if (!$shift) {
        return; // Jika shift tidak ditemukan, keluar dari fungsi
    }

    // Ambil data absen pegawai untuk tanggal hari ini
    $tanggal = date('Y-m-d');
    $absensi = $this->db->get_where('absensi', [
        'pegawai_id' => $pegawai_id,
        'tanggal' => $tanggal
    ])->result();

    $jam_masuk = null;
    $jam_pulang = null;

    foreach ($absensi as $absen) {
        if ($absen->jenis_absen == 'masuk' && (!$jam_masuk || $absen->waktu < $jam_masuk)) {
            $jam_masuk = $absen->waktu; // Ambil absen masuk pertama
        }
        if ($absen->jenis_absen == 'pulang' && (!$jam_pulang || $absen->waktu > $jam_pulang)) {
            $jam_pulang = $absen->waktu; // Ambil absen pulang terakhir
        }
    }

    // Hitung keterlambatan
    $terlambat = 0;
    if ($jam_masuk && strtotime($jam_masuk) > strtotime($shift->jam_mulai)) {
        $terlambat = (strtotime($jam_masuk) - strtotime($shift->jam_mulai)) / 60; // Dalam menit
    }

    // Hitung pulang cepat
    $pulang_cepat = 0;
    if ($jam_pulang && strtotime($jam_pulang) < strtotime($shift->jam_selesai)) {
        $pulang_cepat = (strtotime($shift->jam_selesai) - strtotime($jam_pulang)) / 60; // Dalam menit
    }

    // Hitung lama menit kerja
    $lama_menit_kerja = (9 * 60) - $terlambat - $pulang_cepat; // 9 jam dikurangi terlambat dan pulang cepat
    $lama_menit_kerja = max($lama_menit_kerja, 0); // Pastikan tidak negatif

    // Hitung total gaji
    $gaji_per_menit = ($this->db->get_where('pegawai', ['id' => $pegawai_id])->row()->gaji_per_jam ?? 0) / 60;
    $total_gaji = $lama_menit_kerja * $gaji_per_menit;

    // Update atau insert ke rekap_absensi
    $rekap_data = [
        'tanggal' => $tanggal,
        'pegawai_id' => $pegawai_id,
        'shift_id' => $shift_id,
        'jam_masuk' => $jam_masuk ?? '00:00:00',
        'jam_pulang' => $jam_pulang ?? '00:00:00',
        'terlambat' => $terlambat,
        'pulang_cepat' => $pulang_cepat,
        'lama_menit_kerja' => $lama_menit_kerja,
        'total_gaji' => $total_gaji
    ];

    $existing_rekap = $this->db->get_where('rekap_absensi', [
        'tanggal' => $tanggal,
        'pegawai_id' => $pegawai_id
    ])->row();

    if ($existing_rekap) {
        $this->db->update('rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
    } else {
        $this->db->insert('rekap_absensi', $rekap_data);
    }
}


    private function haversine($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earth_radius * $c;
    }

private function upload_foto() {
    if (empty($_FILES['foto']['name'])) {
        return null; // Jika tidak ada file yang diunggah, kembalikan null
    }

    $config['upload_path']   = './uploads/';
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size']      = 2048; // 2MB
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);
    if ($this->upload->do_upload('foto')) {
        return $this->upload->data('file_name');
    } else {
        log_message('error', 'Upload Error: ' . $this->upload->display_errors());
        return null; // Jika ada kesalahan saat mengunggah, tetap kembalikan null
    }
}


}



