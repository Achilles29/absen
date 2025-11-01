<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasbon extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Kasbon_model');
        $this->load->model('Pegawai_model');
    }

    public function index() {
        $data['title'] = 'Rekapitulasi Kasbon';
        $data['bulan'] = $this->input->get('bulan') ?? date('Y-m');

        // Ambil rekap kasbon per bulan hanya untuk pegawai selain admin
        $pegawai_ids = array_map(function($pegawai) {
            return $pegawai->id;
        }, $this->Pegawai_model->get_all_pegawai_except_admin());

        if (empty($pegawai_ids)) {
            $data['rekap_kasbon'] = []; // Jika tidak ada pegawai, kosongkan hasil
        } else {
            $data['rekap_kasbon'] = $this->Kasbon_model->get_rekap_kasbon($data['bulan'], $pegawai_ids);
        }

        $this->load->view('templates/header', $data);
        $this->load->view('admin/kasbon/index', $data);
        $this->load->view('templates/footer');
    }

    public function input() {
        $data['title'] = 'Input Kasbon';
        $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();
        // Ambil data pegawai kecuali admin
        $data['pegawai'] = $this->Pegawai_model->get_all_pegawai_except_admin();

        if ($this->input->post()) {
            $kasbon_data = [
                'pegawai_id' => $this->input->post('pegawai_id'),
                'tanggal' => $this->input->post('tanggal'),
                'nilai' => $this->input->post('nilai'),
                'jenis' => $this->input->post('jenis'),
                'keterangan' => $this->input->post('keterangan'),
            ];
            $this->Kasbon_model->insert_kasbon($kasbon_data);
            $this->session->set_flashdata('success', 'Kasbon berhasil ditambahkan.');
            redirect('kasbon/index');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('admin/kasbon/input', $data);
        $this->load->view('templates/footer');
    }

    public function detail($pegawai_id) {
        $bulan = $this->input->get('bulan') ?? date('Y-m');
        $data['title'] = 'Detail Kasbon Pegawai';
        $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);
        $data['detail_kasbon'] = $this->Kasbon_model->get_detail_kasbon($pegawai_id, $bulan);
        $data['sisa_kasbon_total'] = $this->Kasbon_model->get_sisa_kasbon_total($pegawai_id);
        $data['bulan'] = $bulan;

        $this->load->view('templates/header', $data);
        $this->load->view('admin/kasbon/detail', $data);
        $this->load->view('templates/footer');
    }

public function log_kasbon() {
    $bulan = $this->input->get('bulan') ?? date('m');
    $tahun = $this->input->get('tahun') ?? date('Y');

    $data['title'] = 'Log Kasbon Pegawai';
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;

    $this->db->select('abs_kasbon.*, abs_pegawai.nama');
    $this->db->from('abs_kasbon');
    $this->db->join('abs_pegawai', 'abs_kasbon.pegawai_id = abs_pegawai.id');
    $this->db->where('MONTH(abs_kasbon.tanggal)', $bulan);
    $this->db->where('YEAR(abs_kasbon.tanggal)', $tahun);
    $this->db->order_by('abs_kasbon.tanggal', 'ASC');
    $data['log_kasbon'] = $this->db->get()->result();

    $this->load->view('templates/header', $data);
    $this->load->view('admin/kasbon/log_kasbon', $data);
    $this->load->view('templates/footer');
}

public function update() {
    $id = $this->input->post('id');
    $data = [
        'tanggal' => $this->input->post('tanggal'),
        'nilai' => $this->input->post('nilai'),
        'jenis' => $this->input->post('jenis'),
        'keterangan' => $this->input->post('keterangan')
    ];
    $this->db->where('id', $id);
    if ($this->db->update('abs_kasbon', $data)) {
        $this->session->set_flashdata('success', 'Kasbon berhasil diperbarui.');
    } else {
        $this->session->set_flashdata('error', 'Gagal memperbarui kasbon.');
    }
    redirect('kasbon/log_kasbon');
}

public function delete() {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if ($id) {
        $this->db->where('id', $id);
        if ($this->db->delete('abs_kasbon')) {
            echo json_encode(['status' => 'success', 'message' => 'Kasbon berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus kasbon.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID kasbon tidak ditemukan.']);
    }
}


}

