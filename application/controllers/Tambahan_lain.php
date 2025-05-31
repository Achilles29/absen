<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tambahan_lain extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $allowed_roles = ['admin', 'spv']; // Daftar role yang diizinkan
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login'); // Redirect jika tidak memiliki izin
        }
        $this->load->model('Tambahan_model');
        $this->load->model('Pegawai_model');
    }

    // Halaman Rekapitulasi Tambahan Lain
    public function index() {
        $bulan = $this->input->get('bulan') ?? date('Y-m');
        $data['title'] = 'Rekapitulasi Tambahan Lain';
        $data['bulan'] = $bulan;

        // Ambil rekap tambahan lain
        $data['rekap_tambahan'] = $this->Tambahan_model->get_rekap_tambahan($bulan);

        $this->load->view('templates/header', $data);
        $this->load->view('tambahan_lain/index', $data);
        $this->load->view('templates/footer');
    }

    // Input Tambahan Lain
    public function input() {
        $data['title'] = 'Input Tambahan Gaji';
        $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();

        if ($this->input->post()) {
            $input_data = [
                'pegawai_id' => $this->input->post('pegawai_id'),
                'tanggal' => $this->input->post('tanggal'),
                'nilai_tambahan' => $this->input->post('nilai_tambahan'),
                'keterangan' => $this->input->post('keterangan'),
            ];
            $this->Tambahan_model->insert_tambahan($input_data);
            $this->session->set_flashdata('success', 'Tambahan berhasil ditambahkan.');
            redirect('tambahan_lain');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('tambahan_lain/input', $data);
        $this->load->view('templates/footer');
    }

    // Detail Tambahan Lain Per Pegawai
    public function detail($pegawai_id) {
        $bulan = $this->input->get('bulan') ?? date('Y-m');
        $data['title'] = 'Detail Tambahan Lain';
        $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
        $data['bulan'] = $bulan;

        $data['detail_tambahan'] = $this->Tambahan_model->get_detail_tambahan($pegawai_id, $bulan);

        $this->load->view('templates/header', $data);
        $this->load->view('tambahan_lain/detail', $data);
        $this->load->view('templates/footer');
    }
public function log_tambahan() {
    $bulan = $this->input->get('bulan') ?? date('m');
    $tahun = $this->input->get('tahun') ?? date('Y');
    $data['title'] = 'Log Tambahan Lain';

    // Ambil data tambahan lain dengan filter bulan dan tahun
    $data['log_tambahan'] = $this->Tambahan_model->get_log_tambahan($bulan, $tahun);
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;

    $this->load->view('templates/header', $data);
    $this->load->view('tambahan_lain/log_tambahan', $data);
    $this->load->view('templates/footer');
}

public function hapus_tambahan($id) {
    if ($this->Tambahan_model->delete_tambahan($id)) {
        echo json_encode(['status' => 'success', 'message' => 'Data tambahan berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data tambahan.']);
    }
}


public function edit_tambahan() {
    if ($this->input->post()) {
        $id = $this->input->post('id');
        $update_data = [
            'tanggal' => $this->input->post('tanggal'),
            'nilai_tambahan' => $this->input->post('nilai_tambahan'),
            'keterangan' => $this->input->post('keterangan')
        ];

        if ($this->Tambahan_model->update_tambahan($id, $update_data)) {
            $this->session->set_flashdata('success', 'Data tambahan berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data tambahan.');
        }
    }
    redirect('tambahan_lain/log_tambahan');
}


}
