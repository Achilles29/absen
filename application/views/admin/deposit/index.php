<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan -->
    <form method="get" action="<?= site_url('deposit/index') ?>" class="form-inline mb-3">
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
        <a href="<?= site_url('deposit/input') ?>" class="btn btn-success ml-2">Tambah Deposit</a>
    </form>

    <!-- Tabel Rekapitulasi Deposit -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Setor</th>
                <th>Total Tarik</th>
                <th>Sisa Deposit</th>
                <th>Sisa Deposit Total</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_setor = 0;
            $total_tarik = 0;
            $total_sisa_deposit = 0;
            $total_sisa_deposit_total = 0;

            foreach ($rekap_deposit as $index => $row): 
                // Hitung total untuk footer
                $sisa_total = $this->Deposit_model->get_sisa_deposit_total($row->id);
                $sisa_total_value = $sisa_total->total_setor - $sisa_total->total_tarik;

                $total_setor += $row->total_setor;
                $total_tarik += $row->total_tarik;
                $total_sisa_deposit += $row->sisa_deposit;
                $total_sisa_deposit_total += $sisa_total_value;
            ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $row->nama ?></td>
                    <td class="text-right">Rp <?= number_format($row->total_setor, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->total_tarik, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($row->sisa_deposit, 2, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($sisa_total_value, 2, ',', '.') ?></td>
                    <td class="text-center">
                        <a href="<?= site_url('deposit/detail/' . $row->id . '?bulan=' . $bulan) ?>" class="btn btn-info btn-sm">Detail</a>
                        <a href="<?= site_url('deposit/rincian/' . $row->id) ?>" class="btn btn-warning btn-sm">Rincian</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center">Jumlah</th>
                <th class="text-right">Rp <?= number_format($total_setor, 2, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format($total_tarik, 2, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format($total_sisa_deposit, 2, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format($total_sisa_deposit_total, 2, ',', '.') ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
