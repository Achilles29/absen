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
    $this->load->view('jadwal_shift/index', $data);
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

    public function import() {
        $data['title'] = 'Import Jadwal Shift';

        if ($this->input->post() && !empty($_FILES['csv_file']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = 2048; // 2MB
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('csv_file')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $file_data = $this->upload->data();
                $file_path = $file_data['full_path'];

                // Baca file CSV
                $csv_data = array_map('str_getcsv', file($file_path));

                // Validasi header
                $header = array_shift($csv_data);
                if ($header !== ['ID Pegawai', 'Tanggal', 'ID Shift']) {
                    $this->session->set_flashdata('error', 'Format CSV tidak sesuai.');
                    unlink($file_path);
                    redirect('jadwal_shift/import');
                }

                $import_data = [];
                foreach ($csv_data as $row) {
                    $import_data[] = [
                        'pegawai_id' => $row[0], // Kolom 1: ID Pegawai
                        'tanggal' => $row[1],    // Kolom 2: Tanggal
                        'shift_id' => $row[2],   // Kolom 3: ID Shift
                    ];
                }

                // Masukkan data ke database
                if (!empty($import_data)) {
                    $this->Jadwal_model->insert_jadwal_shift_batch($import_data);
                    $this->session->set_flashdata('success', 'Data berhasil diimport!');
                } else {
                    $this->session->set_flashdata('error', 'File CSV kosong atau tidak sesuai format.');
                }

                unlink($file_path); // Hapus file setelah diproses
                redirect('jadwal_shift/import');
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('jadwal_shift/import', $data);
        $this->load->view('templates/footer');
    }

    public function download_template() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="template_jadwal_shift.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID Pegawai', 'Tanggal', 'ID Shift']);
        fclose($output);
    }

public function jadwal_tabel() {
    $bulan = $this->input->get('bulan') ?? date('m'); // Default ke bulan ini
    $tahun = $this->input->get('tahun') ?? date('Y'); // Default ke tahun ini
    $tanggal_awal = date("$tahun-$bulan-01");
    $tanggal_akhir = date("$tahun-$bulan-t");

    $this->load->model('Pegawai_model');
    $this->load->model('Jadwal_model');

    // Data Pegawai
    $pegawai = $this->Pegawai_model->get_all_pegawai_except_admin();
    $data['pegawai'] = $pegawai;

    // Data Jadwal Shift
    $jadwal_shift_raw = $this->Jadwal_model->get_jadwal_shift_bulanan($tanggal_awal, $tanggal_akhir);
    $jadwal_shift = [];
    foreach ($jadwal_shift_raw as $jadwal) {
        $jadwal_shift[$jadwal->pegawai_id][$jadwal->tanggal] = $jadwal->kode_shift;
    }
    $data['jadwal_shift'] = $jadwal_shift;

    $data['title'] = 'Input Jadwal Shift';
    $data['bulan'] = $bulan;
    $data['selected_year'] = $tahun; // Pastikan variabel ini didefinisikan
    $data['selected_month'] = $bulan; // Tambahkan jika perlu di view

    $this->load->view('templates/header', $data);
    $this->load->view('jadwal_shift/jadwal_tabel', $data);
    $this->load->view('templates/footer');
}



public function update_jadwal_inline() {
    // Baca payload JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Log data mentah dari payload
    log_message('debug', 'Raw Input: ' . file_get_contents('php://input'));

    // Log data setelah decode
    log_message('debug', 'Decoded Input: ' . json_encode($data));

    $pegawai_id = $data['pegawai_id'] ?? null;
    $tanggal = $data['tanggal'] ?? null;
    $kode_shift = $data['kode_shift'] ?? null;

    // Log validasi data
    log_message('debug', "Pegawai ID: $pegawai_id, Tanggal: $tanggal, Kode Shift: $kode_shift");

    if (empty($pegawai_id) || empty($tanggal) || empty($kode_shift)) {
        log_message('error', 'Input data tidak lengkap.');
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        return;
    }

    // Cari shift_id berdasarkan kode_shift
    $this->db->select('id');
    $this->db->from('shift');
    $this->db->where('kode_shift', $kode_shift);
    $shift = $this->db->get()->row();

    // Tambahkan logging
    log_message('debug', 'Query mencari shift_id: ' . $this->db->last_query());
    log_message('debug', 'Hasil query: ' . json_encode($shift));

    if (!$shift) {
        echo json_encode(['status' => 'error', 'message' => 'Kode shift tidak valid.']);
        return;
    }

    $shift_id = $shift->id;

    // Siapkan data untuk disimpan
    $data = [
        'pegawai_id' => $pegawai_id,
        'tanggal' => $tanggal,
        'shift_id' => $shift_id,
    ];

    // Tambahkan logging sebelum menyimpan
    log_message('debug', 'Data yang akan disimpan: ' . json_encode($data));

    // Simpan data
    $this->load->model('Jadwal_model');
    $this->Jadwal_model->insert_or_update_jadwal($data);

    echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil diperbarui.']);
}

public function delete_jadwal_shift() {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid.']);
        return;
    }

    $this->load->model('Jadwal_model');
    $deleted = $this->Jadwal_model->delete_jadwal_shift($id);

    if ($deleted) {
        echo json_encode(['status' => 'success', 'message' => 'Jadwal shift berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus jadwal shift.']);
    }
}
public function delete_jadwal_shift2() {
    $data = json_decode(file_get_contents('php://input'), true);
    $pegawai_id = $data['pegawai_id'] ?? null;
    $tanggal = $data['tanggal'] ?? null;

    if (!$pegawai_id || !$tanggal) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        return;
    }

    // Hapus data berdasarkan pegawai_id dan tanggal
    $this->db->where('pegawai_id', $pegawai_id);
    $this->db->where('tanggal', $tanggal);
    $this->db->delete('jadwal_shift');

    if ($this->db->affected_rows() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Shift berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Shift tidak ditemukan atau gagal dihapus.']);
    }
}
public function rekap_jadwal()
{
    $selected_month = $this->input->get('month') ?: date('m');
    $selected_year = $this->input->get('year') ?: date('Y');
    $selected_period = $selected_year . '-' . $selected_month;

    log_message('debug', 'Selected Period: ' . $selected_period);

    $data['jadwal'] = $this->Jadwal_model->get_rekap_jadwal($selected_period);
    $data['selected_month'] = $selected_month;
    $data['selected_year'] = $selected_year;
    $data['page_title'] = 'Rekap Jadwal Pegawai';

    $this->load->view('templates/header', $data);
    $this->load->view('jadwal_shift/rekap_jadwal', $data);
    $this->load->view('templates/footer');
}



}
