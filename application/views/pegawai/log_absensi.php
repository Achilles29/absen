<style>
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Untuk pengalaman scroll yang lebih halus di perangkat mobile */
}
.nowrap {
    white-space: nowrap;
}
table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    background-color: #f8f9fa;
    text-align: center;
    font-weight: bold;
}

</style>
<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Informasi Nama Pegawai -->
    <p><strong>Nama Pegawai:</strong> <?= isset($pegawai->nama) ? $pegawai->nama : 'Data tidak ditemukan' ?></p>

    <!-- Filter Bulan -->
    <form method="get" action="" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Filter Bulan:</label>
        <select name="bulan" id="bulan" class="form-control mr-2">
            <?php for ($i = 0; $i < 12; $i++): 
                $month = date('Y-m', strtotime("-$i month"));
                $selected = ($bulan == $month) ? 'selected' : '';
            ?>
                <option value="<?= $month ?>" <?= $selected ?>><?= date('F Y', strtotime($month)) ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel Log Absensi -->
    <div class="table-responsive">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="nowrap">Tanggal</th>
                <th class="nowrap">Jam Masuk</th>
                <th class="nowrap">Jam Pulang</th>
                <th class="nowrap">Terlambat (menit)</th>
                <th class="nowrap">Pulang Cepat (menit)</th>
                <th class="nowrap">Lama Kerja (menit)</th>
                <th class="nowrap">Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($log_absensi)): ?>
                <?php foreach ($log_absensi as $log): ?>
                    <tr>
                        <td class="nowrap text-center"><?= $log->tanggal ?></td>
                        <td class="nowrap text-center"><?= $log->jam_masuk ?></td>
                        <td class="nowrap text-center"><?= $log->jam_pulang ?></td>
                        <td class="nowrap text-right"><?= $log->terlambat ?></td>
                        <td class="nowrap text-right"><?= $log->pulang_cepat ?></td>
                        <td class="nowrap text-right"><?= $log->lama_menit_kerja ?></td>
                        <td class="nowrap text-right">Rp <?= number_format($log->total_gaji, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data absensi untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center font-weight-bold">Jumlah</td>
                <td class="nowrap text-right font-weight-bold"><?= number_format($total_kehadiran, 0, ',', '.') ?> Hari</td>
                <td class="nowrap text-right font-weight-bold"><?= number_format($total_terlambat, 0, ',', '.') ?> Menit</td>
                <td class="nowrap text-right font-weight-bold"><?= number_format($total_pulang_cepat, 0, ',', '.') ?> Menit</td>
                <td class="nowrap text-right font-weight-bold"><?= number_format($total_lama_kerja, 0, ',', '.') ?> Menit</td>
                <td class="nowrap text-right font-weight-bold">Rp <?= number_format($total_gaji, 2, ',', '.') ?></td>
            </tr>
        </tfoot>

    </table>
</div>
</div>
