<?php
class Profil_model extends CI_Model {

    // Ambil data pegawai lengkap berdasarkan ID
    public function get_user_with_details($id) {
        // Ambil data pegawai lengkap dengan join tabel divisi dan jabatan
        $this->db->select('abs_pegawai.*, abs_divisi.nama_divisi, j1.nama_jabatan AS jabatan1, j2.nama_jabatan AS jabatan2');
        $this->db->from('abs_pegawai');
        $this->db->join('abs_divisi', 'abs_pegawai.divisi_id = abs_divisi.id', 'left');
        $this->db->join('abs_jabatan AS j1', 'abs_pegawai.jabatan1_id = j1.id', 'left');
        $this->db->join('abs_jabatan AS j2', 'abs_pegawai.jabatan2_id = j2.id', 'left');
        $this->db->where('abs_pegawai.id', $id);
        return $this->db->get()->row();
    }

    public function get_admin_by_id($id) {
        // Ambil data admin berdasarkan ID
        return $this->db->get_where('abs_admin', ['id' => $id])->row(); // Admin diasumsikan bernama abs_admin
    }

    public function get_user_by_id($id) {
        return $this->db->get_where('abs_pegawai', ['id' => $id])->row();
    }

    public function update_profil($id, $data) {
        $this->db->where('id', $id)->update('abs_pegawai', $data);
    }

    public function update_user($role, $id, $data) {
        $table = ($role === 'admin') ? 'abs_admin' : 'abs_pegawai';
        $this->db->where('id', $id)->update($table, $data);
    }

    public function update_password($role, $id, $old_password, $new_password) {
        $table = ($role === 'admin') ? 'abs_admin' : 'abs_pegawai';
        $user = $this->db->get_where($table, ['id' => $id])->row();

        if ($user && password_verify($old_password, $user->password)) {
            $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
            $this->db->where('id', $id)->update($table, ['password' => $new_password_hashed]);
            return true;
        }
        return false;
    }
}
