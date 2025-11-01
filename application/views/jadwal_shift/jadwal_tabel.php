<style>
.table-container {
    overflow-x: auto;
    overflow-y: auto;
    max-height: 500px; /* Sesuaikan tinggi maksimum */
    border: 1px solid #ddd;
}

table {
    border-collapse: collapse;
    width: 100%;
}

thead th, tbody td {
    border: 1px solid #ddd;
}

thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #f8f9fa; /* Warna background header */
    color: #333; /* Warna teks header */
    border-bottom: 2px solid #ddd;
    text-align: center;
    font-weight: bold;
}

.freeze-column {
    position: sticky;
    left: 0;
    background-color: #f8f9fa;
    z-index: 10;
    border-right: 2px solid #ddd;
}

tbody .freeze-column {
    background-color: #fff;
    border-right: 2px solid #ddd;
}

.bold-text {
    font-weight: bold;
}

.summary-row {
    background-color: #f8f9fa;
    font-weight: bold;
}

.summary-row td {
    background-color: #d1ecf1;
    color: #0c5460;
}
</style>

<div class="container-fluid mt-4">
    <h2><?= $title ?></h2>

    <!-- Filter Bulan dan Tahun -->
    <form method="get" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <label for="bulan">Pilih Bulan:</label>
                <select name="bulan" id="bulan" class="form-control">
                    <?php
                    $months = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                    foreach ($months as $key => $value): ?>
                        <option value="<?= $key ?>" <?= ($selected_month == $key) ? 'selected' : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="tahun">Pilih Tahun:</label>
                <select name="tahun" id="tahun" class="form-control">
                    <?php for ($year = date('Y') - 5; $year <= date('Y') + 5; $year++): ?>
                        <option value="<?= $year ?>" <?= ($selected_year == $year) ? 'selected' : '' ?>><?= $year ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <!-- Tabel Jadwal Shift -->
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="freeze-column">Nama Pegawai</th>
                    <th>Divisi</th>
                    <?php for ($i = 1; $i <= date('t', strtotime("$selected_year-$selected_month")); $i++): ?>
                        <th class="text-center"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                    <?php endfor; ?>
                    <th class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_days = date('t', strtotime("$selected_year-$selected_month"));
                $current_divisi = '';
                $divisi_totals = [];

                foreach ($pegawai as $p): 
                    // Jika divisi berganti, tambahkan baris jumlah per divisi
                    if ($current_divisi != $p->nama_divisi && $current_divisi != ''): ?>
                        <tr class="summary-row">
                            <td colspan="2">Jumlah <?= $current_divisi ?></td>
                            <?php for ($i = 1; $i <= $total_days; $i++): 
                                $current_date = "$selected_year-$selected_month-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                                $jumlah = $divisi_totals[$current_divisi][$current_date] ?? 0;
                            ?>
                                <td class="text-center"><?= $jumlah ?></td>
                            <?php endfor; ?>
                            <td></td>
                        </tr>
                    <?php endif;

                    $current_divisi = $p->nama_divisi; ?>
                    <tr>
                        <td class="freeze-column"><?= $p->nama ?></td>
                        <td><?= $p->nama_divisi ?></td>
                        <?php 
                        $pegawai_total = 0;
                        for ($i = 1; $i <= $total_days; $i++): 
                            $current_date = "$selected_year-$selected_month-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $shift = $jadwal_shift[$p->id][$current_date] ?? '';
                            if (!empty($shift)) $pegawai_total++;
                        ?>
                            <td class="text-center editable-cell" 
                                contenteditable="true" 
                                data-pegawai="<?= $p->id ?>" 
                                data-tanggal="<?= $current_date ?>">
                                <?= $shift ?>
                            </td>
                        <?php endfor; ?>
                        <td class="text-center"><?= $pegawai_total ?></td>
                    </tr>
                <?php endforeach; ?>

                <!-- Tambahkan baris total terakhir untuk divisi terakhir -->
                <tr class="summary-row">
                    <td colspan="2">Jumlah <?= $current_divisi ?></td>
                    <?php for ($i = 1; $i <= $total_days; $i++): 
                        $current_date = "$selected_year-$selected_month-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                        $jumlah = $divisi_totals[$current_divisi][$current_date] ?? 0;
                    ?>
                        <td class="text-center"><?= $jumlah ?></td>
                    <?php endfor; ?>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.editable-cell').forEach(cell => {
        cell.addEventListener('blur', function() {
            const pegawai_id = this.getAttribute('data-pegawai');
            const tanggal = this.getAttribute('data-tanggal');
            const kode_shift = this.textContent.trim();

            if (kode_shift) {
                fetch('<?= site_url('jadwal_shift/update_jadwal_inline') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ pegawai_id, tanggal, kode_shift })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        console.log(data.message);
                    } else {
                        alert(data.message);
                        this.textContent = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data.');
                    this.textContent = '';
                });
            }
        });
    });
});
</script>
