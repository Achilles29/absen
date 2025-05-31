<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bank extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Bank_model');
    }

    // Halaman daftar rekening bank
    public function index() {
        $data['title'] = 'Daftar Rekening Bank';
        $data['banks'] = $this->Bank_model->get_all_banks();
        $this->load->view('templates/header', $data);
        $this->load->view('bank/index', $data);
        $this->load->view('templates/footer');
    }

    // Tambah rekening bank
    public function add() {
        $this->form_validation->set_rules('nama_bank', 'Nama Bank', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Nama bank wajib diisi.');
            redirect('bank');
        }

        $data = ['nama_bank' => $this->input->post('nama_bank')];
        $this->Bank_model->insert_bank($data);

        $this->session->set_flashdata('success', 'Rekening bank berhasil ditambahkan.');
        redirect('bank');
    }

    // Hapus rekening bank
    public function delete($id) {
        $this->Bank_model->delete_bank($id);
        $this->session->set_flashdata('success', 'Rekening bank berhasil dihapus.');
        redirect('bank');
    }
}

