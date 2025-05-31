<div class="container mt-4">
    <h2><?= $title ?></h2>
    <p><strong>Nama Pegawai:</strong> <?= $pegawai->nama ?></p>
    <p><strong>Bulan:</strong> <?= date('F Y', strtotime($bulan)) ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nilai Tambahan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detail_tambahan as $row): ?>
                <tr>
                    <td><?= $row->tanggal ?></td>
                    <td>Rp <?= number_format($row->nilai_tambahan, 2, ',', '.') ?></td>
                    <td><?= $row->keterangan ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
