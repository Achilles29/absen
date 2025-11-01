<style>
    .text-right {
        text-align: right; /* Untuk rata kanan */
    }
    .font-weight-bold {
        font-weight: bold; /* Untuk teks tebal */
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

    <!-- Filter Bulan -->
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

    <!-- Tabel Kasbon -->
         <div class="table-responsive">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="nowrap">Tanggal</th>
                <th class="nowrap">Jenis</th>
                <th class="nowrap">Nilai</th>
                <th class="nowrap">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php if (!empty($kasbon)): ?>
                <?php foreach ($kasbon as $row): ?>
                    <tr>
                        <td class="nowrap text-center"><?= $row->tanggal ?></td>
                        <td class="nowrap text-center"><?= ucfirst($row->jenis) ?></td>
                        <td class=" nowraptext-right">Rp <?= number_format($row->nilai, 2, ',', '.') ?></td>
                        <td class="nowrap text-center"><?= $row->keterangan ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data kasbon untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <!-- Tambahkan Baris Jumlah -->
        <tfoot>
            <tr>
                <td colspan="2" class="nowrap text-center font-weight-bold">Jumlah Kasbon Bulan Ini</td>
                <td colspan="2" style="nowrap text-align: right; font-weight: bold;">
                    Rp <?= number_format($jumlah_kasbon, 2, ',', '.') ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="nowrap text-center font-weight-bold">Jumlah Kasbon Total</td>
                <td colspan="2" style="nowrap text-align: right; font-weight: bold;">
                    Rp <?= number_format($jumlah_kasbon_total, 2, ',', '.') ?>
                </td>
            </tr>
        </tfoot>


    </table>
</div>
</div>
