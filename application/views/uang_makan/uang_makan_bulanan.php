<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-maroon text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-utensils me-2"></i> Rekap Uang Makan Bulanan</h5>
        </div>

        <div class="card-body">
            <!-- Filter Bulan -->
            <form method="get" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Bulan</label>
                    <input type="month" name="periode" class="form-control shadow-sm"
                        value="<?= $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) ?>"
                        onchange="location.href='?bulan='+this.value.split('-')[1]+'&tahun='+this.value.split('-')[0]">
                </div>
            </form>

            <!-- Tabel Rekap -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="bg-gradient-maroon text-white text-center sticky-top">
                        <tr>
                            <th rowspan="2" style="width:200px;">Nama Pegawai</th>
                            <th colspan="<?= $jumlah_hari ?>">Tanggal</th>
                            <th rowspan="2" style="width:120px;">Total</th>
                        </tr>
                        <tr>
                            <?php for ($i = 1; $i <= $jumlah_hari; $i++): ?>
                                <th><?= $i ?></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // === Hitung total per tanggal ===
                        $total_per_tanggal = array_fill(1, $jumlah_hari, 0);
                        $grand_total = 0;
                        ?>

                        <?php if (!empty($rekap)): ?>
                            <?php foreach ($rekap as $pegawai): ?>
                                <tr>
                                    <td class="fw-semibold"><?= strtoupper($pegawai['nama']) ?></td>
                                    <?php for ($i = 1; $i <= $jumlah_hari; $i++): ?>
                                        <td class="text-center">
                                            <?php if (!empty($pegawai['harian'][$i])):
                                                $total_per_tanggal[$i] += $pegawai['harian'][$i];
                                            ?>
                                                <span class="text-success">Rp <?= number_format($pegawai['harian'][$i], 0, ',', '.') ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>
                                    <td class="text-end fw-bold text-success">
                                        <?php
                                        $grand_total += $pegawai['total'];
                                        echo 'Rp ' . number_format($pegawai['total'], 0, ',', '.');
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- ===== Baris Total Akhir ===== -->
                            <tr class="bg-light fw-bold">
                                <td class="text-center">TOTAL</td>
                                <?php for ($i = 1; $i <= $jumlah_hari; $i++): ?>
                                    <td class="text-end text-primary">
                                        <?= $total_per_tanggal[$i] > 0 ? 'Rp ' . number_format($total_per_tanggal[$i], 0, ',', '.') : '-' ?>
                                    </td>
                                <?php endfor; ?>
                                <td class="text-end text-maroon">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                            </tr>

                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $jumlah_hari + 2 ?>" class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle me-1"></i> Tidak ada data untuk bulan ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
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

    .table th,
    .table td {
        font-size: 0.85rem;
        color: #000;
        vertical-align: middle;
    }

    .table thead th {
        font-weight: bold;
    }

    .table td.text-center {
        min-width: 60px;
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