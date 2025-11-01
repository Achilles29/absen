<div class="container mt-4">
    <h2>Rincian Deposit - <?= $pegawai->nama ?></h2>

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
            <?php foreach ($rincian_deposit as $index => $deposit): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= date('d-m-Y', strtotime($deposit->tanggal)) ?></td>
                    <td><?= ucfirst($deposit->jenis) ?></td>
                    <td>Rp <?= number_format($deposit->nilai, 2, ',', '.') ?></td>
                    <td><?= $deposit->keterangan ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= site_url('deposit/index') ?>" class="btn btn-secondary">Kembali</a>
</div>
