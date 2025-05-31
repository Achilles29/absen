<div class="container mt-4">
    <h2><?= $title ?></h2>
    <p>Nama Pegawai: <strong><?= htmlspecialchars($pegawai->nama) ?></strong></p>
    <p>Bulan: <strong><?= date('F Y', strtotime($bulan)) ?></strong></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($detail_kasbon)): ?>
                <?php foreach ($detail_kasbon as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row->tanggal) ?></td>
                        <td><?= ucfirst($row->jenis) ?></td> <!-- Kasbon / Bayar -->
                        <td style="text-align: right; white-space: nowrap;">
                            Rp <?= number_format($row->nilai, 2, ',', '.') ?>
                        </td>
                        <td><?= htmlspecialchars($row->keterangan) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data kasbon untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
