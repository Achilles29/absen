<?php
class Lembur_model extends CI_Model {

    // Ambil nilai lembur per jam
    public function get_nilai_lembur() {
        return $this->db->get('abs_nilai_lembur')->row();
    }

    // // Insert lembur dengan perhitungan per jam
    // public function insert_lembur($data) {
    //     $nilai_lembur = $this->get_nilai_lembur();
        
    //     // Hitung total lembur dalam jam (dibulatkan)
    //     $lama_lembur_jam = ceil($data['lama_lembur']); 

    //     // Total gaji lembur
    //     $data['total_gaji_lembur'] = $lama_lembur_jam * $nilai_lembur->nilai_per_jam;

    //     // Simpan ke database
    //     return $this->db->insert('abs_lembur', $data);
    // }
public function insert_lembur($data) {
    // Ambil nilai lembur berdasarkan nilai_lembur_id
    $nilai_lembur = $this->db->get_where('abs_nilai_lembur', ['id' => $data['nilai_lembur_id']])->row();

    if ($nilai_lembur) {
        // Hitung total gaji lembur
        $lama_lembur_jam = ceil($data['lama_lembur']);
        $data['total_gaji_lembur'] = $lama_lembur_jam * $nilai_lembur->nilai_per_jam;

        // Simpan ke database
        return $this->db->insert('abs_lembur', $data);
    } else {
        return false; // Gagal jika nilai_lembur_id tidak ditemukan
    }
}

    // Ambil laporan lembur
    public function get_laporan_lembur() {
        $this->db->select('abs_lembur.*, abs_pegawai.nama, abs_nilai_lembur.nilai_per_jam');
        $this->db->from('abs_lembur');
        $this->db->join('abs_pegawai', 'abs_lembur.pegawai_id = abs_pegawai.id', 'left');
        $this->db->join('abs_nilai_lembur', '1=1', 'left'); // Untuk ambil nilai lembur
        return $this->db->get()->result();
    }

    public function update_nilai_lembur($data) {
    // Periksa apakah data nilai lembur sudah ada
    $exists = $this->db->get('abs_nilai_lembur')->row();

        if ($exists) {
            // Jika sudah ada, update data
            return $this->db->update('abs_nilai_lembur', $data);
        } else {
            // Jika belum ada, insert data baru
            return $this->db->insert('abs_nilai_lembur', $data);
        }
    }

public function calculate_total_lembur() {
    $this->db->select_sum('total_gaji_lembur', 'total_lembur');
    $this->db->where('MONTH(tanggal)', date('m')); // Filter berdasarkan bulan sekarang
    $this->db->where('YEAR(tanggal)', date('Y')); // Filter berdasarkan tahun sekarang
    $query = $this->db->get('abs_lembur'); // Pastikan tabel lembur benar
    $result = $query->row();
    return $result ? $result->total_lembur : 0;
}

public function get_detail_lembur($pegawai_id, $bulan) {
    $this->db->select('*');
    $this->db->from('abs_lembur');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
    return $this->db->get()->result();
}

public function get_total_lembur($pegawai_id, $bulan) {
    $this->db->select_sum('total_gaji_lembur', 'total');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
    $result = $this->db->get('abs_lembur')->row();
    return $result ? $result->total : 0;
}
// Lembur_model.php

public function get_all_nilai_lembur() {
    return $this->db->get('abs_nilai_lembur')->result();
}



}