<div class="container mt-4">
    <h2>Tambah Kode User</h2>
    <form method="post" action="<?= site_url('admin/tambah_kode_user') ?>">
        <div class="form-group">
            <label>Kode User:</label>
            <input type="text" name="kode_user" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
