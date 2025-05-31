
<?php 
    $total_gaji = 0;
    $total_gaji_lembur = 0;
    $total_tambahan_lain = 0;
    $total_potongan = 0;
    $total_deposit = 0;
    $total_kasbon_bayar = 0;
    $grand_total_penerimaan = 0;
?>

<div class="container mt-4">
    <h2><?= $title ?></h2>
    <p><strong>Nama Pegawai:</strong> <?= $pegawai->nama ?></p>
    <p><strong>Bulan:</strong> <?= date('F Y', strtotime($bulan)) ?></p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Lama Kerja (menit)</th>
                <th>Total Gaji</th>
                <th>Gaji Lembur</th>
                <th>Tambahan</th>
                <th>Potongan</th>
                <th>Deposit</th>
                <th>Bayar Kasbon</th>
                <th>Total Penerimaan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detail_gaji as $row): 
                // Hapus tambahan lain dari total penerimaan
                $total_penerimaan = $row->total_gaji + $row->total_gaji_lembur + $row->tambahan_lain - $row->potongan - $row->deposit - $row->kasbon_bayar;
        // Akumulasi total
                $total_gaji += $row->total_gaji;
                $total_gaji_lembur += $row->total_gaji_lembur;
                $total_tambahan_lain += $row->tambahan_lain;
                $total_potongan += $row->potongan;
                $total_deposit += $row->deposit;
                $total_kasbon_bayar += $row->kasbon_bayar;
                $grand_total_penerimaan += $total_penerimaan;
                    ?>
                <tr>
                    <td><?= $row->tanggal ?></td>
                    <td><?= $row->shift ?? '-' ?></td>
                    <td><?= $row->jam_masuk ?></td>
                    <td><?= $row->jam_pulang ?></td>
                    <td><?= $row->lama_menit_kerja ?></td>
                    <td style="white-space: nowrap; text-align: right;">
                        Rp <?= number_format($row->total_gaji, 2, ',', '.') ?>
                    </td>
                    <td style="white-space: nowrap; text-align: right;">
                        Rp <?= number_format($row->total_gaji_lembur, 2, ',', '.') ?>
                    </td>
                    <td style="white-space: nowrap; text-align: right;">
                        Rp <?= number_format($row->tambahan_lain, 2, ',', '.') ?>
                    </td>
                    <td style="white-space: nowrap; text-align: right;">
                        Rp <?= number_format($row->potongan, 2, ',', '.') ?>
                    </td>
                    <td style="white-space: nowrap; text-align: right;">
                        Rp <?= number_format($row->deposit, 2, ',', '.') ?>
                    </td>
                    <td style="white-space: nowrap; text-align: right;">
                        Rp <?= number_format($row->kasbon_bayar, 2, ',', '.') ?>
                    </td>

                    <td style="white-space: nowrap; text-align: right; font-weight: bold;">
                        Rp <?= number_format($total_penerimaan, 2, ',', '.') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <!-- Baris Total -->
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="5" style="text-align: center;">Total</td>
                <td style="text-align: right;"><?= number_format($total_gaji, 2, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($total_gaji_lembur, 2, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($total_tambahan_lain, 2, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($total_potongan, 2, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($total_deposit, 2, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($total_kasbon_bayar, 2, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($grand_total_penerimaan, 2, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
</div>
