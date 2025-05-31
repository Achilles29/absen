<div class="container mt-4">
    <h2>Edit Profil <?= ucfirst($role) ?></h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('profil/update') ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= $profil->nama ?>" required>
        </div>

        <!-- Tampilkan hanya untuk pegawai -->
        <?php if ($role === 'pegawai'): ?>
            <div class="form-group">
                <label for="divisi">Divisi</label>
                <input type="text" class="form-control" id="divisi" value="<?= $profil->nama_divisi ?>" readonly>
            </div>
            <div class="form-group">
                <label for="jabatan1">Jabatan 1</label>
                <input type="text" class="form-control" id="jabatan1" value="<?= $profil->jabatan1 ?>" readonly>
            </div>
            <div class="form-group">
                <label for="jabatan2">Jabatan 2</label>
                <input type="text" class="form-control" id="jabatan2" value="<?= $profil->jabatan2 ?>" readonly>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="avatar">Foto Profil</label>
            <input type="file" class="form-control" id="avatar" name="avatar">
            <?php if (!empty($profil->avatar)): ?>
                <img src="<?= base_url('uploads/' . $profil->avatar) ?>" class="img-thumbnail mt-2" width="150">
            <?php else: ?>
                <img src="<?= base_url('uploads/default.png') ?>" class="img-thumbnail mt-2" width="150">
            <?php endif; ?>
        </div>

        <!-- Ganti Password -->
        <h4 class="mt-4">Ganti Password</h4>
        <div class="form-group">
            <label for="password_lama">Password Lama</label>
            <input type="password" class="form-control" id="password_lama" name="password_lama">
        </div>
        <div class="form-group">
            <label for="password_baru">Password Baru</label>
            <input type="password" class="form-control" id="password_baru" name="password_baru">
        </div>
        <div class="form-group">
            <label for="konfirmasi_password">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
