<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Bank_model extends CI_Model {
    // Ambil semua data rekening bank
    public function get_all_banks() {
        return $this->db->get('abs_rekening_bank')->result();
    }

    // Tambah rekening bank
    public function insert_bank($data) {
        return $this->db->insert('abs_rekening_bank', $data);
    }

    // Hapus rekening bank
    public function delete_bank($id) {
        return $this->db->delete('abs_rekening_bank', ['id' => $id]);
    }
}



