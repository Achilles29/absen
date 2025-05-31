<div class="container mt-4">
    <h2><?= $title ?></h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <!-- Form Input Nilai Lembur -->
    <form method="post" action="">
        <div class="form-group">
            <label for="nilai_per_jam">Nilai Lembur Per Jam (Rp)</label>
            <input type="number" class="form-control" name="nilai_per_jam" id="nilai_per_jam" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <!-- Tabel Nilai Lembur -->
    <div class="mt-4">
        <h4>Daftar Nilai Lembur</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nilai Per Jam (Rp)</th>
                    <th>Waktu Input</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($nilai_lembur_list)): ?>
                    <?php foreach ($nilai_lembur_list as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>Rp <?= number_format($row->nilai_per_jam, 2, ',', '.') ?></td>
                            <td><?= date('d M Y H:i:s', strtotime($row->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Belum ada nilai lembur yang diinput.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
