<style>
    .table-container {
        margin-left: -100px;
        width: 100%;
        overflow-x: auto;
        min-width: 1200px; /* Panjang minimum tabel */
    }

    .table {
        font-size: 0.9rem;
        border-collapse: collapse;
    }
    .table td, .table th {
        white-space: nowrap;
        text-align: center;
        padding: 0.5rem;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .text-right {
        text-align: right;
    }
</style>


<?php 
    // Inisialisasi total untuk footer
    $total_kehadiran = 0;
    $total_lama_kerja = 0;
    $total_gaji = 0;
    $total_tunjangan = 0;
    $total_gaji_lembur = 0;
    $total_tambahan_lain = 0;
    $total_potongan = 0;
    $total_deposit = 0;
    $total_kasbon = 0;
    $grand_total_penerimaan = 0;
?>

<div class="alert alert-info">
    Menampilkan data gaji dari <strong><?= date('d M Y', strtotime($start_date)) ?></strong> 
    hingga <strong><?= date('d M Y', strtotime($end_date)) ?></strong>.
</div>

<div class="container mt-4">
    <h2 class="text-center mb-4">Laporan Gaji Pegawai</h2>

    <!-- Filter Tanggal -->
    <form method="get" action="<?= site_url('admin/laporan_gaji') ?>" class="form-inline mb-4 justify-content-center">
        <label for="start_date" class="mr-2">Tanggal Awal:</label>
        <input type="date" name="start_date" id="start_date" class="form-control mr-2" 
               value="<?= $this->input->get('start_date') ?? date('Y-m-01') ?>" required>

        <label for="end_date" class="mr-2">Tanggal Akhir:</label>
        <input type="date" name="end_date" id="end_date" class="form-control mr-2" 
               value="<?= $this->input->get('end_date') ?? date('Y-m-t') ?>" required>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel Rekapitulasi Gaji -->
<div class="table-container">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Kehadiran</th>
                <th>Total menit</th>
                <th>Gaji Pokok</th>
                <th>Tunjangan</th>
                <th>Total Lembur</th>
                <th>Tambahan Lain</th>
                <th>Potongan</th>
                <th>Deposit</th>
                <th>Bayar Kasbon</th>
                <th>Total Penerimaan</th>
                <th>Pembulatan Penerimaan</th>
                <th style="width: 150px;">Detail</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        // Inisialisasi total pembulatan
        $grand_total_pembulatan = 0;

        foreach ($laporan_gaji as $index => $row): 
            // Perhitungan total penerimaan
//            $total_penerimaan = $row->total_gaji + $row->tunjangan + $row->total_gaji_lembur + ($row->tambahan_master + $row->total_tambahan_lain) - $row->total_potongan - $row->total_deposit - $row->total_kasbon;
            $total_penerimaan = 
                $row->total_gaji
                + $row->tunjangan
                + $row->total_gaji_lembur
                + ($row->tambahan_master + $row->total_tambahan_lain)
                - $row->total_potongan
                + $row->total_deposit  // sekarang deposit bisa bernilai negatif/positif
                - $row->total_kasbon;

            // Pembulatan penerimaan ke ribuan terdekat ke atas
            $pembulatan_penerimaan = ceil($total_penerimaan / 1000) * 1000;

            // Akumulasi total pembulatan
            $grand_total_pembulatan += $pembulatan_penerimaan;

            // Akumulasi total untuk footer
            $total_kehadiran += $row->total_kehadiran;
            $total_lama_kerja += $row->total_lama_kerja;
            $total_gaji += $row->total_gaji;
            $total_tunjangan += $row->tunjangan;
            $total_gaji_lembur += $row->total_gaji_lembur;
            $total_tambahan_lain += ($row->tambahan_master + $row->total_tambahan_lain);
            $total_potongan += $row->total_potongan;
            $total_deposit += $row->total_deposit;
            $total_kasbon += $row->total_kasbon;
            $grand_total_penerimaan += $total_penerimaan;
        ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td style="text-align: left;"> <?= $row->nama ?> </td>
                <td><?= $row->total_kehadiran ?></td>
                <td><?= $row->total_lama_kerja ?></td>
                <td class="text-right">Rp <?= number_format($row->total_gaji, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row->tunjangan, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row->total_gaji_lembur, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row->tambahan_master + $row->total_tambahan_lain, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row->total_potongan, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row->total_deposit, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row->total_kasbon, 2, ',', '.') ?></td>
                <td class="text-right font-weight-bold">Rp <?= number_format($total_penerimaan, 2, ',', '.') ?></td>
                <td class="text-right font-weight-bold">Rp <?= number_format($pembulatan_penerimaan, 2, ',', '.') ?></td>
                <td>
                    <a href="<?= site_url('admin/detail_laporan_gaji/' . $row->id . '?start_date=' . $start_date . '&end_date=' . $end_date) ?>" 
                       class="btn btn-info btn-sm">Detail</a>
                    <a href="<?= site_url('admin/cetak_slip_gaji/' . $row->id . '?start_date=' . $start_date . '&end_date=' . $end_date) ?>" 
                       class="btn btn-primary btn-sm" target="_blank">Slip Gaji</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="3" style="text-align: center;">Total</td>
                <td><?= $total_kehadiran ?></td>
                <td class="text-right">Rp <?= number_format($total_gaji, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($total_tunjangan, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($total_gaji_lembur, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($total_tambahan_lain, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($total_potongan, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($total_deposit, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($total_kasbon, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($grand_total_penerimaan, 2, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($grand_total_pembulatan, 2, ',', '.') ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</div>

<a href="<?= site_url('admin/generate_laporan_gaji?start_date=' . $start_date . '&end_date=' . $end_date) ?>" class="btn btn-success">Generate Arsip Gaji</a>

