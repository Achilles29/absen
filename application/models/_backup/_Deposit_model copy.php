<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposit_model extends CI_Model {

    // Rekapitulasi deposit per pegawai berdasarkan bulan
    public function get_rekap_deposit($bulan) {
        $this->db->select('pegawai.id, pegawai.nama,
                           COALESCE(SUM(CASE WHEN deposit.jenis = "setor" THEN deposit.nilai ELSE 0 END), 0) AS total_setor,
                           COALESCE(SUM(CASE WHEN deposit.jenis = "tarik" THEN deposit.nilai ELSE 0 END), 0) AS total_tarik,
                           (COALESCE(SUM(CASE WHEN deposit.jenis = "setor" THEN deposit.nilai ELSE 0 END), 0) - 
                           COALESCE(SUM(CASE WHEN deposit.jenis = "tarik" THEN deposit.nilai ELSE 0 END), 0)) AS sisa_deposit');
        $this->db->from('pegawai');
        $this->db->join('deposit', 'deposit.pegawai_id = pegawai.id AND DATE_FORMAT(deposit.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
        $this->db->group_by('pegawai.id');
        return $this->db->get()->result();
    }

    // Detail deposit per pegawai berdasarkan bulan
    public function get_detail_deposit($pegawai_id, $bulan) {
        $this->db->select('*');
        $this->db->from('deposit');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
        return $this->db->get()->result();
    }

    // Total deposit sepanjang waktu
    public function get_sisa_deposit_total($pegawai_id) {
        $this->db->select('
            COALESCE(SUM(CASE WHEN jenis = "setor" THEN nilai ELSE 0 END), 0) AS total_setor,
            COALESCE(SUM(CASE WHEN jenis = "tarik" THEN nilai ELSE 0 END), 0) AS total_tarik');
        $this->db->from('deposit');
        $this->db->where('pegawai_id', $pegawai_id);
        return $this->db->get()->row();
    }

    // Input deposit
    public function insert_deposit($data) {
        $this->db->insert('deposit', $data);
    }

    public function calculate_total_deposit() {
        // Total nilai untuk jenis "setor"
        $this->db->select_sum('nilai', 'total_setor');
        $this->db->where('jenis', 'setor');
        $query_setor = $this->db->get('deposit');
        $result_setor = $query_setor->row();

        $total_setor = $result_setor && $result_setor->total_setor !== null ? (float)$result_setor->total_setor : 0;

        // Total nilai untuk jenis "tarik"
        $this->db->select_sum('nilai', 'total_tarik');
        $this->db->where('jenis', 'tarik');
        $query_tarik = $this->db->get('deposit');
        $result_tarik = $query_tarik->row();

        $total_tarik = $result_tarik && $result_tarik->total_tarik !== null ? (float)$result_tarik->total_tarik : 0;

        // Hitung selisih antara setor dan tarik
        return $total_setor - $total_tarik;
    }


}