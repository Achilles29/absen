<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GenerateTabel extends CI_Controller {
    public function __construct() {
        parent::__construct();

        // Hanya bisa dijalankan melalui terminal (CLI)
        if (php_sapi_name() !== 'cli') {
            echo "Script ini hanya dapat dijalankan melalui terminal.\n";
            exit;
        }

        $this->load->database(); // Load koneksi database
    }

    public function generate_tabel() {
        $this->db->select('
            jadwal_shift.id AS jadwal_id,
            abs_pegawai.id AS pegawai_id,
            abs_pegawai.nama,
            jadwal_shift.tanggal,
            abs_shift.kode_shift,
            abs_shift.jam_mulai,
            abs_shift.jam_selesai
        ');
        $this->db->from('jadwal_shift');
        $this->db->join('abs_pegawai', 'jadwal_shift.pegawai_id = abs_pegawai.id', 'left');
        $this->db->join('abs_shift', 'jadwal_shift.shift_id = abs_shift.id', 'left');
        $jadwal_data = $this->db->get()->result();

        $generated_data = [];

        foreach ($jadwal_data as $row) {
            $jam_masuk = date('H:i:s', strtotime($row->jam_mulai) - 30 * 60);
            $jam_pulang = date('H:i:s', strtotime($row->jam_mulai) + 30 * 60);

            $generated_data[] = [
                'id' => $row->jadwal_id,
                'pegawai_id' => $row->pegawai_id,
                'nama' => $row->nama,
                'pesan' => 'Halo ' . $row->nama . ', jangan lupa absen ya...',
                'tanggal' => $row->tanggal,
                'kode_shift' => $row->kode_shift,
                'jam_masuk' => $jam_masuk,
                'jam_pulang' => $jam_pulang
            ];
        }

        $this->db->insert_batch('generated_tabel', $generated_data);
        echo "Data berhasil di-generate dan disimpan ke tabel 'generated_tabel'.\n";
    }
}
