<div class="container mt-4">
    <h2><?= $title ?></h2>
    <p>Nama Pegawai: <strong><?= $pegawai->nama ?></strong></p>
    <p>Bulan: <strong><?= date('F Y', strtotime($bulan)) ?></strong></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nilai Potongan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detail_potongan as $row): ?>
                <tr>
                    <td><?= $row->tanggal ?></td>
                    <td>Rp <?= number_format($row->nilai, 2, ',', '.') ?></td>
                    <td><?= $row->keterangan ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
