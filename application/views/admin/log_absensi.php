<style>
.d-flex {
    display: flex; /* Gunakan flexbox */
    justify-content: center; /* Posisikan tombol di tengah */
    gap: 10px; /* Jarak antar tombol */
}

.btn {
    white-space: nowrap; /* Hindari pemotongan teks di dalam tombol */
}
/* .table td, .table th {
    white-space: nowrap; /* Mencegah pemotongan teks */
    text-align: center; /* Pusatkan teks */
} */

</style>

<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan dan Tahun -->
    <form method="get" action="<?= site_url('admin/log_absensi') ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Filter Bulan:</label>
        <select name="bulan" id="bulan" class="form-control mr-2">
            <?php for ($i = 1; $i <= 12; $i++): 
                $month = str_pad($i, 2, '0', STR_PAD_LEFT);
                $selected = ($bulan == $month) ? 'selected' : '';
            ?>
                <option value="<?= $month ?>" <?= $selected ?>><?= date('F', mktime(0, 0, 0, $i, 1)) ?></option>
            <?php endfor; ?>
        </select>

        <label for="tahun" class="mr-2">Filter Tahun:</label>
        <select name="tahun" id="tahun" class="form-control mr-2">
            <?php for ($y = date('Y') - 5; $y <= date('Y') + 1; $y++): 
                $selected = ($tahun == $y) ? 'selected' : '';
            ?>
                <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel Rekap Log Absensi -->
    <div class="table-responsive">

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Kehadiran</th>
                <th>Total Terlambat (menit)</th>
                <th>Total Pulang Cepat (menit)</th>
                <th>Total Lama Kerja (menit)</th>
                <th>Total Gaji</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rekap_absensi as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($row->nama) ?></td>
                    <td><?= $row->total_absensi ?></td>
                    <td><?= $row->total_terlambat ?> menit</td>
                    <td><?= $row->total_pulang_cepat ?> menit</td>
                    <td><?= $row->total_lama_kerja ?> menit</td>
                    <td style="white-space: nowrap; text-align: center;">
                        Rp <?= number_format($row->total_gaji, 2, ',', '.') ?>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="<?= site_url('admin/detail_log_absensi/' . $row->id . '?bulan=' . $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT)) ?>" 
                            class="btn btn-info btn-sm mr-1">Detail Rekap</a>
                            <a href="<?= site_url('admin/log_absensi_detail/' . $row->id . '?bulan=' . $bulan . '&tahun=' . $tahun) ?>" 
                            class="btn btn-secondary btn-sm">Detail Rincian</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
