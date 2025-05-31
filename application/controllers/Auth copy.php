<?php
class Auth extends CI_Controller {
public function login() {
    if ($this->input->post()) {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Ambil data user dari tabel pegawai
        $user = $this->db->get_where('abs_pegawai', ['username' => $username])->row();

        // Periksa apakah user ditemukan
        if ($user && password_verify($password, $user->password)) {
            // Tentukan role berdasarkan kode_user
            if ($user->kode_user === 'admin') {
                $role = 'admin';
            } elseif ($user->kode_user === 'hod') {
                $role = 'hod';
            } elseif ($user->kode_user === 'spv') { // Tambahkan kondisi untuk spv
                $role = 'spv';
            } else {
                $role = 'pegawai';
            }

            // Set session user
            $this->session->set_userdata([
                'logged_in' => true,
                'role' => $role,
                'id' => $user->id,
                'nama' => $user->nama,                 // Nama user/admin
                'divisi_id' => $user->divisi_id ?? null, // Divisi hanya untuk HOD
                'avatar' => $user->avatar ?: 'default.png' // Default avatar jika kosong
            ]);

            // Redirect sesuai role
            switch ($role) {
                case 'admin':
                case 'spv': // Tambahkan case untuk spv
                    redirect('beranda');
                    break;
                case 'hod':
                    redirect('beranda');
                    break;
                default:
                    redirect('/pegawai/dashboard');
                    break;
            }

        } else {
            // Jika login gagal
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }
    }

    // Load tampilan login
    $this->load->view('auth/login');
}

public function logout() {
    $this->session->sess_destroy(); // Hapus semua data session
    redirect('/auth/login'); // Redirect ke halaman login
}

}
