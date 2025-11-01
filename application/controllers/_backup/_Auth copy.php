<?php
class Auth extends CI_Controller {
    // public function login() {
    //     if ($this->input->post()) {
    //         $username = $this->input->post('username');
    //         $password = $this->input->post('password');

    //         // Ambil data dari tabel pegawai
    //         $user = $this->db->get_where('pegawai', ['username' => $username])->row();
    //         // Ambil data dari tabel admin
    //         $admin = $this->db->get_where('admin', ['username' => $username])->row();

    //         // Jika login sebagai pegawai
    //         if ($user && password_verify($password, $user->password)) {
    //             $this->session->set_userdata([
    //                 'logged_in' => true,
    //                 'role' => 'pegawai',
    //                 'id' => $user->id,
    //                 'nama' => $user->nama,           // Tambahkan nama user
    //                 'avatar' => $user->avatar ?: 'default.png' // Tambahkan avatar dengan default jika kosong
    //             ]);
    //             redirect('pegawai/absen');

    //         // Jika login sebagai admin
    //         } elseif ($admin && password_verify($password, $admin->password)) {
    //             $this->session->set_userdata([
    //                 'logged_in' => true,
    //                 'role' => 'admin',
    //                 'id' => $admin->id,
    //                 'nama' => $admin->nama,          // Tambahkan nama admin
    //                 'avatar' => $admin->avatar ?: 'default.png' // Tambahkan avatar admin dengan default jika kosong
    //             ]);
    //             redirect('admin');

    //         // Jika login gagal
    //         } else {
    //             $this->session->set_flashdata('error', 'Username atau password salah.');
    //             redirect('auth/login');
    //         }
    //     }

    //     // Load tampilan login
    //     $this->load->view('auth/login');
    // }

public function login() {
    if ($this->input->post()) {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Ambil data user dari tabel pegawai
        $user = $this->db->get_where('pegawai', ['username' => $username])->row();

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
                    redirect('jadwal_shift');
                    break;
                default:
                    redirect('pegawai/absen');
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
