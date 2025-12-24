<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $allowed_roles = ['pegawai', 'spv', 'hod']; // Daftar role yang diizinkan
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login'); // Redirect jika tidak memiliki izin
        }
    }
    // public function absen() {
    //     // Ambil data user berdasarkan sesi login
    //     $role = $this->session->userdata('role');
    //     $pegawai = $this->db->get_where('abs_pegawai', ['id' => $this->session->id])->row();

    //     if (!$pegawai) {
    //         $this->session->set_flashdata('error', 'Data pegawai tidak ditemukan.');
    //         redirect('pegawai/index');
    //     }

    //     $data['title'] = 'Absen Pegawai';
    //     $data['lokasi'] = $this->db->get_where('abs_lokasi_absensi', ['status' => 1])->result();

    //     // Ambil shift pegawai berdasarkan jadwal_shift untuk hari ini
    //     $tanggal_hari_ini = date('Y-m-d');
    //     $this->load->model('Jadwal_model');
    //     $jadwal_shift = $this->Jadwal_model->get_shift_hari_ini($pegawai->id, $tanggal_hari_ini);

    //     $data['shift'] = $jadwal_shift; // Shift untuk hari ini

    //     // Jika form dikirim
    //     if ($this->input->post()) {
    //         $latitude = $this->input->post('latitude');
    //         $longitude = $this->input->post('longitude');
    //         $foto = $this->upload_foto();
    //         $jenis_absen = $this->input->post('jenis_absen');
    //         $lokasi_id = $this->input->post('lokasi_id');

    //         if (!$jadwal_shift) {
    //             $this->session->set_flashdata('error', 'Shift Anda untuk hari ini belum diatur.');
    //             redirect('pegawai/absen');
    //         }

    //         // Validasi lokasi dan jarak
    //         $lokasi = $this->db->get_where('abs_lokasi_absensi', ['id' => $lokasi_id])->row();
    //         if ($lokasi) {
    //             $jarak = $this->haversine($latitude, $longitude, $lokasi->latitude, $lokasi->longitude);

    //             if ($jarak <= $lokasi->range) {
    //                 // Simpan data absensi
    //                 $data_absen = [
    //                     'pegawai_id' => $pegawai->id,
    //                     'shift_id' => $jadwal_shift->shift_id,
    //                     'jenis_absen' => $jenis_absen,
    //                     'tanggal' => $tanggal_hari_ini,
    //                     'waktu' => date('H:i:s'),
    //                     'latitude' => $latitude,
    //                     'longitude' => $longitude,
    //                     'lokasi_id' => $lokasi_id,
    //                     'foto' => $foto
    //                 ];
    //                 $this->db->insert('abs_absensi', $data_absen);

    //                 // Update rekap absensi
    //                 $this->update_rekap_absensi($pegawai->id, $jadwal_shift->shift_id, $tanggal_hari_ini);

    //                 $this->session->set_flashdata('success', 'Absen berhasil dilakukan!');
    //             } else {
    //                 $this->session->set_flashdata('error', 'Anda berada di luar area absen! Jarak: ' . round($jarak, 2) . ' meter.');
    //             }
    //         } else {
    //             $this->session->set_flashdata('error', 'Lokasi absensi tidak valid.');
    //         }
    //         redirect('pegawai/absen');
    //     }

    //     // Tampilkan halaman absen
    //     $this->load->view('templates/header', $data);
    //     $this->load->view('pegawai/absen', $data);
    //     $this->load->view('templates/footer');
    // }



    public function absen()
    {
        $role = $this->session->userdata('role');
        $pegawai = $this->db->get_where('abs_pegawai', ['id' => $this->session->id])->row();

        if (!$pegawai) {
            $this->session->set_flashdata('error', 'Data pegawai tidak ditemukan.');
            redirect('pegawai/index');
        }

        $data['title'] = 'Absen Pegawai';
        $data['lokasi'] = $this->db->get_where('abs_lokasi_absensi', ['status' => 1])->result();

        $tanggal_hari_ini = date('Y-m-d');
        $this->load->model('Jadwal_model');
        $jadwal_shift = $this->Jadwal_model->get_shift_hari_ini($pegawai->id, $tanggal_hari_ini);

        $data['shift'] = $jadwal_shift;

        if ($this->input->post()) {
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            $foto = $this->upload_foto();
            $jenis_absen = $this->input->post('jenis_absen');
            $lokasi_id = $this->input->post('lokasi_id');

            if (!$jadwal_shift) {
                $this->session->set_flashdata('error', 'Shift Anda untuk hari ini belum diatur.');
                redirect('pegawai/absen');
            }

            // Validasi waktu absensi hanya untuk "masuk"
            if ($jenis_absen == 'masuk') {
                $waktu_shift_mulai = strtotime($jadwal_shift->jam_mulai); // contoh: 07:30:00
                $waktu_sekarang = strtotime(date('H:i:s'));

                // Maksimal 30 menit sebelum jam shift
                $batas_awal_absen = $waktu_shift_mulai - (30 * 60); // 30 menit sebelum jam mulai

                if ($waktu_sekarang < $batas_awal_absen) {
                    $this->session->set_flashdata(
                        'error',
                        'Absen masuk baru bisa dilakukan maksimal 30 menit sebelum jam shift dimulai (' . $jadwal_shift->jam_mulai . ').'
                    );
                    redirect('pegawai/absen');
                }
            }

            // Validasi lokasi dan jarak
            $lokasi = $this->db->get_where('abs_lokasi_absensi', ['id' => $lokasi_id])->row();
            if ($lokasi) {
                $jarak = $this->haversine($latitude, $longitude, $lokasi->latitude, $lokasi->longitude);

                if ($jarak <= $lokasi->range) {
                    // Simpan data absensi
                    $data_absen = [
                        'pegawai_id' => $pegawai->id,
                        'shift_id' => $jadwal_shift->shift_id,
                        'jenis_absen' => $jenis_absen,
                        'tanggal' => $tanggal_hari_ini,
                        'waktu' => date('H:i:s'),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'lokasi_id' => $lokasi_id,
                        'foto' => $foto
                    ];
                    $this->db->insert('abs_absensi', $data_absen);

                    // Update rekap absensi
                    $this->update_rekap_absensi($pegawai->id, $jadwal_shift->shift_id, $tanggal_hari_ini);

                    $this->session->set_flashdata('success', 'Absen berhasil dilakukan!');
                } else {
                    $this->session->set_flashdata('error', 'Anda berada di luar area absen! Jarak: ' . round($jarak, 2) . ' meter.');
                }
            } else {
                $this->session->set_flashdata('error', 'Lokasi absensi tidak valid.');
            }
            redirect('pegawai/absen');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('pegawai/absen', $data);
        $this->load->view('templates/footer');
    }

    // private function update_rekap_absensi($pegawai_id, $shift_id, $tanggal)
    // {
    //     // Ambil data pegawai untuk gaji pokok
    //     $pegawai = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();
    //     if (!$pegawai) {
    //         return;
    //     }

    //     // Logika khusus untuk jabatan SECURITY
    //     if ($pegawai->jabatan1_id == 10) { // Asumsi jabatan SECURITY adalah 10
    //         $total_gaji = round($pegawai->gaji_pokok / 30, 2); // Gaji tetap per hari
    //     } else {
    //         // Ambil data shift
    //         $shift = $this->db->get_where('abs_shift', ['id' => $shift_id])->row();
    //         if (!$shift) {
    //             return;
    //         }

    //         // Ambil data absensi pegawai untuk tanggal tertentu
    //         $absensi = $this->db->get_where('abs_absensi', [
    //             'pegawai_id' => $pegawai_id,
    //             'tanggal' => $tanggal
    //         ])->result();

    //         $jam_masuk = null;
    //         $jam_pulang = null;

    //         foreach ($absensi as $absen) {
    //             if ($absen->jenis_absen == 'masuk' && (!$jam_masuk || $absen->waktu < $jam_masuk)) {
    //                 $jam_masuk = $absen->waktu;
    //             }
    //             if ($absen->jenis_absen == 'pulang' && (!$jam_pulang || $absen->waktu > $jam_pulang)) {
    //                 $jam_pulang = $absen->waktu;
    //             }
    //         }

    //         $terlambat = 0;
    //         if ($jam_masuk && strtotime($jam_masuk) > strtotime($shift->jam_mulai)) {
    //             $terlambat = (strtotime($jam_masuk) - strtotime($shift->jam_mulai)) / 60;
    //         }

    //         $pulang_cepat = 0;
    //         if ($jam_pulang && strtotime($jam_pulang) < strtotime($shift->jam_selesai)) {
    //             $pulang_cepat = (strtotime($shift->jam_selesai) - strtotime($jam_pulang)) / 60;
    //         }

    //         $lama_menit_kerja = max((9 * 60) - $terlambat - $pulang_cepat, 0);
    //         $gaji_per_menit = ($pegawai->gaji_per_jam ?? 0) / 60;
    //         $total_gaji = $lama_menit_kerja * $gaji_per_menit;
    //     }

    //     // Data untuk abs_rekap_absensi
    //     $rekap_data = [
    //         'tanggal' => $tanggal,
    //         'pegawai_id' => $pegawai_id,
    //         'shift_id' => $shift_id,
    //         'jam_masuk' => $jam_masuk ?? '00:00:00',
    //         'jam_pulang' => $jam_pulang ?? '00:00:00',
    //         'terlambat' => $terlambat ?? 0,
    //         'pulang_cepat' => $pulang_cepat ?? 0,
    //         'lama_menit_kerja' => $lama_menit_kerja ?? 0,
    //         'total_gaji' => $total_gaji
    //     ];

    //     // Periksa apakah data sudah ada
    //     $existing_rekap = $this->db->get_where('abs_rekap_absensi', [
    //         'tanggal' => $tanggal,
    //         'pegawai_id' => $pegawai_id
    //     ])->row();

    //     if ($existing_rekap) {
    //         $this->db->update('abs_rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
    //     } else {
    //         $this->db->insert('abs_rekap_absensi', $rekap_data);
    //     }
    // }

    // private function update_rekap_absensi($pegawai_id, $shift_id, $tanggal)
    // {
    //     $pegawai = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();
    //     if (!$pegawai) return;

    //     $shift = $this->db->get_where('abs_shift', ['id' => $shift_id])->row();
    //     if (!$shift) return;

    //     // Ambil data absensi hari ini
    //     $absensi = $this->db->get_where('abs_absensi', [
    //         'pegawai_id' => $pegawai_id,
    //         'tanggal' => $tanggal
    //     ])->result();

    //     $jam_masuk = null;
    //     $jam_pulang = null;

    //     foreach ($absensi as $a) {
    //         if ($a->jenis_absen == 'masuk' && (!$jam_masuk || $a->waktu < $jam_masuk)) {
    //             $jam_masuk = $a->waktu;
    //         }
    //         if ($a->jenis_absen == 'pulang' && (!$jam_pulang || $a->waktu > $jam_pulang)) {
    //             $jam_pulang = $a->waktu;
    //         }
    //     }

    //     // Cek apakah rekap sudah ada
    //     $existing_rekap = $this->db->get_where('abs_rekap_absensi', [
    //         'tanggal' => $tanggal,
    //         'pegawai_id' => $pegawai_id
    //     ])->row();

    //     // Jika baru absen masuk → simpan jam_masuk saja
    //     if ($jam_masuk && !$jam_pulang) {
    //         $rekap_data = [
    //             'tanggal' => $tanggal,
    //             'pegawai_id' => $pegawai_id,
    //             'shift_id' => $shift_id,
    //             'jam_masuk' => $jam_masuk,
    //             'jam_pulang' => null,
    //             'terlambat' => null,
    //             'pulang_cepat' => null,
    //             'lama_menit_kerja' => null,
    //             'total_gaji' => null
    //         ];

    //         if ($existing_rekap) {
    //             $this->db->update('abs_rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
    //         } else {
    //             $this->db->insert('abs_rekap_absensi', $rekap_data);
    //         }
    //         return;
    //     }

    //     // Jika sudah ada jam pulang → hitung durasi & gaji
    //     if ($jam_masuk && $jam_pulang) {
    //         // Hitung terlambat dan pulang cepat
    //         $terlambat = 0;
    //         if (strtotime($jam_masuk) > strtotime($shift->jam_mulai)) {
    //             $terlambat = (strtotime($jam_masuk) - strtotime($shift->jam_mulai)) / 60;
    //         }

    //         $pulang_cepat = 0;
    //         if (strtotime($jam_pulang) < strtotime($shift->jam_selesai)) {
    //             $pulang_cepat = (strtotime($shift->jam_selesai) - strtotime($jam_pulang)) / 60;
    //         }

    //         // Hitung lama kerja dalam menit
    //         $lama_menit_kerja = max((strtotime($jam_pulang) - strtotime($jam_masuk)) / 60, 0);

    //         // Hitung gaji
    //         $gaji_per_menit = ($pegawai->gaji_per_jam ?? 0) / 60;
    //         $total_gaji = round($lama_menit_kerja * $gaji_per_menit, 2);

    //         $rekap_data = [
    //             'tanggal' => $tanggal,
    //             'pegawai_id' => $pegawai_id,
    //             'shift_id' => $shift_id,
    //             'jam_masuk' => $jam_masuk,
    //             'jam_pulang' => $jam_pulang,
    //             'terlambat' => $terlambat,
    //             'pulang_cepat' => $pulang_cepat,
    //             'lama_menit_kerja' => $lama_menit_kerja,
    //             'total_gaji' => $total_gaji
    //         ];

    //         if ($existing_rekap) {
    //             $this->db->update('abs_rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
    //         } else {
    //             $this->db->insert('abs_rekap_absensi', $rekap_data);
    //         }
    //     }
    // }


    // private function update_rekap_absensi($pegawai_id, $shift_id, $tanggal)
    // {
    //     $pegawai = $this->db->get_where('abs_pegawai', ['id' => $pegawai_id])->row();
    //     if (!$pegawai) return;

    //     $shift = $this->db->get_where('abs_shift', ['id' => $shift_id])->row();
    //     if (!$shift) return;

    //     // Ambil data absensi hari ini
    //     $absensi = $this->db->get_where('abs_absensi', [
    //         'pegawai_id' => $pegawai_id,
    //         'tanggal' => $tanggal
    //     ])->result();

    //     $jam_masuk = null;
    //     $jam_pulang = null;

    //     foreach ($absensi as $a) {
    //         if ($a->jenis_absen == 'masuk' && (!$jam_masuk || $a->waktu < $jam_masuk)) {
    //             $jam_masuk = $a->waktu;
    //         }
    //         if ($a->jenis_absen == 'pulang' && (!$jam_pulang || $a->waktu > $jam_pulang)) {
    //             $jam_pulang = $a->waktu;
    //         }
    //     }

    //     // Cek apakah rekap sudah ada
    //     $existing_rekap = $this->db->get_where('abs_rekap_absensi', [
    //         'tanggal' => $tanggal,
    //         'pegawai_id' => $pegawai_id
    //     ])->row();

    //     // Jika baru absen masuk → simpan jam_masuk saja
    //     if ($jam_masuk && !$jam_pulang) {
    //         $rekap_data = [
    //             'tanggal' => $tanggal,
    //             'pegawai_id' => $pegawai_id,
    //             'shift_id' => $shift_id,
    //             'jam_masuk' => $jam_masuk,
    //             'jam_pulang' => null,
    //             'terlambat' => null,
    //             'pulang_cepat' => null,
    //             'lama_menit_kerja' => null,
    //             'total_gaji' => null
    //         ];

    //         if ($existing_rekap) {
    //             $this->db->update('abs_rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
    //         } else {
    //             $this->db->insert('abs_rekap_absensi', $rekap_data);
    //         }
    //         return;
    //     }

    //     // Jika sudah ada jam pulang → hitung durasi & gaji
    //     if ($jam_masuk && $jam_pulang) {
    //         // Hitung terlambat dan pulang cepat
    //         $terlambat = 0;
    //         if (strtotime($jam_masuk) > strtotime($shift->jam_mulai)) {
    //             $terlambat = (strtotime($jam_masuk) - strtotime($shift->jam_mulai)) / 60;
    //         }

    //         $pulang_cepat = 0;
    //         if (strtotime($jam_pulang) < strtotime($shift->jam_selesai)) {
    //             $pulang_cepat = (strtotime($shift->jam_selesai) - strtotime($jam_pulang)) / 60;
    //         }

    //         // === Hitung durasi shift dalam menit ===
    //         $durasi_shift = max((strtotime($shift->jam_selesai) - strtotime($shift->jam_mulai)) / 60, 0);

    //         // === Hitung lama kerja aktual ===
    //         $lama_menit_kerja = max((strtotime($jam_pulang) - strtotime($jam_masuk)) / 60, 0);

    //         // === Batasi agar tidak melebihi durasi shift ===
    //         if ($lama_menit_kerja > $durasi_shift) {
    //             $lama_menit_kerja = $durasi_shift;
    //         }

    //         // Hitung gaji
    //         $gaji_per_menit = ($pegawai->gaji_per_jam ?? 0) / 60;
    //         $total_gaji = round($lama_menit_kerja * $gaji_per_menit, 2);

    //         $rekap_data = [
    //             'tanggal' => $tanggal,
    //             'pegawai_id' => $pegawai_id,
    //             'shift_id' => $shift_id,
    //             'jam_masuk' => $jam_masuk,
    //             'jam_pulang' => $jam_pulang,
    //             'terlambat' => $terlambat,
    //             'pulang_cepat' => $pulang_cepat,
    //             'lama_menit_kerja' => $lama_menit_kerja,
    //             'total_gaji' => $total_gaji
    //         ];

    //         if ($existing_rekap) {
    //             $this->db->update('abs_rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
    //         } else {
    //             $this->db->insert('abs_rekap_absensi', $rekap_data);
    //         }
    //     }
    // }

    private function update_rekap_absensi($pegawai_id, $shift_id, $tanggal)
    {
        // Ambil data pegawai + jabatan (pakai jabatan1_id)
        $this->db->select('p.*, j.nama_jabatan');
        $this->db->from('abs_pegawai p');
        $this->db->join('abs_jabatan j', 'p.jabatan1_id = j.id', 'left');
        $this->db->where('p.id', $pegawai_id);
        $pegawai = $this->db->get()->row();
        if (!$pegawai) return;

        $shift = $this->db->get_where('abs_shift', ['id' => $shift_id])->row();
        if (!$shift) return;

        // Ambil data absensi hari ini
        $absensi = $this->db->get_where('abs_absensi', [
            'pegawai_id' => $pegawai_id,
            'tanggal' => $tanggal
        ])->result();

        $jam_masuk = null;
        $jam_pulang = null;

        foreach ($absensi as $a) {
            if ($a->jenis_absen == 'masuk' && (!$jam_masuk || $a->waktu < $jam_masuk)) {
                $jam_masuk = $a->waktu;
            }
            if ($a->jenis_absen == 'pulang' && (!$jam_pulang || $a->waktu > $jam_pulang)) {
                $jam_pulang = $a->waktu;
            }
        }

        // Cek apakah rekap sudah ada
        $existing_rekap = $this->db->get_where('abs_rekap_absensi', [
            'tanggal' => $tanggal,
            'pegawai_id' => $pegawai_id
        ])->row();

        // Jika baru absen masuk → simpan jam_masuk saja
        if ($jam_masuk && !$jam_pulang) {
            $rekap_data = [
                'tanggal' => $tanggal,
                'pegawai_id' => $pegawai_id,
                'shift_id' => $shift_id,
                'jam_masuk' => $jam_masuk,
                'jam_pulang' => null,
                'terlambat' => null,
                'pulang_cepat' => null,
                'lama_menit_kerja' => null,
                'total_gaji' => null
            ];

            if ($existing_rekap) {
                $this->db->update('abs_rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
            } else {
                $this->db->insert('abs_rekap_absensi', $rekap_data);
            }
            return;
        }

        // Jika sudah ada jam pulang → hitung durasi & gaji
        if ($jam_masuk && $jam_pulang) {
            // === Hitung dasar shift ===
            $terlambat = 0;
            if (strtotime($jam_masuk) > strtotime($shift->jam_mulai)) {
                $terlambat = (strtotime($jam_masuk) - strtotime($shift->jam_mulai)) / 60;
            }

            $pulang_cepat = 0;
            if (strtotime($jam_pulang) < strtotime($shift->jam_selesai)) {
                $pulang_cepat = (strtotime($shift->jam_selesai) - strtotime($jam_pulang)) / 60;
            }

            $jam_awal_dihitung = max(strtotime($jam_masuk), strtotime($shift->jam_mulai)); // pilih jam mulai shift jika masuk terlalu awal
            $jam_akhir_dihitung = min(strtotime($jam_pulang), strtotime($shift->jam_selesai)); // pilih jam selesai shift jika pulang terlalu cepat

            $lama_menit_kerja = max(($jam_akhir_dihitung - $jam_awal_dihitung) / 60, 0);
            $durasi_shift = max((strtotime($shift->jam_selesai) - strtotime($shift->jam_mulai)) / 60, 0);

            // Batasi agar tidak melebihi shift
            if ($lama_menit_kerja > $durasi_shift) {
                $lama_menit_kerja = $durasi_shift;
            }

            // === PERLAKUAN KHUSUS: SECURITY ===
            $total_gaji = 0;
            if (strtoupper(trim($pegawai->nama_jabatan)) === 'SECURITY') {
                // === SECURITY: gaji per hari (gaji pokok dibagi 30) ===
                if (isset($pegawai->gaji_pokok) && $pegawai->gaji_pokok > 0) {
                    $total_gaji = round($pegawai->gaji_pokok / 30, 2);
                } else {
                    $total_gaji = round((($pegawai->gaji_per_jam ?? 0) * 9), 2); // fallback jika tidak ada gaji pokok
                }

                // Lama kerja = durasi shift standar
                $lama_menit_kerja = $durasi_shift;

                // Security tidak dihitung terlambat/pulang cepat
                $terlambat = 0;
                $pulang_cepat = 0;
             } else {
                // === NORMAL ===
                $gaji_per_menit = ($pegawai->gaji_per_jam ?? 0) / 60;
                $total_gaji = round($lama_menit_kerja * $gaji_per_menit, 2);
            }

            // === Simpan rekap ===
            $rekap_data = [
                'tanggal' => $tanggal,
                'pegawai_id' => $pegawai_id,
                'shift_id' => $shift_id,
                'jam_masuk' => $jam_masuk,
                'jam_pulang' => $jam_pulang,
                'terlambat' => $terlambat,
                'pulang_cepat' => $pulang_cepat,
                'lama_menit_kerja' => $lama_menit_kerja,
                'total_gaji' => $total_gaji
            ];

            if ($existing_rekap) {
                $this->db->update('abs_rekap_absensi', $rekap_data, ['id' => $existing_rekap->id]);
            } else {
                $this->db->insert('abs_rekap_absensi', $rekap_data);
            }
        }
    }


    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earth_radius * $c;
    }

    private function upload_foto()
    {
        if (empty($_FILES['foto']['name'])) {
            return null;
        }

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            return $this->upload->data('file_name');
        } else {
            log_message('error', 'Upload Error: ' . $this->upload->display_errors());
            return null;
        }
    }
}
