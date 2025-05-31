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

    public function index() {
        $user_id = $this->session->userdata('id');
        $role = $this->session->userdata('role');

        if ($role === 'pegawai') {
            $this->db->select('abs_pegawai.*, abs_divisi.nama_divisi, j1.nama_jabatan AS jabatan1, j2.nama_jabatan AS jabatan2');
            $this->db->from('abs_pegawai');
            $this->db->join('abs_divisi', 'abs_pegawai.divisi_id = abs_divisi.id', 'left');
            $this->db->join('abs_jabatan AS j1', 'abs_pegawai.jabatan1_id = j1.id', 'left');
            $this->db->join('abs_jabatan AS j2', 'abs_pegawai.jabatan2_id = j2.id', 'left');
            $this->db->where('abs_pegawai.id', $user_id);
            $data['profil'] = $this->db->get()->row();
        } else {
            $data['profil'] = $this->db->get_where('abs_pegawai', ['id' => $user_id])->row();
        }

        // Pastikan avatar memiliki default jika kosong
        $data['profil']->avatar = !empty($data['profil']->avatar) ? $data['profil']->avatar : 'default.png';
        $data['role'] = $role;
        $data['title'] = 'Profil';
        $this->load->view('templates/header', $data);
        $this->load->view('profil/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit() {
        $id = $this->session->userdata('id');
        $role = $this->session->userdata('role');
        $data['title'] = 'Profil';

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

    public function ganti_password() {
        $id = $this->session->userdata('id');
        $password_lama = $this->input->post('password_lama');
        $password_baru = $this->input->post('password_baru');
        $konfirmasi_password = $this->input->post('konfirmasi_password');

        $user = $this->Profil_model->get_user_by_id($id);

        if (!password_verify($password_lama, $user->password)) {
            $this->session->set_flashdata('error', 'Password lama salah!');
            redirect('profil/edit');
        }

        if ($password_baru != $konfirmasi_password) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok!');
            redirect('profil/edit');
        }

        $this->Profil_model->update_profil($id, ['password' => password_hash($password_baru, PASSWORD_BCRYPT)]);
        $this->session->set_flashdata('success', 'Password berhasil diubah!');
        redirect('profil/edit');
    }

    private function upload_avatar() {
        if (!empty($_FILES['avatar']['name'])) {
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
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
        return null;
    }

    public function update() {
        $user_id = $this->session->userdata('id');
        $role = $this->session->userdata('role');

        $nama = $this->input->post('nama');
        $password_lama = $this->input->post('password_lama');
        $password_baru = $this->input->post('password_baru');
        $konfirmasi_password = $this->input->post('konfirmasi_password');
        $avatar = $this->upload_avatar();

        $profil = $this->db->get_where('abs_pegawai', ['id' => $user_id])->row();

        if (!$profil) {
            $this->session->set_flashdata('error', 'Data profil tidak ditemukan.');
            redirect('profil');
        }

        $data_update = ['nama' => $nama];

        if (!empty($password_lama) && !empty($password_baru)) {
            if (!password_verify($password_lama, $profil->password)) {
                $this->session->set_flashdata('error', 'Password lama tidak sesuai.');
                redirect('profil/edit');
            }

            if ($password_baru !== $konfirmasi_password) {
                $this->session->set_flashdata('error', 'Konfirmasi password baru tidak cocok.');
                redirect('profil/edit');
            }

            $data_update['password'] = password_hash($password_baru, PASSWORD_DEFAULT);
        }

        if ($avatar) {
            $data_update['avatar'] = $avatar;
        }

        $this->db->where('id', $user_id)->update('abs_pegawai', $data_update);

        $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
        redirect('profil');
    }
}
