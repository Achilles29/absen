<div class="container mt-4">
    <h2><?= $title ?></h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="form-inline" method="get" action="<?= site_url('deposit/log') ?>">
            <label for="bulan" class="mr-2">Bulan:</label>
            <select name="bulan" id="bulan" class="form-control mr-2">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= ($bulan == $i) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
            <label for="tahun" class="mr-2">Tahun:</label>
            <select name="tahun" id="tahun" class="form-control mr-2">
                <?php for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++): ?>
                    <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <a href="<?= site_url('deposit/input') ?>" class="btn btn-success ml-2">Tambah Deposit</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th>Jenis</th>
                <th>Nilai</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($log_deposit)): ?>
                <?php foreach ($log_deposit as $index => $deposit): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d-m-Y', strtotime($deposit->tanggal)) ?></td>
                        <td><?= $deposit->nama_pegawai ?></td>
                        <td><?= ucfirst($deposit->jenis) ?></td>
                        <td class="text-right">Rp <?= number_format($deposit->nilai, 2, ',', '.') ?></td>
                        <td><?= $deposit->keterangan ?></td>
                        <td>
                            <button 
                                class="btn btn-warning btn-sm edit-btn"
                                data-id="<?= $deposit->id ?>"
                                data-tanggal="<?= $deposit->tanggal ?>"
                                data-nilai="<?= $deposit->nilai ?>"
                                data-jenis="<?= $deposit->jenis ?>"
                                data-keterangan="<?= htmlspecialchars($deposit->keterangan) ?>">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $deposit->id ?>">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data deposit untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="editForm" method="post" action="<?= site_url('deposit/edit_deposit') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Deposit</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="form-group">
                        <label for="editTanggal">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="editTanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="editNilai">Nilai</label>
                        <input type="number" class="form-control" name="nilai" id="editNilai" required>
                    </div>
                    <div class="form-group">
                        <label for="editJenis">Jenis</label>
                        <select class="form-control" name="jenis" id="editJenis" required>
                            <option value="setor">Setor</option>
                            <option value="tarik">Tarik</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editKeterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="editKeterangan" rows="3"></textarea>
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
    // Edit Button
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const tanggal = this.dataset.tanggal;
            const nilai = this.dataset.nilai;
            const keterangan = this.dataset.keterangan;

            document.getElementById('editId').value = id;
            document.getElementById('editTanggal').value = tanggal;
            document.getElementById('editNilai').value = nilai;
            document.getElementById('editKeterangan').value = keterangan;

            $('#editModal').modal('show');
        });
    });

    // Form Edit Submit
    const editForm = document.getElementById('editForm');
    editForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Hindari reload halaman

        const formData = new FormData(editForm);

        fetch('<?= site_url('deposit/edit_deposit') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                $('#editModal').modal('hide');
                location.reload(); // Reload halaman untuk memperbarui tampilan
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Delete Button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            if (confirm('Apakah Anda yakin ingin menghapus data deposit ini?')) {
                fetch('<?= site_url('deposit/delete_deposit') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload(); // Reload halaman untuk memperbarui tampilan
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});

</script>
