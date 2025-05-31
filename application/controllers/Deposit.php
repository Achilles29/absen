<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Deposit_model');
        $this->load->model('Pegawai_model');
    }

    // Halaman Rekapitulasi Deposit
    public function index() {
        $data['title'] = 'Rekapitulasi Deposit';
        $data['bulan'] = $this->input->get('bulan') ?? date('Y-m');
        $data['rekap_deposit'] = $this->Deposit_model->get_rekap_deposit($data['bulan']); // Sudah difilter

        $this->load->view('templates/header', $data);
        $this->load->view('admin/deposit/index', $data);
        $this->load->view('templates/footer');
    }
        

    // Form Input Deposit
    public function input() {
        $data['title'] = 'Input Deposit';
        $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();

        if ($this->input->post()) {
            $deposit_data = [
                'pegawai_id' => $this->input->post('pegawai_id'),
                'tanggal' => $this->input->post('tanggal'),
                'nilai' => $this->input->post('nilai'),
                'jenis' => $this->input->post('jenis'),
                'keterangan' => $this->input->post('keterangan')
            ];
            $this->Deposit_model->insert_deposit($deposit_data);
            $this->session->set_flashdata('success', 'Deposit berhasil ditambahkan.');
            redirect('deposit/index');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('admin/deposit/input', $data);
        $this->load->view('templates/footer');
    }

    // Detail Deposit
public function detail($pegawai_id) {
    $bulan = $this->input->get('bulan') ?? date('Y-m');
    $data['title'] = 'Detail Deposit Pegawai';
    $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
    $data['detail_deposit'] = $this->Deposit_model->get_detail_deposit($pegawai_id, $bulan);
    $data['bulan'] = $bulan;

    $this->load->view('templates/header', $data);
    $this->load->view('admin/deposit/detail', $data);
    $this->load->view('templates/footer');
}
    // Log Deposit
    public function log() {
        $data['title'] = 'Log Deposit';
        $data['bulan'] = $this->input->get('bulan') ?? date('m');
        $data['tahun'] = $this->input->get('tahun') ?? date('Y');
        $data['log_deposit'] = $this->Deposit_model->get_log_deposit($data['bulan'], $data['tahun']);

        $this->load->view('templates/header', $data);
        $this->load->view('admin/deposit/log', $data);
        $this->load->view('templates/footer');
    }

    public function edit_deposit() {
        $id = $this->input->post('id');
        $tanggal = $this->input->post('tanggal');
        $nilai = $this->input->post('nilai');
        $keterangan = $this->input->post('keterangan');

        // Perbarui data deposit
        $this->db->where('id', $id);
        $success = $this->db->update('abs_deposit', [
            'tanggal' => $tanggal,
            'nilai' => $nilai,
            'keterangan' => $keterangan
        ]);

        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Deposit berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui deposit.']);
        }
    }
    // Fungsi Hapus Deposit
public function delete_deposit() {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if ($id) {
        $this->db->where('id', $id);
        if ($this->db->delete('abs_deposit')) {
            echo json_encode(['status' => 'success', 'message' => 'Deposit berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus deposit.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID deposit tidak ditemukan.']);
    }
}
public function rincian($pegawai_id) {
    $data['title'] = 'Rincian Deposit Pegawai';
    $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id); // Ambil data pegawai
    $data['rincian_deposit'] = $this->Deposit_model->get_all_deposit_by_pegawai($pegawai_id); // Semua data deposit

    $this->load->view('templates/header', $data);
    $this->load->view('admin/deposit/rincian', $data);
    $this->load->view('templates/footer');
}

}