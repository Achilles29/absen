<div class="container">
    <h1 class="my-4">Detail Absensi - <?= $pegawai->nama ?></h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($detail) > 0): ?>
                <?php foreach ($detail as $index => $absen): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $absen->tanggal ?></td>
                        <td><?= $absen->waktu ?></td>
                        <td><?= $absen->latitude ?></td>
                        <td><?= $absen->longitude ?></td>
                        <td>
                            <?php if ($absen->foto): ?>
                                <img src="<?= base_url('uploads/' . $absen->foto) ?>" alt="Foto Absensi" width="100">
                            <?php else: ?>
                                Tidak ada foto
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data absensi untuk pegawai ini</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="<?= site_url('admin/rekap_absensi') ?>" class="btn btn-secondary">Kembali</a>
</div>
