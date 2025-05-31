<div class="container mt-4">
    <h2><?= $title ?></h2>
    <h4>Nama Pegawai: <?= $detail_lembur[0]->nama_pegawai ?? '-' ?></h4>
<h4>Bulan: <?= date('F Y', strtotime($tahun . '-' . $bulan . '-01')) ?></h4>

<form method="get" class="mb-4">
    <input type="hidden" name="pegawai_id" value="<?= $pegawai_id ?>">
    <div class="row">
        <div class="col-md-4">
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
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>



    <!-- Tabel Lembur -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nilai Lembur (Rp)</th>
                <th>Lama Lembur (Jam)</th>
                <th>Alasan</th>
                <th>Total Uang Lembur (Rp)</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $total_lama_lembur = 0;
                $total_uang_lembur = 0;
            ?>
            <?php if (!empty($detail_lembur)): ?>
                <?php foreach ($detail_lembur as $index => $row): ?>
                    <?php 
                        $total_lama_lembur += $row->lama_lembur;
                        $total_uang_lembur += $row->total_gaji_lembur;
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
                        <td class="text-right">Rp <?= number_format($row->nilai_per_jam, 2, ',', '.') ?></td>
                        <td class="text-right"><?= number_format($row->lama_lembur, 2) ?> Jam</td>
                        <td><?= htmlspecialchars($row->alasan) ?></td>
                        <td class="text-right">Rp <?= number_format($row->total_gaji_lembur, 2, ',', '.') ?></td>
                        <td class="text-right">
                            <button 
                                class="btn btn-sm btn-warning edit-btn" 
                                data-id="<?= $row->id ?>" 
                                data-tanggal="<?= $row->tanggal ?>" 
                                data-lama="<?= $row->lama_lembur ?>" 
                                data-alasan="<?= htmlspecialchars($row->alasan) ?>"
                                data-nilai-lembur-id="<?= $row->nilai_lembur_id ?>">
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
                <!-- Baris Jumlah -->
                <tr class="font-weight-bold">
                    <td colspan="3" class="text-center">Total</td>
                    <td class="text-right"><?= number_format($total_lama_lembur, 2) ?> Jam</td>
                    <td></td>
                    <td class="text-right">Rp <?= number_format($total_uang_lembur, 2, ',', '.') ?></td>
                    <td></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data lembur untuk bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= site_url('lembur') ?>" class="btn btn-secondary">Kembali</a>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
    <form id="editForm" method="post" action="<?= site_url('lembur/update') ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Lembur</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editId">
                <input type="hidden" name="pegawai_id" value="<?= $pegawai_id ?>">
                <input type="hidden" name="bulan" value="<?= $bulan ?>">
                <input type="hidden" name="tahun" value="<?= $tahun ?>">

                <div class="form-group">
                    <label for="editTanggal">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" id="editTanggal" required>
                </div>
                <div class="form-group">
                    <label for="editLama">Lama Lembur (Jam)</label>
                    <input type="number" step="0.01" class="form-control" name="lama_lembur" id="editLama" required>
                </div>
                <div class="form-group">
                    <label for="editNilaiLembur">Nilai Lembur</label>
                    <select class="form-control" name="nilai_lembur_id" id="editNilaiLembur" required>
                        <option value="">-- Pilih Nilai Lembur --</option>
                        <?php foreach ($nilai_lembur_list as $nilai): ?>
                            <option value="<?= $nilai->id ?>">Rp <?= number_format($nilai->nilai_per_jam, 2, ',', '.') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editAlasan">Alasan</label>
                    <textarea class="form-control" name="alasan" id="editAlasan" rows="3" required></textarea>
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
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const tanggal = this.dataset.tanggal;
            const lama = this.dataset.lama;
            const alasan = this.dataset.alasan;
            const nilaiLemburId = this.dataset.nilaiLemburId;

            document.getElementById('editId').value = id;
            document.getElementById('editTanggal').value = tanggal;
            document.getElementById('editLama').value = lama;
            document.getElementById('editAlasan').value = alasan;

            // Set dropdown nilai lembur sesuai nilai lembur ID
            const nilaiLemburDropdown = document.getElementById('editNilaiLembur');
            nilaiLemburDropdown.value = nilaiLemburId;

            $('#editModal').modal('show');
        });
    });
    // Delete Button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            if (confirm('Apakah Anda yakin ingin menghapus data lembur ini?')) {
                fetch(`<?= site_url('lembur/delete_lembur') ?>`, {
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

