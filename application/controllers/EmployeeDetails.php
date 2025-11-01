<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeDetails extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $allowed_roles = ['pegawai', 'spv', 'hod']; // Daftar role yang diizinkan
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login'); // Redirect jika tidak memiliki izin
        }

        $this->load->model('Jadwal_model');
        $this->load->model('Lembur_model');
        $this->load->model('Kasbon_model');
        $this->load->model('Potongan_model');
        $this->load->model('Tambahan_model');
        $this->load->model('Deposit_model'); // Load model Deposit_model
        $this->load->model('Pegawai_model'); // Contoh model lain jika diperlukan
    }

    public function jadwal_detail() {
        $pegawai_id = $this->session->userdata('id');
        $bulan = $this->input->get('bulan') ?? date('Y-m');

        $data['jadwal'] = $this->Jadwal_model->get_jadwal_detail($pegawai_id, $bulan);
        $data['bulan'] = $bulan;
        $data['title'] = 'Jadwal Detail';
        $this->load->view('templates/header', $data);
        $this->load->view('pegawai/jadwal_detail', $data);
        $this->load->view('templates/footer');
    }

public function lembur() {
    $pegawai_id = $this->session->userdata('id');
    $bulan = $this->input->get('bulan') ?? date('Y-m');

    $lembur = $this->Lembur_model->get_detail_lembur($pegawai_id, $bulan);

    // Inisialisasi variabel total
    $total_lama_lembur = 0;
    $total_uang_lembur = 0;

    foreach ($lembur as $item) {
        $total_lama_lembur += $item->lama_lembur;
        $total_uang_lembur += $item->total_gaji_lembur;
    }

    $data['lembur'] = $lembur;
    $data['total_lama_lembur'] = $total_lama_lembur;
    $data['total_uang_lembur'] = $total_uang_lembur;
    $data['bulan'] = $bulan;
    $data['title'] = 'Lembur Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('pegawai/lembur', $data);
    $this->load->view('templates/footer');
}


    public function kasbon() {
        $pegawai_id = $this->session->userdata('id');
        $bulan = $this->input->get('bulan') ?? date('Y-m');

        $kasbon_data = $this->Kasbon_model->get_detail_kasbon($pegawai_id, $bulan);
        $jumlah_kasbon = $this->Kasbon_model->calculate_kasbon_bulan($pegawai_id, $bulan) ?? 0;
        $jumlah_kasbon_total = $this->Kasbon_model->calculate_kasbon_total($pegawai_id) ?? 0;

        $data = [
            'kasbon' => $kasbon_data,
            'jumlah_kasbon' => $jumlah_kasbon,
            'jumlah_kasbon_total' => $jumlah_kasbon_total,
            'bulan' => $bulan,
            'title' => 'Kasbon Pegawai'
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('pegawai/kasbon', $data);
        $this->load->view('templates/footer');
    }

public function potongan() {
    $pegawai_id = $this->session->userdata('id');
    $bulan = $this->input->get('bulan') ?? date('Y-m');

    $potongan = $this->Potongan_model->get_detail_potongan($pegawai_id, $bulan);

    // Hitung total potongan
    $total_potongan = 0;
    foreach ($potongan as $row) {
        $total_potongan += $row->nilai;
    }

    $data['potongan'] = $potongan;
    $data['total_potongan'] = $total_potongan;
    $data['bulan'] = $bulan;
    $data['title'] = 'Potongan';

    $this->load->view('templates/header', $data);
    $this->load->view('pegawai/potongan', $data);
    $this->load->view('templates/footer');
}

public function tambahan() {
    $pegawai_id = $this->session->userdata('id');
    $bulan = $this->input->get('bulan') ?? date('Y-m');

    $tambahan = $this->Tambahan_model->get_detail_tambahan($pegawai_id, $bulan);

    // Debug log
    log_message('debug', 'Data tambahan: ' . print_r($tambahan, true));

    $total_tambahan_lain = 0;
    if (!empty($tambahan)) {
        foreach ($tambahan as $row) {
            $total_tambahan_lain += (float)$row->nilai_tambahan; // Pastikan properti sesuai
        }
    }

    $data['tambahan'] = $tambahan;
    $data['total_tambahan_lain'] = $total_tambahan_lain;
    $data['bulan'] = $bulan;
    $data['title'] = 'Tambahan Lain';

    $this->load->view('templates/header', $data);
    $this->load->view('pegawai/tambahan', $data);
    $this->load->view('templates/footer');
}



public function deposit() {
    $pegawai_id = $this->session->userdata('id');
    $bulan = $this->input->get('bulan') ?? date('Y-m');

    // Data detail deposit berdasarkan bulan
    $deposit = $this->Deposit_model->get_detail_deposit($pegawai_id, $bulan);

    // Hitung total setoran dan penarikan bulan ini
    $total_setor = 0;
    $total_tarik = 0;

    if (!empty($deposit)) {
        foreach ($deposit as $row) {
            if ($row->jenis === 'setor') {
                $total_setor += (float)$row->nilai;
            } elseif ($row->jenis === 'tarik') {
                $total_tarik += (float)$row->nilai;
            }
        }
    }

    // Hitung sisa deposit bulan ini
    $sisa_deposit_bulan_ini = $total_setor - $total_tarik;

    // Hitung sisa deposit total sepanjang waktu
    $sisa_deposit_total = $this->Deposit_model->get_sisa_deposit_total($pegawai_id);

    $data = [
        'deposit' => $deposit,
        'total_setor' => $total_setor,
        'total_tarik' => $total_tarik,
        'sisa_deposit_bulan_ini' => $sisa_deposit_bulan_ini,
        'sisa_deposit_total' => $sisa_deposit_total->total_setor - $sisa_deposit_total->total_tarik,
        'bulan' => $bulan,
        'title' => 'Deposit'
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('pegawai/deposit', $data);
    $this->load->view('templates/footer');
}

}
