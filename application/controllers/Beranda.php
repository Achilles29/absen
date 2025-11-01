<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {
    public function __construct() {
        parent::__construct();

        // Periksa apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login'); // Redirect ke halaman login jika belum login
        }

        // Load semua model yang diperlukan
        $this->load->model('Pegawai_model');
        $this->load->model('Lembur_model');
        $this->load->model('Kasbon_model');
        $this->load->model('Potongan_model');
        $this->load->model('Tambahan_model');
        $this->load->model('Divisi_model');
        $this->load->model('Deposit_model');
        $this->load->model('Jadwal_model');
        $this->load->model('Rekap_model'); // Pastikan Rekap_model diload
    }

    /**
     * Redirect user ke dashboard sesuai dengan role yang dimiliki.
     */
    public function index() {
        $role = $this->session->userdata('role');

        if ($role === 'admin') {
            redirect('admin');
        } elseif ($role === 'pegawai') {
            redirect('pegawai');
        }

        // Jika tidak ada role yang cocok, arahkan ke halaman login
        redirect('auth/login');
    }

    /**
     * Menampilkan dashboard berisi ringkasan data untuk admin.
     */
    public function dashboard() {
        // Ambil data dari model
        $total_gaji_rekap = $this->Rekap_model->calculate_total_gaji(); // Total dari tabel abs_rekap_absensi
        $total_lembur_bulan_ini = $this->Lembur_model->calculate_total_lembur(); // Lembur bulan ini
        $total_kasbon = $this->Kasbon_model->calculate_total_kasbon(); // Total kasbon (kasbon - bayar)
        $total_tambahan_lain = $this->Tambahan_model->calculate_total_tambahan(); // Tambahan dari abs_pegawai + tabel abs_tambahan_lain
        $total_potongan = $this->Potongan_model->calculate_total_potongan(); // Potongan bulan ini
        $total_deposit = $this->Deposit_model->calculate_total_deposit(); // Total deposit (setor - tarik)

        // Hitung gaji berjalan
        $total_gaji_berjalan = 
            $total_gaji_rekap +
            $total_lembur_bulan_ini +
            $total_tambahan_lain -
            $total_kasbon -
            $total_deposit -
            $total_potongan;

        // Hitung gaji berjalan2
        $total_gaji_berjalan2 = 
            $total_gaji_rekap +
            $total_lembur_bulan_ini +
            $total_tambahan_lain -
            $total_deposit -
            $total_potongan;


        // Data lain untuk dashboard
        $data = [
            'total_pegawai' => $this->Pegawai_model->count_all_pegawai(),
            'total_lembur' => $total_lembur_bulan_ini,
            'total_kasbon' => $total_kasbon,
            'total_gaji_berjalan' => $total_gaji_berjalan,
            'total_gaji_berjalan2' => $total_gaji_berjalan2,
            'total_divisi' => $this->Divisi_model->count_all_divisi(),
            'total_tambahan_lain' => $total_tambahan_lain,
            'total_potongan' => $total_potongan,
            'total_shift' => $this->Jadwal_model->count_all_shifts(),
            'total_deposit' => $total_deposit,
            'title' => 'Beranda'
        ];

        // Tampilkan dashboard
        $this->load->view('templates/header', $data);
        $this->load->view('beranda/dashboard', $data);
        $this->load->view('templates/footer');
    }
}
