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


</style>

<div class="container mt-4">
    <h2>Master Kode User</h2>
    <a href="<?= site_url('admin/tambah_kode_user') ?>" class="btn btn-primary mb-3">Tambah Kode User</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode User</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kode_users as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $row->kode_user ?></td>
                    <td>
                        <a href="<?= site_url('admin/edit_kode_user/'.$row->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('admin/hapus_kode_user/'.$row->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
