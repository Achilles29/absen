<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan -->
    <form method="get" action="<?= site_url('hod/index') ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Filter Bulan:</label>
        <select name="bulan" id="bulan" class="form-control mr-2">
            <?php for ($i = 0; $i < 12; $i++): 
                $month = date('Y-m', strtotime("-$i month"));
                $selected = ($bulan == $month) ? 'selected' : '';
            ?>
                <option value="<?= $month ?>" <?= $selected ?>><?= date('F Y', strtotime($month)) ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel Jadwal Shift -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Divisi</th>
                <th>Jumlah Hari Kerja</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pegawai)): ?>
                <?php $no = 1; foreach ($pegawai as $p): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($p->nama) ?></td>
                        <td><?= htmlspecialchars($p->nama_divisi) ?></td>
                        <td class="text-center">
                            <?= $hari_kerja[$p->id] ?? 0 ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= site_url('hod/detail/' . $p->id . '?bulan=' . $bulan) ?>" 
                               class="btn btn-info btn-sm">
                                Lihat Jadwal
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data pegawai untuk ditampilkan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
