<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login'); // Redirect jika belum login
        }
        $this->load->model('Profil_model');
    }

// public function index() {
//     $id = $this->session->userdata('id');
//     $role = $this->session->userdata('role');
//     $data['title'] = 'Profil';

//     if ($role === 'admin') {
//         // Ambil data admin
//         $data['profil'] = $this->Profil_model->get_admin_by_id($id);
//         $data['role'] = 'admin';
//     } else {
//         // Ambil data pegawai lengkap
//         $data['profil'] = $this->Profil_model->get_user_with_details($id);
//         $data['role'] = 'pegawai';
//     }

//     $this->load->view('templates/header', $data);
//     $this->load->view('profil/index', $data);
//     $this->load->view('templates/footer');
// }


public function index() {
    $user_id = $this->session->userdata('id');
    $role = $this->session->userdata('role');

    if ($role === 'pegawai') {
        $this->db->select('pegawai.*, divisi.nama_divisi, j1.nama_jabatan AS jabatan1, j2.nama_jabatan AS jabatan2');
        $this->db->from('pegawai');
        $this->db->join('divisi', 'pegawai.divisi_id = divisi.id', 'left');
        $this->db->join('jabatan AS j1', 'pegawai.jabatan1_id = j1.id', 'left');
        $this->db->join('jabatan AS j2', 'pegawai.jabatan2_id = j2.id', 'left');
        $this->db->where('pegawai.id', $user_id);
        $data['profil'] = $this->db->get()->row();
    } else {
        // Data untuk admin
        $data['profil'] = $this->db->get_where('pegawai', ['id' => $user_id])->row();
    }

    // Pastikan avatar memiliki default jika kosong
    $data['profil']->avatar = !empty($data['profil']->avatar) ? $data['profil']->avatar : 'default.png';
    $data['role'] = $role;
    $data['title'] = 'Profil';
    $this->load->view('templates/header', $data);
    $this->load->view('profil/index', $data);
    $this->load->view('templates/footer');
}


    // Tampilkan halaman edit profil
    public function edit() {
        $id = $this->session->userdata('id');
        $role = $this->session->userdata('role');
        $data['title'] = 'Profil';

        // Ambil data user berdasarkan role
        if ($role === 'pegawai') {
            $data['profil'] = $this->Profil_model->get_user_with_details($id);
            $data['role'] = 'pegawai';
        } else {
            $data['profil'] = $this->Profil_model->get_user_by_id($id);
            $data['role'] = 'admin';
        }

        $this->load->view('templates/header', $data);
        $this->load->view('profil/edit', $data);
        $this->load->view('templates/footer');
    }

    // Proses ganti password
    public function ganti_password() {
        $id = $this->session->userdata('id');
        $password_lama = $this->input->post('password_lama');
        $password_baru = $this->input->post('password_baru');
        $konfirmasi_password = $this->input->post('konfirmasi_password');

        $user = $this->Profil_model->get_user_by_id($id);

        // Validasi password lama
        if (!password_verify($password_lama, $user->password)) {
            $this->session->set_flashdata('error', 'Password lama salah!');
            redirect('profil/edit');
        }

        // Validasi konfirmasi password
        if ($password_baru != $konfirmasi_password) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok!');
            redirect('profil/edit');
        }

        // Update password baru
        $this->Profil_model->update_profil($id, ['password' => password_hash($password_baru, PASSWORD_BCRYPT)]);
        $this->session->set_flashdata('success', 'Password berhasil diubah!');
        redirect('profil/edit');
    }
    // Upload Avatar
private function upload_avatar() {
    if (!empty($_FILES['avatar']['name'])) {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('avatar')) {
            return $this->upload->data('file_name');
        } else {
            log_message('error', 'Upload Error: ' . $this->upload->display_errors());
            $this->session->set_flashdata('error', $this->upload->display_errors());
            return null;
        }
    }
    return null; // Tidak ada file diunggah
}


public function update() {
    $user_id = $this->session->userdata('id');
    $role = $this->session->userdata('role');

    // Ambil data input
    $nama = $this->input->post('nama');
    $password_lama = $this->input->post('password_lama');
    $password_baru = $this->input->post('password_baru');
    $konfirmasi_password = $this->input->post('konfirmasi_password');
    $avatar = $this->upload_avatar(); // Fungsi upload avatar

    // Ambil profil user dari database
    $profil = $this->db->get_where('pegawai', ['id' => $user_id])->row();

    // Validasi data
    if (!$profil) {
        $this->session->set_flashdata('error', 'Data profil tidak ditemukan.');
        redirect('profil');
    }

    // Persiapkan data untuk update
    $data_update = ['nama' => $nama];

    // Validasi password lama jika ingin mengganti password
    if (!empty($password_lama) && !empty($password_baru)) {
        if (!password_verify($password_lama, $profil->password)) {
            $this->session->set_flashdata('error', 'Password lama tidak sesuai.');
            redirect('profil/edit');
        }

        if ($password_baru !== $konfirmasi_password) {
            $this->session->set_flashdata('error', 'Konfirmasi password baru tidak cocok.');
            redirect('profil/edit');
        }

        // Hash password baru
        $data_update['password'] = password_hash($password_baru, PASSWORD_DEFAULT);
    }

    // Upload avatar jika ada
    if ($avatar) {
        $data_update['avatar'] = $avatar;
    }

    // Update profil
    $this->db->where('id', $user_id)->update('pegawai', $data_update);

    // Berikan notifikasi
    $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
    redirect('profil');
}


}
