<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan -->
    <form method="get" action="<?= site_url('potongan/index') ?>" class="form-inline mb-3">
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
        <a href="<?= site_url('potongan/input') ?>" class="btn btn-success ml-2">Tambah Potongan</a>
    </form>

    <!-- Tabel Rekapitulasi Potongan -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Potongan</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rekap_potongan)): ?>
                <?php foreach ($rekap_potongan as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $row->nama ?></td>
                        <td>Rp <?= number_format($row->total_potongan, 2, ',', '.') ?></td>
                        <td>
                            <a href="<?= site_url('potongan/detail/' . $row->id . '?bulan=' . $bulan) ?>" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data potongan untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
