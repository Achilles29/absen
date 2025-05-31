<div class="container mt-4">
    <h2><?= $title ?></h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th>Shift</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Terlambat (Menit)</th>
                <th>Pulang Cepat (Menit)</th>
                <th>Lama Kerja (Menit)</th>
                <th>Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rekap)): ?>
                <?php foreach ($rekap as $row): ?>
                    <tr>
                        <td><?= $row->tanggal ?></td>
                        <td><?= $row->nama_pegawai ?></td>
                        <td><?= $row->kode_shift ?></td>
                        <td><?= $row->jam_masuk ?></td>
                        <td><?= $row->jam_pulang ?></td>
                        <td><?= $row->terlambat ?> menit</td>
                        <td><?= $row->pulang_cepat ?> menit</td>
                        <td><?= $row->lama_menit_kerja ?> menit</td>
                        <td>Rp <?= number_format($row->total_gaji, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">Belum ada data absensi</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
