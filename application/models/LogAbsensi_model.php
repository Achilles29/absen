<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogAbsensi_model extends CI_Model {

    // Rekap log absensi per bulan
    public function get_rekap_log_absensi($pegawai_id, $bulan) {
        $this->db->select('tanggal, jam_masuk, jam_pulang, terlambat, pulang_cepat, lama_menit_kerja, total_gaji');
        $this->db->from('abs_rekap_absensi');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get()->result();
    }

    // Detail log absensi per bulan
    public function get_detail_log_absensi($pegawai_id, $bulan) {
        $this->db->select('tanggal, waktu, latitude, longitude, jenis_absen, kode_shift, foto');
        $this->db->from('abs_absensi');
        $this->db->join('abs_shift', 'abs_absensi.shift_id = abs_shift.id', 'left');
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get()->result();
    }

public function get_total_kehadiran($pegawai_id, $bulan) {
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
    $this->db->from('abs_absensi');
    return $this->db->count_all_results();
}
public function get_total_gaji($pegawai_id, $bulan) {
    $this->db->select('SUM(total_gaji) as total_gaji');
    $this->db->from('abs_rekap_absensi');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan); // Filter bulan
    $result = $this->db->get()->row();

    return $result->total_gaji ?? 0; // Kembalikan total gaji atau 0 jika tidak ada data
}


}
