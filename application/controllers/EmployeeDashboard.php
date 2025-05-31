<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeDashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $allowed_roles = ['pegawai', 'spv', 'hod']; // Daftar role yang diizinkan
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login'); // Redirect jika tidak memiliki izin
        }
        // Load model yang dibutuhkan
        $this->load->model('LogAbsensi_model');
        $this->load->model('Lembur_model');
        $this->load->model('Kasbon_model');
        $this->load->model('Potongan_model');
        $this->load->model('Tambahan_model');
        $this->load->model('Deposit_model');
        $this->load->model('Pegawai_model');
        $this->load->model('Jadwal_model'); // Model untuk jadwal shift
    }
    public function index() {
        $pegawai_id = $this->session->userdata('id'); // Ambil ID pegawai dari sesi

        // Ambil data untuk dashboard pegawai
        $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
        $data['total_kehadiran'] = $this->LogAbsensi_model->get_total_kehadiran($pegawai_id, date('Y-m'));
        $data['total_lembur'] = $this->Lembur_model->get_total_lembur($pegawai_id, date('Y-m'));
        $data['total_kasbon'] = $this->Kasbon_model->get_total_kasbon_pegawai($pegawai_id, date('Y-m'));
        $data['total_potongan'] = $this->Potongan_model->get_total_potongan($pegawai_id, date('Y-m'));
        
        // Ambil nilai tambahan_lain dari tabel abs_pegawai
        $pegawai_data = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
        $tambahan_lain_master = $pegawai_data->tambahan_lain ?? 0;

        // Hitung total tambahan lain (dari Tambahan_model dan tambahan_lain di abs_pegawai)
        $data['total_tambahan_lain'] = $this->Tambahan_model->get_total_tambahan($pegawai_id, date('Y-m')) + $tambahan_lain_master;

        $data['total_deposit'] = $this->Deposit_model->get_total_deposit($pegawai_id, date('Y-m'));
        $data['jumlah_shift'] = $this->Jadwal_model->get_jumlah_shift($pegawai_id, date('Y-m'));

        // Total gaji langsung dari abs_rekap_absensi
        $data['total_gaji'] = $this->LogAbsensi_model->get_total_gaji($pegawai_id, date('Y-m'));

            // Perhitungan gaji berjalan
        $data['gaji_berjalan'] = (
            ($data['total_gaji'] ?? 0) +
            ($data['total_lembur'] ?? 0) +
            ($data['total_tambahan_lain'] ?? 0) -
            ($data['total_potongan'] ?? 0) -
            ($data['total_kasbon'] ?? 0)
        );

        $data['title'] = 'Dashboard Pegawai';

        // Load view
        $this->load->view('templates/header', $data);
        $this->load->view('pegawai/dashboard', $data);
        $this->load->view('templates/footer');
    }



}
