<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    // Ambil data pegawai berdasarkan ID
    public function get_pegawai_by_id($pegawai_id) {
        $this->db->select('
            abs_pegawai.id, 
            abs_pegawai.nama, 
            abs_pegawai.gaji_pokok, 
            abs_pegawai.tambahan_lain, 
            abs_divisi.nama_divisi
        '); // Tambahkan id, tambahan_lain, dan atribut lain yang diperlukan
        $this->db->from('abs_pegawai');
        $this->db->join('abs_divisi', 'abs_divisi.id = abs_pegawai.divisi_id', 'left');
        $this->db->where('abs_pegawai.id', $pegawai_id);
        return $this->db->get()->row();
    }


    // Ambil semua pegawai dengan detail divisi dan jabatan
    public function get_all_pegawai() {
        $this->db->select('abs_pegawai.*, abs_divisi.nama_divisi, j1.nama_jabatan AS jabatan1, j2.nama_jabatan AS jabatan2');
        $this->db->from('abs_pegawai');
        $this->db->join('abs_divisi', 'abs_pegawai.divisi_id = abs_divisi.id');
        $this->db->join('abs_jabatan AS j1', 'abs_pegawai.jabatan1_id = j1.id');
        $this->db->join('abs_jabatan AS j2', 'abs_pegawai.jabatan2_id = j2.id', 'left');
        return $this->db->get()->result();
    }

    // Ambil semua pegawai kecuali admin
    public function get_all_pegawai_except_admin() {
        $this->db->select('abs_pegawai.id, abs_pegawai.nama, abs_divisi.nama_divisi');
        $this->db->from('abs_pegawai');
        $this->db->join('abs_divisi', 'abs_divisi.id = abs_pegawai.divisi_id', 'left');
        $this->db->where('abs_pegawai.kode_user !=', 'admin'); // Kecualikan admin
        //$this->db->where('abs_pegawai.kode_user', 'pegawai'); // Hanya tampilkan pegawai
        $this->db->order_by('abs_divisi.nama_divisi', 'ASC'); // Urutkan berdasarkan nama divisi
        $this->db->order_by('abs_pegawai.id', 'ASC');         // Lalu urutkan berdasarkan ID pegawai
        return $this->db->get()->result();
    }

    // Ambil semua pegawai biasa (kecuali admin dan HOD)
    public function get_pegawai_biasa() {
        $this->db->select('id, nama, divisi_id, kode_user');
        $this->db->from('abs_pegawai');
        $this->db->where_not_in('kode_user', ['admin', 'hod']); // Filter untuk menyembunyikan admin dan HOD
        $this->db->order_by('nama', 'ASC');
        return $this->db->get()->result();
    }

    // Hitung jumlah semua pegawai
    // public function count_all_pegawai() {
    //     $this->db->where('kode_user', 'pegawai');
    //     return $this->db->count_all_results('abs_pegawai');
    // }
    public function count_all_pegawai() {
        $this->db->where('kode_user !=', 'admin');
        return $this->db->count_all_results('abs_pegawai');
    }

    // Hitung total gaji berjalan
    public function calculate_total_gaji_berjalan() {
        $query = $this->db->query("
            SELECT 
                p.id AS pegawai_id,
                p.nama,
                (IFNULL(p.gaji_pokok, 0) + IFNULL(SUM(l.total_gaji_lembur), 0) + IFNULL(SUM(t.nilai_tambahan), 0)) -
                (IFNULL(SUM(po.nilai), 0) + IFNULL(SUM(k.nilai), 0) + IFNULL(SUM(d.nilai), 0)) AS total_gaji_berjalan
            FROM abs_pegawai p
            LEFT JOIN abs_lembur l ON l.pegawai_id = p.id
            LEFT JOIN abs_tambahan_lain t ON t.pegawai_id = p.id
            LEFT JOIN abs_potongan po ON po.pegawai_id = p.id
            LEFT JOIN abs_kasbon k ON k.pegawai_id = p.id
            LEFT JOIN abs_deposit d ON d.pegawai_id = p.id
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

    public function insert_pegawai($data) {
        return $this->db->insert('pegawai', $data);
    }

    public function update_pegawai($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pegawai', $data);
    }

    // public function get_all_pegawai() {
    //     return $this->db->get('pegawai')->result();
    // }

}
