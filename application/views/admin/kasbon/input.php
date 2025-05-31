<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="pegawai_id">Nama Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                <option value="">-- Pilih Pegawai --</option>
                <?php foreach ($pegawai as $p): ?>
                    <option value="<?= htmlspecialchars($p->id) ?>"><?= htmlspecialchars($p->nama) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="jenis">Jenis Transaksi</label>
            <select name="jenis" id="jenis" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="kasbon">Kasbon</option>
                <option value="bayar">Bayar Kasbon</option>
            </select>
        </div>

        <div class="form-group">
            <label for="nilai">Nilai</label>
            <input type="number" name="nilai" id="nilai" class="form-control" min="0" required placeholder="Masukkan nilai kasbon atau bayar">
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan jika diperlukan"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
