<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gaji extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $allowed_roles = ['admin', 'pegawai', 'spv', 'hod']; // Daftar role yang diizinkan
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login'); // Redirect jika tidak memiliki izin
        }
        $this->load->model('Gaji_model'); // Load model untuk data gaji
        $this->load->model('Pegawai_model'); // Load model untuk data pegawai
    }


public function index() {
    $pegawai_id = $this->session->userdata('id'); // Ambil ID pegawai dari sesi login
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Filter bulan (default bulan ini)

    // Ambil data gaji dan profil pegawai
    $data['detail_gaji'] = $this->Gaji_model->get_detail_gaji($pegawai_id, $bulan);
    $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
    $data['bulan'] = $bulan;
    $data['title'] = 'Laporan Gaji';

    // Load tampilan laporan gaji
    $this->load->view('templates/header', $data);
    $this->load->view('pegawai/laporan_gaji', $data);
    $this->load->view('templates/footer');
}
    public function get_slip_gaji($pegawai_id, $start_date, $end_date) {
        // Query untuk menghitung gaji pokok berdasarkan range tanggal
        $this->db->select('SUM(total_gaji) AS gaji_pokok');
        $this->db->from('abs_rekap_absensi');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $gaji_pokok = $this->db->get()->row()->gaji_pokok ?? 0;

        // Query untuk menghitung lembur berdasarkan range tanggal
        $this->db->select('SUM(total_gaji_lembur) AS total_lembur');
        $this->db->from('abs_lembur');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $lembur = $this->db->get()->row()->total_lembur ?? 0;

        // Query untuk menghitung total terlambat dan pulang cepat
        $this->db->select('SUM(terlambat + pulang_cepat) AS total_telat');
        $this->db->from('abs_rekap_absensi');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $telat = $this->db->get()->row()->total_telat ?? 0;

        // Query untuk menghitung potongan
        $this->db->select('SUM(nilai) AS total_potongan');
        $this->db->from('abs_potongan');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $potongan = $this->db->get()->row()->total_potongan ?? 0;

        // Query untuk menghitung tambahan lain
        $this->db->select('SUM(nilai_tambahan) AS total_tambahan');
        $this->db->from('abs_tambahan_lain');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $tambahan = $this->db->get()->row()->total_tambahan ?? 0;

        // Hitung total gaji
        $total_gaji = $gaji_pokok + $lembur + $tambahan - $potongan;

        return [
            'gaji_pokok' => $gaji_pokok,
            'lembur' => $lembur,
            'telat' => $telat,
            'potongan' => $potongan,
            'tambahan' => $tambahan,
            'total_gaji' => $total_gaji
        ];
    }
}
