<div class="container mt-4">
    <h2><?= $title ?></h2>
    <a href="<?= site_url('shift/tambah_shift') ?>" class="btn btn-success mb-3">Tambah Shift</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Divisi</th>
            <th>Nama Shift</th>
            <th>Kode Shift</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($shift as $index => $row): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= $row->nama_divisi ?></td>
            <td><?= $row->nama_shift ?></td>
            <td><?= $row->kode_shift ?></td>
            <td><?= $row->jam_mulai ?></td>
            <td><?= $row->jam_selesai ?></td>
            <td>
                <a href="<?= site_url('shift/edit_shift/'.$row->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= site_url('shift/hapus_shift/'.$row->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus shift ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>
