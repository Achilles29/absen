<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uang_makan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Periksa apakah pengguna sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        // Periksa role, hanya admin dan spv yang bisa mengakses
        $allowed_roles = ['admin', 'spv'];
        if (!in_array($this->session->userdata('role'), $allowed_roles)) {
            redirect('auth/login');
        }
    }



    public function index()
    {
        $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-d');
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');

        $this->db->select('
        r.tanggal,
        p.id AS pegawai_id,
        p.nama AS nama_pegawai,
        p.uang_makan,
        s.kode_shift
    ');
        $this->db->from('abs_rekap_absensi r');
        $this->db->join('abs_pegawai p', 'r.pegawai_id = p.id', 'left');
        $this->db->join('abs_shift s', 'r.shift_id = s.id', 'left');
        $this->db->where('r.jam_masuk IS NOT NULL', null, false);
        $this->db->where('s.kode_shift !=', 'PH');
        $this->db->where('p.uang_makan >', 0);
        $this->db->where('r.tanggal >=', $tanggal_awal);
        $this->db->where('r.tanggal <=', $tanggal_akhir);
        $this->db->order_by('r.tanggal', 'ASC');
        $this->db->order_by('p.nama', 'ASC');
        $data['hasil'] = $this->db->get()->result();

        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['total_uang_makan'] = 0;

        foreach ($data['hasil'] as $row) {
            $data['total_uang_makan'] += $row->uang_makan;
        }

        $data['title'] = 'Uang Makan';
        $this->load->view('templates/header', $data);
        $this->load->view('uang_makan/index', $data);
        $this->load->view('templates/footer');
    }


    public function uang_makan_bulanan()
    {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Tentukan jumlah hari dalam bulan
        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        // Ambil data absensi valid
        $this->db->select('
        r.pegawai_id,
        p.nama AS nama_pegawai,
        p.uang_makan,
        r.tanggal
    ');
        $this->db->from('abs_rekap_absensi r');
        $this->db->join('abs_pegawai p', 'r.pegawai_id = p.id', 'left');
        $this->db->join('abs_shift s', 'r.shift_id = s.id', 'left');
        $this->db->where('r.jam_masuk IS NOT NULL', null, false);
        $this->db->where('s.kode_shift !=', 'PH');
        $this->db->where('p.uang_makan >', 0);
        $this->db->where('MONTH(r.tanggal)', $bulan);
        $this->db->where('YEAR(r.tanggal)', $tahun);
        $this->db->order_by('p.nama', 'ASC');
        $absensi = $this->db->get()->result();

        // Olah data menjadi array [pegawai][tanggal] = uang_makan
        $data_rekap = [];
        foreach ($absensi as $row) {
            $tanggal = (int) date('j', strtotime($row->tanggal));
            $data_rekap[$row->pegawai_id]['nama'] = $row->nama_pegawai;
            $data_rekap[$row->pegawai_id]['uang_makan'] = $row->uang_makan;
            $data_rekap[$row->pegawai_id]['harian'][$tanggal] = $row->uang_makan;
        }

        // Hitung total per pegawai
        foreach ($data_rekap as &$pegawai) {
            $pegawai['total'] = array_sum($pegawai['harian'] ?? []);
        }

        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['jumlah_hari'] = $jumlah_hari;
        $data['rekap'] = $data_rekap;

        $data['title'] = 'Uang Makan Bulanan';
        $this->load->view('templates/header', $data);
        $this->load->view('uang_makan/uang_makan_bulanan', $data);
        $this->load->view('templates/footer');
    }

    public function rekap()
    {
        $bulan = (int)($this->input->get('bulan') ?: date('m'));
        $tahun = (int)($this->input->get('tahun') ?: date('Y'));

        // ---------- 1) Bangun rentang minggu2 (Minggu–Sabtu), boleh lintas bulan ----------
        $firstOfMonth = sprintf('%04d-%02d-01', $tahun, $bulan);
        // cari Minggu (0) terdekat ke belakang dari tanggal 1
        $start = date('w', strtotime($firstOfMonth)) == 0
            ? $firstOfMonth
            : date('Y-m-d', strtotime('last sunday', strtotime($firstOfMonth)));

        // Sabtu terakhir yang masih mencakup akhir bulan ini
        $lastOfMonth = date('Y-m-t', strtotime($firstOfMonth));
        $end = date('w', strtotime($lastOfMonth)) == 6
            ? $lastOfMonth
            : date('Y-m-d', strtotime('next saturday', strtotime($lastOfMonth)));

        // potong2 per minggu
        $minggu_ke = [];
        $cursor = $start;
        while ($cursor <= $end) {
            $mulai = $cursor;                                  // Sunday
            $selesai = date('Y-m-d', strtotime($mulai . ' +6 day')); // Saturday
            $minggu_ke[] = ['mulai' => $mulai, 'selesai' => $selesai];
            $cursor = date('Y-m-d', strtotime($selesai . ' +1 day'));
        }

        // ---------- 2) Ambil semua pegawai aktif (unik, tidak Nonaktif) ----------
        $pegawai_all = $this->db->select('id, nama, uang_makan')
            ->from('abs_pegawai')
            ->where('kode_user !=', 'Nonaktif')
            ->order_by('nama', 'ASC')
            ->get()->result();

        // Siapkan struktur rekap dasar (satu kali; kunci = pegawai_id => unik)
        $rekap = [];
        foreach ($pegawai_all as $peg) {
            $rekap[$peg->id] = [
                'nama'   => $peg->nama,
                'uang'   => (float)$peg->uang_makan,
                'minggu' => [],     // nanti diisi
                'total'  => 0,
            ];
        }

        // ---------- 3) Ambil absensi valid untuk rentang Minggu pertama s/d Sabtu terakhir ----------
        // (lintas bulan tetap ikut)
        $absensi = $this->db->select('r.pegawai_id, r.tanggal, r.jam_masuk, s.kode_shift')
            ->from('abs_rekap_absensi r')
            ->join('abs_shift s', 'r.shift_id = s.id', 'left')
            ->where('r.jam_masuk IS NOT NULL', null, false)
            ->where('s.kode_shift !=', 'PH')
            ->where('r.tanggal >=', $start)
            ->where('r.tanggal <=', $end)
            ->order_by('r.tanggal', 'ASC')
            ->get()->result();

        // ---------- 4) Akumulasi per minggu dengan anti-dobel (per pegawai per tanggal) ----------
        $seen = []; // $seen[pegawai_id][tanggal] = true  → 1 hari dihitung sekali
        foreach ($absensi as $row) {
            $pid = (int)$row->pegawai_id;
            $tgl = $row->tanggal;

            // kalau pegawai tidak ada di daftar aktif, lewati (mis. Nonaktif)
            if (!isset($rekap[$pid])) continue;

            // anti double count per tanggal
            if (isset($seen[$pid][$tgl])) continue;
            $seen[$pid][$tgl] = true;

            // tentukan minggu ke berapa
            foreach ($minggu_ke as $i => $m) {
                if ($tgl >= $m['mulai'] && $tgl <= $m['selesai']) {
                    $rekap[$pid]['minggu'][$i + 1] = ($rekap[$pid]['minggu'][$i + 1] ?? 0) + $rekap[$pid]['uang'];
                    break;
                }
            }
        }

        // ---------- 5) Hitung total per pegawai ----------
        foreach ($rekap as &$p) {
            $p['total'] = array_sum($p['minggu'] ?? []);
        }
        unset($p);

        // ---------- 6) Hitung total per minggu & grand total ----------
        $total_per_minggu = array_fill(1, count($minggu_ke), 0);
        $grand_total = 0;
        foreach ($rekap as $p) {
            foreach ($minggu_ke as $i => $_) {
                $total_per_minggu[$i + 1] += $p['minggu'][$i + 1] ?? 0;
            }
            $grand_total += $p['total'];
        }

        $data = [
            'title'            => 'Uang Makan Mingguan',
            'bulan'            => $bulan,
            'tahun'            => $tahun,
            'minggu_ke'        => $minggu_ke,
            'rekap'            => $rekap,
            'total_per_minggu' => $total_per_minggu,
            'grand_total'      => $grand_total,
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('uang_makan/rekap', $data);
        $this->load->view('templates/footer');
    }

    public function range()
    {
        $tanggal_awal = $this->input->get('awal') ?: date('Y-m-01');
        $tanggal_akhir = $this->input->get('akhir') ?: date('Y-m-t');

        // 1️⃣ Ambil semua pegawai aktif
        $pegawai_all = $this->db->select('id, nama, uang_makan')
            ->from('abs_pegawai')
            ->where('kode_user !=', 'Nonaktif')
            ->order_by('nama', 'ASC')
            ->get()->result();

        // 2️⃣ Ambil absensi valid dalam rentang
        $this->db->select('r.pegawai_id, r.tanggal');
        $this->db->from('abs_rekap_absensi r');
        $this->db->join('abs_shift s', 'r.shift_id = s.id', 'left');
        $this->db->where('r.jam_masuk IS NOT NULL', null, false);
        $this->db->where('s.kode_shift !=', 'PH');
        $this->db->where('r.tanggal >=', $tanggal_awal);
        $this->db->where('r.tanggal <=', $tanggal_akhir);
        $absensi_raw = $this->db->get()->result();

        // 3️⃣ Dedup per pegawai_id + tanggal
        $absensi = [];
        foreach ($absensi_raw as $row) {
            $key = $row->pegawai_id . '_' . $row->tanggal;
            if (!isset($absensi[$key])) $absensi[$key] = $row;
        }

        // 4️⃣ Buat daftar tanggal di range
        $periode = [];
        $cursor = $tanggal_awal;
        while ($cursor <= $tanggal_akhir) {
            $periode[] = $cursor;
            $cursor = date('Y-m-d', strtotime($cursor . ' +1 day'));
        }

        // 5️⃣ Inisialisasi data pegawai
        $rekap = [];
        foreach ($pegawai_all as $p) {
            $rekap[$p->id] = [
                'nama' => trim($p->nama),
                'uang_makan' => (float)$p->uang_makan,
                'tanggal' => array_fill_keys($periode, 0),
                'total' => 0
            ];
        }

        // 6️⃣ Isi data uang makan per hari
        foreach ($absensi as $row) {
            $pid = $row->pegawai_id;
            if (!isset($rekap[$pid])) continue;
            if (isset($rekap[$pid]['tanggal'][$row->tanggal])) {
                $rekap[$pid]['tanggal'][$row->tanggal] = $rekap[$pid]['uang_makan'];
            }
        }

        // 7️⃣ Hitung total per pegawai
        foreach ($rekap as &$r) {
            $r['total'] = array_sum($r['tanggal']);
        }
        unset($r);

        // 8️⃣ Gabungkan pegawai dengan nama sama (fix dobel)
        $rekap_gabung = [];
        foreach ($rekap as $r) {
            $nama = strtoupper(trim($r['nama']));
            if (!isset($rekap_gabung[$nama])) {
                $rekap_gabung[$nama] = [
                    'nama' => $nama,
                    'tanggal' => $r['tanggal'],
                    'total' => $r['total']
                ];
            } else {
                foreach ($r['tanggal'] as $tgl => $nilai) {
                    $rekap_gabung[$nama]['tanggal'][$tgl] += $nilai;
                }
                $rekap_gabung[$nama]['total'] += $r['total'];
            }
        }

        // 9️⃣ Hitung total per tanggal & grand total
        $total_per_tanggal = array_fill_keys($periode, 0);
        $grand_total = 0;
        foreach ($rekap_gabung as $r) {
            foreach ($periode as $t) {
                $total_per_tanggal[$t] += $r['tanggal'][$t] ?? 0;
            }
            $grand_total += $r['total'];
        }

        // 🔟 Kirim data ke view
        $data = [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'periode' => $periode,
            'rekap' => $rekap_gabung,
            'total_per_tanggal' => $total_per_tanggal,
            'grand_total' => $grand_total,
            'title' => 'Rekap Uang Makan - Rentang Tanggal'
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('uang_makan/range', $data);
        $this->load->view('templates/footer');
    }
}
