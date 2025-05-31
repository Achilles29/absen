<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Periksa apakah pengguna sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login'); // Redirect ke halaman login jika belum login
        }
        // Periksa role, hanya admin dan spv yang bisa mengakses
        $allowed_roles = ['admin', 'spv'];
        if (!in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login'); // Redirect jika role bukan admin atau spv
        }
    }

    // Contoh fungsi lain di controller ini
    public function index() {
        $data['title'] = 'Lokasi Absen';
        $this->load->view('templates/header', $data);
        $this->load->view('lokasi/lokasi_absen', $data);
        $this->load->view('templates/footer');
    }
// Lokasi absen
public function lokasi_absen() {
    $data['title'] = 'Daftar Lokasi Absensi';

    // Ambil semua lokasi dari tabel lokasi_absensi
    $data['lokasi_list'] = $this->db->get('abs_lokasi_absensi')->result();

    // Load view dengan data lokasi
    $this->load->view('templates/header', $data);
    $this->load->view('lokasi/lokasi_absen', $data);
    $this->load->view('templates/footer');
}
    public function lokasi_absensi() {
        if ($this->input->post()) {
            $data = [
                'nama_lokasi' => $this->input->post('nama_lokasi'),
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude'),
                'range' => $this->input->post('range')
            ];

            // Update lokasi jika sudah ada, atau tambahkan baru
            $lokasi = $this->db->get('abs_lokasi_absensi')->row();
            if ($lokasi) {
                $this->db->update('abs_lokasi_absensi', $data, ['id' => $lokasi->id]);
            } else {
                $this->db->insert('abs_lokasi_absensi', $data);
            }

            $this->session->set_flashdata('success', 'Lokasi absensi berhasil disimpan!');
            redirect('lokasi_absensi');
        }
        $data['title'] = 'Lokasi Absensi'; // Tambahkan title untuk halaman
        $data['lokasi'] = $this->db->get('abs_lokasi_absensi')->row();
        $this->load->view('templates/header', $data);
        $this->load->view('lokasi/lokasi_absensi', $data);
        $this->load->view('templates/footer', $data);
    }




public function tambah_lokasi() {
    if ($this->input->post()) {
        $data = [
            'nama_lokasi' => $this->input->post('nama_lokasi'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'status' => $this->input->post('status')
        ];
        $this->db->insert('abs_lokasi_absensi', $data);
        $this->session->set_flashdata('success', 'Lokasi berhasil ditambahkan');
        redirect('lokasi_absensi');
    }
}
public function edit_lokasi($id) {
    // Cek apakah lokasi dengan ID yang dipilih ada
    $lokasi = $this->db->get_where('abs_lokasi_absensi', ['id' => $id])->row();

    if (!$lokasi) {
        $this->session->set_flashdata('error', 'Lokasi tidak ditemukan.');
        redirect('lokasi_absen');
    }

    $data['title'] = 'Edit Lokasi Absen';
    $data['lokasi'] = $lokasi;

    // Jika form disubmit
    if ($this->input->post()) {
        $update_data = [
            'nama_lokasi' => $this->input->post('nama_lokasi'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'range' => $this->input->post('range'),
            'status' => $this->input->post('is_active') ?? 0
        ];

        $this->db->where('id', $id)->update('abs_lokasi_absensi', $update_data);
        $this->session->set_flashdata('success', 'Lokasi berhasil diperbarui.');
        redirect('lokasi_absen');
    }

    // Tampilkan view edit
    $this->load->view('templates/header', $data);
    $this->load->view('lokasi/edit_lokasi', $data);
    $this->load->view('templates/footer');
}
public function hapus_lokasi($id) {
    // Cek apakah lokasi dengan ID yang dipilih ada
    $lokasi = $this->db->get_where('abs_lokasi_absensi', ['id' => $id])->row();

    if (!$lokasi) {
        $this->session->set_flashdata('error', 'Lokasi tidak ditemukan.');
    } else {
        // Hapus lokasi
        $this->db->delete('abs_lokasi_absensi', ['id' => $id]);
        $this->session->set_flashdata('success', 'Lokasi berhasil dihapus.');
    }

    redirect('lokasi_absen');
}


public function ubah_status_lokasi($id, $status) {
    $this->db->where('id', $id)->update('abs_lokasi_absensi', ['status' => $status]);
    $this->session->set_flashdata('success', 'Status lokasi berhasil diperbarui');
    redirect('lokasi_absensi');
}

public function simpan_lokasi_absen() {
    $data = [
        'nama_lokasi' => $this->input->post('nama_lokasi'),
        'latitude'    => $this->input->post('latitude'),
        'longitude'   => $this->input->post('longitude'),
        'range'       => $this->input->post('range'),
        'status'      => $this->input->post('status') ?? 'aktif'
    ];

    // Periksa jika lokasi sudah ada
    $existing = $this->db->get_where('abs_lokasi_absensi', ['nama_lokasi' => $data['nama_lokasi']])->row();
    if ($existing) {
        // Update lokasi jika sudah ada
        $this->db->update('abs_lokasi_absensi', $data, ['id' => $existing->id]);
    } else {
        // Tambahkan lokasi baru
        $this->db->insert('abs_lokasi_absensi', $data);
    }

    $this->session->set_flashdata('success', 'Lokasi berhasil disimpan!');
    redirect('lokasi_absen'); // Redirect kembali ke halaman lokasi_absen
}
// batas lokasi absen


}
