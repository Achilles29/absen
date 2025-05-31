<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Periksa apakah pengguna sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        // Periksa role, hanya admin dan spv yang bisa mengakses
        $allowed_roles = ['admin', 'spv'];
        if (!in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Dashboard Admin';
        $this->load->view('templates/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/footer');
    }

    // Halaman master pegawai
public function master_pegawai() {
    $this->db->select('
        abs_pegawai.*, 
        abs_divisi.nama_divisi, 
        j1.nama_jabatan AS jabatan1, 
        j2.nama_jabatan AS jabatan2,
        abs_pegawai.kode_user,
        abs_pegawai.tanggal_kontrak_awal,
        abs_pegawai.durasi_kontrak,
        abs_rekening_bank.nama_bank AS nama_bank,
        abs_pegawai.nomor_rekening
    ');

    $this->db->from('abs_pegawai');
    $this->db->join('abs_divisi', 'abs_pegawai.divisi_id = abs_divisi.id', 'left');
    $this->db->join('abs_jabatan AS j1', 'abs_pegawai.jabatan1_id = j1.id', 'left');
    $this->db->join('abs_jabatan AS j2', 'abs_pegawai.jabatan2_id = j2.id', 'left');
    $this->db->join('abs_rekening_bank', 'abs_pegawai.nama_bank_id = abs_rekening_bank.id', 'left'); // Tambahkan join ke tabel rekening bank
    $this->db->order_by('abs_pegawai.id', 'ASC');

    $data['pegawai'] = $this->db->get()->result();

    $this->load->view('templates/header', $data);
    $this->load->view('admin/pegawai/master_pegawai', $data);
    $this->load->view('templates/footer');
}

public function tambah_pegawai() {
    if ($this->input->post()) {
        $tanggal_kontrak_awal = $this->input->post('tanggal_kontrak_awal');
        $durasi_kontrak = $this->input->post('durasi_kontrak');

        $tanggal_kontrak_akhir = null;
        if (!empty($tanggal_kontrak_awal) && !empty($durasi_kontrak)) {
            $tanggal_kontrak_akhir = date('Y-m-d', strtotime("+$durasi_kontrak months", strtotime($tanggal_kontrak_awal)));
        }

        $gaji_pokok = $this->input->post('gaji_pokok');

        $data = [
            'kode_user' => $this->input->post('kode_user'),
            'nama' => $this->input->post('nama'),
            'divisi_id' => $this->input->post('divisi_id'),
            'jabatan1_id' => $this->input->post('jabatan1_id'),
            'jabatan2_id' => $this->input->post('jabatan2_id') ?: null,
            'gaji_pokok' => $gaji_pokok,
            'gaji_per_jam' => $gaji_pokok / 234,
            'tunjangan' => $this->input->post('tunjangan'),
            'tambahan_lain' => $this->input->post('tambahan_lain'),
            'tanggal_kontrak_awal' => $tanggal_kontrak_awal,
            'durasi_kontrak' => $durasi_kontrak,
            'tanggal_kontrak_akhir' => $tanggal_kontrak_akhir,
            'nama_bank_id' => $this->input->post('nama_bank_id'),
            'nomor_rekening' => $this->input->post('nomor_rekening'),
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
        ];

        $this->db->insert('abs_pegawai', $data);
        $this->session->set_flashdata('success', 'Data pegawai berhasil ditambahkan!');
        redirect('admin/master_pegawai');
    }

    // Ambil data untuk dropdown
    $data['divisi'] = $this->db->get('abs_divisi')->result();
    $data['jabatan'] = $this->db->get('abs_jabatan')->result();
    $data['kode_user'] = $this->db->get('kode_user')->result();
    $data['rekening_bank'] = $this->db->get('abs_rekening_bank')->result(); // Tambahkan pengambilan data bank

    $this->load->view('templates/header');
    $this->load->view('admin/pegawai/tambah_pegawai', $data);
    $this->load->view('templates/footer');
}



public function edit_pegawai($id) {
    if ($this->input->post()) {
        $gaji_pokok = $this->input->post('gaji_pokok');
        $jabatan2_id = $this->input->post('jabatan2_id') ?: null;

        $data = [
            'nama' => $this->input->post('nama'),
            'divisi_id' => $this->input->post('divisi_id'),
            'jabatan1_id' => $this->input->post('jabatan1_id'),
            'jabatan2_id' => $jabatan2_id,
            'gaji_pokok' => $gaji_pokok,
            'gaji_per_jam' => $gaji_pokok ? $gaji_pokok / 234 : 0, // Hindari pembagian nol
            'tunjangan' => $this->input->post('tunjangan') ?: 0,
            'tambahan_lain' => $this->input->post('tambahan_lain') ?: 0,
            'kode_user' => $this->input->post('kode_user'),
            'nomor_rekening' => $this->input->post('nomor_rekening'),
            'nama_bank' => $this->input->post('nama_bank_id'), // ID bank yang dipilih
        ];

        $this->db->where('id', $id)->update('abs_pegawai', $data);
        $this->session->set_flashdata('success', 'Data pegawai berhasil diperbarui!');
        redirect('admin/master_pegawai');
    }

    // Ambil data pegawai dan referensi
    $data['pegawai'] = $this->db->get_where('abs_pegawai', ['id' => $id])->row();
    $data['divisi'] = $this->db->get('abs_divisi')->result();
    $data['jabatan'] = $this->db->get('abs_jabatan')->result();
    $data['kode_users'] = $this->db->get('kode_user')->result();
    $data['rekening_bank'] = $this->db->get('abs_rekening_bank')->result(); // Ambil data bank

    // Load view
    $this->load->view('templates/header');
    $this->load->view('admin/pegawai/edit_pegawai', $data);
    $this->load->view('templates/footer');
}




public function update_pegawai($id) {
    $tanggal_kontrak_awal = $this->input->post('tanggal_kontrak_awal');
    $durasi_kontrak = $this->input->post('durasi_kontrak');

    $tanggal_kontrak_akhir = null;
    if (!empty($tanggal_kontrak_awal) && !empty($durasi_kontrak)) {
        $tanggal_kontrak_akhir = date('Y-m-d', strtotime("+$durasi_kontrak months", strtotime($tanggal_kontrak_awal)));
    }

    $gaji_pokok = $this->input->post('gaji_pokok');

    $data = [
        'kode_user' => $this->input->post('kode_user'),
        'nama' => $this->input->post('nama'),
        'divisi_id' => $this->input->post('divisi_id'),
        'jabatan1_id' => $this->input->post('jabatan1_id'),
        'jabatan2_id' => $this->input->post('jabatan2_id') ?: null,
        'gaji_pokok' => $gaji_pokok,
        'gaji_per_jam' => $gaji_pokok / 234,
        'tunjangan' => $this->input->post('tunjangan'),
        'tambahan_lain' => $this->input->post('tambahan_lain'),
        'tanggal_kontrak_awal' => $tanggal_kontrak_awal,
        'durasi_kontrak' => $durasi_kontrak,
        'tanggal_kontrak_akhir' => $tanggal_kontrak_akhir,
        'nomor_rekening' => $this->input->post('nomor_rekening'),
        'nama_bank_id' => $this->input->post('nama_bank_id'), // Ubah ini
    ];

    $this->db->where('id', $id)->update('abs_pegawai', $data);
    $this->session->set_flashdata('success', 'Data pegawai berhasil diperbarui!');
    redirect('admin/master_pegawai');
}



    public function hapus_pegawai($id) {
        $this->db->delete('abs_pegawai', ['id' => $id]);
        $this->session->set_flashdata('success', 'Pegawai berhasil dihapus!');
        redirect('admin/master_pegawai');
    }

    
public function rekap_absensi_bulanan() {
    $bulan = $this->input->get('bulan', TRUE) ?: date('m'); // Default ke bulan sekarang
    $tahun = $this->input->get('tahun', TRUE) ?: date('Y'); // Default ke tahun sekarang

    // Dropdown bulan
    $data['bulan_dropdown'] = range(1, 12); // 1 sampai 12

    // Dropdown tahun (ambil dari data yang ada di database)
    $data['tahun_dropdown'] = [];
    $start_year = 2020; // Tahun awal
    $end_year = date('Y')+1;; // Tahun sekarang

    for ($year = $start_year; $year <= $end_year; $year++) {
        $data['tahun_dropdown'][] = (object) ['tahun' => $year];
    }


    // Ambil data rekap absensi dengan urutan yang diminta
    $this->db->select('
        abs_pegawai.kode_user, 
        abs_pegawai.id AS pegawai_id, 
        abs_pegawai.nama, 
        abs_pegawai.divisi_id, 
        abs_pegawai.jabatan1_id, 
        SUM(abs_rekap_absensi.terlambat) AS total_terlambat, 
        SUM(abs_rekap_absensi.pulang_cepat) AS total_pulang_cepat, 
        SUM(abs_rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
        SUM(abs_rekap_absensi.total_gaji) AS total_gaji
    ');
    $this->db->from('abs_rekap_absensi');
    $this->db->join('abs_pegawai', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id', 'left');
    $this->db->where("MONTH(abs_rekap_absensi.tanggal) =", $bulan);
    $this->db->where("YEAR(abs_rekap_absensi.tanggal) =", $tahun);
    $this->db->where('abs_pegawai.nama IS NOT NULL'); // Hanya tampilkan nama yang tidak kosong
    $this->db->where('abs_pegawai.nama !=', '');     // Hanya tampilkan nama yang tidak kosong
    $this->db->group_by('abs_rekap_absensi.pegawai_id');
    $this->db->order_by('abs_pegawai.divisi_id', 'ASC');
    $this->db->order_by('abs_pegawai.jabatan1_id', 'ASC');
    $this->db->order_by('abs_pegawai.id', 'ASC');

    $data['rekap_bulanan'] = $this->db->get()->result();

    // Variabel tambahan untuk view
    $data['title'] = 'Rekapitulasi Absensi Bulanan';
    $data['bulan_terpilih'] = $bulan;
    $data['tahun_terpilih'] = $tahun;

    $this->load->view('templates/header', $data);
    $this->load->view('admin/rekap_absensi_bulanan', $data);
    $this->load->view('templates/footer');
}


public function rekap_absensi() {
    $bulan = $this->input->get('bulan') ?: date('Y-m');
    $this->db->select('
        abs_pegawai.id AS pegawai_id, 
        abs_pegawai.nama, 
        SUM(abs_rekap_absensi.terlambat) AS total_terlambat, 
        SUM(abs_rekap_absensi.pulang_cepat) AS total_pulang_cepat, 
        SUM(abs_rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
        SUM(abs_rekap_absensi.total_gaji) AS total_gaji
    ');
    $this->db->from('abs_rekap_absensi');
    $this->db->join('abs_pegawai', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id');
    $this->db->where('DATE_FORMAT(abs_rekap_absensi.tanggal, "%Y-%m")', $bulan);
    $this->db->where('abs_pegawai.kode_user !=', 'nonaktif'); // Filter nonaktif
    $this->db->group_by('abs_rekap_absensi.pegawai_id');
    $this->db->order_by('abs_pegawai.id', 'ASC');
    
    $data['rekap_bulanan'] = $this->db->get()->result();

    $data['title'] = 'Rekapitulasi Absensi Pegawai';
    $this->load->view('templates/header', $data);
    $this->load->view('admin/rekap_absensi_bulanan', $data);
    $this->load->view('templates/footer');
}


public function detail_rekap_absensi($pegawai_id) {
    // Ambil bulan dari input GET atau gunakan bulan sekarang
    $bulan = $this->input->get('bulan', TRUE) ?: date('Y-m');

    // Ambil data bulan unik dari abs_rekap_absensi untuk dropdown
    $data['bulan_dropdown'] = $this->db
        ->select("DISTINCT(DATE_FORMAT(tanggal, '%Y-%m')) AS bulan", FALSE)
        ->from('abs_rekap_absensi')
        ->get()
        ->result();

    // Query untuk detail rekap absensi
    $this->db->select('abs_rekap_absensi.tanggal, abs_pegawai.nama AS nama_pegawai, abs_shift.kode_shift, 
                       abs_rekap_absensi.jam_masuk, abs_rekap_absensi.jam_pulang, 
                       abs_rekap_absensi.terlambat, abs_rekap_absensi.pulang_cepat, 
                       abs_rekap_absensi.lama_menit_kerja, abs_rekap_absensi.total_gaji');
    $this->db->from('abs_rekap_absensi');
    $this->db->join('abs_pegawai', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id', 'left');
    $this->db->join('abs_shift', 'abs_rekap_absensi.shift_id = abs_shift.id', 'left');
    $this->db->where('abs_rekap_absensi.pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(abs_rekap_absensi.tanggal, '%Y-%m') =", $bulan); // Filter bulan
    $this->db->order_by('abs_rekap_absensi.tanggal', 'ASC');

    $data['rekap_harian'] = $this->db->get()->result();
    $data['title'] = 'Detail Rekap Absensi Pegawai';
    $data['bulan_terpilih'] = $bulan;

    $this->load->view('templates/header', $data);
    $this->load->view('admin/detail_rekap_absensi', $data);
    $this->load->view('templates/footer', $data);
}

public function detail_absensi($pegawai_id) {
    // Ambil data abs_absensi berdasarkan ID pegawai
    $data['detail'] = $this->db
        ->select('abs_absensi.*, abs_pegawai.nama')
        ->from('abs_absensi')
        ->join('abs_pegawai', 'abs_absensi.pegawai_id = abs_pegawai.id')
        ->where('abs_pegawai.id', $pegawai_id)
        ->order_by('abs_absensi.tanggal', 'DESC')
        ->get()
        ->result();

    // Ambil informasi pegawai
    $data['pegawai'] = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();

    $data['title'] = 'Detail Absensi Pegawai';
    $this->load->view('templates/header', $data);
    $this->load->view('admin/detail_absensi', $data);
    $this->load->view('templates/footer', $data);
}

private function upload_foto() {
    $config['upload_path'] = './uploads/';
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size'] = 2048; // Maksimum ukuran 2MB
    $config['encrypt_name'] = TRUE; // Beri nama unik untuk file

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('foto')) {
        return $this->upload->data('file_name');
    } else {
        // Log error jika terjadi masalah
        log_message('error', $this->upload->display_errors());
        return null;
    }
}


public function generate_rekap_absensi() {
    // Ambil tanggal hari ini
    $today = date('Y-m-d');

    // Periksa apakah data untuk hari ini sudah di-generate
    $existing_rekap_today = $this->db->where('tanggal', $today)->count_all_results('rekap_absensi');
    if ($existing_rekap_today > 0) {
        $this->session->set_flashdata('info', 'Rekap absensi untuk hari ini sudah di-generate. Tidak ada perubahan.');
        redirect('admin/rekap_absensi_bulanan');
        return;
    }

    // Ambil semua pegawai
    $pegawai_list = $this->db->get('pegawai')->result();

    foreach ($pegawai_list as $pegawai) {
        // Ambil absen masuk dan keluar pegawai hari ini
        $absensi = $this->db
            ->select('MIN(CASE WHEN absensi.jenis_absen = "masuk" THEN waktu END) AS jam_masuk,
                      MAX(CASE WHEN absensi.jenis_absen = "pulang" THEN waktu END) AS jam_pulang,
                      shift.jam_mulai, shift.jam_selesai, absensi.shift_id')
            ->from('absensi')
            ->join('shift', 'absensi.shift_id = shift.id', 'left')
            ->where('absensi.pegawai_id', $pegawai->id)
            ->where('absensi.tanggal', $today)
            ->group_by('absensi.pegawai_id')
            ->get()
            ->row();

        // Default values
        $jam_masuk_shift = $jam_pulang_shift = 0;
        $terlambat = $pulang_cepat = $lama_menit_kerja = $total_gaji = 0;

        // Jika ada data absensi
        if ($absensi) {
            // Waktu shift
            $jam_masuk_shift = !empty($absensi->jam_mulai) ? strtotime($absensi->jam_mulai) : 0;
            $jam_pulang_shift = !empty($absensi->jam_selesai) ? strtotime($absensi->jam_selesai) : 0;

            // Jam masuk dan jam pulang
            $jam_absen_masuk = !empty($absensi->jam_masuk) ? strtotime($absensi->jam_masuk) : 0;
            $jam_absen_pulang = !empty($absensi->jam_pulang) ? strtotime($absensi->jam_pulang) : $jam_absen_masuk;

            // Hitung terlambat
            $terlambat = ($jam_absen_masuk > $jam_masuk_shift) ? ($jam_absen_masuk - $jam_masuk_shift) / 60 : 0;

            // Hitung pulang cepat
            $pulang_cepat = ($jam_pulang_shift > $jam_absen_pulang) ? ($jam_pulang_shift - $jam_absen_pulang) / 60 : 0;

            // Hitung lama kerja: 9 jam dikurangi menit terlambat dan pulang cepat
            $lama_menit_kerja = max(0, (9 * 60) - $terlambat - $pulang_cepat);
        } else {
            // Jika tidak ada absen, jam masuk dan jam pulang diisi dengan 0
            $jam_absen_masuk = 0;
            $jam_absen_pulang = 0;
        }

        // Hitung gaji per menit
        $gaji_per_menit = 0;
        $pegawai_data = $this->db->select('gaji_per_jam')->from('pegawai')->where('id', $pegawai->id)->get()->row();
        if ($pegawai_data) {
            $gaji_per_menit = $pegawai_data->gaji_per_jam / 60;
            $total_gaji = $lama_menit_kerja * $gaji_per_menit;
        }

        // Data untuk rekap_absensi
        $data = [
            'tanggal' => $today,
            'pegawai_id' => $pegawai->id,
            'shift_id' => !empty($absensi->shift_id) ? $absensi->shift_id : null,
            'jam_masuk' => $jam_absen_masuk ? date('H:i:s', $jam_absen_masuk) : null,
            'jam_pulang' => $jam_absen_pulang ? date('H:i:s', $jam_absen_pulang) : null,
            'terlambat' => round($terlambat, 2),
            'pulang_cepat' => round($pulang_cepat, 2),
            'lama_menit_kerja' => round($lama_menit_kerja, 2),
            'total_gaji' => round($total_gaji, 2),
        ];

        // Insert ke tabel rekap_absensi
        $this->db->insert('rekap_absensi', $data);
    }

    $this->session->set_flashdata('success', 'Rekap absensi berhasil diproses untuk tanggal ' . $today);
    redirect('admin/rekap_absensi_bulanan');
}

public function log_absensi() {
    $bulan = $this->input->get('bulan') ?? date('m'); // Default ke bulan ini
    $tahun = $this->input->get('tahun') ?? date('Y'); // Default ke tahun ini
    $this->load->model('Pegawai_model');

    // Ambil data pegawai selain admin
    $pegawai_ids = array_map(function($pegawai) {
        return $pegawai->id;
    }, $this->Pegawai_model->get_all_pegawai_except_admin());

    if (empty($pegawai_ids)) {
        $data['rekap_absensi'] = [];
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['title'] = 'Log Absensi Rekap';
        $this->load->view('templates/header', $data);
        $this->load->view('admin/log_absensi', $data);
        $this->load->view('templates/footer');
        return;
    }

    // Query untuk daftar pegawai dengan total absensi yang valid (lama kerja > 0)
    $this->db->select('abs_pegawai.id, abs_pegawai.nama, 
                       IFNULL(COUNT(CASE WHEN abs_rekap_absensi.lama_menit_kerja > 0 THEN 1 END), 0) AS total_absensi, 
                       IFNULL(SUM(abs_rekap_absensi.terlambat), 0) AS total_terlambat, 
                       IFNULL(SUM(abs_rekap_absensi.pulang_cepat), 0) AS total_pulang_cepat, 
                       IFNULL(SUM(abs_rekap_absensi.lama_menit_kerja), 0) AS total_lama_kerja, 
                       IFNULL(SUM(abs_rekap_absensi.total_gaji), 0) AS total_gaji');
    $this->db->from('abs_pegawai');
    $this->db->join('abs_rekap_absensi', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id AND MONTH(abs_rekap_absensi.tanggal) = "'.$bulan.'" AND YEAR(abs_rekap_absensi.tanggal) = "'.$tahun.'"', 'left');
    $this->db->where('abs_pegawai.kode_user !=', 'nonaktif'); // Jangan tampilkan pegawai tipe nonaktif
//    $this->db->where_in('abs_pegawai.id', $pegawai_ids); // Hanya pegawai dengan kode user selain admin
    $this->db->group_by('abs_pegawai.id');
    $this->db->order_by('abs_pegawai.id', 'ASC'); // Urutkan berdasarkan ID Pegawai

    $data['rekap_absensi'] = $this->db->get()->result();

    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['title'] = 'Log Absensi Rekap';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/log_absensi', $data);
    $this->load->view('templates/footer');
}

public function detail_log_absensi($pegawai_id) {
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default ke bulan ini

    // Ambil data pegawai untuk menampilkan nama
    $data['pegawai'] = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();

    // Query untuk mengambil log absensi per pegawai
    $this->db->select('abs_rekap_absensi.tanggal, abs_pegawai.nama, abs_rekap_absensi.jam_masuk, abs_rekap_absensi.jam_pulang, 
                       abs_rekap_absensi.terlambat, abs_rekap_absensi.pulang_cepat, abs_rekap_absensi.lama_menit_kerja, 
                       abs_rekap_absensi.total_gaji');
    $this->db->from('abs_rekap_absensi');
    $this->db->join('abs_pegawai', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id');
    $this->db->where('abs_rekap_absensi.pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(abs_rekap_absensi.tanggal, '%Y-%m') =", $bulan);
    $this->db->order_by('abs_rekap_absensi.tanggal', 'ASC');
    $data['log_absensi'] = $this->db->get()->result();

    $data['bulan'] = $bulan;
    $data['title'] = 'Detail Log Absensi';
    $this->load->view('templates/header', $data);
    $this->load->view('admin/detail_log_absensi', $data);
    $this->load->view('templates/footer');
}


public function log_absensi_detail($pegawai_id) {
    // Ambil bulan dan tahun dari URL atau default ke bulan ini
    $bulan = $this->input->get('bulan') ?? date('m');
    $tahun = $this->input->get('tahun') ?? date('Y');

    // Ambil data pegawai berdasarkan ID
    $data['pegawai'] = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();

    // Query semua log absensi per pegawai dengan filter bulan dan tahun
    $this->db->select('abs_absensi.tanggal, abs_absensi.waktu, abs_absensi.latitude, abs_absensi.longitude, 
                       abs_absensi.jenis_absen, abs_shift.kode_shift, abs_absensi.foto');
    $this->db->from('abs_absensi');
    $this->db->join('abs_shift', 'abs_absensi.shift_id = abs_shift.id', 'left');
    $this->db->where('abs_absensi.pegawai_id', $pegawai_id);
    $this->db->where('MONTH(abs_absensi.tanggal)', $bulan);
    $this->db->where('YEAR(abs_absensi.tanggal)', $tahun);
    $this->db->order_by('abs_absensi.tanggal', 'ASC');

    $data['log_absensi'] = $this->db->get()->result();
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['title'] = 'Detail Log Absensi';

    // Load View
    $this->load->view('templates/header', $data);
    $this->load->view('admin/log_absensi_detail', $data);
    $this->load->view('templates/footer');
}


// public function laporan_gaji() {
//     // Ambil filter tanggal dari input GET
//     $start_date = $this->input->get('start_date') ?? date('Y-m-01'); // Default awal bulan
//     $end_date = $this->input->get('end_date') ?? date('Y-m-t'); // Default akhir bulan

//     // Validasi range tanggal
//     if (strtotime($start_date) > strtotime($end_date)) {
//         $this->session->set_flashdata('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
//         redirect('admin/laporan_gaji');
//     }

//     // Query untuk laporan gaji pegawai
//     $this->db->select('
//         abs_pegawai.id, 
//         abs_pegawai.nama, 
//         abs_pegawai.tunjangan, 
//         SUM(CASE WHEN abs_rekap_absensi.lama_menit_kerja > 0 THEN 1 ELSE 0 END) AS total_kehadiran, 
//         SUM(abs_rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
//         COALESCE(SUM(abs_rekap_absensi.total_gaji), 0) AS total_gaji, 
//         (
//             SELECT COALESCE(SUM(abs_lembur.total_gaji_lembur), 0)
//             FROM abs_lembur
//             WHERE abs_lembur.pegawai_id = abs_pegawai.id
//             AND abs_lembur.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
//         ) AS total_gaji_lembur,
//         (
//             SELECT COALESCE(SUM(abs_tambahan_lain.nilai_tambahan), 0)
//             FROM abs_tambahan_lain
//             WHERE abs_tambahan_lain.pegawai_id = abs_pegawai.id
//             AND abs_tambahan_lain.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
//         ) AS total_tambahan_lain,
//         COALESCE(abs_pegawai.tambahan_lain, 0) AS tambahan_master,
//         (
//             SELECT COALESCE(SUM(abs_potongan.nilai), 0)
//             FROM abs_potongan
//             WHERE abs_potongan.pegawai_id = abs_pegawai.id
//             AND abs_potongan.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
//         ) AS total_potongan,
//         (
//             SELECT COALESCE(SUM(abs_deposit.nilai), 0)
//             FROM abs_deposit
//             WHERE abs_deposit.pegawai_id = abs_pegawai.id
//             AND abs_deposit.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
//         ) AS total_deposit,
//         (
//             SELECT COALESCE(SUM(abs_kasbon.nilai), 0) 
//             FROM abs_kasbon 
//             WHERE abs_kasbon.pegawai_id = abs_pegawai.id 
//             AND abs_kasbon.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
//             AND abs_kasbon.jenis = "bayar"
//         ) AS total_kasbon
//     ');

//     $this->db->from('abs_pegawai');
//     $this->db->join('abs_rekap_absensi', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id 
//                     AND abs_rekap_absensi.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'', 'left');
//     $this->db->where('abs_pegawai.kode_user !=', 'admin'); // Jangan tampilkan pegawai tipe admin
//     $this->db->group_by('abs_pegawai.id');
//     $this->db->order_by('abs_pegawai.id', 'ASC');

//     // Debug query untuk validasi (opsional, hapus jika tidak perlu)
//     // echo '<pre>'; print_r($this->db->get_compiled_select()); exit;

//     $data['laporan_gaji'] = $this->db->get()->result();
//     $data['start_date'] = $start_date;
//     $data['end_date'] = $end_date;
//     $data['title'] = 'Laporan Gaji Pegawai';

//     $this->load->view('templates/header', $data);
//     $this->load->view('admin/laporan_gaji', $data);
//     $this->load->view('templates/footer');
// }
public function laporan_gaji() {
    // Ambil filter tanggal dari input GET
    $start_date = $this->input->get('start_date') ?? date('Y-m-01'); // Default awal bulan
    $end_date = $this->input->get('end_date') ?? date('Y-m-t'); // Default akhir bulan

    // Validasi range tanggal
    if (strtotime($start_date) > strtotime($end_date)) {
        $this->session->set_flashdata('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
        redirect('admin/laporan_gaji');
    }

    // Query untuk laporan gaji pegawai
    $this->db->select('
        abs_pegawai.id, 
        abs_pegawai.nama, 
        abs_pegawai.tunjangan, 
        COUNT(abs_rekap_absensi.id) AS total_kehadiran, 
        SUM(abs_rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
        COALESCE(SUM(abs_rekap_absensi.total_gaji), 0) AS total_gaji, 
        (
            SELECT COALESCE(SUM(abs_lembur.total_gaji_lembur), 0)
            FROM abs_lembur
            WHERE abs_lembur.pegawai_id = abs_pegawai.id
            AND abs_lembur.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
        ) AS total_gaji_lembur,
        (
            SELECT COALESCE(SUM(abs_tambahan_lain.nilai_tambahan), 0)
            FROM abs_tambahan_lain
            WHERE abs_tambahan_lain.pegawai_id = abs_pegawai.id
            AND abs_tambahan_lain.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
        ) AS total_tambahan_lain,
        COALESCE(abs_pegawai.tambahan_lain, 0) AS tambahan_master,
        (
            SELECT COALESCE(SUM(abs_potongan.nilai), 0)
            FROM abs_potongan
            WHERE abs_potongan.pegawai_id = abs_pegawai.id
            AND abs_potongan.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
        ) AS total_potongan,
(
    SELECT 
        COALESCE(SUM(CASE WHEN abs_deposit.jenis = \'tarik\' THEN abs_deposit.nilai ELSE 0 END), 0) -
        COALESCE(SUM(CASE WHEN abs_deposit.jenis = \'setor\' THEN abs_deposit.nilai ELSE 0 END), 0)
    FROM abs_deposit
    WHERE abs_deposit.pegawai_id = abs_pegawai.id
    AND abs_deposit.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
) AS total_deposit,

        (
            SELECT COALESCE(SUM(abs_kasbon.nilai), 0) 
            FROM abs_kasbon 
            WHERE abs_kasbon.pegawai_id = abs_pegawai.id 
            AND abs_kasbon.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
            AND abs_kasbon.jenis = "bayar"
        ) AS total_kasbon
    ');

    $this->db->from('abs_pegawai');
    $this->db->join('abs_rekap_absensi', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id 
                    AND abs_rekap_absensi.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'', 'left');
    $this->db->where('abs_pegawai.kode_user !=', 'nonaktif'); // Jangan tampilkan pegawai tipe admin
    $this->db->group_by('abs_pegawai.id');
    $this->db->order_by('abs_pegawai.id', 'ASC');

    $data['laporan_gaji'] = $this->db->get()->result();
    $data['start_date'] = $start_date;
    $data['end_date'] = $end_date;
    $data['title'] = 'Laporan Gaji Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/laporan_gaji', $data);
    $this->load->view('templates/footer');
}

public function detail_laporan_gaji($pegawai_id) {
    // Ambil range tanggal dari GET parameter atau gunakan default
    $start_date = $this->input->get('start_date') ?? date('Y-m-01');
    $end_date = $this->input->get('end_date') ?? date('Y-m-t');

    // Validasi range tanggal
    if (strtotime($start_date) > strtotime($end_date)) {
        $this->session->set_flashdata('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
        redirect('admin/laporan_gaji');
    }

    // Query detail laporan gaji dengan filter range tanggal
    $this->db->select('
        abs_rekap_absensi.tanggal,
        abs_rekap_absensi.jam_masuk,
        abs_rekap_absensi.jam_pulang,
        abs_rekap_absensi.lama_menit_kerja,
        abs_rekap_absensi.total_gaji,
        COALESCE(lembur.total_gaji_lembur, 0) AS total_gaji_lembur,
        COALESCE(abs_tambahan_lain.nilai_tambahan, 0) AS tambahan_lain,
        COALESCE(abs_potongan.nilai, 0) AS potongan,
        COALESCE(abs_deposit.nilai, 0) AS deposit,
        COALESCE(abs_kasbon.total_bayar, 0) AS kasbon_bayar,
        abs_shift.kode_shift AS shift
    ');

    $this->db->from('abs_rekap_absensi');

    // Subquery untuk Lembur
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(total_gaji_lembur) AS total_gaji_lembur 
                      FROM abs_lembur 
                      WHERE tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
                      GROUP BY tanggal, pegawai_id) AS lembur', 
                      'lembur.pegawai_id = abs_rekap_absensi.pegawai_id AND lembur.tanggal = abs_rekap_absensi.tanggal', 
                      'left');

    // Subquery untuk Tambahan Lain
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(nilai_tambahan) AS nilai_tambahan 
                      FROM abs_tambahan_lain 
                      WHERE tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
                      GROUP BY tanggal, pegawai_id) AS abs_tambahan_lain',
                      'abs_tambahan_lain.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_tambahan_lain.tanggal = abs_rekap_absensi.tanggal',
                      'left');

    // Subquery untuk Potongan
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(nilai) AS nilai 
                      FROM abs_potongan 
                      WHERE tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
                      GROUP BY tanggal, pegawai_id) AS abs_potongan',
                      'abs_potongan.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_potongan.tanggal = abs_rekap_absensi.tanggal',
                      'left');

    // Subquery untuk Deposit (tarik - setor)
    $this->db->join("(
        SELECT tanggal, pegawai_id, 
            SUM(CASE WHEN jenis = 'tarik' THEN nilai ELSE 0 END) -
            SUM(CASE WHEN jenis = 'setor' THEN nilai ELSE 0 END) AS nilai
        FROM abs_deposit
        WHERE tanggal BETWEEN '$start_date' AND '$end_date'
        GROUP BY tanggal, pegawai_id
    ) AS abs_deposit", 
    "abs_deposit.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_deposit.tanggal = abs_rekap_absensi.tanggal", 'left');


    // Subquery untuk Kasbon Jenis Bayar
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(nilai) AS total_bayar 
                      FROM abs_kasbon 
                      WHERE jenis = "bayar" AND tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
                      GROUP BY tanggal, pegawai_id) AS abs_kasbon',
                      'abs_kasbon.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_kasbon.tanggal = abs_rekap_absensi.tanggal',
                      'left');

    $this->db->join('abs_shift', 'abs_rekap_absensi.shift_id = abs_shift.id', 'left');
    $this->db->where('abs_rekap_absensi.pegawai_id', $pegawai_id);
    $this->db->where('abs_rekap_absensi.tanggal >=', $start_date);
    $this->db->where('abs_rekap_absensi.tanggal <=', $end_date);
    $this->db->group_by(['abs_rekap_absensi.tanggal', 'abs_rekap_absensi.pegawai_id']);
    $this->db->order_by('abs_rekap_absensi.tanggal', 'ASC');

    // Data untuk View
    $data['detail_gaji'] = $this->db->get()->result();
    $data['pegawai'] = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();
    $data['start_date'] = $start_date;
    $data['end_date'] = $end_date;
    $data['title'] = 'Detail Laporan Gaji Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/detail_laporan_gaji', $data);
    $this->load->view('templates/footer');
}

public function absen_pegawai() {
    if ($this->input->post()) {
        $pegawai_id = $this->input->post('pegawai_id');
        $shift_id = $this->input->post('shift_id');
        $jenis_absen = $this->input->post('jenis_absen');
        $tanggal = $this->input->post('tanggal') ?? date('Y-m-d'); // Tanggal bisa dipilih, default hari ini
        $waktu = $this->input->post('waktu') ?? date('H:i:s');

        // Simpan data absensi
        $data_absen = [
            'pegawai_id' => $pegawai_id,
            'shift_id' => $shift_id,
            'jenis_absen' => $jenis_absen,
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'latitude' => null, // Tidak diperlukan
            'longitude' => null,
            'foto' => null
        ];

        $this->db->insert('abs_absensi', $data_absen);

        // Update rekap absensi
        $this->update_rekap_absensi($pegawai_id, $shift_id, $tanggal);

        $this->session->set_flashdata('success', 'Absensi berhasil disimpan.');
        redirect('admin/absen_pegawai');
    }

    // Ambil data pegawai dan shift untuk form
    $data['pegawai'] = $this->db->get('abs_pegawai')->result();
    $data['shift'] = $this->db->get('abs_shift')->result();
    $data['title'] = 'Absen Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/absen_pegawai', $data);
    $this->load->view('templates/footer');
}


private function update_rekap_absensi($pegawai_id, $shift_id, $tanggal) {
    // Ambil data pegawai untuk mendapatkan informasi jabatan dan gaji pokok
    $pegawai = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();

    if (!$pegawai) {
        return; // Jika data pegawai tidak ditemukan, keluar dari fungsi
    }

    // Ambil data shift
    $shift = $this->db->get_where('abs_shift', ['id' => $shift_id])->row();

    // Default data absensi
    $jam_masuk = null;
    $jam_pulang = null;
    $terlambat = 0;
    $pulang_cepat = 0;
    $lama_menit_kerja = 0;
    $total_gaji = 0;

    // Logika khusus untuk jabatan SECURITY
    if ($pegawai->jabatan1_id == 10) { // Asumsi 10 adalah ID jabatan SECURITY
        // Hitung gaji harian (gaji pokok dibagi 30 hari)
        $total_gaji = round($pegawai->gaji_pokok / 30, 2);
    } else {
        if ($shift) {
            // Ambil data absensi pegawai untuk tanggal tertentu
            $absensi = $this->db->select('
                MIN(CASE WHEN jenis_absen = "masuk" THEN waktu END) AS jam_masuk,
                MAX(CASE WHEN jenis_absen = "pulang" THEN waktu END) AS jam_pulang
            ')
            ->from('abs_absensi')
            ->where('pegawai_id', $pegawai_id)
            ->where('tanggal', $tanggal)
            ->group_by('pegawai_id')
            ->get()
            ->row();

            $jam_masuk = $absensi->jam_masuk ?? null;
            $jam_pulang = $absensi->jam_pulang ?? null;

            // Hitung keterlambatan
            if ($jam_masuk && strtotime($jam_masuk) > strtotime($shift->jam_mulai)) {
                $terlambat = (strtotime($jam_masuk) - strtotime($shift->jam_mulai)) / 60;
            }

            // Hitung pulang cepat
            if ($jam_pulang && strtotime($jam_pulang) < strtotime($shift->jam_selesai)) {
                $pulang_cepat = (strtotime($shift->jam_selesai) - strtotime($jam_pulang)) / 60;
            }

            // Hitung lama kerja
            $lama_menit_kerja = (9 * 60) - $terlambat - $pulang_cepat;
            $lama_menit_kerja = max($lama_menit_kerja, 0);

            // Hitung total gaji berdasarkan lama kerja
            $gaji_per_menit = ($pegawai->gaji_per_jam ?? 0) / 60;
            $total_gaji = $lama_menit_kerja * $gaji_per_menit;
        }
    }

    // Data untuk update abs_rekap_absensi
    $rekap_data = [
        'tanggal' => $tanggal,
        'pegawai_id' => $pegawai_id,
        'shift_id' => $shift_id,
        'jam_masuk' => $jam_masuk ?? '00:00:00',
        'jam_pulang' => $jam_pulang ?? '00:00:00',
        'terlambat' => round($terlambat, 2),
        'pulang_cepat' => round($pulang_cepat, 2),
        'lama_menit_kerja' => round($lama_menit_kerja, 2),
        'total_gaji' => round($total_gaji, 2)
    ];

    // Periksa apakah data rekap sudah ada
    $existing_rekap = $this->db->get_where('abs_rekap_absensi', [
        'pegawai_id' => $pegawai_id,
        'tanggal' => $tanggal
    ])->row();

    if ($existing_rekap) {
        $this->db->update('abs_rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
    } else {
        $this->db->insert('abs_rekap_absensi', $rekap_data);
    }
}

public function generate_rekap_absensi_harian() {
    $today = date('Y-m-d'); // Tanggal hari ini

    // Ambil semua pegawai kecuali tipe admin
    $pegawai_list = $this->db->where('kode_user !=', 'nonaktif')->get('abs_pegawai')->result();

    foreach ($pegawai_list as $pegawai) {
        // Cek apakah sudah ada data abs_rekap_absensi untuk hari ini
        $rekap_exist = $this->db->get_where('abs_rekap_absensi', [
            'pegawai_id' => $pegawai->id,
            'tanggal' => $today
        ])->row();

        // Jika belum ada, insert data kosong
        if (!$rekap_exist) {
            $data_insert = [
                'pegawai_id' => $pegawai->id,
                'tanggal' => $today,
                'shift_id' => null,            // Kosongkan shift
                'jam_masuk' => null,           // Kosongkan jam masuk
                'jam_pulang' => null,          // Kosongkan jam pulang
                'terlambat' => 0,              // Default 0
                'pulang_cepat' => 0,           // Default 0
                'lama_menit_kerja' => 0,       // Default 0
                'total_gaji' => 0              // Default 0
            ];
            $this->db->insert('abs_rekap_absensi', $data_insert);
        }
    }

    echo "Rekap absensi harian berhasil dibuat untuk tanggal: " . $today;
}

public function master_kode_user() {
    $data['kode_users'] = $this->db->get('kode_user')->result();
    $this->load->view('templates/header');
    $this->load->view('admin/kode_user/master_kode_user', $data);
    $this->load->view('templates/footer');
}

public function tambah_kode_user() {
    if ($this->input->post()) {
        $data = [
            'kode_user' => $this->input->post('kode_user')
        ];
        $this->db->insert('kode_user', $data);
        $this->session->set_flashdata('success', 'Kode user berhasil ditambahkan!');
        redirect('admin/master_kode_user');
    }

    $this->load->view('templates/header');
    $this->load->view('admin/kode_user/tambah_kode_user');
    $this->load->view('templates/footer');
}

public function edit_kode_user($id) {
    if ($this->input->post()) {
        $data = [
            'kode_user' => $this->input->post('kode_user')
        ];
        $this->db->where('id', $id)->update('kode_user', $data);
        $this->session->set_flashdata('success', 'Kode user berhasil diperbarui!');
        redirect('admin/master_kode_user');
    }

    $data['kode_user'] = $this->db->get_where('kode_user', ['id' => $id])->row();
    $this->load->view('templates/header');
    $this->load->view('admin/kode_user/edit_kode_user', $data);
    $this->load->view('templates/footer');
}
public function rekap_absensi_harian() {
    $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');

    // Query data rekap absensi harian
    $this->db->select('
        abs_rekap_absensi.*, 
        abs_pegawai.kode_user, 
        abs_pegawai.nama, 
        abs_shift.nama_shift
    ');
    $this->db->from('abs_rekap_absensi');
    $this->db->join('abs_pegawai', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id', 'left');
    $this->db->join('abs_shift', 'abs_rekap_absensi.shift_id = abs_shift.id', 'left');
    $this->db->where('abs_rekap_absensi.tanggal', $tanggal);
    $this->db->where('abs_pegawai.kode_user IS NOT NULL'); // Hanya pegawai dengan kode user
    $this->db->where('abs_pegawai.kode_user !=', 'nonaktif'); // Tidak termasuk admin
    $this->db->order_by('abs_pegawai.divisi_id', 'ASC');
    $this->db->order_by('abs_pegawai.jabatan1_id', 'ASC');
    $this->db->order_by('abs_pegawai.id', 'ASC');

    $data['rekap_harian'] = $this->db->get()->result();
    $data['title'] = 'Rekap Absen Harian';
    $data['tanggal'] = $tanggal;

    $this->load->view('templates/header', $data);
    $this->load->view('admin/rekap_absensi_harian', $data);
    $this->load->view('templates/footer');
}
public function log_absensi_total() {
    $bulan = $this->input->get('bulan') ?? date('m'); // Default bulan ini
    $tahun = $this->input->get('tahun') ?? date('Y'); // Default tahun ini

    // Query data log absensi total
    $this->db->select('
        abs_absensi.id,
        abs_absensi.tanggal,
        abs_absensi.jenis_absen,
        abs_absensi.waktu,
        abs_absensi.latitude,
        abs_absensi.longitude,
        abs_absensi.foto,
        abs_shift.kode_shift,
        abs_pegawai.nama AS nama_pegawai
    ');
    $this->db->from('abs_absensi');
    $this->db->join('abs_pegawai', 'abs_absensi.pegawai_id = abs_pegawai.id', 'left');
    $this->db->join('abs_shift', 'abs_absensi.shift_id = abs_shift.id', 'left');
    $this->db->where('MONTH(abs_absensi.tanggal)', $bulan);
    $this->db->where('YEAR(abs_absensi.tanggal)', $tahun);
    $this->db->order_by('abs_absensi.tanggal', 'ASC');

    $data['log_absensi'] = $this->db->get()->result();

    $data['title'] = 'Log Absensi Total';
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['bulan_dropdown'] = range(1, 12);
    $data['tahun_dropdown'] = range(date('Y') - 5, date('Y') + 1);

    $this->load->view('templates/header', $data);
    $this->load->view('admin/log_absensi_total', $data);
    $this->load->view('templates/footer');
}

public function edit_log_absensi() {
    $id = $this->input->post('id');
    $data = [
        'tanggal' => $this->input->post('tanggal'),
        'jenis_absen' => $this->input->post('jenis_absen'),
        'waktu' => $this->input->post('waktu'),
        'latitude' => $this->input->post('latitude'),
        'longitude' => $this->input->post('longitude')
    ];

    $this->db->where('id', $id);
    if ($this->db->update('abs_absensi', $data)) {
        echo json_encode(['status' => 'success', 'message' => 'Log absensi berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui log absensi!']);
    }
}

public function delete_log_absensi() {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if ($id) {
        $this->db->where('id', $id);
        if ($this->db->delete('abs_absensi')) {
            echo json_encode(['status' => 'success', 'message' => 'Log absensi berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus log absensi!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan!']);
    }
}

public function absen_pegawai_pending() {
    $user_id = $this->session->userdata('id'); // Ambil ID pengguna dari session

    if (!$user_id) {
        $this->session->set_flashdata('error', 'Sesi tidak valid. Silakan login ulang.');
        redirect('auth/login');
    }

    if ($this->input->post()) {
        $pegawai_id = $this->input->post('pegawai_id');
        $shift_id = $this->input->post('shift_id');
        $jenis_absen = $this->input->post('jenis_absen');
        $tanggal = $this->input->post('tanggal') ?? date('Y-m-d');
        $waktu = $this->input->post('waktu') ?? date('H:i:s');

        // Simpan data absensi pending
        $data_absen = [
            'pegawai_id' => $pegawai_id,
            'shift_id' => $shift_id,
            'jenis_absen' => $jenis_absen,
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'created_by' => $user_id, // Ambil dari session
            'status' => 'pending'
        ];

        $this->db->insert('abs_absensi_pending', $data_absen);

        $this->session->set_flashdata('success', 'Absensi berhasil diajukan untuk verifikasi.');
        redirect('admin/absen_pegawai_pending');
    }

    $data['pegawai'] = $this->db->get('abs_pegawai')->result();
    $data['shift'] = $this->db->get('abs_shift')->result();
    $data['title'] = 'Absen Pegawai - Pending';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/absen_pegawai_pending', $data);
    $this->load->view('templates/footer');
}

public function verifikasi_absen() {
    $this->db->select('
        abs_absensi_pending.*,
        abs_pegawai.nama AS nama_pegawai,
        abs_shift.nama_shift AS nama_shift
    ');
    $this->db->from('abs_absensi_pending');
    $this->db->join('abs_pegawai', 'abs_absensi_pending.pegawai_id = abs_pegawai.id', 'left');
    $this->db->join('abs_shift', 'abs_absensi_pending.shift_id = abs_shift.id', 'left');
    $this->db->where('abs_absensi_pending.status', 'pending');
    $data['absensi_pending'] = $this->db->get()->result();

    $data['title'] = 'Verifikasi Absensi Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/verifikasi_absen', $data);
    $this->load->view('templates/footer');
}


public function proses_verifikasi($id, $status) {
    $absensi_pending = $this->db->get_where('abs_absensi_pending', ['id' => $id])->row();

    if (!$absensi_pending) {
        $this->session->set_flashdata('error', 'Data absensi tidak ditemukan.');
        redirect('admin/verifikasi_absen');
    }

    if ($status === 'verified') {
        // Pindahkan data ke abs_absensi
        $data_absen = [
            'pegawai_id' => $absensi_pending->pegawai_id,
            'shift_id' => $absensi_pending->shift_id,
            'jenis_absen' => $absensi_pending->jenis_absen,
            'tanggal' => $absensi_pending->tanggal,
            'waktu' => $absensi_pending->waktu,
            'verified_by' => $this->session->userdata('user_id'), // ID admin
            'verified_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('abs_absensi', $data_absen);
        $this->update_rekap_absensi($absensi_pending->pegawai_id, $absensi_pending->shift_id, $absensi_pending->tanggal);
    }

    // Update status di abs_absensi_pending
    $this->db->update('abs_absensi_pending', [
        'status' => $status,
        'verified_by' => $this->session->userdata('user_id'),
        'verified_at' => date('Y-m-d H:i:s')
    ], ['id' => $id]);

    $this->session->set_flashdata('success', 'Absensi berhasil ' . ($status === 'verified' ? 'diverifikasi' : 'ditolak') . '.');
    redirect('admin/verifikasi_absen');
}

public function cetak_slip_gaji($pegawai_id) {
    $range_start = $this->input->get('start_date') ?? date('Y-m-01');
    $range_end = $this->input->get('end_date') ?? date('Y-m-t');

    // Validasi range tanggal
    if (strtotime($range_start) > strtotime($range_end)) {
        $this->session->set_flashdata('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
        redirect('admin/laporan_gaji');
    }

    // Ambil data pegawai dan jabatan
    $this->db->select('abs_pegawai.id, abs_pegawai.nama, abs_jabatan.nama_jabatan AS jabatan');
    $this->db->from('abs_pegawai');
    $this->db->join('abs_jabatan', 'abs_pegawai.jabatan1_id = abs_jabatan.id', 'left');
    $this->db->where('abs_pegawai.id', $pegawai_id);
    $pegawai = $this->db->get()->row();

    // Data rekapitulasi
    $lama_menit_kerja = $this->db->select('SUM(lama_menit_kerja) AS total_menit')
                                ->from('abs_rekap_absensi')
                                ->where('pegawai_id', $pegawai_id)
                                ->where('tanggal >=', $range_start)
                                ->where('tanggal <=', $range_end)
                                ->get()->row()->total_menit ?? 0;

    $gaji_pokok = $this->db->select('SUM(total_gaji) AS total_gaji')
                           ->from('abs_rekap_absensi')
                           ->where('pegawai_id', $pegawai_id)
                           ->where('tanggal >=', $range_start)
                           ->where('tanggal <=', $range_end)
                           ->get()->row()->total_gaji ?? 0;

    $lembur = $this->db->select('SUM(total_gaji_lembur) AS total_lembur')
                       ->from('abs_lembur')
                       ->where('pegawai_id', $pegawai_id)
                       ->where('tanggal >=', $range_start)
                       ->where('tanggal <=', $range_end)
                       ->get()->row()->total_lembur ?? 0;

    $telat = $this->db->select('SUM(terlambat + pulang_cepat) AS total_telat')
                      ->from('abs_rekap_absensi')
                      ->where('pegawai_id', $pegawai_id)
                      ->where('tanggal >=', $range_start)
                      ->where('tanggal <=', $range_end)
                      ->get()->row()->total_telat ?? 0;

    $tunjangan = $this->db->select('tunjangan')
                          ->from('abs_pegawai')
                          ->where('id', $pegawai_id)
                          ->get()->row()->tunjangan ?? 0;

    $tambahan_lain = $this->db->select('SUM(nilai_tambahan) AS total_tambahan')
                              ->from('abs_tambahan_lain')
                              ->where('pegawai_id', $pegawai_id)
                              ->where('tanggal >=', $range_start)
                              ->where('tanggal <=', $range_end)
                              ->get()->row()->total_tambahan ?? 0;

    $potongan = $this->db->select('SUM(nilai) AS total_potongan')
                         ->from('abs_potongan')
                         ->where('pegawai_id', $pegawai_id)
                         ->where('tanggal >=', $range_start)
                         ->where('tanggal <=', $range_end)
                         ->get()->row()->total_potongan ?? 0;

    // $deposit = $this->db->select('SUM(nilai) AS total_deposit')
    //                     ->from('abs_deposit')
    //                     ->where('pegawai_id', $pegawai_id)
    //                     ->where('tanggal >=', $range_start)
    //                     ->where('tanggal <=', $range_end)
    //                     ->get()->row()->total_deposit ?? 0;
    $deposit_tarik = $this->db->select('SUM(nilai) AS total')
        ->from('abs_deposit')
        ->where('pegawai_id', $pegawai_id)
        ->where('jenis', 'tarik')
        ->where('tanggal >=', $range_start)
        ->where('tanggal <=', $range_end)
        ->get()->row()->total ?? 0;

    $deposit_setor = $this->db->select('SUM(nilai) AS total')
        ->from('abs_deposit')
        ->where('pegawai_id', $pegawai_id)
        ->where('jenis', 'setor')
        ->where('tanggal >=', $range_start)
        ->where('tanggal <=', $range_end)
        ->get()->row()->total ?? 0;

    $deposit = $deposit_tarik - $deposit_setor;

    $bayar_kasbon = $this->db->select('SUM(nilai) AS total_kasbon')
                             ->from('abs_kasbon')
                             ->where('pegawai_id', $pegawai_id)
                             ->where('jenis', 'bayar')
                             ->where('tanggal >=', $range_start)
                             ->where('tanggal <=', $range_end)
                             ->get()->row()->total_kasbon ?? 0;

    // Hitung total gaji
    $total_gaji = $gaji_pokok + $tunjangan + $lembur + $tambahan_lain - $potongan + $deposit - $bayar_kasbon - $telat;

    // Data untuk view
    $data = compact('pegawai', 'range_start', 'range_end', 'lama_menit_kerja', 'gaji_pokok', 'tunjangan', 'lembur', 'telat', 'tambahan_lain', 'potongan', 'deposit', 'bayar_kasbon', 'total_gaji');

    $this->load->view('admin/slip_gaji', $data);
}

public function cetak_slip_gaji_pdf($pegawai_id) {
    $this->load->library('dompdf_gen'); // Pastikan Dompdf sudah disiapkan

    // Ambil data range tanggal
    $range_start = $this->input->get('start_date') ?? date('Y-m-01');
    $range_end = $this->input->get('end_date') ?? date('Y-m-t');

    // Validasi range tanggal
    if (strtotime($range_start) > strtotime($range_end)) {
        $this->session->set_flashdata('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
        redirect('admin/laporan_gaji');
    }

    // Ambil data pegawai dan detail gaji
    $pegawai = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();
    $data['pegawai'] = $pegawai;
    $data['range_start'] = $range_start;
    $data['range_end'] = $range_end;

    // Rekap data gaji (sesuaikan dengan logika Anda)
    $data['gaji_pokok'] = 1000000; // Contoh data dummy
    $data['tunjangan'] = 500000;
    $data['lembur'] = 100000;
    $data['tambahan_lain'] = 20000;
    $data['potongan'] = 50000;
    $data['deposit'] = 10000;
    $data['bayar_kasbon'] = 0;
    $data['total_gaji'] = $data['gaji_pokok'] + $data['tunjangan'] + $data['lembur'] + $data['tambahan_lain'] - $data['potongan'] - $data['deposit'] - $data['bayar_kasbon'];


    // Nama file PDF
    $nama_pegawai = str_replace(' ', '_', strtolower($pegawai->nama)); // Ganti spasi dengan underscore
    $bulan = date('F_Y', strtotime($range_start)); // Format bulan_tahun
    $nama_file = "gaji_{$bulan}_{$nama_pegawai}.pdf";
    // Render ke View
    $html = $this->load->view('admin/slip_gaji_pdf', $data, true);

    // Konversi ke PDF
    $this->dompdf->loadHtml($html);
    $this->dompdf->setPaper('A5', 'portrait'); // Ukuran kertas A5, potret
    $this->dompdf->render();

    // Stream PDF dengan nama file dinamis
    $this->dompdf->stream($nama_file, ["Attachment" => 1]); // Attachment 1 untuk unduhan
}
public function generate_laporan_gaji() {
    $start_date = $this->input->get('start_date') ?? date('Y-m-01');
    $end_date = $this->input->get('end_date') ?? date('Y-m-t');

    // Query untuk mengambil data laporan gaji dengan logika perhitungan yang sesuai
    $laporan_gaji = $this->db
        ->select('
            abs_pegawai.id AS pegawai_id,
            abs_pegawai.nama,
            abs_pegawai.nomor_rekening,
            abs_rekening_bank.nama_bank,
            abs_divisi.nama_divisi AS divisi,
            j1.nama_jabatan AS jabatan1,
            j2.nama_jabatan AS jabatan2,
            COUNT(abs_rekap_absensi.id) AS total_kehadiran,
            SUM(abs_rekap_absensi.lama_menit_kerja) AS total_menit,
            COALESCE(SUM(abs_rekap_absensi.total_gaji), 0) AS total_gaji,
            abs_pegawai.tunjangan,
            (
                SELECT COALESCE(SUM(abs_lembur.total_gaji_lembur), 0)
                FROM abs_lembur
                WHERE abs_lembur.pegawai_id = abs_pegawai.id
                AND abs_lembur.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
            ) AS total_gaji_lembur,
            (
                SELECT COALESCE(SUM(abs_tambahan_lain.nilai_tambahan), 0)
                FROM abs_tambahan_lain
                WHERE abs_tambahan_lain.pegawai_id = abs_pegawai.id
                AND abs_tambahan_lain.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
            ) AS total_tambahan_lain,
            (
                SELECT COALESCE(SUM(abs_potongan.nilai), 0)
                FROM abs_potongan
                WHERE abs_potongan.pegawai_id = abs_pegawai.id
                AND abs_potongan.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
            ) AS total_potongan,
(
    SELECT 
        COALESCE(SUM(CASE WHEN abs_deposit.jenis = \'tarik\' THEN abs_deposit.nilai ELSE 0 END), 0) -
        COALESCE(SUM(CASE WHEN abs_deposit.jenis = \'setor\' THEN abs_deposit.nilai ELSE 0 END), 0)
    FROM abs_deposit
    WHERE abs_deposit.pegawai_id = abs_pegawai.id
    AND abs_deposit.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
) AS total_deposit,

            (
                SELECT COALESCE(SUM(abs_kasbon.nilai), 0)
                FROM abs_kasbon
                WHERE abs_kasbon.pegawai_id = abs_pegawai.id
                AND abs_kasbon.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'
                AND abs_kasbon.jenis = "bayar"
            ) AS total_kasbon
        ')
        ->from('abs_pegawai')
        ->join('abs_rekap_absensi', 'abs_rekap_absensi.pegawai_id = abs_pegawai.id 
                    AND abs_rekap_absensi.tanggal BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'', 'left')
        ->join('abs_rekening_bank', 'abs_pegawai.nama_bank_id = abs_rekening_bank.id', 'left')
        ->join('abs_divisi', 'abs_pegawai.divisi_id = abs_divisi.id', 'left')
        ->join('abs_jabatan AS j1', 'abs_pegawai.jabatan1_id = j1.id', 'left')
        ->join('abs_jabatan AS j2', 'abs_pegawai.jabatan2_id = j2.id', 'left')
        ->group_by('abs_pegawai.id')
        ->get()
        ->result();

    // Proses Insert atau Update ke Tabel Arsip
    foreach ($laporan_gaji as $row) {
        // Perhitungan total penerimaan
        $total_penerimaan = $row->total_gaji + $row->tunjangan + $row->total_gaji_lembur + $row->total_tambahan_lain - $row->total_potongan + $row->total_deposit - $row->total_kasbon;

        
        // Pembulatan penerimaan ke ribuan terdekat ke atas
        $pembulatan_penerimaan = ceil($total_penerimaan / 1000) * 1000;

        $data = [
            'tanggal_awal' => $start_date,
            'tanggal_akhir' => $end_date,
            'pegawai_id' => $row->pegawai_id,
            'nama_pegawai' => $row->nama,
            'nomor_rekening' => $row->nomor_rekening,
            'nama_bank' => $row->nama_bank,
            'divisi' => $row->divisi,
            'jabatan1' => $row->jabatan1,
            'jabatan2' => $row->jabatan2,
            'total_kehadiran' => $row->total_kehadiran,
            'total_menit' => $row->total_menit,
            'total_jam' => $row->total_menit / 60,
            'gaji_pokok' => $row->total_gaji,
            'tunjangan' => $row->tunjangan,
            'total_lembur' => $row->total_gaji_lembur,
            'tambahan_lain' => $row->total_tambahan_lain,
            'potongan' => $row->total_potongan,
            'deposit' => $row->total_deposit,
            'bayar_kasbon' => $row->total_kasbon,
            'total_penerimaan' => $total_penerimaan,
            'pembulatan_penerimaan' => $pembulatan_penerimaan,
        ];

        // Cek apakah data sudah ada
        $existing = $this->db
            ->get_where('abs_arsip_gaji', [
                'tanggal_awal' => $start_date,
                'tanggal_akhir' => $end_date,
                'pegawai_id' => $row->pegawai_id,
            ])
            ->row();

        if ($existing) {
            // Update data jika sudah ada
            $this->db->where('id', $existing->id)->update('abs_arsip_gaji', $data);
        } else {
            // Insert data baru
            $this->db->insert('abs_arsip_gaji', $data);
        }
    }

    $this->session->set_flashdata('success', 'Laporan gaji berhasil diarsipkan!');
    redirect('admin/laporan_gaji');
}

public function arsip_gaji()
{
    // Ambil data arsip gaji
    $data['arsip_gaji'] = $this->db->get('abs_arsip_gaji')->result();

    // Set judul halaman
    $data['title'] = 'Arsip Gaji Pegawai';

    // Tampilkan view
    $this->load->view('templates/header', $data);
    $this->load->view('admin/arsip_gaji', $data);
    $this->load->view('templates/footer');
}

public function export_arsip_gaji()
{
    // Ambil data arsip gaji
    $arsip_gaji = $this->db->get('abs_arsip_gaji')->result();

    // Load library Spreadsheet
    $this->load->library('Spreadsheet');

    // Buat spreadsheet baru
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No')
          ->setCellValue('B1', 'ID Pegawai')
          ->setCellValue('C1', 'Nama Pegawai')
          ->setCellValue('D1', 'Nomor Rekening')
          ->setCellValue('E1', 'Bank')
          ->setCellValue('F1', 'Divisi')
          ->setCellValue('G1', 'Jabatan 1')
          ->setCellValue('H1', 'Jabatan 2')
          ->setCellValue('I1', 'Total Kehadiran')
          ->setCellValue('J1', 'Total Jam')
          ->setCellValue('K1', 'Gaji Pokok')
          ->setCellValue('L1', 'Tunjangan')
          ->setCellValue('M1', 'Total Lembur')
          ->setCellValue('N1', 'Tambahan Lain')
          ->setCellValue('O1', 'Potongan')
          ->setCellValue('P1', 'Deposit')
          ->setCellValue('Q1', 'Bayar Kasbon')
          ->setCellValue('R1', 'Total Penerimaan')
          ->setCellValue('S1', 'Pembulatan')
          ->setCellValue('T1', 'Periode');

    // Data isi
    $rowNumber = 2;
    foreach ($arsip_gaji as $index => $row) {
        $sheet->setCellValue("A$rowNumber", $index + 1)
              ->setCellValue("B$rowNumber", $row->pegawai_id)
              ->setCellValue("C$rowNumber", $row->nama_pegawai)
              ->setCellValue("D$rowNumber", $row->nomor_rekening)
              ->setCellValue("E$rowNumber", $row->nama_bank)
              ->setCellValue("F$rowNumber", $row->divisi)
              ->setCellValue("G$rowNumber", $row->jabatan1)
              ->setCellValue("H$rowNumber", $row->jabatan2)
              ->setCellValue("I$rowNumber", $row->total_kehadiran)
              ->setCellValue("J$rowNumber", $row->total_jam)
              ->setCellValue("K$rowNumber", $row->gaji_pokok)
              ->setCellValue("L$rowNumber", $row->tunjangan)
              ->setCellValue("M$rowNumber", $row->total_lembur)
              ->setCellValue("N$rowNumber", $row->tambahan_lain)
              ->setCellValue("O$rowNumber", $row->potongan)
              ->setCellValue("P$rowNumber", $row->deposit)
              ->setCellValue("Q$rowNumber", $row->bayar_kasbon)
              ->setCellValue("R$rowNumber", $row->total_penerimaan)
              ->setCellValue("S$rowNumber", $row->pembulatan_penerimaan)
              ->setCellValue("T$rowNumber", date('d M Y', strtotime($row->tanggal_awal)) . ' - ' . date('d M Y', strtotime($row->tanggal_akhir)));
        $rowNumber++;
    }

    // Simpan sebagai file Excel
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'Arsip_Gaji_' . date('Ymd') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save('php://output');
}
public function export_arsip_gaji_csv() {
    // Ambil data arsip gaji dari database
    $arsip_gaji = $this->db->get('abs_arsip_gaji')->result();

    // Set nama file
    $filename = 'arsip_gaji_' . date('YmdHis') . '.csv';

    // Header CSV
    $headers = [
        'No', 'Nama Pegawai', 'Nomor Rekening', 'Bank', 'Divisi',
        'Jabatan 1', 'Jabatan 2', 'Total Kehadiran', 'Total Jam',
        'Gaji Pokok', 'Tunjangan', 'Total Lembur', 'Tambahan Lain',
        'Potongan', 'Deposit', 'Bayar Kasbon', 'Total Penerimaan',
        'Pembulatan', 'Periode'
    ];

    // Output header untuk file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Buka output untuk menulis CSV
    $output = fopen('php://output', 'w');

    // Tulis header ke CSV (gunakan `fputcsv` dengan pemisah `;`)
    fputcsv($output, $headers, ';');

    // Tulis data arsip gaji ke CSV
    foreach ($arsip_gaji as $index => $row) {
        $data = [
            $index + 1,
            $row->nama_pegawai,
            $row->nomor_rekening,
            $row->nama_bank,
            $row->divisi,
            $row->jabatan1,
            $row->jabatan2,
            $row->total_kehadiran,
            number_format($row->total_jam ?? 0, 2, ',', ''), // Format dengan koma
            number_format($row->gaji_pokok ?? 0, 0, ',', ''),
            number_format($row->tunjangan ?? 0, 0, ',', ''),
            number_format($row->total_lembur ?? 0, 0, ',', ''),
            number_format($row->tambahan_lain ?? 0, 0, ',', ''),
            number_format($row->potongan ?? 0, 0, ',', ''),
            number_format($row->deposit ?? 0, 0, ',', ''),
            number_format($row->bayar_kasbon ?? 0, 0, ',', ''),
            number_format($row->total_penerimaan ?? 0, 0, ',', ''),
            number_format($row->pembulatan_penerimaan ?? 0, 0, ',', ''),
            date('d M Y', strtotime($row->tanggal_awal)) . ' - ' . date('d M Y', strtotime($row->tanggal_akhir)),
        ];
        fputcsv($output, $data, ';'); // Gunakan pemisah `;`
    }

    // Tutup output
    fclose($output);
    exit();
}

}
