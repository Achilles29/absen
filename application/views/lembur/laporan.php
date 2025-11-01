<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="get" action="<?= site_url('lembur/laporan') ?>" class="form-inline mb-3">
        <label for="bulan" class="mr-2">Filter Bulan:</label>
        <select name="bulan" id="bulan" class="form-control mr-2">
            <?php
            $currentYear = date('Y');
            $currentMonth = date('m');
            for ($i = 0; $i < 12; $i++) {
                $month = date('Y-m', strtotime("-$i month", strtotime("$currentYear-$currentMonth-01")));
                $selected = ($bulan == $month) ? 'selected' : '';
                echo "<option value='$month' $selected>" . date('F Y', strtotime($month . '-01')) . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <?php if (!empty($laporan_lembur)): ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Pegawai</th>
            <th>Nilai Lembur (Rp)</th>
            <th>Lama Lembur (Jam)</th>
            <th>Alasan</th>
            <th>Total Uang Lembur</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total_lama_lembur = 0;
        $total_uang_lembur = 0;
        foreach ($laporan_lembur as $index => $lembur): 
            $total_lama_lembur += $lembur->lama_lembur;
            $total_uang_lembur += $lembur->total_gaji_lembur;
        ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= date('d-m-Y', strtotime($lembur->tanggal)) ?></td>
                <td><?= $lembur->nama_pegawai ?></td>
                <td class="text-right">Rp <?= number_format($lembur->nilai_per_jam, 2, ',', '.') ?></td>
                <td class="text-right"><?= number_format($lembur->lama_lembur, 2) ?> Jam</td>
                <td><?= $lembur->alasan ?></td>
                <td class="text-right">Rp <?= number_format($lembur->total_gaji_lembur, 2, ',', '.') ?></td>
                <td class="text-center">
                    <div class="btn-group">
                        <button 
                            class="btn btn-sm btn-warning edit-btn" 
                            data-id="<?= $lembur->id ?>" 
                            data-tanggal="<?= $lembur->tanggal ?>" 
                            data-lama="<?= $lembur->lama_lembur ?>" 
                            data-alasan="<?= htmlspecialchars($lembur->alasan) ?>"
                            data-nilai-lembur-id="<?= $lembur->nilai_lembur_id ?>">
                            Edit
                        </button>
                        <button 
                            class="btn btn-sm btn-danger delete-btn" 
                            data-id="<?= $lembur->id ?>">
                            Hapus
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-right">Total</th>
            <th class="text-right"><?= number_format($total_lama_lembur, 2) ?> Jam</th>
            <th></th>
            <th class="text-right">Rp <?= number_format($total_uang_lembur, 2, ',', '.') ?></th>
            <th></th>
        </tr>
    </tfoot>
</table>

    <?php else: ?>
        <div class="alert alert-info">Tidak ada data lembur untuk bulan ini.</div>
    <?php endif; ?>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="editForm" method="post" action="<?= site_url('lembur/update2') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Lembur</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">

                    <!-- Input Tanggal -->
                    <div class="form-group">
                        <label for="editTanggal">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="editTanggal" required>
                    </div>

                    <!-- Dropdown Nilai Lembur -->
                    <div class="form-group">
                        <label for="editNilaiLembur">Nilai Lembur (Rp)</label>
                        <select class="form-control" name="nilai_lembur_id" id="editNilaiLembur" required>
                            <option value="">-- Pilih Nilai Lembur --</option>
                            <?php foreach ($nilai_lembur_list as $nilai): ?>
                                <option value="<?= $nilai->id ?>">Rp <?= number_format($nilai->nilai_per_jam, 2, ',', '.') ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <!-- Input Lama Lembur -->
                    <div class="form-group">
                        <label for="editLama">Lama Lembur (Jam)</label>
                        <input type="number" step="0.01" class="form-control" name="lama_lembur" id="editLama" required>
                    </div>

                    <!-- Input Alasan -->
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

            // Set nilai lembur pada dropdown
            const nilaiLemburDropdown = document.getElementById('editNilaiLembur');
            nilaiLemburDropdown.value = nilaiLemburId || ''; // Jika null, set ke default kosong

            $('#editModal').modal('show');
        });
    });

    // Fungsi Delete
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
