<div class="container mt-4">
    <h2><?= $title ?></h2>
    <a href="<?= site_url('divisi/tambah_jabatan') ?>" class="btn btn-success mb-3">Tambah Jabatan</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jabatan</th>
                <th>Divisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jabatan as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $row->nama_jabatan ?></td>
                <td><?= $row->nama_divisi ?></td>
                <td>
                    <a href="<?= site_url('divisi/edit_jabatan/'.$row->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= site_url('divisi/hapus_jabatan/'.$row->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus jabatan ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
