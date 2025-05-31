<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Divisi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Divisi_model');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login'); // Redirect ke halaman login jika belum login
        }
        // Periksa role, hanya admin dan spv yang bisa mengakses
        $allowed_roles = ['admin', 'spv'];
        if (!in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login'); // Redirect jika role bukan admin atau spv
        }
    }

    // Halaman Divisi
    public function index() {
        $data['title'] = 'Data Divisi';
        $data['divisi'] = $this->Divisi_model->get_all_divisi();
        $this->load->view('templates/header', $data);
        $this->load->view('divisi/index', $data);
        $this->load->view('templates/footer');
    }

    // Tambah Divisi
    public function tambah() {
        if ($this->input->post()) {
            $this->Divisi_model->tambah_divisi($this->input->post('nama_divisi'));
            redirect('divisi');
        }
        $this->load->view('templates/header');
        $this->load->view('divisi/tambah');
        $this->load->view('templates/footer');
    }

    // Edit Divisi
    public function edit($id) {
        if ($this->input->post()) {
            $this->Divisi_model->edit_divisi($id, $this->input->post('nama_divisi'));
            redirect('divisi');
        }
        $data['divisi'] = $this->Divisi_model->get_divisi_by_id($id);
        $this->load->view('templates/header', $data);
        $this->load->view('divisi/edit', $data);
        $this->load->view('templates/footer');
    }

    // Hapus Divisi
    public function hapus($id) {
        $this->Divisi_model->hapus_divisi($id);
        redirect('divisi');
    }

    // Halaman Jabatan
    public function jabatan() {
        $data['title'] = 'Data Jabatan';
        $data['jabatan'] = $this->Divisi_model->get_all_jabatan();
        $data['divisi'] = $this->Divisi_model->get_all_divisi(); // Untuk dropdown divisi

        $this->load->view('templates/header', $data);
        $this->load->view('divisi/jabatan', $data);
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
            redirect('divisi/jabatan');
        }

        $data['divisi'] = $this->Divisi_model->get_all_divisi();
        $this->load->view('templates/header');
        $this->load->view('divisi/tambah_jabatan', $data);
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
            redirect('divisi/jabatan');
        }

        $data['jabatan'] = $this->Divisi_model->get_jabatan_by_id($id);
        $data['divisi'] = $this->Divisi_model->get_all_divisi();

        $this->load->view('templates/header', $data);
        $this->load->view('divisi/edit_jabatan', $data);
        $this->load->view('templates/footer');
    }

    // Hapus Jabatan
    public function hapus_jabatan($id) {
        $this->Divisi_model->delete_jabatan($id);
        $this->session->set_flashdata('success', 'Jabatan berhasil dihapus!');
        redirect('divisi/jabatan');
    }

}
