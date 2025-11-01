<style>
    h1.text-center {
        margin-bottom: 20px;
        font-weight: bold;
        color: #3f51b5;
    }

    .form-label {
        font-weight: bold;
        color: #3f51b5;
    }

    .table {
        margin-top: 20px;
        border-collapse: collapse;
    }

    .table th {
        background-color: #3f51b5;
        color: #fff;
        text-align: center;
        font-weight: bold;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .btn-primary {
        background-color: #3f51b5;
        border: none;
    }

    .btn-primary:hover {
        background-color: #5c6bc0;
    }
</style>

<div class="container mt-5">
    <h1 class="text-center">Rekap Jadwal Pegawai</h1>

    <!-- Filter Dropdown Bulan dan Tahun -->
<form method="GET" action="<?= base_url('jadwal_shift/rekap_jadwal') ?>" class="mb-4">
    <div class="form-row align-items-center">
        <div class="col-auto">
            <label for="month" class="form-label">Pilih Bulan:</label>
            <select id="month" name="month" class="form-control">
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
        <div class="col-auto">
            <label for="year" class="form-label">Pilih Tahun:</label>
            <select id="year" name="year" class="form-control">
                <?php for ($year = date('Y') - 5; $year <= date('Y') + 5; $year++): ?>
                    <option value="<?= $year ?>" <?= ($selected_year == $year) ? 'selected' : '' ?>><?= $year ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-auto mt-4">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>


    <!-- Tabel Rekap Jadwal -->
    <?php if (!empty($jadwal)): ?>
        <?php 
        // Group data berdasarkan divisi
        $groupedJadwal = [];
        foreach ($jadwal as $row) {
            $groupedJadwal[$row['nama_divisi']][] = $row;
        }
        ?>

        <?php foreach ($groupedJadwal as $divisi => $rows): ?>
            <div class="mt-4">
                <h3 class="text-center"><?= $divisi ?></h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style="background-color: #343a40; color: white;" class="text-center">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama</th>
                                <?php
                                // Ambil semua kode_shift unik untuk kolom header
                                $kodeShifts = array_unique(array_column($rows, 'kode_shift'));
                                foreach ($kodeShifts as $kodeShift): ?>
                                    <th><?= $kodeShift ?></th>
                                <?php endforeach; ?>
                                <th>Jumlah</th> <!-- Kolom Jumlah -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Ambil semua pegawai unik dalam divisi
                            $pegawai = [];
                            foreach ($rows as $row) {
                                $pegawai[$row['nama']][] = $row;
                            }

                            $no = 1; // Nomor awal
                            ?>
                            <?php foreach ($pegawai as $nama => $shifts): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $nama ?></td>
                                    <?php 
                                    $total = 0; // Variabel untuk total jumlah shift
                                    foreach ($kodeShifts as $kodeShift): ?>
                                        <td class="text-center">
                                            <?php
                                            // Tampilkan jumlah untuk shift tertentu
                                            $jumlah = 0;
                                            foreach ($shifts as $shift) {
                                                if ($shift['kode_shift'] === $kodeShift) {
                                                    $jumlah = $shift['jumlah'];
                                                    $total += $jumlah; // Tambahkan ke total
                                                    break;
                                                }
                                            }
                                            echo $jumlah ?: '-';
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="text-center"><strong><?= $total ?></strong></td> <!-- Tampilkan Total -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center mt-5">
            <p>Tidak ada data jadwal untuk bulan ini.</p>
        </div>
    <?php endif; ?>
</div>
