<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan_model extends CI_Model {

public function get_all_jabatan() {
    $this->db->select('abs_jabatan.*, abs_divisi.nama_divisi');
    $this->db->from('abs_jabatan');
    $this->db->join('abs_divisi', 'abs_jabatan.divisi_id = abs_divisi.id');
    return $this->db->get()->result();
}

public function get_jabatan_by_id($id) {
    return $this->db->get_where('abs_jabatan', ['id' => $id])->row();
}

public function tambah_jabatan($data) {
    $this->db->insert('abs_jabatan', $data);
}

public function update_jabatan($id, $data) {
    $this->db->where('id', $id)->update('abs_jabatan', $data);
}

public function delete_jabatan($id) {
    $this->db->delete('abs_jabatan', ['id' => $id]);
}
public function count_all_divisi() {
    return $this->db->count_all('abs_divisi');
}

}
