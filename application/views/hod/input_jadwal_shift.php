<div class="container mt-4">
    <h2><?= $title ?></h2>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('jadwal_shift/input_jadwal_shift') ?>">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pegawai_id">Pilih Pegawai</label>
            <select name="pegawai_id" class="form-control" required>
                <option value="">-- Pilih Pegawai --</option>
                <?php foreach ($pegawai as $p): ?>
                    <option value="<?= $p->id ?>"><?= $p->nama ?> - <?= $p->nama_divisi ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="shift_id">Pilih Shift</label>
            <select name="shift_id" class="form-control" required>
                <option value="">-- Pilih Shift --</option>
                <?php foreach ($shifts as $shift): ?>
                    <option value="<?= $shift->id ?>"><?= $shift->kode_shift ?> (<?= $shift->jam_mulai ?> - <?= $shift->jam_selesai ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
