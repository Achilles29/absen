<div class="container mt-4">
    <h2>Input Lembur Pegawai</h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('lembur/add') ?>">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" required>
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
            <label for="nilai_lembur_id">Nilai Lembur</label>
            <select name="nilai_lembur_id" class="form-control" required>
                <option value="">-- Pilih Nilai Lembur --</option>
                <?php foreach ($nilai_lembur_list as $row): ?>
                    <option value="<?= $row->id ?>">Rp <?= number_format($row->nilai_per_jam, 2, ',', '.') ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="lama_lembur">Lama Lembur (Jam)</label>
            <input type="number" step="1" min="1" class="form-control" name="lama_lembur" id="lama_lembur" required>
        </div>


        <div class="form-group">
            <label for="alasan">Alasan Lembur</label>
            <textarea class="form-control" name="alasan" id="alasan" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
