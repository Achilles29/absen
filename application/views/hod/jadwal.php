<div class="container mt-4">
    <h2><?= $title ?></h2>

    <form method="get" class="mb-3">
        <label for="bulan">Pilih Bulan:</label>
        <select name="bulan" class="form-control" style="width: 200px; display: inline-block;">
            <?php for ($i = 0; $i < 12; $i++): 
                $month = date('Y-m', strtotime("-$i month"));
                $selected = ($bulan == $month) ? 'selected' : '';
            ?>
                <option value="<?= $month ?>" <?= $selected ?>><?= date('F Y', strtotime($month)) ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-bordered table-striped">
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
            <?php foreach ($jadwal_shift as $jadwal): ?>
                <tr>
                    <td><?= date('d M Y', strtotime($jadwal->tanggal)) ?></td>
                    <td><?= htmlspecialchars($jadwal->nama_pegawai) ?></td>
                    <td><?= htmlspecialchars($jadwal->kode_shift) ?></td>
                    <td><?= $jadwal->jam_mulai ?></td>
                    <td><?= $jadwal->jam_selesai ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
