<style>
    .table-container {
        margin-left: -50px;
        width: 100%;
        overflow-x: auto;
        min-width: 1400px; /* Lebar lebih besar */
    }

    .table {
        font-size: 0.9rem;
        border-collapse: collapse;
    }

    .table td, .table th {
        white-space: nowrap; /* No wrap untuk teks */
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
        text-align: right; /* Rata kanan untuk nominal */
    }
</style>
<div class="container mt-4">
    <h2 class="mb-4 text-center">Arsip Gaji Pegawai</h2>

    <a href="<?= site_url('admin/export_arsip_gaji') ?>" class="btn btn-success mb-3">Export to Excel</a>
    <a href="<?= site_url('admin/export_arsip_gaji_csv') ?>" class="btn btn-success mb-3">Export to CSV</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <!-- <th>ID Pegawai</th> -->
                    <th>Nama Pegawai</th>
                    <th>Nomor Rekening</th>
                    <th>Bank</th>
                    <th>Divisi</th>
                    <th>Jabatan 1</th>
                    <th>Jabatan 2</th>
                    <th>Total Kehadiran</th>
                    <th>Total Jam</th>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan</th>
                    <th>Total Lembur</th>
                    <th>Tambahan Lain</th>
                    <th>Potongan</th>
                    <th>Deposit</th>
                    <th>Bayar Kasbon</th>
                    <th>Total Penerimaan</th>
                    <th>Pembulatan</th>
                    <th>Periode</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arsip_gaji as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <!-- <td><?= $row->pegawai_id ?></td> -->
                    <td style="text-align: left;"><?= $row->nama_pegawai ?></td>
                    <td><?= $row->nomor_rekening ?></td>
                    <td><?= $row->nama_bank ?></td>
                    <td><?= $row->divisi ?></td>
                    <td><?= $row->jabatan1 ?></td>
                    <td><?= $row->jabatan2 ?></td>
                    <td><?= $row->total_kehadiran ?></td>
                    <td class="text-right"><?= number_format($row->total_jam ?? 0, 2) ?></td>
                    <td class="text-right">Rp <?= number_format($row->gaji_pokok ?? 0, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->tunjangan ?? 0, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->total_lembur ?? 0, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->tambahan_lain ?? 0, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->potongan ?? 0, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->deposit ?? 0, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->bayar_kasbon ?? 0, 0, ',', '.') ?></td>
                    <td class="text-right font-weight-bold">Rp <?= number_format($row->total_penerimaan ?? 0, 0, ',', '.') ?></td>
                    <td class="text-right font-weight-bold">Rp <?= number_format($row->pembulatan_penerimaan ?? 0, 0, ',', '.') ?></td>
                    <td><?= date('d M Y', strtotime($row->tanggal_awal)) ?> - <?= date('d M Y', strtotime($row->tanggal_akhir)) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>
