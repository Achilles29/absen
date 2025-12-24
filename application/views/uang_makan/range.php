<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-maroon text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>Rekap Uang Makan (Rentang Tanggal)</h5>
        </div>

        <div class="card-body">
            <!-- Filter Tanggal -->
            <form method="get" class="row g-3 mb-4 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal Awal</label>
                    <input type="date" name="awal" class="form-control shadow-sm" value="<?= $tanggal_awal ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal Akhir</label>
                    <input type="date" name="akhir" class="form-control shadow-sm" value="<?= $tanggal_akhir ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-maroon shadow-sm">
                        <i class="fas fa-filter me-1"></i> Tampilkan
                    </button>
                </div>
            </form>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="bg-gradient-maroon text-white text-center sticky-top">
                        <tr>
                            <th>Nama Pegawai</th>
                            <?php foreach ($periode as $tgl): ?>
                                <th><?= date('d/m', strtotime($tgl)) ?></th>
                            <?php endforeach; ?>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rekap as $r): ?>
                            <tr>
                                <td class="fw-semibold"><?= strtoupper($r['nama']) ?></td>
                                <?php foreach ($periode as $tgl): ?>
                                    <td class="text-end">
                                        <?= ($r['tanggal'][$tgl] > 0)
                                            ? 'Rp ' . number_format($r['tanggal'][$tgl], 0, ',', '.')
                                            : '-' ?>
                                    </td>
                                <?php endforeach; ?>
                                <td class="text-end fw-bold text-success">
                                    Rp <?= number_format($r['total'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td class="text-center">TOTAL</td>
                            <?php foreach ($total_per_tanggal as $v): ?>
                                <td class="text-end text-primary">Rp <?= number_format($v, 0, ',', '.') ?></td>
                            <?php endforeach; ?>
                            <td class="text-end text-maroon">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
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

    .text-maroon {
        color: #800000 !important;
    }

    .text-primary {
        color: #007bff !important;
    }
</style>