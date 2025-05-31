<div class="container mt-4">
    <h2>Detail Jadwal Shift Pegawai</h2>

    <p><strong>Nama Pegawai:</strong> <?= htmlspecialchars($pegawai->nama ?? '-') ?></p>
    <p><strong>Divisi:</strong> <?= htmlspecialchars($pegawai->nama_divisi ?? '-') ?></p>
    <p><strong>Bulan:</strong> <?= date('F Y', strtotime($bulan)) ?></p>

    <!-- Tabel Jadwal Shift -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($jadwal_shift)): ?>
                <?php foreach ($jadwal_shift as $jadwal): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($jadwal->tanggal)) ?></td>
                        <td><?= htmlspecialchars($jadwal->kode_shift ?? '-') ?></td>
                        <td><?= htmlspecialchars($jadwal->jam_mulai ?? '-') ?></td>
                        <td><?= htmlspecialchars($jadwal->jam_selesai ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Belum ada jadwal shift untuk pegawai ini di bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
