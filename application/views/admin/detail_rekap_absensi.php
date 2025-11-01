<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- <div class="mb-3">
        <a href="<?= site_url('admin/absen_pegawai') ?>" class="btn btn-success">Tambah Absensi Manual</a>
    </div> -->

    <!-- Form Filter Bulan -->
    <form method="get" class="mb-3">
        <label for="bulan">Filter Bulan:</label>
        <select id="bulan" name="bulan" class="form-control" onchange="this.form.submit()">
            <option value="">-- Pilih Bulan --</option>
            <?php foreach ($bulan_dropdown as $row): ?>
                <option value="<?= $row->bulan ?>" <?= ($row->bulan == $bulan_terpilih) ? 'selected' : '' ?>>
                    <?= date('F Y', strtotime($row->bulan . '-01')) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Tabel Data Rekap Harian -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th>Shift</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Terlambat</th>
                <th>Pulang Cepat</th>
                <th>Lama Kerja</th>
                <th>Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rekap_harian)): ?>
                <?php foreach ($rekap_harian as $row): ?>
                    <tr>
                        <td><?= $row->tanggal ?></td>
                        <td><?= $row->nama_pegawai ?></td>
                        <td><?= $row->kode_shift ?></td>
                        <td><?= $row->jam_masuk ?></td>
                        <td><?= $row->jam_pulang ?></td>
                        <td><?= $row->terlambat ?> menit</td>
                        <td><?= $row->pulang_cepat ?> menit</td>
                        <td><?= $row->lama_menit_kerja ?> menit</td>
                        <td>Rp <?= number_format($row->total_gaji ?? 0, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">Data tidak ditemukan untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
