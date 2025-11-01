<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Form Filter Bulan dan Tombol Input Tambahan -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Filter Bulan -->
        <form method="get" class="form-inline">
            <label for="bulan" class="mr-2">Filter Bulan:</label>
            <select name="bulan" id="bulan" class="form-control mr-2">
                <?php
                $current_month = date('Y-m');
                for ($i = 0; $i < 12; $i++):
                    $month = date('Y-m', strtotime("-$i month"));
                    $selected = ($bulan == $month) ? 'selected' : '';
                ?>
                    <option value="<?= $month ?>" <?= $selected ?>><?= date('F Y', strtotime($month)) ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Tombol Input Tambahan -->
        <a href="<?= site_url('tambahan_lain/input') ?>" class="btn btn-success">Input Tambahan</a>
    </div>

    <!-- Tabel Rekapitulasi Tambahan -->
    <table class="table table-bordered">
        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Tambahan</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rekap_tambahan)): ?>
                <?php foreach ($rekap_tambahan as $index => $row): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($row->nama) ?></td>
                        <td class="text-right" style="white-space: nowrap;">
                            Rp <?= number_format($row->total_tambahan, 2, ',', '.') ?>
                        </td>
                        <td style="white-space: nowrap; text-align: center;" class="action-buttons">
                            <a href="<?= site_url('tambahan_lain/detail/'.$row->id) ?>" class="btn btn-warning btn-sm">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data tambahan lain untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
