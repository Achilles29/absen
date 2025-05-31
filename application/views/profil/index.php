<div class="container mt-4">
    <h2>Profil <?= ucfirst($role) ?></h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <?php if ($role === 'admin'): ?>
            <!-- Tampilkan hanya nama jika admin -->
            <tr>
                <th>Nama</th>
                <td><?= !empty($profil->nama) ? $profil->nama : $profil->username; ?></td>
            </tr>
            <tr>
                <th>Foto Profil</th>
                <td>
                    <img src="<?= base_url('uploads/' . (!empty($profil->avatar) ? $profil->avatar : 'default.png')) ?>" 
                        class="img-thumbnail" width="150">
                </td>
            </tr>
        <?php else: ?>
            <!-- Tampilkan data lengkap jika pegawai -->
            <tr>
                <th>Nama</th>
                <td><?= $profil->nama ?></td>
            </tr>
            <tr>
                <th>Divisi</th>
                <td><?= !empty($profil->nama_divisi) ? $profil->nama_divisi : '-' ?></td>
            </tr>
            <tr>
                <th>Jabatan 1</th>
                <td><?= !empty($profil->jabatan1) ? $profil->jabatan1 : '-' ?></td>
            </tr>
            <tr>
                <th>Jabatan 2</th>
                <td><?= !empty($profil->jabatan2) ? $profil->jabatan2 : '-' ?></td>
            </tr>
            <tr>
                <th>Gaji Pokok</th>
                <td>Rp <?= number_format($profil->gaji_pokok, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Gaji Per Jam</th>
                <td>Rp <?= number_format($profil->gaji_per_jam, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Tunjangan</th>
                <td>Rp <?= number_format($profil->tunjangan, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Tambahan Lain</th>
                <td>Rp <?= number_format($profil->tambahan_lain, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Foto Profil</th>
                <td>
                    <img src="<?= base_url('uploads/' . (!empty($profil->avatar) ? $profil->avatar : 'default.png')) ?>" 
                        class="img-thumbnail" width="150">
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Tombol Edit -->
    <a href="<?= site_url('profil/edit') ?>" class="btn btn-primary mt-3">Edit Profil</a>
</div>
