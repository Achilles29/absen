<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Periksa apakah pengguna sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login'); // Redirect ke halaman login jika belum login
        }
        // Periksa role, hanya admin yang bisa mengakses
        if ($this->session->userdata('role') !== 'admin') {
            redirect('auth/login'); // Redirect jika role bukan admin
        }
    }

    public function index() {
        $data['title'] = 'Dashboard Admin'; // Tambahkan title untuk halaman
        $data['pegawai'] = $this->db->get('pegawai')->result();
        $this->load->view('templates/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/footer', $data);

    }

public function master_pegawai() {
    $this->db->select('pegawai.*, divisi.nama_divisi, j1.nama_jabatan AS jabatan1, j2.nama_jabatan AS jabatan2');
    $this->db->from('pegawai');
    $this->db->join('divisi', 'pegawai.divisi_id = divisi.id', 'left');
    $this->db->join('jabatan AS j1', 'pegawai.jabatan1_id = j1.id', 'left');
    $this->db->join('jabatan AS j2', 'pegawai.jabatan2_id = j2.id', 'left');
    $this->db->order_by('pegawai.id', 'ASC'); // Tambahkan ORDER BY id ASC
    $data['pegawai'] = $this->db->get()->result();

    $this->load->view('templates/header', $data);
    $this->load->view('admin/master_pegawai', $data);
    $this->load->view('templates/footer');
}


public function tambah_pegawai() {
    if ($this->input->post()) {
        $gaji_pokok = $this->input->post('gaji_pokok');

        $data = [
            'nama' => $this->input->post('nama'),
            'divisi_id' => $this->input->post('divisi_id'),
            'jabatan1_id' => $this->input->post('jabatan1_id'),
            'jabatan2_id' => $this->input->post('jabatan2_id') ?: null, // Set null jika kosong
            'gaji_pokok' => $gaji_pokok,
            'gaji_per_jam' => $gaji_pokok / 234, // Hitung otomatis gaji per jam
            'tunjangan' => $this->input->post('tunjangan'),
            'tambahan_lain' => $this->input->post('tambahan_lain'),
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
        ];

        $this->db->insert('pegawai', $data);
        $this->session->set_flashdata('success', 'Data pegawai berhasil ditambahkan!');
        redirect('admin/master_pegawai');
    }

    $data['divisi'] = $this->db->get('divisi')->result();
    $data['jabatan'] = $this->db->get('jabatan')->result();

    $this->load->view('templates/header');
    $this->load->view('admin/tambah_pegawai', $data);
    $this->load->view('templates/footer');
}

public function edit_pegawai($id) {
    if ($this->input->post()) {
        // Ambil data dari form
        $gaji_pokok = $this->input->post('gaji_pokok');
        $jabatan2_id = $this->input->post('jabatan2_id') ?: null; // Ubah '' menjadi NULL

        // Data yang akan diperbarui
        $data = [
            'nama' => $this->input->post('nama'),
            'divisi_id' => $this->input->post('divisi_id'),
            'jabatan1_id' => $this->input->post('jabatan1_id'),
            'jabatan2_id' => $jabatan2_id,
            'gaji_pokok' => $gaji_pokok,
            'gaji_per_jam' => $gaji_pokok / 234, // Hitung ulang gaji per jam
            'tunjangan' => $this->input->post('tunjangan'),
            'tambahan_lain' => $this->input->post('tambahan_lain'),
        ];

        // Update ke database
        $this->db->where('id', $id)->update('pegawai', $data);

        // Set pesan berhasil
        $this->session->set_flashdata('success', 'Data pegawai berhasil diperbarui!');
        redirect('admin/master_pegawai');
    }

    // Ambil data pegawai untuk form edit
    $data['pegawai'] = $this->db->get_where('pegawai', ['id' => $id])->row();
    $data['divisi'] = $this->db->get('divisi')->result();
    $data['jabatan'] = $this->db->get('jabatan')->result();

    $this->load->view('templates/header');
    $this->load->view('admin/edit_pegawai', $data);
    $this->load->view('templates/footer');
}

public function update_pegawai($id) {
    $gaji_pokok = $this->input->post('gaji_pokok');
    $jabatan2_id = $this->input->post('jabatan2_id') ?: null; // Ubah nilai kosong menjadi NULL

    $data = [
        'nama' => $this->input->post('nama'),
        'divisi_id' => $this->input->post('divisi_id'),
        'jabatan1_id' => $this->input->post('jabatan1_id'),
        'jabatan2_id' => $jabatan2_id,
        'gaji_pokok' => $gaji_pokok,
        'gaji_per_jam' => $gaji_pokok / 234, // Hitung ulang Gaji Per Jam
        'tunjangan' => $this->input->post('tunjangan'),
        'tambahan_lain' => $this->input->post('tambahan_lain'),
    ];

    $this->db->where('id', $id)->update('pegawai', $data);
    $this->session->set_flashdata('success', 'Data pegawai berhasil diperbarui!');
    redirect('admin/master_pegawai');
}


    public function hapus_pegawai($id) {
        $this->db->delete('pegawai', ['id' => $id]);
        $this->session->set_flashdata('success', 'Pegawai berhasil dihapus!');
        redirect('admin/master_pegawai');
    }

    public function lokasi_absensi() {
        if ($this->input->post()) {
            $data = [
                'nama_lokasi' => $this->input->post('nama_lokasi'),
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude'),
                'range' => $this->input->post('range')
            ];

            // Update lokasi jika sudah ada, atau tambahkan baru
            $lokasi = $this->db->get('lokasi_absensi')->row();
            if ($lokasi) {
                $this->db->update('lokasi_absensi', $data, ['id' => $lokasi->id]);
            } else {
                $this->db->insert('lokasi_absensi', $data);
            }

            $this->session->set_flashdata('success', 'Lokasi absensi berhasil disimpan!');
            redirect('admin/lokasi_absensi');
        }
        $data['title'] = 'Lokasi Absensi'; // Tambahkan title untuk halaman
        $data['lokasi'] = $this->db->get('lokasi_absensi')->row();
        $this->load->view('templates/header', $data);
        $this->load->view('admin/lokasi_absensi', $data);
        $this->load->view('templates/footer', $data);
    }

// public function lokasi_absensi() {
//     // Ambil semua data lokasi
//     $data['lokasi'] = $this->db->get('lokasi_absensi')->result();
//     $data['title'] = 'Pengaturan Lokasi Absensi';

//     $this->load->view('templates/header', $data);
//     $this->load->view('admin/lokasi_absensi', $data);
//     $this->load->view('templates/footer');
// }

public function lokasi_absen() {
    $data['title'] = 'Daftar Lokasi Absensi';

    // Ambil semua lokasi dari tabel lokasi_absensi
    $data['lokasi_list'] = $this->db->get('lokasi_absensi')->result();

    // Load view dengan data lokasi
    $this->load->view('templates/header', $data);
    $this->load->view('admin/lokasi_absen', $data);
    $this->load->view('templates/footer');
}


public function tambah_lokasi() {
    if ($this->input->post()) {
        $data = [
            'nama_lokasi' => $this->input->post('nama_lokasi'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status' => $this->input->post('status')
        ];
        $this->db->insert('lokasi_absensi', $data);
        $this->session->set_flashdata('success', 'Lokasi berhasil ditambahkan');
        redirect('admin/lokasi_absensi');
    }
}
public function edit_lokasi($id) {
    // Cek apakah lokasi dengan ID yang dipilih ada
    $lokasi = $this->db->get_where('lokasi_absensi', ['id' => $id])->row();

    if (!$lokasi) {
        $this->session->set_flashdata('error', 'Lokasi tidak ditemukan.');
        redirect('admin/lokasi_absen');
    }

    $data['title'] = 'Edit Lokasi Absen';
    $data['lokasi'] = $lokasi;

    // Jika form disubmit
    if ($this->input->post()) {
        $update_data = [
            'nama_lokasi' => $this->input->post('nama_lokasi'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'range' => $this->input->post('range'),
            'status' => $this->input->post('is_active') ?? 0
        ];

        $this->db->where('id', $id)->update('lokasi_absensi', $update_data);
        $this->session->set_flashdata('success', 'Lokasi berhasil diperbarui.');
        redirect('admin/lokasi_absen');
    }

    // Tampilkan view edit
    $this->load->view('templates/header', $data);
    $this->load->view('admin/edit_lokasi', $data);
    $this->load->view('templates/footer');
}
public function hapus_lokasi($id) {
    // Cek apakah lokasi dengan ID yang dipilih ada
    $lokasi = $this->db->get_where('lokasi_absensi', ['id' => $id])->row();

    if (!$lokasi) {
        $this->session->set_flashdata('error', 'Lokasi tidak ditemukan.');
    } else {
        // Hapus lokasi
        $this->db->delete('lokasi_absensi', ['id' => $id]);
        $this->session->set_flashdata('success', 'Lokasi berhasil dihapus.');
    }

    redirect('admin/lokasi_absen');
}


public function ubah_status_lokasi($id, $status) {
    $this->db->where('id', $id)->update('lokasi_absensi', ['status' => $status]);
    $this->session->set_flashdata('success', 'Status lokasi berhasil diperbarui');
    redirect('admin/lokasi_absensi');
}

public function simpan_lokasi_absen() {
    $data = [
        'nama_lokasi' => $this->input->post('nama_lokasi'),
        'latitude'    => $this->input->post('latitude'),
        'longitude'   => $this->input->post('longitude'),
        'range'       => $this->input->post('range'),
        'status'      => $this->input->post('status') ?? 'aktif'
    ];

    // Periksa jika lokasi sudah ada
    $existing = $this->db->get_where('lokasi_absensi', ['nama_lokasi' => $data['nama_lokasi']])->row();
    if ($existing) {
        // Update lokasi jika sudah ada
        $this->db->update('lokasi_absensi', $data, ['id' => $existing->id]);
    } else {
        // Tambahkan lokasi baru
        $this->db->insert('lokasi_absensi', $data);
    }

    $this->session->set_flashdata('success', 'Lokasi berhasil disimpan!');
    redirect('admin/lokasi_absen'); // Redirect kembali ke halaman lokasi_absen
}


        // public function rekap_absensi() {
        //     // Ambil data absensi per pegawai
        //     $data['rekap'] = $this->db
        //         ->select('pegawai.id, pegawai.nama, COUNT(absensi.id) AS total_absensi')
        //         ->from('pegawai')
        //         ->join('absensi', 'pegawai.id = absensi.pegawai_id', 'left')
        //         ->group_by('pegawai.id')
        //         ->order_by('pegawai.nama', 'ASC')
        //         ->get()
        //         ->result();

        //     $data['title'] = 'Rekapitulasi Absensi Pegawai';
        //     $this->load->view('templates/header', $data);
        //     $this->load->view('admin/rekap_absensi', $data);
        //     $this->load->view('templates/footer', $data);
        // }


public function rekap_absensi_bulanan() {
    // Ambil bulan dari input GET atau gunakan bulan sekarang
    $bulan = $this->input->get('bulan', TRUE) ?: date('Y-m');

    // Ambil data bulan unik dari rekap_absensi untuk dropdown
    $data['bulan_dropdown'] = $this->db
        ->select("DISTINCT(DATE_FORMAT(tanggal, '%Y-%m')) AS bulan", FALSE)
        ->from('rekap_absensi')
        ->get()
        ->result();

    // Query untuk rekap absensi total per bulan
    $this->db->select('pegawai.id AS pegawai_id, pegawai.nama, 
                       SUM(rekap_absensi.terlambat) AS total_terlambat, 
                       SUM(rekap_absensi.pulang_cepat) AS total_pulang_cepat, 
                       SUM(rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
                       SUM(rekap_absensi.total_gaji) AS total_gaji');
    $this->db->from('rekap_absensi');
    $this->db->join('pegawai', 'rekap_absensi.pegawai_id = pegawai.id', 'left');
    $this->db->where('pegawai.kode_user', 'pegawai'); // Tambahkan filter kode_user = 'pegawai'
    $this->db->where("DATE_FORMAT(rekap_absensi.tanggal, '%Y-%m') =", $bulan); // Filter bulan
    $this->db->group_by('rekap_absensi.pegawai_id');
    $this->db->order_by('pegawai.id', 'ASC'); // Urutkan berdasarkan ID Pegawai

    $data['rekap_bulanan'] = $this->db->get()->result();
    $data['title'] = 'Rekapitulasi Absensi Bulanan';
    $data['bulan_terpilih'] = $bulan;

    $this->load->view('templates/header', $data);
    $this->load->view('admin/rekap_absensi_bulanan', $data);
    $this->load->view('templates/footer', $data);
}



// public function rekap_absensi() {
//     $bulan = $this->input->get('bulan', TRUE) ?: date('Y-m'); // Ambil bulan dari input GET atau gunakan bulan sekarang

//     $this->db->select('pegawai.id AS pegawai_id, pegawai.nama, 
//                        SUM(rekap_absensi.terlambat) AS total_terlambat, 
//                        SUM(rekap_absensi.pulang_cepat) AS total_pulang_cepat, 
//                        SUM(rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
//                        SUM(rekap_absensi.total_gaji) AS total_gaji');
//     $this->db->from('rekap_absensi');
//     $this->db->join('pegawai', 'rekap_absensi.pegawai_id = pegawai.id', 'left');
//     $this->db->where("DATE_FORMAT(rekap_absensi.tanggal, '%Y-%m') =", $bulan); // Filter berdasarkan bulan
//     $this->db->group_by('rekap_absensi.pegawai_id');
//     $rekap_bulanan = $this->db->get()->result();

//     $data['rekap_bulanan'] = $rekap_bulanan;
//     $data['title'] = 'Rekapitulasi Absensi Bulanan';
//     $data['bulan'] = $bulan; // Untuk informasi bulan yang dipilih

//     $this->load->view('templates/header', $data);
//     $this->load->view('admin/rekap_absensi_bulanan', $data);
//     $this->load->view('templates/footer', $data);
// }

public function rekap_absensi() {
    $bulan = $this->input->get('bulan') ?: date('Y-m');
    $this->db->select('pegawai.id AS pegawai_id, pegawai.nama, SUM(rekap_absensi.terlambat) AS total_terlambat, 
                       SUM(rekap_absensi.pulang_cepat) AS total_pulang_cepat, SUM(rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
                       SUM(rekap_absensi.total_gaji) AS total_gaji');
    $this->db->from('rekap_absensi');
    $this->db->join('pegawai', 'rekap_absensi.pegawai_id = pegawai.id');
    $this->db->where('DATE_FORMAT(rekap_absensi.tanggal, "%Y-%m")', $bulan);
    $this->db->where('pegawai.kode_user !=', 'admin'); // Filter admin
    $this->db->group_by('rekap_absensi.pegawai_id');
    $this->db->order_by('pegawai.id', 'ASC');
    $data['rekap_bulanan'] = $this->db->get()->result();

    $data['title'] = 'Rekapitulasi Absensi Pegawai';
    $this->load->view('templates/header', $data);
    $this->load->view('admin/rekap_absensi_bulanan', $data);
    $this->load->view('templates/footer');
}

public function detail_rekap_absensi($pegawai_id) {
    // Ambil bulan dari input GET atau gunakan bulan sekarang
    $bulan = $this->input->get('bulan', TRUE) ?: date('Y-m');

    // Ambil data bulan unik dari rekap_absensi untuk dropdown
    $data['bulan_dropdown'] = $this->db
        ->select("DISTINCT(DATE_FORMAT(tanggal, '%Y-%m')) AS bulan", FALSE)
        ->from('rekap_absensi')
        ->get()
        ->result();

    // Query untuk detail rekap absensi
    $this->db->select('rekap_absensi.tanggal, pegawai.nama AS nama_pegawai, shift.kode_shift, 
                       rekap_absensi.jam_masuk, rekap_absensi.jam_pulang, 
                       rekap_absensi.terlambat, rekap_absensi.pulang_cepat, 
                       rekap_absensi.lama_menit_kerja, rekap_absensi.total_gaji');
    $this->db->from('rekap_absensi');
    $this->db->join('pegawai', 'rekap_absensi.pegawai_id = pegawai.id', 'left');
    $this->db->join('shift', 'rekap_absensi.shift_id = shift.id', 'left');
    $this->db->where('rekap_absensi.pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(rekap_absensi.tanggal, '%Y-%m') =", $bulan); // Filter bulan
    $this->db->order_by('rekap_absensi.tanggal', 'ASC');

    $data['rekap_harian'] = $this->db->get()->result();
    $data['title'] = 'Detail Rekap Absensi Pegawai';
    $data['bulan_terpilih'] = $bulan;

    $this->load->view('templates/header', $data);
    $this->load->view('admin/detail_rekap_absensi', $data);
    $this->load->view('templates/footer', $data);
}


        public function detail_absensi($pegawai_id) {
            // Ambil data absensi berdasarkan ID pegawai
            $data['detail'] = $this->db
                ->select('absensi.*, pegawai.nama')
                ->from('absensi')
                ->join('pegawai', 'absensi.pegawai_id = pegawai.id')
                ->where('pegawai.id', $pegawai_id)
                ->order_by('absensi.tanggal', 'DESC')
                ->get()
                ->result();

            // Ambil informasi pegawai
            $data['pegawai'] = $this->db->get_where('pegawai', ['id' => $pegawai_id])->row();

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

public function shift() {
    // Ambil data shift dengan join ke tabel divisi
    $this->db->select('shift.*, divisi.nama_divisi');
    $this->db->from('shift');
    $this->db->join('divisi', 'shift.divisi_id = divisi.id');
    $data['shift'] = $this->db->get()->result();

    $data['divisi'] = $this->db->get('divisi')->result(); // Untuk dropdown divisi
    $data['title'] = 'Daftar Shift Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/shift', $data);
    $this->load->view('templates/footer', $data);
}

public function tambah_shift() {
    if ($this->input->post()) {
        $data = [
            'divisi_id' => $this->input->post('divisi_id'),
            'kode_shift' => $this->input->post('kode_shift'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
        ];

        $this->db->insert('shift', $data);
        $this->session->set_flashdata('success', 'Shift berhasil ditambahkan!');
        redirect('admin/shift');
    }

    // Ambil data divisi untuk dropdown
    $data['divisi'] = $this->db->get('divisi')->result();
    $data['title'] = 'Tambah Shift Pegawai';

    // Load view tambah shift
    $this->load->view('templates/header', $data);
    $this->load->view('admin/tambah_shift', $data);
    $this->load->view('templates/footer', $data);
}
public function edit_shift($id) {
    if ($this->input->post()) {
        $data = [
            'divisi_id' => $this->input->post('divisi_id'),
            'kode_shift' => $this->input->post('kode_shift'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
        ];

        $this->db->where('id', $id)->update('shift', $data);
        $this->session->set_flashdata('success', 'Shift berhasil diperbarui!');
        redirect('admin/shift');
    }

    // Ambil data shift berdasarkan ID
    $data['shift'] = $this->db->get_where('shift', ['id' => $id])->row();
    $data['divisi'] = $this->db->get('divisi')->result();
    $data['title'] = 'Edit Shift Pegawai';

    // Load view edit shift
    $this->load->view('templates/header', $data);
    $this->load->view('admin/edit_shift', $data);
    $this->load->view('templates/footer', $data);
}

public function hapus_shift($id) {
    $this->db->delete('shift', ['id' => $id]);
    $this->session->set_flashdata('success', 'Shift berhasil dihapus!');
    redirect('admin/shift');
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
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default ke bulan ini

    // Query untuk daftar pegawai dengan total absensi yang valid (lama kerja > 0)
    $this->db->select('pegawai.id, pegawai.nama, 
                       IFNULL(COUNT(CASE WHEN rekap_absensi.lama_menit_kerja > 0 THEN 1 END), 0) AS total_absensi, 
                       IFNULL(SUM(rekap_absensi.terlambat), 0) AS total_terlambat, 
                       IFNULL(SUM(rekap_absensi.pulang_cepat), 0) AS total_pulang_cepat, 
                       IFNULL(SUM(rekap_absensi.lama_menit_kerja), 0) AS total_lama_kerja, 
                       IFNULL(SUM(rekap_absensi.total_gaji), 0) AS total_gaji');
    $this->db->from('pegawai');
    $this->db->join('rekap_absensi', 'rekap_absensi.pegawai_id = pegawai.id AND DATE_FORMAT(rekap_absensi.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
    $this->db->group_by('pegawai.id');
    $this->db->order_by('pegawai.id', 'ASC'); // Urutkan berdasarkan ID Pegawai

    $data['rekap_absensi'] = $this->db->get()->result();

    $data['bulan'] = $bulan;
    $data['title'] = 'Log Absensi Rekap';
    $this->load->view('templates/header', $data);
    $this->load->view('admin/log_absensi', $data);
    $this->load->view('templates/footer');
}


public function detail_log_absensi($pegawai_id) {
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default ke bulan ini

    // Ambil data pegawai untuk menampilkan nama
    $data['pegawai'] = $this->db->get_where('pegawai', ['id' => $pegawai_id])->row();

    // Query untuk mengambil log absensi per pegawai
    $this->db->select('rekap_absensi.tanggal, pegawai.nama, rekap_absensi.jam_masuk, rekap_absensi.jam_pulang, 
                       rekap_absensi.terlambat, rekap_absensi.pulang_cepat, rekap_absensi.lama_menit_kerja, 
                       rekap_absensi.total_gaji');
    $this->db->from('rekap_absensi');
    $this->db->join('pegawai', 'rekap_absensi.pegawai_id = pegawai.id');
    $this->db->where('rekap_absensi.pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(rekap_absensi.tanggal, '%Y-%m') =", $bulan);
    $this->db->order_by('rekap_absensi.tanggal', 'ASC');
    $data['log_absensi'] = $this->db->get()->result();

    $data['bulan'] = $bulan;
    $data['title'] = 'Detail Log Absensi';
    $this->load->view('templates/header', $data);
    $this->load->view('admin/detail_log_absensi', $data);
    $this->load->view('templates/footer');
}


public function log_absensi_detail($pegawai_id) {
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default ke bulan ini

    // Ambil data pegawai berdasarkan ID
    $data['pegawai'] = $this->db->get_where('pegawai', ['id' => $pegawai_id])->row();

    // Query semua log absensi per pegawai dengan filter bulan
    $this->db->select('absensi.tanggal, absensi.waktu, absensi.latitude, absensi.longitude, 
                       absensi.jenis_absen, shift.kode_shift, absensi.foto');
    $this->db->from('absensi');
    $this->db->join('shift', 'absensi.shift_id = shift.id', 'left');
    $this->db->where('absensi.pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(absensi.tanggal, '%Y-%m') = ", $bulan);
    $this->db->order_by('absensi.tanggal', 'ASC');

    $data['log_absensi'] = $this->db->get()->result();
    $data['bulan'] = $bulan;
    $data['title'] = 'Detail Log Absensi';

    // Load View
    $this->load->view('templates/header', $data);
    $this->load->view('admin/log_absensi_detail', $data);
    $this->load->view('templates/footer');
}

// public function laporan_gaji() {
//     $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default bulan ini

//     // Query untuk laporan gaji pegawai
//     $this->db->select('
//         pegawai.id, 
//         pegawai.nama, 
//         SUM(CASE WHEN rekap_absensi.lama_menit_kerja > 0 THEN 1 ELSE 0 END) AS total_kehadiran, 
//         SUM(rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
//         COALESCE(SUM(rekap_absensi.total_gaji), 0) AS total_gaji, 
//         COALESCE(SUM(lembur.total_gaji_lembur), 0) AS total_gaji_lembur,
//         COALESCE(pegawai.tambahan_lain, 0) AS tambahan_lain -- Hanya diambil sekali dari tabel pegawai
//     ');
//     $this->db->from('pegawai');
//     $this->db->join('rekap_absensi', 'rekap_absensi.pegawai_id = pegawai.id AND DATE_FORMAT(rekap_absensi.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
//     $this->db->join('lembur', 'lembur.pegawai_id = pegawai.id AND DATE_FORMAT(lembur.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
//     $this->db->where('pegawai.kode_user !=', 'admin'); // Jangan tampilkan pegawai tipe admin
//     $this->db->group_by('pegawai.id'); // Group by id pegawai
//     $this->db->order_by('pegawai.id', 'ASC');

//     $data['laporan_gaji'] = $this->db->get()->result();
//     $data['bulan'] = $bulan;
//     $data['title'] = 'Laporan Gaji Pegawai';

//     $this->load->view('templates/header', $data);
//     $this->load->view('admin/laporan_gaji', $data);
//     $this->load->view('templates/footer');
// }

// public function laporan_gaji() {
//     $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default bulan ini

//     // Query untuk laporan gaji pegawai
//     $this->db->select('
//         pegawai.id, 
//         pegawai.nama, 
//         SUM(CASE WHEN rekap_absensi.lama_menit_kerja > 0 THEN 1 ELSE 0 END) AS total_kehadiran, 
//         SUM(rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
//         COALESCE(SUM(rekap_absensi.total_gaji), 0) AS total_gaji, 
//         COALESCE(SUM(lembur.total_gaji_lembur), 0) AS total_gaji_lembur,
//         COALESCE(pegawai.tambahan_lain, 0) AS tambahan_master, -- Tambahan dari master pegawai
//         COALESCE(SUM(DISTINCT tambahan_lain.nilai_tambahan), 0) AS total_tambahan_lain, -- Tambahan lain dihitung sekali
//         COALESCE(SUM(DISTINCT potongan.nilai), 0) AS total_potongan, -- Potongan dihitung sekali
//         COALESCE(SUM(DISTINCT deposit.nilai), 0) AS total_deposit, -- Deposit dihitung sekali
//         (
//             SELECT COALESCE(SUM(kasbon.nilai), 0) 
//             FROM kasbon 
//             WHERE kasbon.pegawai_id = pegawai.id 
//             AND DATE_FORMAT(kasbon.tanggal, "%Y-%m") = "'.$bulan.'"
//             AND kasbon.jenis = "bayar"
//         ) AS total_kasbon -- Bayar kasbon dihitung sekali dari subquery
//     ');

//     $this->db->from('pegawai');
//     $this->db->join('rekap_absensi', 'rekap_absensi.pegawai_id = pegawai.id AND DATE_FORMAT(rekap_absensi.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
//     $this->db->join('lembur', 'lembur.pegawai_id = pegawai.id AND DATE_FORMAT(lembur.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
//     $this->db->join('tambahan_lain', 'tambahan_lain.pegawai_id = pegawai.id AND DATE_FORMAT(tambahan_lain.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
//     $this->db->join('potongan', 'potongan.pegawai_id = pegawai.id AND DATE_FORMAT(potongan.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
//     $this->db->join('deposit', 'deposit.pegawai_id = pegawai.id AND DATE_FORMAT(deposit.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
//     $this->db->where('pegawai.kode_user !=', 'admin'); // Jangan tampilkan pegawai tipe admin
//     $this->db->group_by('pegawai.id'); // Group by id pegawai
//     $this->db->order_by('pegawai.id', 'ASC');

//     $data['laporan_gaji'] = $this->db->get()->result();
//     $data['bulan'] = $bulan;
//     $data['title'] = 'Laporan Gaji Pegawai';

//     $this->load->view('templates/header', $data);
//     $this->load->view('admin/laporan_gaji', $data);
//     $this->load->view('templates/footer');
// }

public function laporan_gaji() {
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default bulan ini

    // Query untuk laporan gaji pegawai
    $this->db->select('
        pegawai.id, 
        pegawai.nama, 
        SUM(CASE WHEN rekap_absensi.lama_menit_kerja > 0 THEN 1 ELSE 0 END) AS total_kehadiran, 
        SUM(rekap_absensi.lama_menit_kerja) AS total_lama_kerja, 
        COALESCE(SUM(rekap_absensi.total_gaji), 0) AS total_gaji, 
        (
            SELECT COALESCE(SUM(lembur.total_gaji_lembur), 0)
            FROM lembur
            WHERE lembur.pegawai_id = pegawai.id
            AND DATE_FORMAT(lembur.tanggal, "%Y-%m") = "'.$bulan.'"
        ) AS total_gaji_lembur,
        COALESCE(pegawai.tambahan_lain, 0) AS tambahan_master,
        COALESCE(SUM(DISTINCT tambahan_lain.nilai_tambahan), 0) AS total_tambahan_lain,
        COALESCE(SUM(DISTINCT potongan.nilai), 0) AS total_potongan,
        COALESCE(SUM(DISTINCT deposit.nilai), 0) AS total_deposit,
        (
            SELECT COALESCE(SUM(kasbon.nilai), 0) 
            FROM kasbon 
            WHERE kasbon.pegawai_id = pegawai.id 
            AND DATE_FORMAT(kasbon.tanggal, "%Y-%m") = "'.$bulan.'"
            AND kasbon.jenis = "bayar"
        ) AS total_kasbon
    ');

    $this->db->from('pegawai');
    $this->db->join('rekap_absensi', 'rekap_absensi.pegawai_id = pegawai.id AND DATE_FORMAT(rekap_absensi.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
    $this->db->join('tambahan_lain', 'tambahan_lain.pegawai_id = pegawai.id AND DATE_FORMAT(tambahan_lain.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
    $this->db->join('potongan', 'potongan.pegawai_id = pegawai.id AND DATE_FORMAT(potongan.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
    $this->db->join('deposit', 'deposit.pegawai_id = pegawai.id AND DATE_FORMAT(deposit.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
    $this->db->where('pegawai.kode_user !=', 'admin'); // Jangan tampilkan pegawai tipe admin
    $this->db->group_by('pegawai.id');
    $this->db->order_by('pegawai.id', 'ASC');

    $data['laporan_gaji'] = $this->db->get()->result();
    $data['bulan'] = $bulan;
    $data['title'] = 'Laporan Gaji Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/laporan_gaji', $data);
    $this->load->view('templates/footer');
}


public function detail_laporan_gaji($pegawai_id) {
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default bulan ini

    // Query detail laporan gaji dengan Tambahan Lain, Potongan, Deposit, dan Kasbon
    $this->db->select('
        rekap_absensi.tanggal,
        rekap_absensi.jam_masuk,
        rekap_absensi.jam_pulang,
        rekap_absensi.lama_menit_kerja,
        rekap_absensi.total_gaji,
        COALESCE(lembur.total_gaji_lembur, 0) AS total_gaji_lembur,
        COALESCE(tambahan_lain.nilai_tambahan, 0) AS tambahan_lain,
        COALESCE(potongan.nilai, 0) AS potongan,
        COALESCE(deposit.nilai, 0) AS deposit,
        COALESCE(kasbon.total_bayar, 0) AS kasbon_bayar,
        shift.kode_shift AS shift
    ');

    $this->db->from('rekap_absensi');

    // Subquery untuk Lembur
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(total_gaji_lembur) AS total_gaji_lembur 
                      FROM lembur 
                      WHERE DATE_FORMAT(tanggal, "%Y-%m") = "'.$bulan.'" 
                      GROUP BY tanggal, pegawai_id) AS lembur', 
                      'lembur.pegawai_id = rekap_absensi.pegawai_id AND lembur.tanggal = rekap_absensi.tanggal', 
                      'left');

    // Subquery untuk Tambahan Lain
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(nilai_tambahan) AS nilai_tambahan 
                      FROM tambahan_lain 
                      WHERE DATE_FORMAT(tanggal, "%Y-%m") = "'.$bulan.'"
                      GROUP BY tanggal, pegawai_id) AS tambahan_lain',
                      'tambahan_lain.pegawai_id = rekap_absensi.pegawai_id AND tambahan_lain.tanggal = rekap_absensi.tanggal',
                      'left');

    // Subquery untuk Potongan
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(nilai) AS nilai 
                      FROM potongan 
                      WHERE DATE_FORMAT(tanggal, "%Y-%m") = "'.$bulan.'"
                      GROUP BY tanggal, pegawai_id) AS potongan',
                      'potongan.pegawai_id = rekap_absensi.pegawai_id AND potongan.tanggal = rekap_absensi.tanggal',
                      'left');

    // Subquery untuk Deposit
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(nilai) AS nilai 
                      FROM deposit 
                      WHERE DATE_FORMAT(tanggal, "%Y-%m") = "'.$bulan.'"
                      GROUP BY tanggal, pegawai_id) AS deposit',
                      'deposit.pegawai_id = rekap_absensi.pegawai_id AND deposit.tanggal = rekap_absensi.tanggal',
                      'left');

    // Subquery untuk Kasbon Jenis Bayar
    $this->db->join('(SELECT tanggal, pegawai_id, SUM(nilai) AS total_bayar 
                      FROM kasbon 
                      WHERE jenis = "bayar" AND DATE_FORMAT(tanggal, "%Y-%m") = "'.$bulan.'"
                      GROUP BY tanggal, pegawai_id) AS kasbon',
                      'kasbon.pegawai_id = rekap_absensi.pegawai_id AND kasbon.tanggal = rekap_absensi.tanggal',
                      'left');

    $this->db->join('shift', 'rekap_absensi.shift_id = shift.id', 'left');
    $this->db->where('rekap_absensi.pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(rekap_absensi.tanggal, '%Y-%m') =", $bulan);
    $this->db->group_by(['rekap_absensi.tanggal', 'rekap_absensi.pegawai_id']);
    $this->db->order_by('rekap_absensi.tanggal', 'ASC');

    $data['detail_gaji'] = $this->db->get()->result();
    $data['pegawai'] = $this->db->get_where('pegawai', ['id' => $pegawai_id])->row();
    $data['bulan'] = $bulan;
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

        $this->db->insert('absensi', $data_absen);

        // Update rekap absensi
        $this->update_rekap_absensi($pegawai_id, $shift_id, $tanggal);

        $this->session->set_flashdata('success', 'Absensi berhasil disimpan.');
        redirect('admin/absen_pegawai');
    }

    // Ambil data pegawai dan shift untuk form
    $data['pegawai'] = $this->db->get('pegawai')->result();
    $data['shift'] = $this->db->get('shift')->result();
    $data['title'] = 'Absen Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('admin/absen_pegawai', $data);
    $this->load->view('templates/footer');
}

private function update_rekap_absensi($pegawai_id, $shift_id, $tanggal) {
    // Ambil data shift
    $shift = $this->db->get_where('shift', ['id' => $shift_id])->row();

    if (!$shift) {
        return; // Jika shift tidak ditemukan, keluar dari fungsi
    }

    // Ambil data absen pegawai untuk tanggal yang dipilih
    $absensi = $this->db->select('
            MIN(CASE WHEN jenis_absen = "masuk" THEN waktu END) AS jam_masuk,
            MAX(CASE WHEN jenis_absen = "pulang" THEN waktu END) AS jam_pulang
        ')
        ->from('absensi')
        ->where('pegawai_id', $pegawai_id)
        ->where('tanggal', $tanggal)
        ->group_by('pegawai_id')
        ->get()
        ->row();

    $jam_masuk = $absensi->jam_masuk ?? null;
    $jam_pulang = $absensi->jam_pulang ?? null;

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

    // Hitung lama kerja
    $lama_menit_kerja = 0;
    if ($jam_masuk && $jam_pulang) {
        $lama_menit_kerja = max(0, (strtotime($jam_pulang) - strtotime($jam_masuk)) / 60 - $terlambat - $pulang_cepat);
    }

    // Hitung total gaji
    $gaji_per_menit = ($this->db->get_where('pegawai', ['id' => $pegawai_id])->row()->gaji_per_jam ?? 0) / 60;
    $total_gaji = $lama_menit_kerja * $gaji_per_menit;

    // Data untuk update rekap_absensi
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
    $existing_rekap = $this->db->get_where('rekap_absensi', [
        'pegawai_id' => $pegawai_id,
        'tanggal' => $tanggal
    ])->row();

    if ($existing_rekap) {
        $this->db->update('rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
    } else {
        $this->db->insert('rekap_absensi', $rekap_data);
    }
}

public function generate_rekap_absensi_harian() {
    $today = date('Y-m-d'); // Tanggal hari ini

    // Ambil semua pegawai kecuali tipe admin
    $pegawai_list = $this->db->where('kode_user !=', 'admin')->get('pegawai')->result();

    foreach ($pegawai_list as $pegawai) {
        // Cek apakah sudah ada data rekap_absensi untuk hari ini
        $rekap_exist = $this->db->get_where('rekap_absensi', [
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
            $this->db->insert('rekap_absensi', $data_insert);
        }
    }

    echo "Rekap absensi harian berhasil dibuat untuk tanggal: " . $today;
}


}
