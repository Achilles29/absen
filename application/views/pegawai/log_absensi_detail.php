<style>
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Untuk pengalaman scroll yang lebih halus di perangkat mobile */
}
.nowrap {
    white-space: nowrap;
}
table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    background-color: #f8f9fa;
    text-align: center;
    font-weight: bold;
}

</style>
<div class="container mt-4">
    <h2><?= $title ?></h2>
    <h4>Nama Pegawai: <?= $pegawai->nama ?></h4>

<form method="get" action="" class="form-inline mb-3">
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

    <div class="table-responsive">

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th class="nowrap">Tanggal</th>
                <th class="nowrap">Waktu Absen</th>
                <th class="nowrap">Koordinat</th>
                <th class="nowrap">Jenis Absen</th>
                <th class="nowrap">Shift</th>
                <th class="nowrap">Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($log_absensi)): ?>
                <?php foreach ($log_absensi as $row): ?>
                    <tr>
                        <td class="nowrap text-center"><?= $row->tanggal ?></td>
                        <td class="nowrap text-center"><?= $row->waktu ?></td>
                        <td class="nowrap text-center"><?= $row->latitude ?>, <?= $row->longitude ?></td>
                        <td class="nowrap text-center"><?= ucfirst($row->jenis_absen) ?></td>
                        <td class="nowrap text-center"><?= $row->kode_shift ?? '-' ?></td>
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
                    <td colspan="6" class="nowrap text-center">Tidak ada data absensi untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
