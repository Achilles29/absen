<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan dan Tahun -->
    <form method="get" action="<?= site_url('jadwal_shift/index') ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Bulan:</label>
        <select name="bulan" id="bulan" class="form-control mr-2">
            <?php 
            $months = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            foreach ($months as $key => $value): ?>
                <option value="<?= $key ?>" <?= ($selected_month == $key) ? 'selected' : '' ?>><?= $value ?></option>
            <?php endforeach; ?>
        </select>

        <label for="tahun" class="mr-2">Tahun:</label>
        <select name="tahun" id="tahun" class="form-control mr-2">
            <?php for ($year = date('Y') - 5; $year <= date('Y') + 5; $year++): ?>
                <option value="<?= $year ?>" <?= ($selected_year == $year) ? 'selected' : '' ?>><?= $year ?></option>
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
                             <a href="<?= site_url('jadwal_shift/detail/' . $p->id . '?bulan=' . $bulan) ?>" 
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

