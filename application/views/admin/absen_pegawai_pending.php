<div class="container mt-4">
    <h2><?= $title ?></h2>

    <!-- Notifikasi -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Form Absen -->
    <form method="post" action="<?= site_url('admin/absen_pegawai_pending') ?>">
        <div class="form-group">
            <label>Pegawai:</label>
            <select name="pegawai_id" class="form-control" required>
                <option value="">-- Pilih Pegawai --</option>
                <?php foreach ($pegawai as $row): ?>
                    <option value="<?= $row->id ?>"><?= $row->nama ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Shift:</label>
            <select name="shift_id" class="form-control" required>
                <option value="">-- Pilih Shift --</option>
                <?php foreach ($shift as $row): ?>
                    <option value="<?= $row->id ?>"><?= $row->kode_shift ?> (<?= $row->jam_mulai ?> - <?= $row->jam_selesai ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Absen:</label>
            <select name="jenis_absen" class="form-control" required>
                <option value="masuk">Absen Masuk</option>
                <option value="pulang">Absen Pulang</option>
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal Absen:</label>
            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label>Waktu Absen:</label>
            <input type="time" name="waktu" class="form-control" value="<?= date('H:i') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Ajukan Absen</button>
    </form>
</div>
