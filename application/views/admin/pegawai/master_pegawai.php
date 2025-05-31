<style>
    .container-wide {
        max-width: 95%; /* Lebar kontainer */
        margin: 0 auto; /* Menjaga kontainer tetap di tengah */
    }

    .table-responsive {
        margin-top: 20px; /* Jarak atas tabel */
    }

    .table th,
    .table td {
        font-size: 0.9rem; /* Ukuran font untuk tabel agar sesuai */
        vertical-align: middle; /* Konten di tengah secara vertikal */
        text-align: center; /* Konten di tengah secara horizontal */
        white-space: nowrap; /* Mencegah teks terpotong */
    }

    .table th {
        font-weight: bold; /* Membuat header tabel lebih tebal */
    }

    .action-buttons {
        white-space: nowrap; /* Mencegah tombol berantakan */
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 0.8rem; /* Ukuran font lebih kecil untuk layar kecil */
        }
    }

    /* Responsif untuk layar sangat kecil */
    @media (max-width: 576px) {
        .table th,
        .table td {
            font-size: 0.7rem; /* Ukuran font lebih kecil */
            padding: 5px; /* Mengurangi padding untuk ruang */
        }

        .btn {
            font-size: 0.8rem; /* Ukuran font tombol lebih kecil */
            padding: 2px 5px; /* Ukuran tombol lebih kecil */
        }
    }

    .font-weight-bold {
        font-weight: bold; /* Menonjolkan teks jumlah */
    }
</style>

<div class="container-wide mt-4">
    <h2 class="mb-4">Master Pegawai</h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <a href="<?= site_url('admin/tambah_pegawai') ?>" class="btn btn-primary mb-3">Tambah Pegawai</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Kode User</th>
                    <th>Nama</th>
                    <th>Bank</th>
                    <th>Rekening </th>
                    <th>Username</th>
                    <th>Divisi</th>
                    <th>Jabatan 1</th>
                    <th>Jabatan 2</th>
                    <th>Gaji Pokok</th>
                    <th>Gaji Per Jam</th>
                    <th>Tunjangan</th>
                    <th>Tambahan</th>
                    <th>Tanggal Kontrak</th>
                    <th>Durasi Kontrak</th>
                    <th>Akhir Kontrak </th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_gaji_pokok = 0;
                $total_tambahan_lain = 0;
                foreach ($pegawai as $index => $row): 
                    $total_gaji_pokok += $row->gaji_pokok;
                    $total_tambahan_lain += $row->tambahan_lain;
                ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $row->kode_user ?></td>
                    <td><?= $row->nama ?></td>
                    <td><?= $row->nama_bank ?? 'Tidak Ada' ?></td>
                    <td><?= $row->nomor_rekening ?? '-' ?></td>

                    <td><?= $row->username ?></td>
                    <td><?= $row->nama_divisi ?></td>
                    <td><?= $row->jabatan1 ?></td>
                    <td><?= $row->jabatan2 ?></td>
                    <td style="text-align: right;">Rp <?= number_format($row->gaji_pokok, 0, ',', '.') ?></td>
                    <td style="text-align: right;">Rp <?= number_format($row->gaji_per_jam, 2, ',', '.') ?></td>
                    <td style="text-align: right;">Rp <?= number_format($row->tunjangan, 0, ',', '.') ?></td>
                    <td style="text-align: right;">Rp <?= number_format($row->tambahan_lain, 0, ',', '.') ?></td>
                    <td>
                        <?php if (!empty($row->tanggal_kontrak_awal)): ?>
                            <?= date('Y-m-d', strtotime($row->tanggal_kontrak_awal)) ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?= $row->durasi_kontrak ?> Bulan</td>
                    <td>
                        <?php if (!empty($row->tanggal_kontrak_akhir)): ?>
                            <?= date('Y-m-d', strtotime($row->tanggal_kontrak_akhir)) ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="action-buttons">
                        <a href="<?= site_url('admin/edit_pegawai/'.$row->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('admin/hapus_pegawai/'.$row->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="6" class="text-center">Jumlah</td>
                    <td style="text-align: right;">Rp <?= number_format($total_gaji_pokok, 0, ',', '.') ?></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;">Rp <?= number_format($total_tambahan_lain, 0, ',', '.') ?></td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
