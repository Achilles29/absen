<style>
    .text-right {
        text-align: right; /* Untuk rata kanan */
    }
    .font-weight-bold {
        font-weight: bold; /* Untuk teks tebal */
    }
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
                <th class="nowrap">Alasan</th>
                <th class="nowrap">Lama Lembur (Jam)</th>
                <th class="nowrap">Total Uang Lembur</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($lembur)): ?>
                <?php foreach ($lembur as $row): ?>
                    <tr>
                        <td class="nowrap text-center"><?= $row->tanggal ?></td>
                        <td class="nowrap text-center"><?= $row->alasan ?></td>
                        <td class="nowrap text-right"><?= $row->lama_lembur ?></td>
                        <td class="nowrap text-right">Rp <?= number_format($row->total_gaji_lembur, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data lembur.</td>
                </tr>
            <?php endif; ?>
        </tbody>
<tfoot>
    <tr class="font-weight-bold">
        <td colspan="2" class="text-center">Jumlah</td>
        <td class="text-right"><?= number_format($total_lama_lembur, 2, ',', '.') ?> Jam</td>
        <td class="text-right">Rp <?= number_format($total_uang_lembur, 2, ',', '.') ?></td>
    </tr>
</tfoot>


    </table>
</div>
</div>
