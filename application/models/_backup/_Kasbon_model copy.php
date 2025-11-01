<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasbon_model extends CI_Model {
    // Rekap Kasbon
public function get_rekap_kasbon($bulan) {
    $this->db->select('pegawai.id, pegawai.nama, 
        COALESCE(SUM(CASE WHEN kasbon.jenis = "kasbon" THEN kasbon.nilai ELSE 0 END), 0) AS total_kasbon,
        COALESCE(SUM(CASE WHEN kasbon.jenis = "bayar" THEN kasbon.nilai ELSE 0 END), 0) AS total_bayar,
        (COALESCE(SUM(CASE WHEN kasbon.jenis = "kasbon" THEN kasbon.nilai ELSE 0 END), 0) 
        - COALESCE(SUM(CASE WHEN kasbon.jenis = "bayar" THEN kasbon.nilai ELSE 0 END), 0)) AS sisa_kasbon');
    $this->db->from('pegawai');
    $this->db->join('kasbon', 'kasbon.pegawai_id = pegawai.id AND DATE_FORMAT(kasbon.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
    $this->db->group_by('pegawai.id');
    return $this->db->get()->result();
}


    // Total Kasbon Global

    public function get_total_kasbon_bayar($pegawai_id) {
    $this->db->select('
        COALESCE(SUM(CASE WHEN jenis = "kasbon" THEN nilai_kasbon ELSE 0 END), 0) AS total_kasbon,
        COALESCE(SUM(CASE WHEN jenis = "bayar" THEN nilai_bayar ELSE 0 END), 0) AS total_bayar
    ');
    $this->db->from('kasbon');
    $this->db->where('pegawai_id', $pegawai_id);
    return $this->db->get()->row();
}

    public function get_sisa_kasbon_total($pegawai_id) {
        $this->db->select('
            COALESCE(SUM(CASE WHEN jenis = "kasbon" THEN nilai ELSE 0 END), 0) AS total_kasbon,
            COALESCE(SUM(CASE WHEN jenis = "bayar" THEN nilai ELSE 0 END), 0) AS total_bayar');
        $this->db->where('pegawai_id', $pegawai_id);
        return $this->db->get('kasbon')->row();
    }

    // Detail Kasbon Pegawai
    public function get_detail_kasbon($pegawai_id, $bulan) {
        $this->db->select('*');
        $this->db->from('kasbon');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
        return $this->db->get()->result();
    }

    // Insert Kasbon
    public function insert_kasbon($data) {
        $this->db->insert('kasbon', $data);
    }
public function get_total_kasbon() {
    $this->db->select_sum('nilai'); // Asumsikan kolom 'jumlah' menyimpan total kasbon
    return $this->db->get('kasbon')->row()->jumlah ?? 0;
}
// public function calculate_total_kasbon() {
//     $this->db->select_sum('nilai');
//     $result = $this->db->get('kasbon')->row();
//     return $result->jumlah ?? 0;
// }
public function calculate_total_kasbon() {
    $this->db->select('
        COALESCE(SUM(CASE WHEN jenis = "kasbon" THEN nilai ELSE 0 END), 0) AS total_kasbon,
        COALESCE(SUM(CASE WHEN jenis = "bayar" THEN nilai ELSE 0 END), 0) AS total_bayar
    ');
    $query = $this->db->get('kasbon'); // Pastikan tabel kasbon benar
    $result = $query->row();
    return $result ? $result->total_kasbon - $result->total_bayar : 0;
}
}


