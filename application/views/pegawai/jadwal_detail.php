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

    <form method="get" action="">
        <div class="form-group">
            <label for="bulan">Pilih Bulan:</label>
            <input type="month" name="bulan" id="bulan" value="<?= $bulan ?>" class="form-control" style="width: 200px;">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

        <div class="table-responsive">

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th class="nowrap">Tanggal</th>
                <th class="nowrap">Shift</th>
                <th class="nowrap">Jam Mulai</th>
                <th class="nowrap">Jam Selesai</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($jadwal)): ?>
                <?php foreach ($jadwal as $row): ?>
                    <tr>
                        <td class="nowrap text-center"><?= $row->tanggal ?></td>
                        <td class="nowrap text-center"><?= $row->kode_shift ?></td>
                        <td class="nowrap text-center"><?= $row->jam_mulai ?></td>
                        <td class="nowrap text-center"><?= $row->jam_selesai ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada jadwal untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
