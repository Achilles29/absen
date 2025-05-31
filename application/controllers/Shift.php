<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Periksa apakah pengguna sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        // Periksa role, hanya admin dan spv yang bisa mengakses
        $allowed_roles = ['admin', 'spv'];
        if (!in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login');
        }
    }


public function index() {
    // Ambil data abs_shift dengan join ke tabel abs_divisi
    $this->db->select('abs_shift.*, abs_divisi.nama_divisi');
    $this->db->from('abs_shift');
    $this->db->join('abs_divisi', 'abs_shift.divisi_id = abs_divisi.id');
    $data['shift'] = $this->db->get()->result();

    $data['divisi'] = $this->db->get('abs_divisi')->result(); // Untuk dropdown divisi
    $data['title'] = 'Daftar Shift Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('shift/index', $data);
    $this->load->view('templates/footer', $data);
}


public function tambah_shift() {
    if ($this->input->post()) {
        $data = [
            'divisi_id' => $this->input->post('divisi_id'),
            'nama_shift' => $this->input->post('nama_shift'),
            'kode_shift' => $this->input->post('kode_shift'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
        ];

        $this->db->insert('abs_shift', $data);
        $this->session->set_flashdata('success', 'Shift berhasil ditambahkan!');
        redirect('shift');
    }

    $data['divisi'] = $this->db->get('abs_divisi')->result();
    $data['title'] = 'Tambah Shift Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('shift/tambah_shift', $data);
    $this->load->view('templates/footer', $data);
}


public function edit_shift($id) {
    if ($this->input->post()) {
        $data = [
            'divisi_id' => $this->input->post('divisi_id'),
            'nama_shift' => $this->input->post('nama_shift'),
            'kode_shift' => $this->input->post('kode_shift'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
        ];

        $this->db->where('id', $id)->update('abs_shift', $data);
        $this->session->set_flashdata('success', 'Shift berhasil diperbarui!');
        redirect('shift');
    }

    $data['shift'] = $this->db->get_where('abs_shift', ['id' => $id])->row();
    $data['divisi'] = $this->db->get('abs_divisi')->result();
    $data['title'] = 'Edit Shift Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('shift/edit_shift', $data);
    $this->load->view('templates/footer', $data);
}


public function hapus_shift($id) {
    $this->db->delete('abs_shift', ['id' => $id]);
    $this->session->set_flashdata('success', 'Shift berhasil dihapus!');
    redirect('shift');

}

}
