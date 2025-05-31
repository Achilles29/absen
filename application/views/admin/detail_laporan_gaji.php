<style>
    .table-container {
        width: 100%;
        overflow-x: auto;
    }
    .table {
        font-size: 0.9rem;
        border-collapse: collapse;
        width: 100%;
    }
    .table td, .table th {
        white-space: nowrap;
        text-align: center;
        padding: 0.5rem;
    }
    .table th {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
    .text-right {
        text-align: right;
    }
</style>

<div class="container mt-4">
    <h2 class="text-center mb-4">Detail Laporan Gaji</h2>
    <p><strong>Nama Pegawai:</strong> <?= $pegawai->nama ?></p>
    <p><strong>Range Tanggal:</strong> <?= date('d M Y', strtotime($start_date)) ?> - <?= date('d M Y', strtotime($end_date)) ?></p>

    <div class="table-container">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th> <!-- Tambahkan kolom nomor urut -->
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lama Kerja (menit)</th>
                    <th>Total Gaji</th>
                    <th>Tunjangan</th>
                    <th>Gaji Lembur</th>
                    <th>Tambahan</th>
                    <th>Potongan</th>
                    <th>Deposit</th>
                    <th>Bayar Kasbon</th>
                    <th>Total Penerimaan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1; // Inisialisasi nomor urut
                $total_gaji = 0;
                $total_tunjangan = 0;
                $total_gaji_lembur = 0;
                $total_tambahan_lain = 0;
                $total_potongan = 0;
                $total_deposit = 0;
                $total_kasbon_bayar = 0;
                $grand_total_penerimaan = 0;

                foreach ($detail_gaji as $row):
                    $tunjangan = ($row->tanggal === date('Y-m-01', strtotime($row->tanggal))) ? $pegawai->tunjangan : 0;
                    $total_penerimaan = $row->total_gaji + $tunjangan + $row->total_gaji_lembur + $row->tambahan_lain - $row->potongan + $row->deposit - $row->kasbon_bayar;

                    $total_gaji += $row->total_gaji;
                    $total_tunjangan += $tunjangan;
                    $total_gaji_lembur += $row->total_gaji_lembur;
                    $total_tambahan_lain += $row->tambahan_lain;
                    $total_potongan += $row->potongan;
                    $total_deposit += $row->deposit;
                    $total_kasbon_bayar += $row->kasbon_bayar;
                    $grand_total_penerimaan += $total_penerimaan;
                ?>
                <tr>
                    <td><?= $no++ ?></td> <!-- Menambahkan nomor urut -->
                    <td><?= $row->tanggal ?></td>
                    <td><?= $row->shift ?? '-' ?></td>
                    <td><?= $row->jam_masuk ?></td>
                    <td><?= $row->jam_pulang ?></td>
                    <td><?= $row->lama_menit_kerja ?></td>
                    <td class="text-right">Rp <?= number_format($row->total_gaji, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($tunjangan, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->total_gaji_lembur, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->tambahan_lain, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->potongan, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->deposit, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->kasbon_bayar, 2, ',', '.') ?></td>
                    <td class="text-right font-weight-bold">Rp <?= number_format($total_penerimaan, 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="6" style="text-align: center;">Total</td>
                    <td class="text-right">Rp <?= number_format($total_gaji, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_tunjangan, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_gaji_lembur, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_tambahan_lain, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_potongan, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_deposit, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_kasbon_bayar, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($grand_total_penerimaan, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

