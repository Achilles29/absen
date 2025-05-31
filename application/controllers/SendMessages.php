<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SendMessages extends CI_Controller {
    private $apiUrl = 'https://api.ultramsg.com/instance102297/';
    private $token = 'j1qtu1lrg7lkzaly';
    private $groupId = '120363296468769530@g.us';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function send_pending_messages() {
        $current_time = date('H:i:s');
        $current_date = date('Y-m-d');

        // Ambil pesan dengan status pending dan waktu sudah terlewati
        $query = $this->db->query("
            SELECT * FROM generated_tabel
            WHERE tanggal = ? AND (
                (status_masuk = 'pending' AND jam_masuk <= ?) OR
                (status_pulang = 'pending' AND jam_pulang <= ?)
            )
        ", [$current_date, $current_time, $current_time]);

        $messages = $query->result();

        foreach ($messages as $message) {
            // Kirim pesan jam masuk
            if ($message->status_masuk === 'pending' && $message->jam_masuk <= $current_time) {
                $this->send_message($message->pesan); // Mengirim kolom 'pesan'
                $this->update_status($message->id, 'status_masuk');
            }

            // Kirim pesan jam pulang
            if ($message->status_pulang === 'pending' && $message->jam_pulang <= $current_time) {
                $pesan_pulang = "Halo {$message->nama}, terima kasih atas kerja keras Anda hari ini.";
                $this->send_message($pesan_pulang);
                $this->update_status($message->id, 'status_pulang');
            }
        }

        echo "Pesan berhasil diproses.";
    }

    private function send_message($message) {
        $payload = [
            'token' => $this->token,
            'to' => $this->groupId,
            'body' => $message
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . 'messages/chat',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload)
        ]);

        $response = curl_exec($curl);

        // Debugging untuk memeriksa respons
        if (!$response) {
            echo 'Curl Error: ' . curl_error($curl);
        } else {
            echo 'API Response: ' . $response;
        }

        curl_close($curl);

        return $response;
    }

    private function update_status($id, $status_column) {
        $this->db->set($status_column, 'sent');
        $this->db->where('id', $id);
        $this->db->update('generated_tabel');
    }
}
