<div class="container mt-4">
    <h2>Edit Data Pegawai</h2>
    <form method="post" action="<?= site_url('admin/update_pegawai/'.$pegawai->id) ?>">
    <div class="form-group">
        <label>Kode User:</label>
        <select name="kode_user" class="form-control" required>
            <option value="">-- Pilih Kode User --</option>
            <?php foreach ($kode_users as $row): ?>
                <option value="<?= $row->kode_user ?>" <?= $row->kode_user == $pegawai->kode_user ? 'selected' : '' ?>>
                    <?= $row->kode_user ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

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
        <div class="form-group">
            <label>Tanggal Kontrak Awal:</label>
            <input type="date" name="tanggal_kontrak_awal" class="form-control" value="<?= $pegawai->tanggal_kontrak_awal ?>" required>
        </div>
        <div class="form-group">
            <label>Durasi Kontrak (Bulan):</label>
            <input type="number" name="durasi_kontrak" class="form-control" value="<?= $pegawai->durasi_kontrak ?>" required>
        </div>
        <div class="form-group">
            <label for="nama_bank_id">Nama Bank</label>
            <select name="nama_bank_id" id="nama_bank_id" class="form-control" required>
                <option value="">-- Pilih Bank --</option>
                <?php foreach ($rekening_bank as $bank): ?>
                    <option value="<?= $bank->id ?>" <?= $bank->id == $pegawai->nama_bank_id ? 'selected' : '' ?>>
                        <?= $bank->nama_bank ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nomor_rekening">Nomor Rekening</label>
            <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control" 
                placeholder="Nomor Rekening" value="<?= $pegawai->nomor_rekening ?>" required>
        </div>


        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
