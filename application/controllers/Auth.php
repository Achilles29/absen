<?php
class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function login() {
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('auth/login');
            }

            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Ambil data user dari tabel pegawai
            $user = $this->db->get_where('abs_pegawai', ['username' => $username])->row();

            // Periksa apakah user ditemukan
            if ($user && password_verify($password, $user->password)) {
                // Tentukan role berdasarkan kode_user
                $role = $this->get_user_role($user->kode_user);

                // Set session user
                $this->session->set_userdata([
                    'logged_in' => true,
                    'role' => $role,
                    'id' => $user->id, // Pastikan 'id' digunakan untuk konsistensi
                    'nama' => $user->nama,
                    'divisi_id' => $user->divisi_id ?? null,
                    'avatar' => $user->avatar ?: 'default.png'
                ]);

                // Redirect sesuai role
                $this->redirect_user($role);
            } else {
                $this->session->set_flashdata('error', 'Username atau password salah.');
                redirect('auth/login');
            }
        }

        // Load tampilan login
        $this->load->view('auth/login');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('/auth/login');
    }

    private function get_user_role($kode_user) {
        switch ($kode_user) {
            case 'admin':
                return 'admin';
            case 'hod':
                return 'hod';
            case 'spv':
                return 'spv';
            default:
                return 'pegawai';
        }
    }

    private function redirect_user($role) {
        switch ($role) {
            case 'admin':
            case 'spv':
                redirect('beranda');
                break;
            case 'hod':
                redirect('beranda');
                break;
            default:
                redirect('/pegawai/dashboard');
                break;
        }
    }
}
