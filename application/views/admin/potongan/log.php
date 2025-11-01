<div class="container mt-4">
    <h2><?= $title ?></h2>

<form method="get" class="mb-4">
    <div class="row align-items-end">
        <div class="col-md-4">
            <label for="bulan">Pilih Bulan:</label>
            <select name="bulan" id="bulan" class="form-control">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= ($bulan == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="tahun">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="form-control">
                <?php for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++): ?>
                    <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>>
                        <?= $y ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="<?= site_url('potongan/input') ?>" class="btn btn-success">Tambah Potongan</a>
        </div>
    </div>
</form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th>Nilai Potongan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($log_potongan)): ?>
                <?php foreach ($log_potongan as $index => $potongan): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d-m-Y', strtotime($potongan->tanggal)) ?></td>
                        <td><?= htmlspecialchars($potongan->nama_pegawai) ?></td>
                        <td>Rp <?= number_format($potongan->nilai, 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($potongan->keterangan) ?></td>
                        <td>
                            <button 
                                class="btn btn-warning btn-sm edit-btn" 
                                data-id="<?= $potongan->id ?>" 
                                data-tanggal="<?= $potongan->tanggal ?>" 
                                data-nilai="<?= $potongan->nilai ?>" 
                                data-keterangan="<?= htmlspecialchars($potongan->keterangan) ?>">Edit</button>
                            <button 
                                class="btn btn-danger btn-sm delete-btn" 
                                data-id="<?= $potongan->id ?>">Hapus</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data potongan untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="editForm" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Potongan</h5>
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
                        <label for="editKeterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="editKeterangan" rows="3" required></textarea>
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

    // Delete Button
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        if (confirm('Apakah Anda yakin ingin menghapus potongan ini?')) {
            fetch(`<?= site_url('potongan/delete') ?>`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});


    // Handle Edit Form Submission
    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(`<?= site_url('potongan/edit') ?>`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
