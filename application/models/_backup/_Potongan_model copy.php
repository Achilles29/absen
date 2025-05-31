<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Potongan_model extends CI_Model {

    // Rekapitulasi potongan per pegawai berdasarkan bulan
    public function get_rekap_potongan($bulan) {
        $this->db->select('pegawai.id, pegawai.nama, 
                           COALESCE(SUM(potongan.nilai), 0) AS total_potongan');
        $this->db->from('pegawai');
        $this->db->join('potongan', 'potongan.pegawai_id = pegawai.id AND DATE_FORMAT(potongan.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
        $this->db->group_by('pegawai.id');
        return $this->db->get()->result();
    }

    // Detail potongan per pegawai berdasarkan bulan
    public function get_detail_potongan($pegawai_id, $bulan) {
        $this->db->select('*');
        $this->db->from('potongan');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
        return $this->db->get()->result();
    }

    // Input potongan baru
    public function insert_potongan($data) {
        $this->db->insert('potongan', $data);
    }

// public function calculate_total_potongan() {
//     $this->db->select_sum('nilai');
//     $result = $this->db->get('potongan')->row();
//     return $result->jumlah ?? 0;
// }


public function calculate_total_potongan() {
    $this->db->select_sum('nilai', 'total_potongan');
    $this->db->where('MONTH(tanggal)', date('m')); // Filter berdasarkan bulan sekarang
    $this->db->where('YEAR(tanggal)', date('Y')); // Filter berdasarkan tahun sekarang
    $query = $this->db->get('potongan');
    $result = $query->row();
    return $result && $result->total_potongan !== null ? (float)$result->total_potongan : 0;
}

}
