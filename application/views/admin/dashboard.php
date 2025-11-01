<h1>Dashboard Admin</h1>
<a href="<?= site_url('admin/tambah_pegawai') ?>">Tambah Pegawai</a>
<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Divisi</th>
            <th>Jabatan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pegawai as $p): ?>
        <tr>
            <td><?= $p->nama ?></td>
            <td><?= $p->divisi ?></td>
            <td><?= $p->jabatan1 ?> / <?= $p->jabatan2 ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
