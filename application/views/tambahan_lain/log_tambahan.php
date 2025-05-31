<div class="container mt-4">
    <h2><?= $title ?></h2>

<form method="get" class="mb-3">
    <div class="row align-items-end">
        <div class="col-md-3">
            <label for="bulan">Pilih Bulan:</label>
            <select name="bulan" id="bulan" class="form-control">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" 
                        <?= ($bulan == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="tahun">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="form-control">
                <?php for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++): ?>
                    <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>>
                        <?= $y ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-3">
            <a href="<?= site_url('tambahan_lain/input') ?>" class="btn btn-success w-100">Input Tambahan</a>
        </div>
    </div>
</form>

    <br><table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Nilai Tambahan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($log_tambahan)): ?>
                <?php foreach ($log_tambahan as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $row->nama_pegawai ?></td>
                        <td><?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
                        <td>Rp <?= number_format($row->nilai_tambahan, 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row->keterangan) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" 
                                    data-id="<?= $row->id ?>" 
                                    data-tanggal="<?= $row->tanggal ?>" 
                                    data-nilai="<?= $row->nilai_tambahan ?>" 
                                    data-keterangan="<?= htmlspecialchars($row->keterangan) ?>">
                                Edit
                            </button>
                            <button 
                                class="btn btn-sm btn-danger delete-btn" 
                                data-id="<?= $row->id ?>">
                                Hapus
                            </button>


                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data tambahan untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="editForm" method="post" action="<?= site_url('tambahan_lain/edit_tambahan') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Tambahan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="form-group">
                        <label for="editTanggal">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="editTanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="editNilai">Nilai Tambahan</label>
                        <input type="number" step="0.01" class="form-control" name="nilai_tambahan" id="editNilai" required>
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
    // document.querySelectorAll('.delete-btn').forEach(btn => {
    //     btn.addEventListener('click', function () {
    //         const id = this.dataset.id;
    //         if (confirm('Apakah Anda yakin ingin menghapus data tambahan ini?')) {
    //             fetch(`<?= site_url('tambahan_lain/hapus_tambahan/') ?>${id}`, { method: 'GET' })
    //                 .then(response => response.json())
    //                 .then(data => {
    //                     if (data.status === 'success') {
    //                         alert(data.message);
    //                         location.reload();
    //                     } else {
    //                         alert(data.message);
    //                     }
    //                 })
    //                 .catch(error => console.error('Error:', error));
    //         }
    //     });
    // });
});
document.addEventListener('DOMContentLoaded', function () {
    // Delete Button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            if (confirm('Apakah Anda yakin ingin menghapus data tambahan ini?')) {
                fetch(`<?= site_url('tambahan_lain/hapus_tambahan/') ?>${id}`, { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            // Hapus baris tabel secara langsung
                            this.closest('tr').remove();
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
