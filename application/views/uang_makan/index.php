<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-maroon text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-utensils me-2"></i> Rekap Uang Makan Harian
            </h5>
            <span class="small">
                <i class="far fa-calendar-alt me-1"></i> <?= date('d F Y') ?>
            </span>
        </div>

        <div class="card-body">
            <!-- Filter Range Tanggal -->
            <form method="get" class="row g-3 mb-4">
                <?php
                $today = date('Y-m-d');
                $tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : $today;
                $tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : $today;
                ?>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control shadow-sm" value="<?= $tanggal_awal ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control shadow-sm" value="<?= $tanggal_akhir ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-maroon w-100 shadow-sm">
                        <i class="fas fa-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle border">
                    <thead class="bg-gradient-maroon text-white">
                        <tr class="text-center">
                            <th style="width: 60px;">No</th>
                            <th>Nama Pegawai</th>
                            <th>Kode Shift</th>
                            <th>Tanggal</th>
                            <th class="text-end">Uang Makan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($hasil)): ?>
                            <?php $no = 1;
                            foreach ($hasil as $row): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="fw-semibold"><?= strtoupper($row->nama_pegawai) ?></td>
                                    <td class="text-center"><span class="badge bg-secondary"><?= $row->kode_shift ?></span></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($row->tanggal)) ?></td>
                                    <td class="text-end fw-semibold">Rp <?= number_format($row->uang_makan, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-1"></i> Tidak ada data pada rentang tanggal ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <?php if (!empty($hasil)): ?>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Uang Makan:</td>
                                <td class="text-end fw-bold text-success">Rp <?= number_format($total_uang_makan, 0, ',', '.') ?></td>
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

    .btn-maroon {
        background-color: #800000;
        color: #fff;
        border: none;
        transition: all 0.2s ease-in-out;
    }

    .btn-maroon:hover {
        background-color: #a30000;
    }

    .bg-gradient-maroon {
        background: linear-gradient(90deg, #800000 0%, #9b1c1c 100%);
    }

    .table th,
    .table td {
        font-size: 0.9rem;
        vertical-align: middle;
        color: #000 !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(128, 0, 0, 0.05);
    }

    .card-header {
        border-bottom: 3px solid #a30000;
    }

    .shadow-sm {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1) !important;
    }
</style>