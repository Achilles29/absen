<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pegawai_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    // public function get_pegawai_by_id($id) {
    //     return $this->db->get_where('pegawai', ['id' => $id])->row();
    // }
public function get_pegawai_by_id($pegawai_id) {
    $this->db->select('pegawai.nama, divisi.nama_divisi');
    $this->db->from('pegawai');
    $this->db->join('divisi', 'divisi.id = pegawai.divisi_id', 'left');
    $this->db->where('pegawai.id', $pegawai_id);
    return $this->db->get()->row();
    
}

public function get_all_pegawai() {
    $this->db->select('pegawai.*, divisi.nama_divisi, j1.nama_jabatan AS jabatan1, j2.nama_jabatan AS jabatan2');
    $this->db->from('pegawai');
    $this->db->join('divisi', 'pegawai.divisi_id = divisi.id');
    $this->db->join('jabatan AS j1', 'pegawai.jabatan1_id = j1.id');
    $this->db->join('jabatan AS j2', 'pegawai.jabatan2_id = j2.id', 'left');
    return $this->db->get()->result();
}
public function get_all_pegawai_except_admin() {
    $this->db->select('pegawai.id, pegawai.nama, divisi.nama_divisi');
    $this->db->from('pegawai');
    $this->db->join('divisi', 'divisi.id = pegawai.divisi_id', 'left');
    $this->db->where('pegawai.kode_user', 'pegawai'); // Hanya tampilkan pegawai
    $this->db->order_by('divisi.nama_divisi', 'ASC'); // Urutkan berdasarkan nama divisi
    $this->db->order_by('pegawai.id', 'ASC');         // Lalu urutkan berdasarkan ID pegawai
    return $this->db->get()->result();
}


public function get_pegawai_biasa()
{
    $this->db->select('id, nama, divisi_id, kode_user');
    $this->db->from('pegawai');
    // Filter berdasarkan kode_user untuk menyembunyikan admin dan HOD
    $this->db->where_not_in('kode_user', ['admin', 'hod']);
    $this->db->order_by('nama', 'ASC');
    return $this->db->get()->result();
}

public function count_all_pegawai() {
    $this->db->where('kode_user', 'pegawai');
    return $this->db->count_all_results('pegawai');
}


public function calculate_total_gaji_berjalan() {
    $query = $this->db->query("
        SELECT 
            p.id AS pegawai_id,
            p.nama,
            (IFNULL(p.gaji_pokok, 0) + IFNULL(SUM(lembur.total_gaji_lembur), 0) + IFNULL(SUM(tambahan.nilai_tambahan), 0)) -
            (IFNULL(SUM(potongan.nilai), 0) + IFNULL(SUM(kasbon.nilai), 0) + IFNULL(SUM(deposit.nilai), 0)) AS total_gaji_berjalan
        FROM pegawai p
        LEFT JOIN lembur ON lembur.pegawai_id = p.id
        LEFT JOIN tambahan_lain tambahan ON tambahan.pegawai_id = p.id
        LEFT JOIN potongan ON potongan.pegawai_id = p.id
        LEFT JOIN kasbon ON kasbon.pegawai_id = p.id
        LEFT JOIN deposit ON deposit.pegawai_id = p.id
        WHERE p.kode_user = 'pegawai'
        GROUP BY p.id
    ");

    $result = $query->result();

    $total_gaji = 0;
    foreach ($result as $row) {
        $total_gaji += $row->total_gaji_berjalan;
    }

    return $total_gaji;
}


}

