<div class="container mt-4">
    <h2><?= $title ?></h2>

    <form method="post" action="">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pegawai_id">Nama Pegawai</label>
            <select name="pegawai_id" class="form-control" required>
                <option value="">-- Pilih Pegawai --</option>
                <?php foreach ($pegawai as $row): ?>
                    <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="nilai_tambahan">Nilai Tambahan</label>
            <input type="number" name="nilai_tambahan" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
