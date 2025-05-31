<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_model extends CI_Model {
    
    public function get_jadwal_shift($bulan) {
        $this->db->select('
            abs_pegawai.nama AS nama_pegawai,
            abs_divisi.nama_divisi AS nama_divisi,
            abs_jadwal_shift.tanggal,
            abs_shift.kode_shift,
            abs_shift.jam_mulai,
            abs_shift.jam_selesai
        ');
        $this->db->from('abs_jadwal_shift');
        $this->db->join('abs_pegawai', 'abs_pegawai.id = abs_jadwal_shift.pegawai_id', 'left');
        $this->db->join('abs_divisi', 'abs_divisi.id = abs_pegawai.divisi_id', 'left');
        $this->db->join('abs_shift', 'abs_shift.id = abs_jadwal_shift.shift_id', 'left');
        $this->db->where('DATE_FORMAT(abs_jadwal_shift.tanggal, "%Y-%m") =', $bulan);
        $this->db->order_by('abs_jadwal_shift.tanggal', 'ASC');
        return $this->db->get()->result();
    }
public function get_jadwal_shift_bulanan($tanggal_awal, $tanggal_akhir) {
    // Validasi input tanggal untuk menghindari kesalahan
    if (empty($tanggal_awal) || empty($tanggal_akhir)) {
        log_message('error', 'Tanggal awal atau akhir kosong di get_jadwal_shift_bulanan');
        return [];
    }

    // Query untuk mendapatkan data jadwal shift bulanan
    $this->db->select('
        abs_pegawai.id AS pegawai_id,
        abs_pegawai.nama AS nama_pegawai,
        abs_divisi.nama_divisi AS nama_divisi,
        abs_jadwal_shift.pegawai_id,
        abs_jadwal_shift.tanggal,
        abs_shift.kode_shift,
        abs_shift.jam_mulai,
        abs_shift.jam_selesai
    ');
    $this->db->from('abs_jadwal_shift');
    $this->db->join('abs_pegawai', 'abs_pegawai.id = abs_jadwal_shift.pegawai_id', 'left');
    $this->db->join('abs_divisi', 'abs_divisi.id = abs_pegawai.divisi_id', 'left');
    $this->db->join('abs_shift', 'abs_shift.id = abs_jadwal_shift.shift_id', 'left');
    $this->db->where('abs_jadwal_shift.tanggal >=', $tanggal_awal);
    $this->db->where('abs_jadwal_shift.tanggal <=', $tanggal_akhir);
    $this->db->order_by('abs_divisi.nama_divisi', 'ASC');
    $this->db->order_by('abs_pegawai.id', 'ASC');

    // Debug query untuk log jika diperlukan
    $query = $this->db->get();
    log_message('debug', 'Query get_jadwal_shift_bulanan: ' . $this->db->last_query());

    // Pastikan hasil query berupa objek
    return $query->result();
}

// public function get_jadwal_shift_bulanan($tanggal_awal, $tanggal_akhir) {
//     $this->db->select('
//         abs_pegawai.id AS pegawai_id,
//         abs_pegawai.nama AS nama_pegawai,
//         abs_divisi.nama_divisi AS nama_divisi,
//         abs_jadwal_shift.pegawai_id,
//         abs_jadwal_shift.tanggal,
//         abs_shift.kode_shift,
//         abs_shift.jam_mulai,
//         abs_shift.jam_selesai
//     ');
//     $this->db->from('abs_jadwal_shift');
//     $this->db->join('abs_pegawai', 'abs_pegawai.id = abs_jadwal_shift.pegawai_id', 'left');
//     $this->db->join('abs_divisi', 'abs_divisi.id = abs_pegawai.divisi_id', 'left');
//     $this->db->join('abs_shift', 'abs_shift.id = abs_jadwal_shift.shift_id', 'left');
//     $this->db->where('abs_jadwal_shift.tanggal >=', $tanggal_awal);
//     $this->db->where('abs_jadwal_shift.tanggal <=', $tanggal_akhir);
//     $this->db->order_by('abs_divisi.nama_divisi', 'ASC');
//     $this->db->order_by('abs_pegawai.id', 'ASC');

//     // Log query
//     log_message('debug', 'Query Get Jadwal Shift Bulanan: ' . $this->db->last_query());

//     return $this->db->get()->result();
// }


    public function delete_jadwal_shift($id) {
        return $this->db->delete('abs_jadwal_shift', ['id' => $id]);
    }

    public function get_jadwal_shift_bulanan_detail($tanggal_awal, $tanggal_akhir, $pegawai_id = null) {
        $this->db->select('
            abs_jadwal_shift.id,
            abs_jadwal_shift.tanggal,
            abs_shift.kode_shift,
            abs_shift.nama_shift,
            abs_shift.jam_mulai,
            abs_shift.jam_selesai
        ');
        $this->db->from('abs_jadwal_shift');
        $this->db->join('abs_shift', 'abs_shift.id = abs_jadwal_shift.shift_id', 'left');
        $this->db->where('abs_jadwal_shift.tanggal >=', $tanggal_awal);
        $this->db->where('abs_jadwal_shift.tanggal <=', $tanggal_akhir);

        if ($pegawai_id) {
            $this->db->where('abs_jadwal_shift.pegawai_id', $pegawai_id);
        }

        $this->db->order_by('abs_jadwal_shift.tanggal', 'ASC');
        return $this->db->get()->result();
    }

    public function insert_jadwal_shift_batch($data) {
        if (!empty($data)) {
            $this->db->insert_batch('abs_jadwal_shift', $data);
        }
    }

    public function get_all_shift() {
        $this->db->select('id, kode_shift, jam_mulai, jam_selesai');
        $this->db->from('abs_shift');
        $this->db->order_by('kode_shift', 'ASC');
        return $this->db->get()->result();
    }

    public function insert_or_update_jadwal($data) {
        $this->db->where('pegawai_id', $data['pegawai_id']);
        $this->db->where('tanggal', $data['tanggal']);
        $existing = $this->db->get('abs_jadwal_shift')->row();

        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('abs_jadwal_shift', $data);
        } else {
            $this->db->insert('abs_jadwal_shift', $data);
        }
    }

    public function get_shift_by_pegawai_tanggal($pegawai_id, $tanggal) {
        $this->db->select('abs_shift.kode_shift');
        $this->db->from('abs_jadwal_shift');
        $this->db->join('abs_shift', 'abs_shift.id = abs_jadwal_shift.shift_id', 'left');
        $this->db->where('abs_jadwal_shift.pegawai_id', $pegawai_id);
        $this->db->where('abs_jadwal_shift.tanggal', $tanggal);
        return $this->db->get()->row();
    }

    public function update_jadwal_shift($pegawai_id, $tanggal, $shift_id) {
        $this->db->where('pegawai_id', $pegawai_id);
        $this->db->where('tanggal', $tanggal);
        $exists = $this->db->get('abs_jadwal_shift')->row();

        if ($exists) {
            $this->db->where('id', $exists->id);
            $this->db->update('abs_jadwal_shift', ['shift_id' => $shift_id]);
        } else {
            $this->db->insert('abs_jadwal_shift', [
                'pegawai_id' => $pegawai_id,
                'tanggal' => $tanggal,
                'shift_id' => $shift_id
            ]);
        }
    }

    public function get_shift_hari_ini($pegawai_id, $tanggal) {
        $this->db->select('abs_jadwal_shift.shift_id, abs_shift.kode_shift, abs_shift.nama_shift, abs_shift.jam_mulai, abs_shift.jam_selesai');
        $this->db->from('abs_jadwal_shift');
        $this->db->join('abs_shift', 'abs_shift.id = abs_jadwal_shift.shift_id', 'left');
        $this->db->where('abs_jadwal_shift.pegawai_id', $pegawai_id);
        $this->db->where('abs_jadwal_shift.tanggal', $tanggal);
        return $this->db->get()->row();
    }

    public function get_jadwal_shift_by_divisi($divisi_id, $tanggal_awal, $tanggal_akhir) {
        $this->db->select('
            abs_pegawai.nama AS nama_pegawai,
            abs_jadwal_shift.tanggal,
            abs_shift.kode_shift,
            abs_shift.jam_mulai,
            abs_shift.jam_selesai
        ');
        $this->db->from('abs_jadwal_shift');
        $this->db->join('abs_pegawai', 'abs_pegawai.id = abs_jadwal_shift.pegawai_id', 'left');
        $this->db->join('abs_shift', 'abs_shift.id = abs_jadwal_shift.shift_id', 'left');
        $this->db->where('abs_pegawai.divisi_id', $divisi_id);
        $this->db->where('abs_jadwal_shift.tanggal >=', $tanggal_awal);
        $this->db->where('abs_jadwal_shift.tanggal <=', $tanggal_akhir);
        $this->db->order_by('abs_jadwal_shift.tanggal', 'ASC');
        return $this->db->get()->result();
    }

    public function get_rekap_jadwal($period) {
        $this->db->select('abs_pegawai.nama, abs_divisi.nama_divisi, abs_shift.kode_shift, COUNT(abs_jadwal_shift.shift_id) as jumlah');
        $this->db->from('abs_jadwal_shift');
        $this->db->join('abs_pegawai', 'abs_jadwal_shift.pegawai_id = abs_pegawai.id', 'inner');
        $this->db->join('abs_divisi', 'abs_pegawai.divisi_id = abs_divisi.id', 'inner');
        $this->db->join('abs_shift', 'abs_jadwal_shift.shift_id = abs_shift.id', 'inner');
        $this->db->where('DATE_FORMAT(abs_jadwal_shift.tanggal, "%Y-%m") =', $period);
        $this->db->group_by(['abs_pegawai.id', 'abs_divisi.id', 'abs_shift.kode_shift']);
        $this->db->order_by('abs_divisi.nama_divisi', 'ASC');
        $this->db->order_by('abs_pegawai.nama', 'ASC');

        return $this->db->get()->result_array();
    }

    public function count_all_shifts() {
        return $this->db->count_all('abs_jadwal_shift');
    }

public function get_jadwal_detail($pegawai_id, $bulan) {
    $this->db->select('tanggal, kode_shift, jam_mulai, jam_selesai');
    $this->db->from('abs_jadwal_shift');
    $this->db->join('abs_shift', 'abs_jadwal_shift.shift_id = abs_shift.id', 'left');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
    $this->db->order_by('tanggal', 'ASC');
    return $this->db->get()->result();
}


public function get_jumlah_shift($pegawai_id, $bulan) {
    $this->db->select('COUNT(*) AS jumlah_shift');
    $this->db->from('abs_jadwal_shift');
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where('DATE_FORMAT(tanggal, "%Y-%m") =', $bulan);
    $result = $this->db->get()->row();

    return $result ? $result->jumlah_shift : 0; // Kembalikan jumlah shift atau 0 jika tidak ada data
}

public function get_hari_kerja_per_pegawai($tanggal_awal, $tanggal_akhir) {
    $this->db->select('pegawai_id, COUNT(tanggal) as hari_kerja');
    $this->db->from('abs_jadwal_shift');
    $this->db->where('tanggal >=', $tanggal_awal);
    $this->db->where('tanggal <=', $tanggal_akhir);
    $this->db->group_by('pegawai_id');

    $result = $this->db->get()->result();

    $hari_kerja = [];
    foreach ($result as $row) {
        $hari_kerja[$row->pegawai_id] = $row->hari_kerja;
    }

    return $hari_kerja;
}
public function get_jadwal_shift_detail($pegawai_id, $tanggal_awal, $tanggal_akhir) {
    $this->db->select('
        abs_jadwal_shift.id,
        abs_jadwal_shift.tanggal,
        abs_shift.kode_shift,
        abs_shift.nama_shift,  -- Ambil kolom nama_shift
        abs_shift.jam_mulai,
        abs_shift.jam_selesai
    ');
    $this->db->from('abs_jadwal_shift');
    $this->db->join('abs_shift', 'abs_shift.id = abs_jadwal_shift.shift_id', 'left'); // JOIN dengan tabel abs_shift
    $this->db->where('abs_jadwal_shift.pegawai_id', $pegawai_id);
    $this->db->where('abs_jadwal_shift.tanggal >=', $tanggal_awal);
    $this->db->where('abs_jadwal_shift.tanggal <=', $tanggal_akhir);
    $this->db->order_by('abs_jadwal_shift.tanggal', 'ASC');

    // Debug query untuk memastikan kebenaran JOIN
    log_message('debug', 'Query get_jadwal_shift_detail: ' . $this->db->last_query());

    return $this->db->get()->result();
}




}
