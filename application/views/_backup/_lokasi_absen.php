<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Notifikasi sukses -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <!-- Tombol Tambah Lokasi -->
    <div class="mb-3">
        <a href="<?= site_url('admin/lokasi_absensi') ?>" class="btn btn-success">Tambah Lokasi Absen</a>
    </div>

    <!-- Tabel Daftar Lokasi -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lokasi</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Range (Meter)</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($lokasi_list)): ?>
                <?php foreach ($lokasi_list as $index => $lokasi): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($lokasi->nama_lokasi) ?></td>
                        <td><?= $lokasi->latitude ?></td>
                        <td><?= $lokasi->longitude ?></td>
                        <td><?= $lokasi->range ?> m</td>
                        <td><?= $lokasi->status == 1 ? 'Aktif' : 'Nonaktif' ?></td>
                        <td>
                            <a href="<?= site_url('admin/lokasi_absen/edit/' . $lokasi->id) ?>" 
                               class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('admin/lokasi_absen/hapus/' . $lokasi->id) ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                               Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Belum ada lokasi yang ditambahkan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
