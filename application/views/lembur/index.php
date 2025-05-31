<div class="container mt-4">
    <h2><?= $title ?></h2>
    <p>Silakan pilih menu lembur di bawah ini:</p>

    <div class="row">
        <!-- Master Lembur -->
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-header">
                    <strong>Master Lembur</strong>
                </div>
                <div class="card-body">
                    <p>Atur nilai lembur per jam.</p>
                    <a href="<?= site_url('lembur/master') ?>" class="btn btn-primary">Input Master Lembur</a>
                </div>
            </div>
        </div>

        <!-- Input Lembur -->
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-header">
                    <strong>Input Lembur</strong>
                </div>
                <div class="card-body">
                    <p>Input lembur pegawai dengan alasan.</p>
                    <a href="<?= site_url('lembur/input') ?>" class="btn btn-success">Input Lembur</a>
                </div>
            </div>
        </div>

        <!-- Laporan Lembur -->
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-header">
                    <strong>Laporan Lembur</strong>
                </div>
                <div class="card-body">
                    <p>Lihat rekapitulasi lembur pegawai.</p>
                    <a href="<?= site_url('lembur/laporan') ?>" class="btn btn-info">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">

    <!-- Form Filter Bulan -->
    <form method="get" action="<?= site_url('lembur') ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Filter Bulan:</label>
        <select name="bulan" id="bulan" class="form-control mr-2">
            <?php
            $currentYear = date('Y');
            $currentMonth = date('m');
            for ($i = 0; $i < 12; $i++) {
                $month = date('Y-m', strtotime("-$i month", strtotime("$currentYear-$currentMonth-01")));
                $selected = ($bulan == $month) ? 'selected' : '';
                echo "<option value='$month' $selected>" . date('F Y', strtotime($month . '-01')) . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Terapkan</button>
    </form>

    <!-- Tabel Rekapitulasi Lembur -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Lembur (Jam)</th>
                <th>Total Uang Lembur</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_lembur_jam = 0;
            $total_uang_lembur = 0;

            if (!empty($rekap_lembur)): 
                foreach ($rekap_lembur as $index => $row): 
                    $total_lembur_jam += $row->total_lembur;
                    $total_uang_lembur += $row->total_uang_lembur;
            ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $row->nama_pegawai ?></td>
                        <td class="text-right"><?= number_format($row->total_lembur, 2) ?> Jam</td>
                        <td class="text-right">Rp <?= number_format($row->total_uang_lembur, 2, ',', '.') ?></td>
                        <td class="text-center">
                            <a href="<?= site_url('lembur/detail/' . $row->id . '?pegawai_id=' . $row->id . '&bulan=' . date('m', strtotime($bulan . '-01')) . '&tahun=' . date('Y', strtotime($bulan . '-01'))) ?>" 
                            class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data lembur untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center">Jumlah</th>
                <th class="text-right"><?= number_format($total_lembur_jam, 2) ?> Jam</th>
                <th class="text-right">Rp <?= number_format($total_uang_lembur, 2, ',', '.') ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
