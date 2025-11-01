<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Potongan_model extends CI_Model {

    // Rekapitulasi potongan per pegawai berdasarkan bulan
    public function get_rekap_potongan($bulan) {
        $this->db->select('abs_pegawai.id, abs_pegawai.nama, 
                           COALESCE(SUM(abs_potongan.nilai), 0) AS total_potongan');
        $this->db->from('abs_pegawai');
        $this->db->join('abs_potongan', 'abs_potongan.pegawai_id = abs_pegawai.id AND DATE_FORMAT(abs_potongan.tanggal, "%Y-%m") = "'.$bulan.'"', 'left');
        $this->db->group_by('abs_pegawai.id');
        return $this->db->get()->result();
    }

    // Detail potongan per pegawai berdasarkan bulan
    public function get_detail_potongan($pegawai_id, $bulan) {
        $this->db->select('*');
        $this->db->from('abs_potongan');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
        return $this->db->get()->result();
    }

    // Input potongan baru
    public function insert_potongan($data) {
        $this->db->insert('abs_potongan', $data);
    }

    // Hitung total potongan untuk bulan ini
    public function calculate_total_potongan() {
        $this->db->select_sum('nilai', 'total_potongan');
        $this->db->where('MONTH(tanggal)', date('m')); // Filter berdasarkan bulan sekarang
        $this->db->where('YEAR(tanggal)', date('Y')); // Filter berdasarkan tahun sekarang
        $query = $this->db->get('abs_potongan');
        $result = $query->row();
        return $result && $result->total_potongan !== null ? (float)$result->total_potongan : 0;
    }

    public function get_total_potongan($pegawai_id, $bulan) {
        $this->db->select_sum('nilai', 'total_potongan');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
        $result = $this->db->get('abs_potongan')->row();
        return $result ? $result->total_potongan : 0;
    }
    public function get_log_potongan($bulan, $tahun) {
        $this->db->select('abs_potongan.*, abs_pegawai.nama AS nama_pegawai');
        $this->db->from('abs_potongan');
        $this->db->join('abs_pegawai', 'abs_potongan.pegawai_id = abs_pegawai.id', 'left');
        $this->db->where('MONTH(abs_potongan.tanggal)', $bulan);
        $this->db->where('YEAR(abs_potongan.tanggal)', $tahun);
        $this->db->order_by('abs_potongan.tanggal', 'ASC');
        return $this->db->get()->result();
    }

    public function update_potongan($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('abs_potongan', $data);
    }

    public function delete_potongan($id) {
        $this->db->where('id', $id);
        $this->db->delete('abs_potongan');
    }
}
