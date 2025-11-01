<?php
class Tambahan_model extends CI_Model {

    public function get_rekap_tambahan($bulan) {
        $this->db->select('abs_pegawai.id, abs_pegawai.nama, SUM(abs_tambahan_lain.nilai_tambahan) AS total_tambahan');
        $this->db->from('abs_pegawai');
        $this->db->join('abs_tambahan_lain', 'abs_pegawai.id = abs_tambahan_lain.pegawai_id', 'left');
        $this->db->where("DATE_FORMAT(abs_tambahan_lain.tanggal, '%Y-%m') =", $bulan);
        $this->db->group_by('abs_pegawai.id');
        return $this->db->get()->result();
    }

    public function insert_tambahan($data) {
        $this->db->insert('abs_tambahan_lain', $data);
    }

    // public function get_detail_tambahan($pegawai_id, $bulan) {
    //     $this->db->select('*');
    //     $this->db->from('abs_tambahan_lain');
    //     $this->db->where('pegawai_id', $pegawai_id);
    //     $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
    //     return $this->db->get()->result();
    // }

    public function get_detail_tambahan($pegawai_id, $bulan) {
    $this->db->select('tanggal, nilai_tambahan AS nilai_tambahan, keterangan'); // Gunakan alias jika perlu
    $this->db->from('abs_tambahan_lain');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
    return $this->db->get()->result();
}

    public function get_pegawai_by_id($id) {
        return $this->db->get_where('abs_pegawai', ['id' => $id])->row();
    }

    public function calculate_total_tambahan($bulan = null) {
        $bulan = $bulan ?? date('m'); // Default ke bulan sekarang
        $tahun = date('Y'); // Gunakan tahun sekarang

        // Total dari tabel abs_tambahan_lain
        $this->db->select_sum('nilai_tambahan', 'total_tambahan_lain');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $query1 = $this->db->get('abs_tambahan_lain');
        $result1 = $query1->row();

        $total_tambahan_lain = $result1 && $result1->total_tambahan_lain !== null ? (float)$result1->total_tambahan_lain : 0;

        // Total dari kolom tambahan_lain di tabel abs_pegawai
        $this->db->select_sum('tambahan_lain', 'total_tambahan_pegawai');
        $query2 = $this->db->get('abs_pegawai');
        $result2 = $query2->row();

        $total_tambahan_pegawai = $result2 && $result2->total_tambahan_pegawai !== null ? (float)$result2->total_tambahan_pegawai : 0;

        // Gabungkan total
        return $total_tambahan_lain + $total_tambahan_pegawai;
    }

public function get_total_tambahan($pegawai_id, $bulan) {
    $this->db->select_sum('nilai_tambahan', 'total_tambahan');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
    $result = $this->db->get('abs_tambahan_lain')->row();
    return $result ? $result->total_tambahan : 0;
}
public function get_log_tambahan($bulan, $tahun) {
    $this->db->select('abs_tambahan_lain.*, abs_pegawai.nama AS nama_pegawai');
    $this->db->from('abs_tambahan_lain');
    $this->db->join('abs_pegawai', 'abs_tambahan_lain.pegawai_id = abs_pegawai.id', 'left');
    $this->db->where('MONTH(abs_tambahan_lain.tanggal)', $bulan);
    $this->db->where('YEAR(abs_tambahan_lain.tanggal)', $tahun);
    $this->db->order_by('abs_tambahan_lain.tanggal', 'ASC');
    return $this->db->get()->result();
}

public function delete_tambahan($id) {
    return $this->db->delete('abs_tambahan_lain', ['id' => $id]);
}

public function update_tambahan($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('abs_tambahan_lain', $data);
}


}
