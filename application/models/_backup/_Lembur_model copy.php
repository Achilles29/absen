<?php
class Lembur_model extends CI_Model {

    // Ambil nilai lembur per jam
    public function get_nilai_lembur() {
        return $this->db->get('nilai_lembur')->row();
    }

    // Insert lembur dengan perhitungan per jam
    public function insert_lembur($data) {
        $nilai_lembur = $this->get_nilai_lembur();
        
        // Hitung total lembur dalam jam (dibulatkan)
        $lama_lembur_jam = ceil($data['lama_lembur']); 

        // Total gaji lembur
        $data['total_gaji_lembur'] = $lama_lembur_jam * $nilai_lembur->nilai_per_jam;

        // Simpan ke database
        return $this->db->insert('lembur', $data);
    }

    // Ambil laporan lembur
    public function get_laporan_lembur() {
        $this->db->select('lembur.*, pegawai.nama, nilai_lembur.nilai_per_jam');
        $this->db->from('lembur');
        $this->db->join('pegawai', 'lembur.pegawai_id = pegawai.id', 'left');
        $this->db->join('nilai_lembur', '1=1', 'left'); // Untuk ambil nilai lembur
        return $this->db->get()->result();
    }

    public function update_nilai_lembur($data) {
    // Periksa apakah data nilai lembur sudah ada
    $exists = $this->db->get('nilai_lembur')->row();

    if ($exists) {
        // Jika sudah ada, update data
        return $this->db->update('nilai_lembur', $data);
    } else {
        // Jika belum ada, insert data baru
        return $this->db->insert('nilai_lembur', $data);
    }
}


// public function calculate_total_lembur() {
//     $this->db->select_sum('total_gaji_lembur', 'total_lembur');
//     $query = $this->db->get('lembur'); // Pastikan tabel lembur sudah benar
//     $result = $query->row();
//     return $result ? $result->total_lembur : 0;
// }

public function calculate_total_lembur() {
    $this->db->select_sum('total_gaji_lembur', 'total_lembur');
    $this->db->where('MONTH(tanggal)', date('m')); // Filter berdasarkan bulan sekarang
    $this->db->where('YEAR(tanggal)', date('Y')); // Filter berdasarkan tahun sekarang
    $query = $this->db->get('lembur'); // Pastikan tabel lembur benar
    $result = $query->row();
    return $result ? $result->total_lembur : 0;
}
}