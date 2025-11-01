<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Potongan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Potongan_model');
        $this->load->model('Pegawai_model');
    }

    // Halaman Rekapitulasi Potongan
    public function index() {
        $data['title'] = 'Rekapitulasi Potongan';
        $data['bulan'] = $this->input->get('bulan') ?? date('Y-m');
        $data['rekap_potongan'] = $this->Potongan_model->get_rekap_potongan($data['bulan']);

        $this->load->view('templates/header', $data);
        $this->load->view('admin/potongan/index', $data);
        $this->load->view('templates/footer');
    }

    // Form Input Potongan
    public function input() {
        $data['title'] = 'Input Potongan';
        $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();

        if ($this->input->post()) {
            $potongan_data = [
                'pegawai_id' => $this->input->post('pegawai_id'),
                'tanggal' => $this->input->post('tanggal'),
                'nilai' => $this->input->post('nilai'),
                'keterangan' => $this->input->post('keterangan')
            ];
            $this->Potongan_model->insert_potongan($potongan_data);
            $this->session->set_flashdata('success', 'Potongan berhasil ditambahkan.');
            redirect('potongan/index');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('admin/potongan/input', $data);
        $this->load->view('templates/footer');
    }

    // Detail Potongan
    public function detail($pegawai_id) {
        $bulan = $this->input->get('bulan') ?? date('Y-m');
        $data['title'] = 'Detail Potongan Pegawai';
        $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
        $data['detail_potongan'] = $this->Potongan_model->get_detail_potongan($pegawai_id, $bulan);
        $data['bulan'] = $bulan;

        $this->load->view('templates/header', $data);
        $this->load->view('admin/potongan/detail', $data);
        $this->load->view('templates/footer');
    }
   // Log Potongan
    public function log() {
        $bulan = $this->input->get('bulan') ?? date('m');
        $tahun = $this->input->get('tahun') ?? date('Y');
        $data['title'] = 'Log Potongan';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['log_potongan'] = $this->Potongan_model->get_log_potongan($bulan, $tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('admin/potongan/log', $data);
        $this->load->view('templates/footer');
    }

    // Edit Potongan
    public function edit() {
        $id = $this->input->post('id');
        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'nilai' => $this->input->post('nilai'),
            'keterangan' => $this->input->post('keterangan'),
        ];
        $this->Potongan_model->update_potongan($id, $data);
        echo json_encode(['status' => 'success', 'message' => 'Potongan berhasil diperbarui!']);
    }

    // Hapus Potongan
public function delete() {
    // Ambil ID dari request
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if ($id) {
        // Hapus data berdasarkan ID
        if ($this->db->delete('abs_potongan', ['id' => $id])) {
            echo json_encode(['status' => 'success', 'message' => 'Potongan berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus potongan.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID potongan tidak ditemukan.']);
    }
}

}
