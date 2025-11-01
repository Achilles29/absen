<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lembur extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('Lembur_model');
        $this->load->model('Pegawai_model');
    }
public function index() {
    $data['title'] = 'Lembur Pegawai';

    // Ambil filter bulan (default bulan ini)
    $bulan = $this->input->get('bulan') ?? date('Y-m');

    // Rekapitulasi lembur per pegawai
    $this->db->select('pegawai.id, pegawai.nama AS nama_pegawai, SUM(lembur.lama_lembur) AS total_lembur, SUM(lembur.lama_lembur * nilai_lembur.nilai_per_jam) AS total_uang_lembur');
    $this->db->from('lembur');
    $this->db->join('pegawai', 'lembur.pegawai_id = pegawai.id', 'left');
    $this->db->join('nilai_lembur', '1=1', 'left');
    $this->db->where("DATE_FORMAT(lembur.tanggal, '%Y-%m') =", $bulan);
    $this->db->group_by('pegawai.id');
    $this->db->order_by('pegawai.nama', 'ASC');
    $data['rekap_lembur'] = $this->db->get()->result();

    // Kirim data bulan ke view
    $data['bulan'] = $bulan;

    $this->load->view('templates/header', $data);
    $this->load->view('lembur/index', $data);
    $this->load->view('templates/footer');
}


    // Master Lembur - Input Nilai Lembur
public function master() {
    $data['title'] = 'Master Nilai Lembur';

    // Ambil nilai lembur
    $data['nilai_lembur'] = $this->Lembur_model->get_nilai_lembur();

    // Jika form disubmit
    if ($this->input->post()) {
        $nilai = $this->input->post('nilai_per_jam');

        $update_data = ['nilai_per_jam' => $nilai];

        // Update nilai lembur
        if ($this->Lembur_model->update_nilai_lembur($update_data)) {
            $this->session->set_flashdata('success', 'Nilai lembur berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui nilai lembur.');
        }

        redirect('lembur/master');
    }

    $this->load->view('templates/header', $data);
    $this->load->view('lembur/master', $data);
    $this->load->view('templates/footer');
}


    // Input Lembur Pegawai
    public function input() {
        if ($this->input->post()) {
            $data = [
                'pegawai_id' => $this->input->post('pegawai_id'),
                'tanggal' => $this->input->post('tanggal'),
                'lama_lembur' => $this->input->post('lama_lembur'),
                'alasan' => $this->input->post('alasan')
            ];
            $this->Lembur_model->insert_lembur($data);
            $this->session->set_flashdata('success', 'Data lembur berhasil ditambahkan.');
            redirect('lembur/input');
        }
        $data['title'] = 'Input Lembur Pegawai';
        $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();
        $this->load->view('templates/header', $data);
        $this->load->view('lembur/input', $data);
        $this->load->view('templates/footer');
    }

    // Laporan Lembur
public function laporan() {
    $bulan = $this->input->get('bulan') ?? date('Y-m');

    $this->db->select('lembur.*, pegawai.nama AS nama_pegawai');
    $this->db->from('lembur');
    $this->db->join('pegawai', 'lembur.pegawai_id = pegawai.id', 'left');
    $this->db->where("DATE_FORMAT(lembur.tanggal, '%Y-%m') =", $bulan);
    $this->db->order_by('lembur.tanggal', 'ASC');
    $data['laporan_lembur'] = $this->db->get()->result();

    $data['title'] = 'Laporan Lembur';
    $data['bulan'] = $bulan;

    $this->load->view('templates/header', $data);
    $this->load->view('lembur/laporan', $data);
    $this->load->view('templates/footer');
}



public function add() {
    // Data input form
    $data = [
        'tanggal' => $this->input->post('tanggal'),
        'pegawai_id' => $this->input->post('pegawai_id'),
        'lama_lembur' => $this->input->post('lama_lembur'), // Lama lembur dalam jam
        'alasan' => $this->input->post('alasan')
    ];

    // Simpan data lembur
    if ($this->Lembur_model->insert_lembur($data)) {
        $this->session->set_flashdata('success', 'Data lembur berhasil disimpan.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menyimpan data lembur.');
    }

    redirect('lembur/input');
}

public function detail($pegawai_id) {
    // Ambil filter bulan dan tahun, default ke bulan dan tahun sekarang
    $bulan = $this->input->get('bulan') ?? date('m');
    $tahun = $this->input->get('tahun') ?? date('Y');

    // Ambil data lembur berdasarkan pegawai, bulan, dan tahun
    $this->db->select('lembur.*, pegawai.nama AS nama_pegawai');
    $this->db->from('lembur');
    $this->db->join('pegawai', 'lembur.pegawai_id = pegawai.id', 'left');
    $this->db->where('lembur.pegawai_id', $pegawai_id);
    $this->db->where('MONTH(lembur.tanggal)', $bulan);
    $this->db->where('YEAR(lembur.tanggal)', $tahun);
    $this->db->order_by('lembur.tanggal', 'ASC');
    $data['detail_lembur'] = $this->db->get()->result();

    // Data tambahan
    $data['title'] = 'Detail Lembur Pegawai';
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['pegawai_id'] = $pegawai_id;

    $this->load->view('templates/header', $data);
    $this->load->view('lembur/detail', $data);
    $this->load->view('templates/footer');
}


public function edit_lembur() {
    $id = $this->input->post('id');
    $lama_lembur = $this->input->post('lama_lembur');
    $alasan = $this->input->post('alasan');

    // Perbarui data lembur
    $this->db->where('id', $id);
    $this->db->update('lembur', [
        'lama_lembur' => $lama_lembur,
        'alasan' => $alasan,
        'total_gaji_lembur' => ceil($lama_lembur) * $this->Lembur_model->get_nilai_lembur()->nilai_per_jam
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Lembur berhasil diperbarui!']);
}

public function delete_lembur() {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;

    if ($id) {
        $this->db->where('id', $id);
        if ($this->db->delete('lembur')) {
            echo json_encode(['status' => 'success', 'message' => 'Data lembur berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data lembur.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID lembur tidak ditemukan.']);
    }
}

public function update() {
    // Ambil nilai lembur per jam dari model
    $nilai_lembur = $this->Lembur_model->get_nilai_lembur();

    // Data yang diinput oleh user
    $data = [
        'id' => $this->input->post('id'),
        'tanggal' => $this->input->post('tanggal'),
        'lama_lembur' => $this->input->post('lama_lembur'),
        'alasan' => $this->input->post('alasan'),
        'total_gaji_lembur' => $this->input->post('lama_lembur') * $nilai_lembur->nilai_per_jam
    ];

    // Update data lembur
    $this->db->where('id', $data['id']);
    if ($this->db->update('lembur', $data)) {
        $this->session->set_flashdata('success', 'Data lembur berhasil diperbarui.');
    } else {
        $this->session->set_flashdata('error', 'Gagal memperbarui data lembur.');
    }
    redirect('lembur');
}

}
