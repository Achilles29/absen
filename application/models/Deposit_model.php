<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposit_model extends CI_Model {

    // Rekapitulasi deposit per pegawai berdasarkan bulan
    // public function get_rekap_deposit($bulan) {
    //     $this->db->select('abs_pegawai.id, abs_pegawai.nama,
    //                        COALESCE(SUM(CASE WHEN abs_deposit.jenis = "setor" THEN abs_deposit.nilai ELSE 0 END), 0) AS total_setor,
    //                        COALESCE(SUM(CASE WHEN abs_deposit.jenis = "tarik" THEN abs_deposit.nilai ELSE 0 END), 0) AS total_tarik,
    //                        (COALESCE(SUM(CASE WHEN abs_deposit.jenis = "setor" THEN abs_deposit.nilai ELSE 0 END), 0) - 
    //                        COALESCE(SUM(CASE WHEN abs_deposit.jenis = "tarik" THEN abs_deposit.nilai ELSE 0 END), 0)) AS sisa_deposit');
    //     $this->db->from('abs_pegawai');
    //     $this->db->join('abs_deposit', 'abs_deposit.pegawai_id = abs_pegawai.id AND DATE_FORMAT(abs_deposit.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
    //     $this->db->group_by('abs_pegawai.id');
    //     return $this->db->get()->result();
    // }

    public function get_rekap_deposit($bulan) {
    $this->db->select('abs_pegawai.id, abs_pegawai.nama,
                       COALESCE(SUM(CASE WHEN abs_deposit.jenis = "setor" THEN abs_deposit.nilai ELSE 0 END), 0) AS total_setor,
                       COALESCE(SUM(CASE WHEN abs_deposit.jenis = "tarik" THEN abs_deposit.nilai ELSE 0 END), 0) AS total_tarik,
                       (COALESCE(SUM(CASE WHEN abs_deposit.jenis = "setor" THEN abs_deposit.nilai ELSE 0 END), 0) - 
                       COALESCE(SUM(CASE WHEN abs_deposit.jenis = "tarik" THEN abs_deposit.nilai ELSE 0 END), 0)) AS sisa_deposit');
    $this->db->from('abs_pegawai');
    $this->db->join('abs_deposit', 'abs_deposit.pegawai_id = abs_pegawai.id AND DATE_FORMAT(abs_deposit.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
    $this->db->where('abs_pegawai.kode_user !=', 'admin'); // Filter selain admin
    $this->db->group_by('abs_pegawai.id');
    return $this->db->get()->result();
}

    // Detail deposit per pegawai berdasarkan bulan
    public function get_detail_deposit($pegawai_id, $bulan) {
        $this->db->select('*');
        $this->db->from('abs_deposit');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
        return $this->db->get()->result();
    }

    // Total deposit sepanjang waktu
    public function get_sisa_deposit_total($pegawai_id) {
        $this->db->select('
            COALESCE(SUM(CASE WHEN jenis = "setor" THEN nilai ELSE 0 END), 0) AS total_setor,
            COALESCE(SUM(CASE WHEN jenis = "tarik" THEN nilai ELSE 0 END), 0) AS total_tarik');
        $this->db->from('abs_deposit');
        $this->db->where('pegawai_id', $pegawai_id);
        return $this->db->get()->row();
    }

    // Input deposit
    public function insert_deposit($data) {
        $this->db->insert('abs_deposit', $data);
    }

    // Total deposit sepanjang waktu
    public function calculate_total_deposit() {
        // Total nilai untuk jenis "setor"
        $this->db->select_sum('nilai', 'total_setor');
        $this->db->where('jenis', 'setor');
        $query_setor = $this->db->get('abs_deposit');
        $result_setor = $query_setor->row();

        $total_setor = $result_setor && $result_setor->total_setor !== null ? (float)$result_setor->total_setor : 0;

        // Total nilai untuk jenis "tarik"
        $this->db->select_sum('nilai', 'total_tarik');
        $this->db->where('jenis', 'tarik');
        $query_tarik = $this->db->get('abs_deposit');
        $result_tarik = $query_tarik->row();

        $total_tarik = $result_tarik && $result_tarik->total_tarik !== null ? (float)$result_tarik->total_tarik : 0;

        // Hitung selisih antara setor dan tarik
        return $total_setor - $total_tarik;
    }

public function get_total_deposit($pegawai_id, $bulan) {
    // Total setor pada bulan tertentu
    $this->db->select_sum('nilai', 'total_setor');
    $this->db->where('jenis', 'setor');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
    $setor = $this->db->get('abs_deposit')->row()->total_setor ?? 0;

    // Total tarik pada bulan tertentu
    $this->db->select_sum('nilai', 'total_tarik');
    $this->db->where('jenis', 'tarik');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
    $tarik = $this->db->get('abs_deposit')->row()->total_tarik ?? 0;

    // Hitung total deposit (setor - tarik)
    return $setor - $tarik;
}
    // Ambil log deposit
    public function get_log_deposit($bulan, $tahun) {
        $this->db->select('abs_deposit.*, abs_pegawai.nama AS nama_pegawai');
        $this->db->from('abs_deposit');
        $this->db->join('abs_pegawai', 'abs_deposit.pegawai_id = abs_pegawai.id', 'left');
        $this->db->where('MONTH(abs_deposit.tanggal)', $bulan);
        $this->db->where('YEAR(abs_deposit.tanggal)', $tahun);
        $this->db->order_by('abs_deposit.tanggal', 'ASC');
        return $this->db->get()->result();
    }

    // Update deposit
    public function update_deposit($data) {
        $this->db->where('id', $data['id']);
        return $this->db->update('abs_deposit', $data);
    }

    // Hapus deposit
    public function delete_deposit($id) {
        $this->db->where('id', $id);
        return $this->db->delete('abs_deposit');
    }

public function get_all_deposit_by_pegawai($pegawai_id) {
    $this->db->select('*');
    $this->db->from('abs_deposit');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->order_by('tanggal', 'ASC'); // Urutkan berdasarkan tanggal
    return $this->db->get()->result();
}

}
