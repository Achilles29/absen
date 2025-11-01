<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan -->
    <form method="get" action="" class="mb-3">
        <label for="bulan">Filter Bulan:</label>
        <input type="month" name="bulan" id="bulan" value="<?= $bulan ?>" class="form-control" style="width: 200px; display: inline-block;">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel Jadwal Shift -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
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
                        <td><?= htmlspecialchars($jadwal->nama_pegawai) ?></td>
                        <td><?= htmlspecialchars($jadwal->kode_shift) ?></td>
                        <td><?= $jadwal->jam_mulai ?></td>
                        <td><?= $jadwal->jam_selesai ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Jadwal shift tidak tersedia untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
