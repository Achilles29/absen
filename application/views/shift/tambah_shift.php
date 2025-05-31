<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="post" action="">
        <div class="form-group">
            <label>Divisi:</label>
            <select name="divisi_id" class="form-control" required>
                <option value="">-- Pilih Divisi --</option>
                <?php foreach ($divisi as $row): ?>
                    <option value="<?= $row->id ?>"><?= $row->nama_divisi ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Kode Shift:</label>
            <input type="text" name="kode_shift" class="form-control" required>
        </div>
    <div class="form-group">
    <label>Nama Shift:</label>
    <input type="text" name="nama_shift" class="form-control" required>
</div>

        <div class="form-group">
            <label>Jam Mulai:</label>
            <input type="time" name="jam_mulai" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Jam Selesai:</label>
            <input type="time" name="jam_selesai" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
