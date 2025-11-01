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
                <th class="nowrap">Nilai</th>
                <th class="nowrap">Keterangan</th>
            </tr>
        </thead>
<tbody>
    <?php if (!empty($tambahan)): ?>
        <?php foreach ($tambahan as $row): ?>
            <tr>
                <td class="nowrap text-center"><?= $row->tanggal ?></td>
                <td class="nowrap text-right">Rp <?= number_format($row->nilai_tambahan, 2, ',', '.') ?></td>
                <td class="nowrap text-center"><?= $row->keterangan ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3" class="nowrap text-center">Tidak ada data tambahan.</td>
        </tr>
    <?php endif; ?>
</tbody>
<tfoot>
    <tr class="font-weight-bold">
        <td colspan="2" class="nowrap text-center">Jumlah</td>
        <td class="nowrap text-right">Rp <?= number_format($total_tambahan_lain, 2, ',', '.') ?></td>
    </tr>
</tfoot>



    </table>
</div>
</div>
