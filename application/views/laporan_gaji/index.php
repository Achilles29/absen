<?php 
    $total_kehadiran = 0;
    $total_lama_kerja = 0;
    $total_gaji = 0;
    $total_gaji_lembur = 0;
    $total_tambahan_lain = 0;
    $total_potongan = 0;
    $total_deposit = 0;
    $total_kasbon = 0;
    $grand_total_penerimaan = 0;
?>

<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan -->
    <form method="get" action="<?= site_url('admin/laporan_gaji') ?>" class="form-inline mb-3">
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

    <!-- Tabel Rekapitulasi Gaji -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Kehadiran</th>
                <th>Total Lama Kerja (menit)</th>
                <th>Total Gaji</th>
                <th>Total Gaji Lembur</th>
                <th>Tambahan Lain</th>
                <th>Potongan</th>
                <th>Deposit</th>
                <th>Bayar Kasbon</th>
                <th>Total Penerimaan</th>
                <th>Detail</th>
            </tr>
        </thead>
<tbody>
    <?php foreach ($laporan_gaji as $index => $row): 
        // Total penerimaan = total gaji + total gaji lembur + tambahan lain (sekali sebulan)
        $total_penerimaan = $row->total_gaji 
                    + $row->total_gaji_lembur 
                    + ($row->tambahan_master + $row->total_tambahan_lain) 
                    - $row->total_potongan 
                    - $row->total_deposit
                    - $row->total_kasbon; 

        // Akumulasi total dari setiap kolom
        $total_kehadiran += $row->total_kehadiran;
        $total_lama_kerja += $row->total_lama_kerja;
        $total_gaji += $row->total_gaji;
        $total_gaji_lembur += $row->total_gaji_lembur;
        $total_tambahan_lain += ($row->tambahan_master + $row->total_tambahan_lain);
        $total_potongan += $row->total_potongan;
        $total_deposit += $row->total_deposit;
        $total_kasbon += $row->total_kasbon;
        $grand_total_penerimaan += $total_penerimaan;

?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td style="white-space: nowrap; text-align: left;">
                <?= $row->nama ?>
            </td>
            <td style="text-align: center;"><?= $row->total_kehadiran ?></td>
            <td style="text-align: center;"><?= $row->total_lama_kerja ?> </td>
            <td style="white-space: nowrap; text-align: right;">
                Rp <?= number_format($row->total_gaji, 2, ',', '.') ?>
            </td>
            <td style="white-space: nowrap; text-align: right;">
                Rp <?= number_format($row->total_gaji_lembur, 2, ',', '.') ?>
            </td>
            <td style="white-space: nowrap; text-align: right;">
                Rp <?= number_format($row->tambahan_master + $row->total_tambahan_lain, 2, ',', '.') ?>
            </td>
            <td style="white-space: nowrap; text-align: right;">
                Rp <?= number_format($row->total_potongan, 2, ',', '.') ?>
            </td>
            <td style="white-space: nowrap; text-align: right;">
                Rp <?= number_format($row->total_deposit, 2, ',', '.') ?>
            </td>
            <td style="white-space: nowrap; text-align: right;">
                Rp <?= number_format($row->total_kasbon, 2, ',', '.') ?>
            </td>
            <td style="white-space: nowrap; text-align: right; font-weight: bold;">
                Rp <?= number_format($total_penerimaan, 2, ',', '.') ?>
            </td>
            <td>
                <a href="<?= site_url('admin/detail_laporan_gaji/' . $row->id . '?bulan=' . $bulan) ?>" 
                   class="btn btn-info btn-sm">Detail</a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
<!-- Baris Total -->
<tfoot>
    <tr style="font-weight: bold;">
        <td colspan="2" style="text-align: center;">Total</td>
        <td style="text-align: center;"><?= $total_kehadiran ?></td>
        <td style="text-align: center;"><?= $total_lama_kerja ?></td>
        <td style="text-align: right;"><?= number_format($total_gaji, 2, ',', '.') ?></td>
        <td style="text-align: right;"><?= number_format($total_gaji_lembur, 2, ',', '.') ?></td>
        <td style="text-align: right;"><?= number_format($total_tambahan_lain, 2, ',', '.') ?></td>
        <td style="text-align: right;"><?= number_format($total_potongan, 2, ',', '.') ?></td>
        <td style="text-align: right;"><?= number_format($total_deposit, 2, ',', '.') ?></td>
        <td style="text-align: right;"><?= number_format($total_kasbon, 2, ',', '.') ?></td>
        <td style="text-align: right;"><?= number_format($grand_total_penerimaan, 2, ',', '.') ?></td>
        <td></td> <!-- Kolom kosong untuk aksi -->
    </tr>
</tfoot>
    </table>
</div>
