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
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Terlambat (menit)</th>
                <th>Pulang Cepat (menit)</th>
                <th>Lama Kerja (menit)</th>
                <th>Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($log_absensi)): ?>
                <?php foreach ($log_absensi as $log): ?>
                    <tr>
                        <td><?= $log->tanggal ?></td>
                        <td><?= $log->jam_masuk ?></td>
                        <td><?= $log->jam_pulang ?></td>
                        <td><?= $log->terlambat ?></td>
                        <td><?= $log->pulang_cepat ?></td>
                        <td><?= $log->lama_menit_kerja ?></td>
                        <td>Rp <?= number_format($log->total_gaji, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data absensi untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
