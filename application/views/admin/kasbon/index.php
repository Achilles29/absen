<div class="container mt-4">
    <h2><?= $title ?></h2>

    <form method="get" action="<?= site_url('kasbon/index') ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Filter Bulan:</label>
        <select name="bulan" id="bulan" class="form-control">
            <?php for ($i = 0; $i < 12; $i++): 
                $month = date('Y-m', strtotime("-$i month"));
                $selected = ($bulan == $month) ? 'selected' : '';
            ?>
                <option value="<?= $month ?>" <?= $selected ?>>
                    <?= date('F Y', strtotime($month)) ?>
                </option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="<?= site_url('kasbon/input') ?>" class="btn btn-success ml-2">Input Kasbon</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Kasbon</th>
                <th>Total Bayar</th>
                <th>Sisa Kasbon</th>
                <th>Sisa Kasbon Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rekap_kasbon as $index => $kasbon): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $kasbon->nama ?></td>
                    <td><?= number_format($kasbon->total_kasbon, 2, ',', '.') ?></td>
                    <td><?= number_format($kasbon->total_bayar, 2, ',', '.') ?></td>
                    <td><?= number_format($kasbon->sisa_kasbon, 2, ',', '.') ?></td>
                    <td><?= number_format($this->Kasbon_model->get_sisa_kasbon_total($kasbon->id)->total_kasbon - $this->Kasbon_model->get_sisa_kasbon_total($kasbon->id)->total_bayar, 2, ',', '.') ?></td>
                    <td>
                       
                    <a href="<?= site_url('kasbon/detail/' . $kasbon->id . '?bulan=' . $bulan) ?>" class="btn btn-info btn-sm">Detail</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
