<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_model extends CI_Model {
    public function get_jadwal_shift($bulan) {
        $this->db->select('
            pegawai.nama AS nama_pegawai,
            divisi.nama_divisi AS nama_divisi,
            jadwal_shift.tanggal,
            shift.kode_shift,
            shift.jam_mulai,
            shift.jam_selesai
        ');
        $this->db->from('jadwal_shift');
        $this->db->join('pegawai', 'pegawai.id = jadwal_shift.pegawai_id', 'left');
        $this->db->join('divisi', 'divisi.id = pegawai.divisi_id', 'left');
        $this->db->join('shift', 'shift.id = jadwal_shift.shift_id', 'left');
        $this->db->where('DATE_FORMAT(jadwal_shift.tanggal, "%Y-%m") =', $bulan);
        $this->db->order_by('jadwal_shift.tanggal', 'ASC');
        return $this->db->get()->result();
    }
public function get_jadwal_shift_bulanan($tanggal_awal, $tanggal_akhir) {
    $this->db->select('
        pegawai.id AS pegawai_id,
        pegawai.nama AS nama_pegawai,
        divisi.nama_divisi AS nama_divisi,
        jadwal_shift.pegawai_id,
        jadwal_shift.tanggal,
        shift.kode_shift,
        shift.jam_mulai,
        shift.jam_selesai
    ');
    $this->db->from('jadwal_shift');
    $this->db->join('pegawai', 'pegawai.id = jadwal_shift.pegawai_id', 'left');
    $this->db->join('divisi', 'divisi.id = pegawai.divisi_id', 'left');
    $this->db->join('shift', 'shift.id = jadwal_shift.shift_id', 'left');
    $this->db->where('jadwal_shift.tanggal >=', $tanggal_awal);
    $this->db->where('jadwal_shift.tanggal <=', $tanggal_akhir);

    // Tambahkan sorting: divisi ASC lalu ID pegawai ASC
    $this->db->order_by('divisi.nama_divisi', 'ASC');
    $this->db->order_by('pegawai.id', 'ASC');

    $query = $this->db->get();
    return $query->result();
}
public function delete_jadwal_shift($id) {
    return $this->db->delete('jadwal_shift', ['id' => $id]);
}


public function get_jadwal_shift_bulanan_detail($tanggal_awal, $tanggal_akhir, $pegawai_id = null) {
    $this->db->select('
        jadwal_shift.id,
        jadwal_shift.tanggal,
        shift.kode_shift,
        shift.jam_mulai,
        shift.jam_selesai
    ');
    $this->db->from('jadwal_shift');
    $this->db->join('shift', 'shift.id = jadwal_shift.shift_id', 'left');
    $this->db->where('jadwal_shift.tanggal >=', $tanggal_awal);
    $this->db->where('jadwal_shift.tanggal <=', $tanggal_akhir);

    // Filter berdasarkan pegawai_id jika ada
    if ($pegawai_id) {
        $this->db->where('jadwal_shift.pegawai_id', $pegawai_id);
    }

    // Urutkan berdasarkan tanggal
    $this->db->order_by('jadwal_shift.tanggal', 'ASC');
    
    return $this->db->get()->result();
}

// public function insert_jadwal_shift_batch($data) {
//     $this->db->insert_batch('jadwal_shift', $data);
// }

public function insert_jadwal_shift_batch($data) {
    if (!empty($data)) {
        $this->db->insert_batch('jadwal_shift', $data);
    }
}


public function get_all_shift() {
    $this->db->select('id, kode_shift, jam_mulai, jam_selesai');
    $this->db->from('shift');
    $this->db->order_by('kode_shift', 'ASC');
    return $this->db->get()->result();
}
public function insert_or_update_jadwal($data) {
    log_message('debug', 'Data untuk insert/update: ' . json_encode($data));

    $this->db->where('pegawai_id', $data['pegawai_id']);
    $this->db->where('tanggal', $data['tanggal']);
    $existing = $this->db->get('jadwal_shift')->row();

    if ($existing) {
        log_message('debug', 'Update data jadwal_shift: ' . json_encode($existing));
        $this->db->where('id', $existing->id);
        $this->db->update('jadwal_shift', $data);
    } else {
        log_message('debug', 'Insert data jadwal_shift: ' . json_encode($data));
        $this->db->insert('jadwal_shift', $data);
    }
}


public function get_shift_by_pegawai_tanggal($pegawai_id, $tanggal) {
    $this->db->select('shift.kode_shift');
    $this->db->from('jadwal_shift');
    $this->db->join('shift', 'shift.id = jadwal_shift.shift_id', 'left');
    $this->db->where('jadwal_shift.pegawai_id', $pegawai_id);
    $this->db->where('jadwal_shift.tanggal', $tanggal);
    return $this->db->get()->row();
}
public function update_jadwal_shift($pegawai_id, $tanggal, $shift_id) {
    // Cek apakah data sudah ada
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where('tanggal', $tanggal);
    $exists = $this->db->get('jadwal_shift')->row();

    // Jika sudah ada, update
    if ($exists) {
        $this->db->where('id', $exists->id);
        $this->db->update('jadwal_shift', ['shift_id' => $shift_id]);
    } else {
        // Jika belum ada, insert
        $this->db->insert('jadwal_shift', [
            'pegawai_id' => $pegawai_id,
            'tanggal' => $tanggal,
            'shift_id' => $shift_id
        ]);
    }
}

public function get_shift_hari_ini($pegawai_id, $tanggal) {
    $this->db->select('jadwal_shift.shift_id, shift.kode_shift, shift.jam_mulai, shift.jam_selesai');
    $this->db->from('jadwal_shift');
    $this->db->join('shift', 'shift.id = jadwal_shift.shift_id', 'left');
    $this->db->where('jadwal_shift.pegawai_id', $pegawai_id);
    $this->db->where('jadwal_shift.tanggal', $tanggal);
    return $this->db->get()->row();
}

public function get_jadwal_shift_by_divisi($divisi_id, $tanggal_awal, $tanggal_akhir) {
    $this->db->select('
        pegawai.nama AS nama_pegawai,
        jadwal_shift.tanggal,
        shift.kode_shift,
        shift.jam_mulai,
        shift.jam_selesai
    ');
    $this->db->from('jadwal_shift');
    $this->db->join('pegawai', 'pegawai.id = jadwal_shift.pegawai_id', 'left');
    $this->db->join('shift', 'shift.id = jadwal_shift.shift_id', 'left');
    $this->db->where('pegawai.divisi_id', $divisi_id);
    $this->db->where('jadwal_shift.tanggal >=', $tanggal_awal);
    $this->db->where('jadwal_shift.tanggal <=', $tanggal_akhir);
    $this->db->order_by('jadwal_shift.tanggal', 'ASC');
    return $this->db->get()->result();
}

public function get_rekap_jadwal($period)
{
    $this->db->select('pegawai.nama, divisi.nama_divisi, shift.kode_shift, COUNT(jadwal_shift.shift_id) as jumlah');
    $this->db->from('jadwal_shift');
    $this->db->join('pegawai', 'jadwal_shift.pegawai_id = pegawai.id', 'inner');
    $this->db->join('divisi', 'pegawai.divisi_id = divisi.id', 'inner');
    $this->db->join('shift', 'jadwal_shift.shift_id = shift.id', 'inner');
    $this->db->where('DATE_FORMAT(jadwal_shift.tanggal, "%Y-%m") =', $period);
    $this->db->group_by(['pegawai.id', 'divisi.id', 'shift.kode_shift']);
    $this->db->order_by('divisi.nama_divisi', 'ASC');
    $this->db->order_by('pegawai.nama', 'ASC');

    return $this->db->get()->result_array();
}

public function count_all_shifts() {
    return $this->db->count_all('jadwal_shift');
}



}
