<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="post" action="">
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nama Pegawai</label>
            <select name="pegawai_id" class="form-control" required>
                <option value="">-- Pilih Pegawai --</option>
                <?php foreach ($pegawai as $p): ?>
                    <option value="<?= $p->id ?>"><?= $p->nama ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Nilai Potongan</label>
            <input type="number" name="nilai" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
