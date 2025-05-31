<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gaji_model extends CI_Model {

    public function get_detail_gaji($pegawai_id, $bulan) {
        $this->db->select('
            abs_rekap_absensi.tanggal,
            abs_shift.kode_shift AS shift,
            abs_rekap_absensi.jam_masuk,
            abs_rekap_absensi.jam_pulang,
            abs_rekap_absensi.lama_menit_kerja,
            abs_rekap_absensi.total_gaji,
            COALESCE(abs_lembur.total_gaji_lembur, 0) AS total_gaji_lembur,
            COALESCE(abs_tambahan_lain.nilai_tambahan, 0) AS tambahan_lain,
            COALESCE(abs_potongan.nilai, 0) AS potongan,
            COALESCE(abs_deposit.nilai, 0) AS deposit,
            COALESCE(abs_kasbon.nilai, 0) AS kasbon_bayar
        ');
        $this->db->from('abs_rekap_absensi');
        $this->db->join('abs_shift', 'abs_rekap_absensi.shift_id = abs_shift.id', 'left');
        $this->db->join('abs_lembur', 'abs_lembur.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_lembur.tanggal = abs_rekap_absensi.tanggal', 'left');
        $this->db->join('abs_tambahan_lain', 'abs_tambahan_lain.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_tambahan_lain.tanggal = abs_rekap_absensi.tanggal', 'left');
        $this->db->join('abs_potongan', 'abs_potongan.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_potongan.tanggal = abs_rekap_absensi.tanggal', 'left');
      //  $this->db->join('abs_deposit', 'abs_deposit.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_deposit.tanggal = abs_rekap_absensi.tanggal', 'left');
        // Ganti bagian join abs_deposit ini:
        $this->db->select("
            ...
            (
                SELECT 
                    SUM(CASE WHEN jenis = 'tarik' THEN nilai ELSE 0 END) -
                    SUM(CASE WHEN jenis = 'setor' THEN nilai ELSE 0 END)
                FROM abs_deposit d
                WHERE d.pegawai_id = abs_rekap_absensi.pegawai_id AND d.tanggal = abs_rekap_absensi.tanggal
            ) AS deposit,
        ");

        $this->db->join('abs_kasbon', 'abs_kasbon.pegawai_id = abs_rekap_absensi.pegawai_id AND abs_kasbon.tanggal = abs_rekap_absensi.tanggal AND abs_kasbon.jenis = "bayar"', 'left');
        $this->db->where('abs_rekap_absensi.pegawai_id', $pegawai_id);
        $this->db->where("DATE_FORMAT(abs_rekap_absensi.tanggal, '%Y-%m') =", $bulan);
        $this->db->order_by('abs_rekap_absensi.tanggal', 'ASC');

        return $this->db->get()->result();
    }
}
