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
    <p><strong>Nama Pegawai:</strong> <?= $pegawai->nama ?></p>
    <p><strong>Bulan:</strong> <?= date('F Y', strtotime($bulan)) ?></p>

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

    <?php if (!empty($detail_gaji)): ?>
        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                      <th class="nowrap">Tanggal</th>
                        <th class="nowrap">Shift</th>
                        <th class="nowrap">Jam Masuk</th>
                        <th class="nowrap">Jam Pulang</th>
                        <th class="nowrap">Lama Kerja (menit)</th>
                        <th class="nowrap">Total Gaji</th>
                        <th class="nowrap">Gaji Lembur</th>
                        <th class="nowrap">Tambahan</th>
                        <th class="nowrap">Potongan</th>
                        <th class="nowrap">Deposit</th>
                        <th class="nowrap">Bayar Kasbon</th>
                        <th class="nowrap">Total Penerimaan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_gaji = 0;
                    $total_gaji_lembur = 0;
                    $total_tambahan_lain = 0;
                    $total_potongan = 0;
                    $total_deposit = 0;
                    $total_kasbon_bayar = 0;
                    $grand_total_penerimaan = 0;

                    foreach ($detail_gaji as $row): 
                        $total_penerimaan = $row->total_gaji + $row->total_gaji_lembur + $row->tambahan_lain - $row->potongan - $row->deposit - $row->kasbon_bayar;

                        $total_gaji += $row->total_gaji;
                        $total_gaji_lembur += $row->total_gaji_lembur;
                        $total_tambahan_lain += $row->tambahan_lain;
                        $total_potongan += $row->potongan;
                        $total_deposit += $row->deposit;
                        $total_kasbon_bayar += $row->kasbon_bayar;
                        $grand_total_penerimaan += $total_penerimaan;
                    ?>
                        <tr>
                            <td class="nowrap"><?= $row->tanggal ?></td>
                            <td class="nowrap"><?= $row->shift ?? '-' ?></td>
                            <td class="nowrap"><?= $row->jam_masuk ?></td>
                            <td class="nowrap"><?= $row->jam_pulang ?></td>
                            <td class="nowrap"><?= $row->lama_menit_kerja ?></td>
                            <td class="nowrap" style="text-align: right;">Rp <?= number_format($row->total_gaji, 2, ',', '.') ?></td>
                            <td class="nowrap" style="text-align: right;">Rp <?= number_format($row->total_gaji_lembur, 2, ',', '.') ?></td>
                            <td class="nowrap" style="text-align: right;">Rp <?= number_format($row->tambahan_lain, 2, ',', '.') ?></td>
                            <td class="nowrap" style="text-align: right;">Rp <?= number_format($row->potongan, 2, ',', '.') ?></td>
                            <td class="nowrap" style="text-align: right;">Rp <?= number_format($row->deposit, 2, ',', '.') ?></td>
                            <td class="nowrap" style="text-align: right;">Rp <?= number_format($row->kasbon_bayar, 2, ',', '.') ?></td>
                            <td class="nowrap" style="text-align: right; font-weight: bold;">Rp <?= number_format($total_penerimaan, 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td colspan="5" style="text-align: center;">Total</td>
                        <td class="nowrap" style="text-align: right;">Rp <?= number_format($total_gaji, 2, ',', '.') ?></td>
                        <td class="nowrap" style="text-align: right;">Rp <?= number_format($total_gaji_lembur, 2, ',', '.') ?></td>
                        <td class="nowrap" style="text-align: right;">Rp <?= number_format($total_tambahan_lain, 2, ',', '.') ?></td>
                        <td class="nowrap" style="text-align: right;">Rp <?= number_format($total_potongan, 2, ',', '.') ?></td>
                        <td class="nowrap" style="text-align: right;">Rp <?= number_format($total_deposit, 2, ',', '.') ?></td>
                        <td class="nowrap" style="text-align: right;">Rp <?= number_format($total_kasbon_bayar, 2, ',', '.') ?></td>
                        <td class="nowrap" style="text-align: right;">Rp <?= number_format($grand_total_penerimaan, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <p>Tidak ada data gaji untuk bulan ini.</p>
    <?php endif; ?>
</div>
