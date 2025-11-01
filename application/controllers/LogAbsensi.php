<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogAbsensi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Periksa apakah user sudah login
        $allowed_roles = ['pegawai', 'spv', 'hod']; // Daftar role yang diizinkan
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login'); // Redirect jika tidak memiliki izin
        }

        $this->load->model('LogAbsensi_model'); // Load model log absensi
        $this->load->model('Pegawai_model'); // Load model pegawai
    }

    // Rekap log absensi
public function index() {
    $pegawai_id = $this->session->userdata('id'); // ID pegawai dari sesi
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Filter bulan (default bulan ini)

    $log_absensi = $this->LogAbsensi_model->get_rekap_log_absensi($pegawai_id, $bulan);

    // Inisialisasi variabel total
    $total_kehadiran = 0;
    $total_terlambat = 0;
    $total_pulang_cepat = 0;
    $total_lama_kerja = 0;
    $total_gaji = 0;

    foreach ($log_absensi as $log) {
        $total_kehadiran++;
        $total_terlambat += $log->terlambat;
        $total_pulang_cepat += $log->pulang_cepat;
        $total_lama_kerja += $log->lama_menit_kerja;
        $total_gaji += $log->total_gaji;
    }

    $data['log_absensi'] = $log_absensi;
    $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
    $data['bulan'] = $bulan;
    $data['total_kehadiran'] = $total_kehadiran;
    $data['total_terlambat'] = $total_terlambat;
    $data['total_pulang_cepat'] = $total_pulang_cepat;
    $data['total_lama_kerja'] = $total_lama_kerja;
    $data['total_gaji'] = $total_gaji;
    $data['title'] = 'Rekap Log Absensi';

    $this->load->view('templates/header', $data);
    $this->load->view('pegawai/log_absensi', $data);
    $this->load->view('templates/footer');
}


    // Detail log absensi
    public function detail() {
        $pegawai_id = $this->session->userdata('id'); // ID pegawai dari sesi
        $bulan = $this->input->get('bulan') ?? date('Y-m'); // Filter bulan (default bulan ini)

        $data['log_absensi'] = $this->LogAbsensi_model->get_detail_log_absensi($pegawai_id, $bulan);
        $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
        $data['bulan'] = $bulan;
        $data['title'] = 'Detail Log Absensi';

        $this->load->view('templates/header', $data);
        $this->load->view('pegawai/log_absensi_detail', $data);
        $this->load->view('templates/footer');
    }
}
