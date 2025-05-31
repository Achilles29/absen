<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_shift extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('Jadwal_model');
        $this->load->model('Pegawai_model');
    }

    public function jadwal_shift_bulanan() {
        $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default bulan ini
        $tanggal_awal = date('Y-m-01', strtotime($bulan));
        $tanggal_akhir = date('Y-m-t', strtotime($bulan));

        $this->load->model('Jadwal_model');

        // Ambil jadwal shift pegawai
        $jadwal_shift = $this->Jadwal_model->get_jadwal_shift_bulanan($tanggal_awal, $tanggal_akhir);

        $data['title'] = 'Jadwal Shift Pegawai Bulanan';
        $data['bulan'] = $bulan;
        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['jadwal_shift'] = $jadwal_shift;

        $this->load->view('templates/header', $data);
        $this->load->view('jadwal/jadwal_shift_bulanan', $data);
        $this->load->view('templates/footer');
    }

    public function input_jadwal_shift() {
    $this->load->model('Pegawai_model');
    $this->load->model('Shift_model');

    $data['title'] = 'Input Jadwal Shift Pegawai';
    $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();
    $data['shifts'] = $this->Shift_model->get_all_shift();

    if ($this->input->post()) {
        $tanggal_awal = $this->input->post('tanggal_awal');
        $tanggal_akhir = $this->input->post('tanggal_akhir');
        $pegawai_id = $this->input->post('pegawai_id');
        $shift_id = $this->input->post('shift_id');

        $jadwal_data = [];
        $current_date = strtotime($tanggal_awal);
        $end_date = strtotime($tanggal_akhir);

        while ($current_date <= $end_date) {
            $jadwal_data[] = [
                'pegawai_id' => $pegawai_id,
                'tanggal' => date('Y-m-d', $current_date),
                'shift_id' => $shift_id
            ];
            $current_date = strtotime("+1 day", $current_date);
        }

        $this->load->model('Jadwal_model');
        $this->Jadwal_model->insert_jadwal_shift_batch($jadwal_data);

        $this->session->set_flashdata('success', 'Jadwal shift berhasil ditambahkan!');
        redirect('jadwal/input_jadwal_shift');
    }

    $this->load->view('templates/header', $data);
    $this->load->view('jadwal/input_jadwal_shift', $data);
    $this->load->view('templates/footer');
}

}
