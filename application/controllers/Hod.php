<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hod extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'hod') {
            redirect('auth/login'); // Hanya HOD yang bisa mengakses
        }
        $this->load->model('Jadwal_model');
        $this->load->model('Pegawai_model');

    }

    public function jadwal() {
        $data['title'] = 'Jadwal Shift Divisi';
        $data['bulan'] = $this->input->get('bulan') ?? date('Y-m');
        $tanggal_awal = date('Y-m-01', strtotime($data['bulan']));
        $tanggal_akhir = date('Y-m-t', strtotime($data['bulan']));

        // Ambil jadwal shift berdasarkan divisi HOD
        $divisi_id = $this->session->userdata('divisi_id'); // Pastikan ada divisi_id di session
        $data['jadwal_shift'] = $this->Jadwal_model->get_jadwal_shift_by_divisi($divisi_id, $tanggal_awal, $tanggal_akhir);

        $this->load->view('templates/header', $data);
        $this->load->view('hod/jadwal', $data);
        $this->load->view('templates/footer');
    }    
    public function jadwal_shift() {
        $data['title'] = 'Jadwal Shift Pegawai Divisi';
        $bulan = $this->input->get('bulan') ?? date('Y-m');
        $tanggal_awal = date('Y-m-01', strtotime($bulan));
        $tanggal_akhir = date('Y-m-t', strtotime($bulan));

        // Ambil jadwal shift hanya untuk divisi HOD
        $divisi_id = $this->session->userdata('divisi_id');
        $data['jadwal_shift'] = $this->Jadwal_model->get_jadwal_shift_by_divisi($divisi_id, $tanggal_awal, $tanggal_akhir);

        $data['bulan'] = $bulan;

        $this->load->view('templates/header', $data);
        $this->load->view('hod/jadwal_shift', $data);
        $this->load->view('templates/footer');
    }

    
public function index() {
    $this->load->model('Pegawai_model');
    $this->load->model('Jadwal_model');

    $data['title'] = 'Jadwal Shift Pegawai';
    $data['bulan'] = $this->input->get('bulan') ?? date('Y-m');
    $tanggal_awal = date('Y-m-01', strtotime($data['bulan']));
    $tanggal_akhir = date('Y-m-t', strtotime($data['bulan']));

    // Ambil data pegawai
    $data['pegawai'] = $this->Pegawai_model->get_all_pegawai_except_admin();

    // Ambil jadwal shift
    $data['jadwal_shift'] = $this->Jadwal_model->get_jadwal_shift_bulanan($tanggal_awal, $tanggal_akhir);

    // Hitung hari kerja
    $data['hari_kerja'] = [];
    foreach ($data['jadwal_shift'] as $jadwal) {
        if (!empty($jadwal->kode_shift)) {
            $data['hari_kerja'][$jadwal->pegawai_id] = 
                isset($data['hari_kerja'][$jadwal->pegawai_id]) ? $data['hari_kerja'][$jadwal->pegawai_id] + 1 : 1;
        }
    }

    $this->load->view('templates/header', $data);
    $this->load->view('hod/index', $data);
    $this->load->view('templates/footer');
}


public function jadwal_shift_bulanan() {
    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default bulan ini
    $tanggal_awal = date('Y-m-01', strtotime($bulan));
    $tanggal_akhir = date('Y-m-t', strtotime($bulan));

    $this->load->model('Jadwal_model');

    // Ambil jadwal shift pegawai
    $jadwal_shift = $this->Jadwal_model->get_jadwal_shift_bulanan($tanggal_awal, $tanggal_akhir);

    // Hitung jumlah hari kerja per pegawai
    $hari_kerja = [];
    foreach ($jadwal_shift as $jadwal) {
        if (!empty($jadwal->kode_shift)) {
            $hari_kerja[$jadwal->pegawai_id] = ($hari_kerja[$jadwal->pegawai_id] ?? 0) + 1;
        }
    }

    $data['title'] = 'Jadwal Shift Pegawai Bulanan';
    $data['bulan'] = $bulan;
    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;
    $data['jadwal_shift'] = $jadwal_shift;
    $data['hari_kerja'] = $hari_kerja;

    $this->load->view('templates/header', $data);
    $this->load->view('jadwal_shift/jadwal_shift_bulanan', $data);
    $this->load->view('templates/footer');
}


public function input_jadwal_shift() {
    $this->load->model('Pegawai_model');
    $this->load->model('Jadwal_model');

    $data['title'] = 'Input Jadwal Shift Pegawai';
    $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();
    $data['shifts'] = $this->Jadwal_model->get_all_shift();

    if ($this->input->post()) {
        $tanggal = $this->input->post('tanggal');
        $pegawai_id = $this->input->post('pegawai_id');
        $shift_id = $this->input->post('shift_id');

        // Siapkan data jadwal
        $jadwal_data = [
            'pegawai_id' => $pegawai_id,
            'tanggal' => $tanggal,
            'shift_id' => $shift_id
        ];

        // Simpan data ke database
        $this->Jadwal_model->insert_jadwal_shift_batch([$jadwal_data]);

        $this->session->set_flashdata('success', 'Jadwal shift berhasil ditambahkan!');
        redirect('jadwal_shift/input_jadwal_shift');
    }

    $this->load->view('templates/header', $data);
    $this->load->view('jadwal_shift/input_jadwal_shift', $data);
    $this->load->view('templates/footer');
}

public function input_jadwal_shift_tabel() {
    $this->load->model('Jadwal_model');
    $this->load->model('Pegawai_model');

    $bulan = $this->input->get('bulan') ?? date('Y-m'); // Default bulan ini
    $tanggal_awal = date('Y-m-01', strtotime($bulan));
    $tanggal_akhir = date('Y-m-t', strtotime($bulan));

    // Ambil data jadwal shift dari database
    $data['jadwal_shift'] = $this->Jadwal_model->get_jadwal_shift_bulanan($tanggal_awal, $tanggal_akhir);

    // Ambil semua pegawai kecuali admin, pastikan sorted
    $data['pegawai'] = $this->Pegawai_model->get_all_pegawai_except_admin();

    $data['title'] = 'Input Jadwal Shift Pegawai';
    $data['bulan'] = $bulan;
$divisi_totals = [];
foreach ($data['jadwal_shift'] as $jadwal) {
    $tanggal = $jadwal->tanggal;
    $divisi = $jadwal->nama_divisi;

    if (!isset($divisi_totals[$divisi][$tanggal])) {
        $divisi_totals[$divisi][$tanggal] = 0;
    }

    // Tambahkan jika ada shift
    if (!empty($jadwal->kode_shift)) {
        $divisi_totals[$divisi][$tanggal]++;
    }
}
$data['divisi_totals'] = $divisi_totals;
    $this->load->view('templates/header', $data);
    $this->load->view('jadwal_shift/input_jadwal_shift_tabel', $data);
    $this->load->view('templates/footer');
}



public function simpan_jadwal_shift_ajax() {
    $this->load->model('Jadwal_model');
    $pegawai_id = $this->input->post('pegawai_id');
    $tanggal = $this->input->post('tanggal');
    $shift_id = $this->input->post('shift_id');

    $data = [
        'pegawai_id' => $pegawai_id,
        'tanggal' => $tanggal,
        'shift_id' => $shift_id
    ];

    // Simpan atau update data
    $this->Jadwal_model->insert_or_update_jadwal($data);

    echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil disimpan']);
}
public function get_shift_by_divisi() {
    $pegawai_id = $this->input->get('pegawai_id');

    // Ambil divisi pegawai
    $pegawai = $this->db->get_where('pegawai', ['id' => $pegawai_id])->row();
    if (!$pegawai) {
        echo json_encode([]); return;
    }

    // Ambil shift sesuai divisi pegawai
    $shifts = $this->db->get_where('shift', ['divisi_id' => $pegawai->divisi_id])->result();
    echo json_encode($shifts);
}

public function update_jadwal_shift() {
    $data = json_decode(file_get_contents('php://input'), true);
    $pegawai_id = $data['pegawai_id'];
    $tanggal = $data['tanggal'];
    $shift_id = $data['shift_id'];

    $this->load->model('Jadwal_model');
    $this->Jadwal_model->update_jadwal_shift($pegawai_id, $tanggal, $shift_id);

    echo json_encode(['success' => true]);
}
public function detail($pegawai_id) {
    $this->load->model('Jadwal_model');
    $this->load->model('Pegawai_model');

    $data['title'] = 'Detail Jadwal Shift Pegawai';
    $data['bulan'] = $this->input->get('bulan') ?? date('Y-m');
    $tanggal_awal = date('Y-m-01', strtotime($data['bulan']));
    $tanggal_akhir = date('Y-m-t', strtotime($data['bulan']));

    // Ambil data pegawai
    $data['pegawai'] = $this->Pegawai_model->get_pegawai_by_id($pegawai_id);

    // Ambil data jadwal shift yang sudah difilter berdasarkan pegawai_id
    $data['jadwal_shift'] = $this->Jadwal_model->get_jadwal_shift_bulanan_detail($tanggal_awal, $tanggal_akhir, $pegawai_id);

    // Periksa apakah data pegawai valid
    if (!$data['pegawai']) {
        show_error('Pegawai tidak ditemukan.', 404);
    }

    $this->load->view('templates/header', $data);
    $this->load->view('jadwal_shift/detail', $data);
    $this->load->view('templates/footer');
}




}
