<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasbon_model extends CI_Model {

public function get_rekap_kasbon($bulan) {
    $this->db->select('
        abs_pegawai.id, 
        abs_pegawai.nama, 
        COALESCE(SUM(CASE WHEN abs_kasbon.jenis = "kasbon" THEN abs_kasbon.nilai ELSE 0 END), 0) AS total_kasbon,
        COALESCE(SUM(CASE WHEN abs_kasbon.jenis = "bayar" THEN abs_kasbon.nilai ELSE 0 END), 0) AS total_bayar,
        (
            COALESCE(SUM(CASE WHEN abs_kasbon.jenis = "kasbon" THEN abs_kasbon.nilai ELSE 0 END), 0) 
            - COALESCE(SUM(CASE WHEN abs_kasbon.jenis = "bayar" THEN abs_kasbon.nilai ELSE 0 END), 0)
        ) AS sisa_kasbon
    ');
    $this->db->from('abs_pegawai');
    $this->db->join('abs_kasbon', 'abs_kasbon.pegawai_id = abs_pegawai.id', 'left');
    $this->db->where('DATE_FORMAT(abs_kasbon.tanggal, "%Y-%m") =', $bulan); // Parameter binding
    $this->db->group_by('abs_pegawai.id');
    $this->db->order_by('abs_pegawai.nama', 'ASC'); // Urutkan berdasarkan nama pegawai

    return $this->db->get()->result();
}


    // Total Kasbon Global
    public function get_total_kasbon_bayar($pegawai_id) {
        $this->db->select('
            COALESCE(SUM(CASE WHEN jenis = "kasbon" THEN nilai ELSE 0 END), 0) AS total_kasbon,
            COALESCE(SUM(CASE WHEN jenis = "bayar" THEN nilai ELSE 0 END), 0) AS total_bayar
        ');
        $this->db->from('abs_kasbon');
        $this->db->where('pegawai_id', $pegawai_id);
        return $this->db->get()->row();
    }

    public function get_sisa_kasbon_total($pegawai_id) {
        $this->db->select('
            COALESCE(SUM(CASE WHEN jenis = "kasbon" THEN nilai ELSE 0 END), 0) AS total_kasbon,
            COALESCE(SUM(CASE WHEN jenis = "bayar" THEN nilai ELSE 0 END), 0) AS total_bayar');
        $this->db->from('abs_kasbon');
        $this->db->where('pegawai_id', $pegawai_id);
        return $this->db->get()->row();
    }

    // Detail Kasbon Pegawai
    public function get_detail_kasbon($pegawai_id, $bulan) {
        $this->db->select('*');
        $this->db->from('abs_kasbon');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
        return $this->db->get()->result();
    }

    // Insert Kasbon
    public function insert_kasbon($data) {
        $this->db->insert('abs_kasbon', $data);
    }

    // Total kasbon
    public function get_total_kasbon() {
        $this->db->select_sum('nilai');
        return $this->db->get('abs_kasbon')->row()->nilai ?? 0;
    }

    // Hitung total kasbon dan pembayaran
    public function calculate_total_kasbon() {
        $this->db->select('
            COALESCE(SUM(CASE WHEN jenis = "kasbon" THEN nilai ELSE 0 END), 0) AS total_kasbon,
            COALESCE(SUM(CASE WHEN jenis = "bayar" THEN nilai ELSE 0 END), 0) AS total_bayar
        ');
        $query = $this->db->get('abs_kasbon'); // Pastikan tabel kasbon benar
        $result = $query->row();
        return $result ? $result->total_kasbon - $result->total_bayar : 0;
    }

    public function calculate_kasbon_bulan($pegawai_id, $bulan) {
    $this->db->select('
        COALESCE(SUM(CASE WHEN jenis = "kasbon" THEN nilai ELSE 0 END), 0) AS total_kasbon,
        COALESCE(SUM(CASE WHEN jenis = "bayar" THEN nilai ELSE 0 END), 0) AS total_bayar
    ');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
    $result = $this->db->get('abs_kasbon')->row();
    return $result ? $result->total_kasbon - $result->total_bayar : 0;
}

public function calculate_kasbon_total($pegawai_id) {
    $this->db->select('
        COALESCE(SUM(CASE WHEN jenis = "kasbon" THEN nilai ELSE 0 END), 0) AS total_kasbon,
        COALESCE(SUM(CASE WHEN jenis = "bayar" THEN nilai ELSE 0 END), 0) AS total_bayar
    ');
    $this->db->where('pegawai_id', $pegawai_id);
    $result = $this->db->get('abs_kasbon')->row();
    return $result ? $result->total_kasbon - $result->total_bayar : 0;
}
public function get_total_kasbon_pegawai($pegawai_id, $bulan) {
    $this->db->select('SUM(CASE WHEN jenis = "kasbon" THEN nilai ELSE 0 END) - SUM(CASE WHEN jenis = "bayar" THEN nilai ELSE 0 END) AS total');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
    $result = $this->db->get('abs_kasbon')->row();
    return $result ? $result->total : 0;
}


}
