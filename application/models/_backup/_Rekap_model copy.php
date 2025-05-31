<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_model extends CI_Model {
    public function __construct() {
        parent::__construct();

        // Periksa apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login'); // Redirect ke halaman login jika belum login
        }

        // Load semua model yang diperlukan untuk dashboard
        $this->load->model('Pegawai_model');
        $this->load->model('Lembur_model');
        $this->load->model('Kasbon_model');
        $this->load->model('Potongan_model');
        $this->load->model('Tambahan_model');
        $this->load->model('Divisi_model');
        $this->load->model('Deposit_model');
        $this->load->model('Jadwal_model');
        $this->load->model('Rekap_model'); // Pastikan model Rekap_model diload
    }

    public function generate_absensi_harian() {
        // Ambil daftar pegawai non-admin
        $pegawai_list = $this->db->where('kode_user !=', 'admin')->get('abs_pegawai')->result();
        $today = date('Y-m-d');

        foreach ($pegawai_list as $pegawai) {
            // Cek apakah rekap sudah ada
            $exists = $this->db->get_where('abs_rekap_absensi', [
                'pegawai_id' => $pegawai->id,
                'tanggal' => $today
            ])->row();

            if (!$exists) {
                // Buat data kosong jika belum ada absen
                $data = [
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $today,
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'lama_menit_kerja' => 0,
                    'total_gaji' => 0
                ];
                $this->db->insert('abs_rekap_absensi', $data);
            }
        }
    }

    public function calculate_total_gaji() {
        $this->db->select_sum('total_gaji', 'total_gaji_rekap');
        $query = $this->db->get('abs_rekap_absensi');
        $result = $query->row();
        return $result ? (float)$result->total_gaji_rekap : 0;
    }
}
