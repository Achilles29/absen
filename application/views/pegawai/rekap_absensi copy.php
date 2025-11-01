<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rekapitulasi Absensi</title>
</head>
<body>
    <h1>Rekapitulasi Absensi Saya</h1>
    <table border="1" cellpadding="5" cellspacing="0">
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
            <?php if (count($absensi) > 0): ?>
                <?php foreach ($absensi as $index => $absen): ?>
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
                    <td colspan="6">Belum ada data absensi</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
