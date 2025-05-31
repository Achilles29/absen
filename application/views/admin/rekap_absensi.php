<div class="container mt-4">
    <h2><?= $title ?></h2>

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

    <!-- Tabel Rekap Bulanan -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Pegawai</th>
                <th>Total Terlambat (menit)</th>
                <th>Total Pulang Cepat (menit)</th>
                <th>Total Lama Kerja (menit)</th>
                <th>Total Gaji</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rekap_bulanan)): ?>
                <?php foreach ($rekap_bulanan as $row): ?>
                    <tr>
                        <td><?= $row->nama ?></td>
                        <td><?= $row->total_terlambat ?></td>
                        <td><?= $row->total_pulang_cepat ?></td>
                        <td><?= $row->total_lama_kerja ?></td>
                        <td>Rp <?= number_format($row->total_gaji, 2, ',', '.') ?></td>
                        <td><a href="<?= site_url('admin/detail_rekap_absensi/' . $row->pegawai_id) ?>" class="btn btn-info btn-sm">Detail</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Data tidak ditemukan untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
