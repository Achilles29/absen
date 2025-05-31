<div class="container">
    <h1 class="my-4">Rekapitulasi Absensi Pegawai</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Total Absensi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($rekap) > 0): ?>
                <?php foreach ($rekap as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $row->nama ?></td>
                        <td><?= $row->total_absensi ?></td>
                        <td>
                            <a href="<?= site_url('admin/detail_absensi/' . $row->id) ?>" class="btn btn-primary btn-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Tidak ada data absensi</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
