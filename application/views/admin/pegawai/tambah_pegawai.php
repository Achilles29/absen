<div class="container mt-4">
    <h2>Tambah Data Pegawai</h2>
    <form method="post" action="<?= site_url('admin/tambah_pegawai') ?>">
        <div class="form-group">
            <label>Kode User:</label>
            <select name="kode_user" class="form-control" required>
                <option value="">-- Pilih Kode User --</option>
                <?php foreach ($kode_user as $user): ?>
                    <option value="<?= $user->kode_user ?>"><?= $user->kode_user ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
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
            <label>Jabatan 1:</label>
            <select name="jabatan1_id" class="form-control" required>
                <option value="">-- Pilih Jabatan 1 --</option>
                <?php foreach ($jabatan as $row): ?>
                    <option value="<?= $row->id ?>"><?= $row->nama_jabatan ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Jabatan 2:</label>
            <select name="jabatan2_id" class="form-control">
                <option value="">-- Pilih Jabatan 2 (Opsional) --</option>
                <?php foreach ($jabatan as $row): ?>
                    <option value="<?= $row->id ?>"><?= $row->nama_jabatan ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Gaji Pokok:</label>
            <input type="number" name="gaji_pokok" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tunjangan:</label>
            <input type="number" name="tunjangan" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tambahan Lain:</label>
            <input type="number" name="tambahan_lain" class="form-control">
        </div>
        <div class="form-group">
            <label>Tanggal Kontrak Awal:</label>
            <input type="date" name="tanggal_kontrak_awal" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Durasi Kontrak (Bulan):</label>
            <input type="number" name="durasi_kontrak" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nama Bank:</label>
            <select name="nama_bank_id" class="form-control" required>
                <option value="">-- Pilih Bank --</option>
                <?php foreach ($rekening_bank as $bank): ?>
                    <option value="<?= $bank->id ?>"><?= $bank->nama_bank ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Nomor Rekening:</label>
            <input type="text" name="nomor_rekening" class="form-control">
        </div>
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
