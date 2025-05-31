<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="get" action="<?= site_url('jadwal_shift/jadwal_shift_bulanan') ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Filter Bulan:</label>
        <input type="month" name="bulan" id="bulan" class="form-control mr-2" value="<?= $bulan ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align: middle;">Nama Pegawai</th>
                <th rowspan="2" style="vertical-align: middle;">Divisi</th>
                <?php
                $tanggal_awal = strtotime($tanggal_awal);
                $tanggal_akhir = strtotime($tanggal_akhir);
                for ($i = $tanggal_awal; $i <= $tanggal_akhir; $i += 86400): ?>
                    <th><?= date('d', $i) ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $current_pegawai = '';
            $tanggal_shift = [];
            foreach ($jadwal_shift as $shift) {
                $tanggal_shift[$shift->nama_pegawai][$shift->nama_divisi][$shift->tanggal] = $shift->kode_shift;
            }
            foreach ($tanggal_shift as $pegawai => $divisi_shift): 
                foreach ($divisi_shift as $divisi => $shifts): ?>
                <tr>
                    <td><?= $pegawai ?></td>
                    <td><?= $divisi ?></td>
                    <?php for ($i = $tanggal_awal; $i <= $tanggal_akhir; $i += 86400): 
                        $tanggal = date('Y-m-d', $i);
                        $kode_shift = $shifts[$tanggal] ?? '-';
                    ?>
                        <td><?= $kode_shift ?></td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; endforeach; ?>
        </tbody>
    </table>
</div>
