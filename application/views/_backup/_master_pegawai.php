<style>
    .action-buttons {
        white-space: nowrap;
    }
</style>

<h1>Master Pegawai</h1>

<?php if ($this->session->flashdata('success')): ?>
    <p style="color: green;"><?= $this->session->flashdata('success'); ?></p>
<?php endif; ?>

<a href="<?= site_url('admin/tambah_pegawai') ?>" class="btn btn-primary mb-3">Tambah Pegawai</a>
<div class="container mt-4">
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Jabatan 1</th>
                <th>Jabatan 2</th>
                <th>Gaji Pokok</th>
                <th>Gaji Per Jam</th>
                <th>Tunjangan</th>
                <th>Tambahan Lain</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($abs_pegawai as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $row->nama ?></td>
                <td><?= $row->nama_divisi ?></td>
                <td><?= $row->jabatan1 ?></td>
                <td><?= $row->jabatan2 ?></td>
                <td style="text-align: right;"><?= number_format($row->gaji_pokok, 0, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($row->gaji_per_jam, 2, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($row->tunjangan, 0, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($row->tambahan_lain, 0, ',', '.') ?></td>
                <td class="action-buttons">
                    <a href="<?= site_url('admin/edit_pegawai/'.$row->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= site_url('admin/hapus_pegawai/'.$row->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
