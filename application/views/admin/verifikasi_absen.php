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

    <!-- Tabel Verifikasi -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>Shift</th>
                <th>Jenis Absen</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <?php if ($this->session->userdata('role') === 'admin'): ?>
                    <th>Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($absensi_pending as $absen): ?>
                <tr>
                    <td><?= $absen->nama_pegawai ?></td>
                    <td><?= $absen->nama_shift ?></td>
                    <td><?= ucfirst($absen->jenis_absen) ?></td>
                    <td><?= $absen->tanggal ?></td>
                    <td><?= $absen->waktu ?></td>
                    <?php if ($this->session->userdata('role') === 'admin'): ?>
                        <td>
                            <a href="<?= site_url('admin/proses_verifikasi/' . $absen->id . '/verified') ?>" class="btn btn-success btn-sm">Verifikasi</a>
                            <a href="<?= site_url('admin/proses_verifikasi/' . $absen->id . '/rejected') ?>" class="btn btn-danger btn-sm">Tolak</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
