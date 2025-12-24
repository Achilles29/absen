<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-maroon text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-calendar-week me-2"></i> Rekap Uang Makan Mingguan
            </h5>
        </div>

        <div class="card-body">
            <!-- 🔸 Filter Bulan & Tahun -->
            <form method="get" class="row g-3 mb-4 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Bulan</label>
                    <select name="bulan" class="form-select shadow-sm">
                        <?php
                        $nama_bulan = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember'
                        ];
                        foreach ($nama_bulan as $key => $val): ?>
                            <option value="<?= $key ?>" <?= $bulan == $key ? 'selected' : '' ?>><?= $val ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Tahun</label>
                    <select name="tahun" class="form-select shadow-sm">
                        <?php for ($t = date('Y') - 3; $t <= date('Y') + 1; $t++): ?>
                            <option value="<?= $t ?>" <?= $tahun == $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-maroon shadow-sm">
                        <i class="fas fa-filter me-1"></i> Tampilkan
                    </button>
                </div>
            </form>

            <!-- 🔸 Tabel Rekap -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="bg-gradient-maroon text-white text-center sticky-top">
                        <tr>
                            <th>Nama Pegawai</th>
                            <?php foreach ($minggu_ke as $i => $m): ?>
                                <th>
                                    Minggu <?= $i + 1 ?><br>
                                    <small>(<?= date('d/m', strtotime($m['mulai'])) ?> - <?= date('d/m', strtotime($m['selesai'])) ?>)</small>
                                </th>
                            <?php endforeach; ?>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($rekap)): ?>
                            <?php foreach ($rekap as $p): ?>
                                <tr>
                                    <td class="fw-semibold"><?= strtoupper($p['nama']) ?></td>
                                    <?php foreach ($minggu_ke as $i => $m): ?>
                                        <td class="text-end">
                                            <?= !empty($p['minggu'][$i + 1])
                                                ? 'Rp ' . number_format($p['minggu'][$i + 1], 0, ',', '.')
                                                : '-' ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="text-end fw-bold text-success">
                                        Rp <?= number_format($p['total'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= count($minggu_ke) + 2 ?>" class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle me-1"></i> Tidak ada data untuk bulan ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <!-- 🔸 Total Keseluruhan -->
                    <?php if (!empty($rekap)): ?>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td class="text-center">TOTAL</td>
                                <?php foreach ($total_per_minggu as $t): ?>
                                    <td class="text-end text-primary">Rp <?= number_format($t, 0, ',', '.') ?></td>
                                <?php endforeach; ?>
                                <td class="text-end text-maroon">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-maroon {
        background-color: #800000 !important;
    }

    .bg-gradient-maroon {
        background: linear-gradient(90deg, #800000 0%, #9b1c1c 100%);
    }

    .btn-maroon {
        background-color: #800000;
        color: #fff;
    }

    .btn-maroon:hover {
        background-color: #9b1c1c;
        color: #fff;
    }

    .table th,
    .table td {
        font-size: 0.9rem;
        color: #000;
        vertical-align: middle;
    }

    .table thead th {
        font-weight: bold;
    }

    .shadow-sm {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1) !important;
    }

    .text-maroon {
        color: #800000 !important;
    }

    .text-primary {
        color: #007bff !important;
    }
</style>