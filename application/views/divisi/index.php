<a href="<?= site_url('divisi/tambah') ?>" class="btn btn-success mb-2">Tambah Divisi</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Divisi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($divisi as $index => $row): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= $row->nama_divisi ?></td>
            <td>
                <a href="<?= site_url('divisi/edit/'.$row->id) ?>" class="btn btn-warning">Edit</a>
                <a href="<?= site_url('divisi/hapus/'.$row->id) ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-danger">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
