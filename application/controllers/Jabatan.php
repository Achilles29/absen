<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Jabatan_model');
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
    }

    // Halaman Divisi
    public function index() {
        $data['title'] = 'Data Jabatan';
        $data['jabatan'] = $this->Jabatan_model->get_all_jabatan();
        $this->load->view('templates/header', $data);
        $this->load->view('jabatan/index', $data);
        $this->load->view('templates/footer');
    }

    // Halaman Jabatan
    public function jabatan() {
        $data['title'] = 'Data Jabatan';
        $data['jabatan'] = $this->Jabatan_model->get_all_jabatan();
       // $data['divisi'] = $this->Divisi_model->get_all_divisi(); // Untuk dropdown divisi

        $this->load->view('templates/header', $data);
        $this->load->view('jabatan/jabatan', $data);
        $this->load->view('templates/footer');
    }

    // Tambah Jabatan
    public function tambah_jabatan() {
        if ($this->input->post()) {
            $this->Divisi_model->tambah_jabatan([
                'divisi_id' => $this->input->post('divisi_id'),
                'nama_jabatan' => $this->input->post('nama_jabatan')
            ]);
            $this->session->set_flashdata('success', 'Jabatan berhasil ditambahkan!');
            redirect('jabatan/jabatan');
        }

        $data['divisi'] = $this->Divisi_model->get_all_divisi();
        $this->load->view('templates/header');
        $this->load->view('jabatan/tambah_jabatan', $data);
        $this->load->view('templates/footer');
    }

    // Edit Jabatan
    public function edit_jabatan($id) {
        if ($this->input->post()) {
            $this->Divisi_model->update_jabatan($id, [
                'divisi_id' => $this->input->post('divisi_id'),
                'nama_jabatan' => $this->input->post('nama_jabatan')
            ]);
            $this->session->set_flashdata('success', 'Jabatan berhasil diperbarui!');
            redirect('jabatan/jabatan');
        }

        $data['jabatan'] = $this->Jabatan_model->get_jabatan_by_id($id);
        $data['divisi'] = $this->Divisi_model->get_all_divisi();

        $this->load->view('templates/header', $data);
        $this->load->view('jabatan/edit_jabatan', $data);
        $this->load->view('templates/footer');
    }

    // Hapus Jabatan
    public function hapus_jabatan($id) {
        $this->Jabatan_model->delete_jabatan($id);
        $this->session->set_flashdata('success', 'Jabatan berhasil dihapus!');
        redirect('jabatan/jabatan');
    }

}
