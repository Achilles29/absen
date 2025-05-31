<div class="container mt-4">
    <h2><?= $title ?></h2>
    <p><strong>Nama Pegawai:</strong> <?= $pegawai->nama ?></p>
    <p><strong>Bulan:</strong> <?= date('F Y', strtotime($bulan)) ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($detail_deposit)): ?>
                <?php foreach ($detail_deposit as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
                        <td><?= ucfirst($row->jenis) ?></td>
                        <td style="text-align: right; white-space: nowrap;">
                            Rp <?= number_format($row->nilai, 2, ',', '.') ?>
                        </td>
                        <td><?= $row->keterangan ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data deposit untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
