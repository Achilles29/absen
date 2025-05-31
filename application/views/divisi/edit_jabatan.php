<div class="container mt-4">
    <h2>Edit Jabatan</h2>
    <form method="post" action="">
        <div class="form-group">
            <label>Divisi:</label>
            <select name="divisi_id" class="form-control" required>
                <option value="">-- Pilih Divisi --</option>
                <?php foreach ($divisi as $row): ?>
                    <option value="<?= $row->id ?>" <?= $row->id == $jabatan->divisi_id ? 'selected' : '' ?>>
                        <?= $row->nama_divisi ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Nama Jabatan:</label>
            <input type="text" name="nama_jabatan" class="form-control" value="<?= $jabatan->nama_jabatan ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
