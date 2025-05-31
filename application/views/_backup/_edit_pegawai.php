<div class="container mt-4">
    <h2>Edit Data Pegawai</h2>
    <form method="post" action="<?= site_url('admin/update_pegawai/'.$pegawai->id) ?>">
        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" class="form-control" value="<?= $pegawai->nama ?>" required>
        </div>

        <div class="form-group">
            <label>Divisi:</label>
            <select name="divisi_id" class="form-control" required>
                <?php foreach ($divisi as $row): ?>
                    <option value="<?= $row->id ?>" <?= $row->id == $pegawai->divisi_id ? 'selected' : '' ?>>
                        <?= $row->nama_divisi ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jabatan 1:</label>
            <select name="jabatan1_id" class="form-control" required>
                <?php foreach ($jabatan as $row): ?>
                    <option value="<?= $row->id ?>" <?= $row->id == $pegawai->jabatan1_id ? 'selected' : '' ?>>
                        <?= $row->nama_jabatan ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

<div class="form-group">
    <label>Jabatan 2:</label>
    <select name="jabatan2_id" class="form-control">
        <option value="">-- Pilih Jabatan 2 (Opsional) --</option>
        <?php foreach ($jabatan as $row): ?>
            <option value="<?= $row->id ?>" <?= $row->id == $pegawai->jabatan2_id ? 'selected' : '' ?>>
                <?= $row->nama_jabatan ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

        <div class="form-group">
            <label>Gaji Pokok:</label>
            <input type="number" name="gaji_pokok" class="form-control" value="<?= $pegawai->gaji_pokok ?>" required>
        </div>

        <div class="form-group">
            <label>Tunjangan:</label>
            <input type="number" name="tunjangan" class="form-control" value="<?= $pegawai->tunjangan ?>" required>
        </div>

        <div class="form-group">
            <label>Tambahan Lain:</label>
            <input type="number" name="tambahan_lain" class="form-control" value="<?= $pegawai->tambahan_lain ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
