<div class="container mt-4">
    <h2><?= $title ?></h2>
    <h4>Nama Pegawai: <?= $pegawai->nama ?></h4>

    <form method="get" action="">
        <div class="row">
            <div class="col-md-6">
                <label for="bulan">Pilih Bulan:</label>
                <select name="bulan" id="bulan" class="form-control">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" 
                                <?= ($bulan == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="tahun">Pilih Tahun:</label>
                <select name="tahun" id="tahun" class="form-control">
                    <?php for ($y = date('Y') - 5; $y <= date('Y') + 1; $y++): ?>
                        <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Filter</button>
    </form>


    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu Absen</th>
                <th>Koordinat</th>
                <th>Jenis Absen</th>
                <th>Shift</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($log_absensi)): ?>
                <?php foreach ($log_absensi as $row): ?>
                    <tr>
                        <td><?= $row->tanggal ?></td>
                        <td><?= $row->waktu ?></td>
                        <td><?= $row->latitude ?>, <?= $row->longitude ?></td>
                        <td><?= ucfirst($row->jenis_absen) ?></td>
                        <td><?= $row->kode_shift ?? '-' ?></td>
                        <td>
                            <?php if (!empty($row->foto)): ?>
                                <img src="<?= base_url('uploads/' . $row->foto) ?>" alt="Foto Absen" width="100">
                            <?php else: ?>
                                Tidak Ada Foto
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data absensi untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
