<style>
    .text-right {
        text-align: right; /* Untuk rata kanan */
    }
    .font-weight-bold {
        font-weight: bold; /* Untuk teks tebal */
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

    <!-- Form Filter Bulan dan Tahun -->
    <form method="get" class="mb-3">
        <div class="form-row">
            <div class="col-md-6">
                <label for="bulan">Filter Bulan:</label>
                <select id="bulan" name="bulan" class="form-control">
                    <option value="">-- Pilih Bulan --</option>
                    <?php foreach ($bulan_dropdown as $row_bulan): ?>
                        <option value="<?= $row_bulan ?>" <?= ($row_bulan == $bulan_terpilih) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $row_bulan, 1)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="tahun">Filter Tahun:</label>
                <select id="tahun" name="tahun" class="form-control">
                    <option value="">-- Pilih Tahun --</option>
                    <?php foreach ($tahun_dropdown as $row_tahun): ?>
                        <option value="<?= $row_tahun->tahun ?>" <?= ($row_tahun->tahun == $tahun_terpilih) ? 'selected' : '' ?>>
                            <?= $row_tahun->tahun ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Filter</button>
    </form>

    <!-- Tabel Rekap Bulanan -->
         <div class="table-responsive">

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
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
                <?php $no = 1; foreach ($rekap_bulanan as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row->nama ?? '-') ?></td>
                        <td><?= $row->total_terlambat ?? 0 ?></td>
                        <td><?= $row->total_pulang_cepat ?? 0 ?></td>
                        <td><?= $row->total_lama_kerja ?? 0 ?></td>
                        <td class="nowrap text-right">Rp <?= number_format(isset($row->total_gaji) ? $row->total_gaji : 0, 0, ',', '.') ?></td>
                        <td><a href="<?= site_url('admin/detail_rekap_absensi/' . $row->pegawai_id) ?>" class="btn btn-info btn-sm">Detail</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Data tidak ditemukan untuk bulan dan tahun ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</div>
