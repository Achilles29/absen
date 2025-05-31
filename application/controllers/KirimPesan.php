<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KirimPesan extends CI_Controller {
    private $apiUrl = 'https://api.ultramsg.com/instance102297/';
    private $token = 'j1qtu1lrg7lkzaly';

    public function kirim_pesan_jadwal() {
        // Load database
        $this->load->database();

        // Waktu sekarang
        $now = date('H:i:00'); // Format waktu jam:menit:detik
        $today = date('Y-m-d');

        // Ambil data untuk pesan masuk yang belum terkirim
        $masukData = $this->db
            ->select('*')
            ->from('generated_tabel') // Ganti dengan nama tabel Anda
            ->where('tanggal', $today)
            ->where('jam_masuk', $now)
            ->where('status_masuk', 'pending')
            ->get()
            ->result();

        // Ambil data untuk pesan pulang yang belum terkirim
        $pulangData = $this->db
            ->select('*')
            ->from('generated_tabel') // Ganti dengan nama tabel Anda
            ->where('tanggal', $today)
            ->where('jam_pulang', $now)
            ->where('status_pulang', 'pending')
            ->get()
            ->result();

        // Kirim pesan untuk jam masuk
        foreach ($masukData as $row) {
            $this->kirim_pesan_ultramsg($row->pesan, $row->nama);
            $this->update_status_pesan($row->id, 'status_masuk');
        }

        // Kirim pesan untuk jam pulang
        foreach ($pulangData as $row) {
            $this->kirim_pesan_ultramsg($row->pesan, $row->nama);
            $this->update_status_pesan($row->id, 'status_pulang');
        }

        echo "Pesan telah dikirim untuk jadwal saat ini.";
    }

    private function kirim_pesan_ultramsg($pesan, $groupId) {
        $url = $this->apiUrl . 'messages/chat';
        $data = [
            'token' => $this->token,
            'to' => '120363296468769530@g.us', // Grup WA
            'body' => $pesan
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function update_status_pesan($id, $field) {
        // Update status pesan menjadi "sent"
        $this->db
            ->where('id', $id)
            ->update('generated_tabel', [$field => 'sent']);
    }
}