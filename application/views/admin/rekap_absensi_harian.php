<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Form Filter Tanggal -->
    <form method="get" class="mb-3">
        <label for="tanggal">Filter Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= $tanggal ?>" onchange="this.form.submit()">
    </form>

    <!-- Tabel Rekap Absen Harian -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="white-space: nowrap;">No</th>
                    <th style="white-space: nowrap;">Nama Pegawai</th>
                    <th style="white-space: nowrap;">Nama Shift</th>
                    <th style="white-space: nowrap;">Jam Masuk</th>
                    <th style="white-space: nowrap;">Jam Pulang</th>
                    <th style="white-space: nowrap;">Terlambat</th>
                    <th style="white-space: nowrap;">Pulang Cepat</th>
                    <th style="white-space: nowrap;">Total Menit</th>
                    <th style="white-space: nowrap;">Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rekap_harian)): ?>
                    <?php $no = 1; foreach ($rekap_harian as $rekap): ?>
                        <tr>
                            <td style="white-space: nowrap;"><?= $no++ ?></td>
                            <td style="white-space: nowrap;"><?= htmlspecialchars($rekap->nama) ?></td>
                            <td style="white-space: nowrap;"><?= htmlspecialchars($rekap->nama_shift ?? '-') ?></td>
                            <td style="white-space: nowrap;"><?= $rekap->jam_masuk ? date('d-m-Y H:i:s', strtotime($rekap->jam_masuk)) : '-' ?></td>
                            <td style="white-space: nowrap;"><?= $rekap->jam_pulang ? date('d-m-Y H:i:s', strtotime($rekap->jam_pulang)) : '-' ?></td>
                            <td style="white-space: nowrap;"><?= $rekap->terlambat ?: '0' ?></td>
                            <td style="white-space: nowrap;"><?= $rekap->pulang_cepat ?: '0' ?></td>
                            <td style="white-space: nowrap;"><?= $rekap->lama_menit_kerja ?: '0' ?></td>
                            <td style="white-space: nowrap;">Rp <?= number_format($rekap->total_gaji ?? 0, 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data absensi untuk tanggal ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
