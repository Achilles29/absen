<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="post" action="<?= site_url('bank/add') ?>" class="form-inline mb-3">
        <input type="text" name="nama_bank" class="form-control mr-2" placeholder="Nama Bank" required>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bank</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($banks)): ?>
                <?php foreach ($banks as $index => $bank): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($bank->nama_bank) ?></td>
                        <td>
                            <a href="<?= site_url('bank/delete/' . $bank->id) ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus rekening bank ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Tidak ada rekening bank.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
