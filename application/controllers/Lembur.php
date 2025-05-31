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

    // public function index() {
    //     $data['title'] = 'Lembur Pegawai';

    //     // Ambil filter bulan (default bulan ini)
    //     $bulan = $this->input->get('bulan') ?? date('Y-m');

    //     // Rekapitulasi lembur per pegawai
    //     $this->db->select('abs_pegawai.id, abs_pegawai.nama AS nama_pegawai, SUM(abs_lembur.lama_lembur) AS total_lembur, SUM(abs_lembur.lama_lembur * abs_nilai_lembur.nilai_per_jam) AS total_uang_lembur');
    //     $this->db->from('abs_lembur');
    //     $this->db->join('abs_pegawai', 'abs_lembur.pegawai_id = abs_pegawai.id', 'left');
    //     $this->db->join('abs_nilai_lembur', '1=1', 'left');
    //     $this->db->where("DATE_FORMAT(abs_lembur.tanggal, '%Y-%m') =", $bulan);
    //     $this->db->group_by('abs_pegawai.id');
    //     $this->db->order_by('abs_pegawai.nama', 'ASC');
    //     $data['rekap_lembur'] = $this->db->get()->result();

    //     // Kirim data bulan ke view
    //     $data['bulan'] = $bulan;

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('lembur/index', $data);
    //     $this->load->view('templates/footer');
    // }
public function index() {
    $data['title'] = 'Lembur Pegawai';

    // Ambil filter bulan (default bulan ini)
    $bulan = $this->input->get('bulan') ?? date('Y-m');

    // Query Rekapitulasi Lembur
    $this->db->select('abs_pegawai.id, abs_pegawai.nama AS nama_pegawai, 
        SUM(abs_lembur.lama_lembur) AS total_lembur, 
        SUM(abs_lembur.lama_lembur * abs_nilai_lembur.nilai_per_jam) AS total_uang_lembur');
    $this->db->from('abs_lembur');
    $this->db->join('abs_pegawai', 'abs_lembur.pegawai_id = abs_pegawai.id', 'left');
    $this->db->join('abs_nilai_lembur', 'abs_lembur.nilai_lembur_id = abs_nilai_lembur.id', 'left');
    $this->db->where("DATE_FORMAT(abs_lembur.tanggal, '%Y-%m') =", $bulan);
    $this->db->group_by('abs_pegawai.id');
    $this->db->order_by('abs_pegawai.nama', 'ASC');
    $data['rekap_lembur'] = $this->db->get()->result();

    $data['bulan'] = $bulan;

    $this->load->view('templates/header', $data);
    $this->load->view('lembur/index', $data);
    $this->load->view('templates/footer');
}



    // // Master Lembur - Input Nilai Lembur
    // public function master() {
    //     $data['title'] = 'Master Nilai Lembur';

    //     // Ambil nilai lembur
    //     $data['nilai_lembur'] = $this->Lembur_model->get_nilai_lembur();

    //     // Jika form disubmit
    //     if ($this->input->post()) {
    //         $nilai = $this->input->post('nilai_per_jam');

    //         $update_data = ['nilai_per_jam' => $nilai];

    //         // Update nilai lembur
    //         if ($this->Lembur_model->update_nilai_lembur($update_data)) {
    //             $this->session->set_flashdata('success', 'Nilai lembur berhasil diperbarui.');
    //         } else {
    //             $this->session->set_flashdata('error', 'Gagal memperbarui nilai lembur.');
    //         }

    //         redirect('lembur/master');
    //     }

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('lembur/master', $data);
    //     $this->load->view('templates/footer');
    // }

    // // Input Lembur Pegawai
    // public function input() {
    //     if ($this->input->post()) {
    //         $data = [
    //             'pegawai_id' => $this->input->post('pegawai_id'),
    //             'tanggal' => $this->input->post('tanggal'),
    //             'lama_lembur' => $this->input->post('lama_lembur'),
    //             'alasan' => $this->input->post('alasan')
    //         ];
    //         $this->Lembur_model->insert_lembur($data);
    //         $this->session->set_flashdata('success', 'Data lembur berhasil ditambahkan.');
    //         redirect('lembur/input');
    //     }
    //     $data['title'] = 'Input Lembur Pegawai';
    //     $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();
    //     $this->load->view('templates/header', $data);
    //     $this->load->view('lembur/input', $data);
    //     $this->load->view('templates/footer');
    // }
// Lembur.php

public function master() {
    $data['title'] = 'Master Nilai Lembur';

    // Ambil semua nilai lembur
    $data['nilai_lembur_list'] = $this->Lembur_model->get_all_nilai_lembur();

    // Jika form disubmit
    if ($this->input->post()) {
        $nilai = $this->input->post('nilai_per_jam');

        // Simpan data nilai lembur baru
        $this->db->insert('abs_nilai_lembur', [
            'nilai_per_jam' => $nilai,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Nilai lembur berhasil ditambahkan.');
        redirect('lembur/master');
    }

    $this->load->view('templates/header', $data);
    $this->load->view('lembur/master', $data);
    $this->load->view('templates/footer');
}

public function input() {
    $data['title'] = 'Input Lembur Pegawai';
    $data['pegawai'] = $this->Pegawai_model->get_all_pegawai();
    $data['nilai_lembur_list'] = $this->Lembur_model->get_all_nilai_lembur();

    $this->load->view('templates/header', $data);
    $this->load->view('lembur/input', $data);
    $this->load->view('templates/footer');
}

    // Laporan Lembur
    public function laporan() {
    $bulan = $this->input->get('bulan') ?? date('Y-m');

    // Tambahkan join dengan abs_nilai_lembur untuk mendapatkan nilai_per_jam
    $this->db->select('
        abs_lembur.*, 
        abs_pegawai.nama AS nama_pegawai, 
        abs_nilai_lembur.nilai_per_jam
    ');
    $this->db->from('abs_lembur');
    $this->db->join('abs_pegawai', 'abs_lembur.pegawai_id = abs_pegawai.id', 'left');
    $this->db->join('abs_nilai_lembur', 'abs_lembur.nilai_lembur_id = abs_nilai_lembur.id', 'left'); // Join ke tabel nilai lembur
    $this->db->where("DATE_FORMAT(abs_lembur.tanggal, '%Y-%m') =", $bulan);
    $this->db->order_by('abs_lembur.tanggal', 'ASC');
    $data['laporan_lembur'] = $this->db->get()->result();

    // Pastikan nilai lembur tersedia untuk dropdown di modal
    $data['nilai_lembur_list'] = $this->Lembur_model->get_all_nilai_lembur();

    $data['title'] = 'Laporan Lembur';
    $data['bulan'] = $bulan;

    $this->load->view('templates/header', $data);
    $this->load->view('lembur/laporan', $data);
    $this->load->view('templates/footer');
}


    // public function add() {
    //     // Data input form
    //     $data = [
    //         'tanggal' => $this->input->post('tanggal'),
    //         'pegawai_id' => $this->input->post('pegawai_id'),
    //         'lama_lembur' => $this->input->post('lama_lembur'), // Lama lembur dalam jam
    //         'alasan' => $this->input->post('alasan')
    //     ];

    //     // Simpan data lembur
    //     if ($this->Lembur_model->insert_lembur($data)) {
    //         $this->session->set_flashdata('success', 'Data lembur berhasil disimpan.');
    //     } else {
    //         $this->session->set_flashdata('error', 'Gagal menyimpan data lembur.');
    //     }

    //     redirect('lembur/input');
    // }
public function add() {
    // Data input form
    $data = [
        'tanggal' => $this->input->post('tanggal'),
        'pegawai_id' => $this->input->post('pegawai_id'),
        'lama_lembur' => $this->input->post('lama_lembur'), // Lama lembur dalam jam
        'alasan' => $this->input->post('alasan'),
        'nilai_lembur_id' => $this->input->post('nilai_lembur_id') // Tambahkan nilai_lembur_id
    ];

    // Simpan data lembur
    if ($this->Lembur_model->insert_lembur($data)) {
        $this->session->set_flashdata('success', 'Data lembur berhasil disimpan.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menyimpan data lembur.');
    }

    redirect('lembur/input');
}

    // public function detail($pegawai_id) {
    //     // Ambil filter bulan dan tahun, default ke bulan dan tahun sekarang
    //     $bulan = $this->input->get('bulan') ?? date('m');
    //     $tahun = $this->input->get('tahun') ?? date('Y');

    //     // Ambil data lembur berdasarkan pegawai, bulan, dan tahun
    //     $this->db->select('abs_lembur.*, abs_pegawai.nama AS nama_pegawai');
    //     $this->db->from('abs_lembur');
    //     $this->db->join('abs_pegawai', 'abs_lembur.pegawai_id = abs_pegawai.id', 'left');
    //     $this->db->where('abs_lembur.pegawai_id', $pegawai_id);
    //     $this->db->where('MONTH(abs_lembur.tanggal)', $bulan);
    //     $this->db->where('YEAR(abs_lembur.tanggal)', $tahun);
    //     $this->db->order_by('abs_lembur.tanggal', 'ASC');
    //     $data['detail_lembur'] = $this->db->get()->result();

    //     // Data tambahan
    //     $data['title'] = 'Detail Lembur Pegawai';
    //     $data['bulan'] = $bulan;
    //     $data['tahun'] = $tahun;
    //     $data['pegawai_id'] = $pegawai_id;

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('lembur/detail', $data);
    //     $this->load->view('templates/footer');
    // }
public function detail($pegawai_id = null) {
    if (!$pegawai_id) {
        show_error('Pegawai ID diperlukan.', 400);
    }

    // Ambil filter bulan dan tahun, default ke bulan dan tahun sekarang
    $bulan = $this->input->get('bulan') ?? date('m');
    $tahun = $this->input->get('tahun') ?? date('Y');

    // Ambil data lembur berdasarkan pegawai, bulan, dan tahun
    $this->db->select('abs_lembur.*, abs_pegawai.nama AS nama_pegawai, abs_nilai_lembur.nilai_per_jam');
    $this->db->from('abs_lembur');
    $this->db->join('abs_pegawai', 'abs_lembur.pegawai_id = abs_pegawai.id', 'left');
    $this->db->join('abs_nilai_lembur', 'abs_lembur.nilai_lembur_id = abs_nilai_lembur.id', 'left');
    $this->db->where('abs_lembur.pegawai_id', $pegawai_id);
    $this->db->where('MONTH(abs_lembur.tanggal)', $bulan);
    $this->db->where('YEAR(abs_lembur.tanggal)', $tahun);
    $this->db->order_by('abs_lembur.tanggal', 'ASC');
    $data['detail_lembur'] = $this->db->get()->result();

    // Ambil data nilai lembur untuk dropdown
    $data['nilai_lembur_list'] = $this->Lembur_model->get_all_nilai_lembur();

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
    $this->db->update('abs_lembur', [
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
        if ($this->db->delete('abs_lembur')) {
            echo json_encode(['status' => 'success', 'message' => 'Data lembur berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data lembur.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID lembur tidak ditemukan.']);
    }
}

// public function update() {
//     // Ambil nilai lembur per jam dari model
//     $nilai_lembur = $this->Lembur_model->get_nilai_lembur();

//     // Data yang diinput oleh user
//     $data = [
//         'id' => $this->input->post('id'),
//         'tanggal' => $this->input->post('tanggal'),
//         'lama_lembur' => $this->input->post('lama_lembur'),
//         'alasan' => $this->input->post('alasan'),
//         'total_gaji_lembur' => $this->input->post('lama_lembur') * $nilai_lembur->nilai_per_jam
//     ];

//     // Update data lembur
//     $this->db->where('id', $data['id']);
//     if ($this->db->update('abs_lembur', $data)) {
//         $this->session->set_flashdata('success', 'Data lembur berhasil diperbarui.');
//     } else {
//         $this->session->set_flashdata('error', 'Gagal memperbarui data lembur.');
//     }
//     redirect('lembur');
// }

public function update() {
    $id = $this->input->post('id');
    $lama_lembur = $this->input->post('lama_lembur');
    $nilai_lembur_id = $this->input->post('nilai_lembur_id');
    $pegawai_id = $this->input->post('pegawai_id');
    $bulan = $this->input->post('bulan');
    $tahun = $this->input->post('tahun');

    // Ambil nilai lembur berdasarkan ID
    $nilai_lembur = $this->db->get_where('abs_nilai_lembur', ['id' => $nilai_lembur_id])->row();

    if ($nilai_lembur) {
        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'lama_lembur' => $lama_lembur,
            'alasan' => $this->input->post('alasan'),
            'nilai_lembur_id' => $nilai_lembur_id,
            'total_gaji_lembur' => ceil($lama_lembur) * $nilai_lembur->nilai_per_jam
        ];

        $this->db->where('id', $id);
        if ($this->db->update('abs_lembur', $data)) {
            $this->session->set_flashdata('success', 'Data lembur berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data lembur.');
        }
    } else {
        $this->session->set_flashdata('error', 'Nilai lembur tidak ditemukan.');
    }

    // Redirect dengan parameter lengkap
    redirect('lembur/detail/' . $pegawai_id . '?bulan=' . $bulan . '&tahun=' . $tahun);
}


public function update2() {
    $id = $this->input->post('id');
    $nilai_lembur_id = $this->input->post('nilai_lembur_id');
    $lama_lembur = $this->input->post('lama_lembur');
    $alasan = $this->input->post('alasan');

    // Ambil nilai lembur berdasarkan nilai_lembur_id
    $nilai_lembur = $this->db->get_where('abs_nilai_lembur', ['id' => $nilai_lembur_id])->row();

    if ($nilai_lembur) {
        $total_gaji_lembur = ceil($lama_lembur) * $nilai_lembur->nilai_per_jam;

        // Update data lembur
        $data = [
            'nilai_lembur_id' => $nilai_lembur_id,
            'lama_lembur' => $lama_lembur,
            'alasan' => $alasan,
            'total_gaji_lembur' => $total_gaji_lembur,
        ];
        $this->db->where('id', $id);
        if ($this->db->update('abs_lembur', $data)) {
            $this->session->set_flashdata('success', 'Data lembur berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data lembur.');
        }
    } else {
        $this->session->set_flashdata('error', 'Nilai lembur tidak ditemukan.');
    }

    redirect('lembur/laporan?bulan=' . $this->input->get('bulan'));
}


}
