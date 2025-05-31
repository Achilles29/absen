<div class="container mt-4">
    <h2><?= $title ?></h2>
    
    <!-- Notifikasi -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>
    
    <!-- Form Upload -->
    <form method="post" action="<?= site_url('jadwal_shift/import') ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label>Upload File Excel:</label>
            <input type="file" name="excel_file" class="form-control" accept=".xls,.xlsx" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <!-- Download Template -->
    <div class="mt-4">
        <a href="<?= site_url('jadwal_shift/download_template') ?>" class="btn btn-success">Download Template CSV</a>
    </div>
</div>
