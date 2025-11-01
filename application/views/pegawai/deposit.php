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
                <th class="nowrap">Jenis</th>
                <th class="nowrap">Nilai</th>
                <th class="nowrap">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($deposit)): ?>
                <?php foreach ($deposit as $row): ?>
                    <tr>
                        <td><?= $row->tanggal ?></td>
                        <td><?= ucfirst($row->jenis) ?></td>
                        <td class="text-right">Rp <?= number_format($row->nilai, 2, ',', '.') ?></td>
                        <td><?= $row->keterangan ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data deposit.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td colspan="2" class="nowrap text-center">Jumlah Setor Bulan Ini</td>
                <td class="nowrap text-right">Rp <?= number_format($total_setor, 2, ',', '.') ?></td>
                <td></td>
            </tr>
            <tr class="font-weight-bold">
                <td colspan="2" class="nowrap text-center">Jumlah Tarik Bulan Ini</td>
                <td class="nowrap text-right">Rp <?= number_format($total_tarik, 2, ',', '.') ?></td>
                <td></td>
            </tr>
            <tr class="font-weight-bold">
                <td colspan="2" class="nowrap text-center">Sisa Deposit Bulan Ini</td>
                <td class="nowrap text-right">Rp <?= number_format($sisa_deposit_bulan_ini, 2, ',', '.') ?></td>
                <td></td>
            </tr>
            <tr class="nowrap font-weight-bold">
                <td colspan="2" class="nowrap text-center">Sisa Deposit Total</td>
                <td class="nowrap text-right">Rp <?= number_format($sisa_deposit_total, 2, ',', '.') ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
</div>
