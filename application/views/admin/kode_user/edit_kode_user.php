<div class="container mt-4">
    <h2>Edit Kode User</h2>
    <form method="post" action="<?= site_url('admin/edit_kode_user/'.$kode_user->id) ?>">
        <div class="form-group">
            <label>Kode User:</label>
            <input type="text" name="kode_user" class="form-control" value="<?= $kode_user->kode_user ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
