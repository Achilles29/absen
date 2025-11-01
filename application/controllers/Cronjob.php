<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load model jika diperlukan
        $this->load->model('Rekap_model');
    }

    // Fungsi untuk generate rekap absensi harian
    public function generate_rekap_absensi_harian() {
        // Pastikan hanya bisa diakses dari server atau IP tertentu
        // $allowed_ips = ['127.0.0.1', '::1']; // Tambahkan IP Anda jika diperlukan
        // if (!in_array($this->input->ip_address(), $allowed_ips)) {
        //     show_error('Access denied. This endpoint is not public.', 403);
        //     return;
        // }

        // Logika untuk generate rekap_absensi
        $this->load->model('Rekap_model');
        $this->Rekap_model->generate_absensi_harian();

        // Output success
        echo "Rekap absensi harian berhasil dijalankan.";
    }
}
