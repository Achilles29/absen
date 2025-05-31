<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="get" class="form-inline mb-3">
        <div class="form-group mr-2">
            <label for="bulan" class="mr-2">Bulan:</label>
            <select name="bulan" id="bulan" class="form-control">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" 
                        <?= ($bulan == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group mr-2">
            <label for="tahun" class="mr-2">Tahun:</label>
            <select name="tahun" id="tahun" class="form-control">
                <?php for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++): ?>
                    <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>>
                        <?= $y ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="<?= site_url('kasbon/input') ?>" class="btn btn-success ml-2">Input Kasbon</a>
    </form>

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
            <?php if (!empty($log_kasbon)): ?>
                <?php foreach ($log_kasbon as $index => $kasbon): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d-m-Y', strtotime($kasbon->tanggal)) ?></td>
                        <td><?= $kasbon->nama ?></td>
                        <td><?= ucfirst($kasbon->jenis) ?></td>
                        <td class="text-right">Rp <?= number_format($kasbon->nilai, 2, ',', '.') ?></td>
                        <td><?= $kasbon->keterangan ?></td>
                        <td>
                            <button 
                                class="btn btn-sm btn-warning edit-btn" 
                                data-id="<?= $kasbon->id ?>" 
                                data-tanggal="<?= $kasbon->tanggal ?>" 
                                data-nilai="<?= $kasbon->nilai ?>" 
                                data-jenis="<?= $kasbon->jenis ?>" 
                                data-keterangan="<?= $kasbon->keterangan ?>"
                            >
                                Edit
                            </button>
                            <button 
                                class="btn btn-sm btn-danger delete-btn" 
                                data-id="<?= $kasbon->id ?>"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data kasbon untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="editForm" method="post" action="<?= site_url('kasbon/update') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kasbon</h5>
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
                        <input type="number" step="0.01" class="form-control" name="nilai" id="editNilai" required>
                    </div>
                    <div class="form-group">
                        <label for="editJenis">Jenis</label>
                        <select class="form-control" name="jenis" id="editJenis">
                            <option value="kasbon">Kasbon</option>
                            <option value="bayar">Bayar</option>
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
            const jenis = this.dataset.jenis;
            const keterangan = this.dataset.keterangan;

            document.getElementById('editId').value = id;
            document.getElementById('editTanggal').value = tanggal;
            document.getElementById('editNilai').value = nilai;
            document.getElementById('editJenis').value = jenis;
            document.getElementById('editKeterangan').value = keterangan;

            $('#editModal').modal('show');
        });
    });

    // Delete Button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            if (confirm('Apakah Anda yakin ingin menghapus data kasbon ini?')) {
                fetch(`<?= site_url('kasbon/delete') ?>`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload();
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
