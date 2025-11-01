<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="get" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Bulan:</label>
        <select name="bulan" id="bulan" class="form-control mr-2">
            <?php foreach ($bulan_dropdown as $b): ?>
                <option value="<?= $b ?>" <?= ($bulan == $b) ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $b, 10)) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tahun" class="mr-2">Tahun:</label>
        <select name="tahun" id="tahun" class="form-control mr-2">
            <?php foreach ($tahun_dropdown as $t): ?>
                <option value="<?= $t ?>" <?= ($tahun == $t) ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Jenis Absen</th>
                <th>Waktu</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($log_absensi)): ?>
                <?php foreach ($log_absensi as $index => $log): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $log->nama_pegawai ?></td>
                        <td><?= $log->tanggal ?></td>
                        <td><?= $log->jenis_absen ?></td>
                        <td><?= $log->waktu ?></td>
                        <td><?= $log->latitude ?></td>
                        <td><?= $log->longitude ?></td>
                        <td>
                            <!-- <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $log->id ?>"
                                    data-tanggal="<?= $log->tanggal ?>" data-jenis="<?= $log->jenis_absen ?>"
                                    data-waktu="<?= $log->waktu ?>" data-latitude="<?= $log->latitude ?>"
                                    data-longitude="<?= $log->longitude ?>">Edit</button> -->
                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $log->id ?>">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data log absensi</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Log Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="form-group">
                        <label for="editTanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="editTanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editJenis">Jenis Absen</label>
                        <input type="text" name="jenis_absen" id="editJenis" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editWaktu">Waktu</label>
                        <input type="time" name="waktu" id="editWaktu" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editLatitude">Latitude</label>
                        <input type="text" name="latitude" id="editLatitude" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editLongitude">Longitude</label>
                        <input type="text" name="longitude" id="editLongitude" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Edit
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const modal = $('#editModal');
            modal.find('#editId').val(this.dataset.id);
            modal.find('#editTanggal').val(this.dataset.tanggal);
            modal.find('#editJenis').val(this.dataset.jenis);
            modal.find('#editWaktu').val(this.dataset.waktu);
            modal.find('#editLatitude').val(this.dataset.latitude);
            modal.find('#editLongitude').val(this.dataset.longitude);
            modal.modal('show');
        });
    });

    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();
        fetch('<?= site_url('admin/edit_log_absensi') ?>', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') location.reload();
        });
    });

    // Delete
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (confirm('Hapus log absensi ini?')) {
                fetch('<?= site_url('admin/delete_log_absensi') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: this.dataset.id })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === 'success') location.reload();
                });
            }
        });
    });
});
</script>
