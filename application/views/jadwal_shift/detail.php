<div class="container mt-4">
    <h2>Detail Jadwal Shift Pegawai</h2>

    <p><strong>Nama Pegawai:</strong> <?= htmlspecialchars($pegawai->nama ?? '-') ?></p>
    <p><strong>Divisi:</strong> <?= htmlspecialchars($pegawai->nama_divisi ?? '-') ?></p>
    <p><strong>Bulan:</strong> <?= date('F Y', strtotime($bulan)) ?></p>

    <!-- Tabel Jadwal Shift -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Nama Shift</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($jadwal_shift)): ?>
                <?php foreach ($jadwal_shift as $jadwal): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($jadwal->tanggal)) ?></td>
                        <td><?= htmlspecialchars($jadwal->kode_shift ?? '-') ?></td>
                        <td><?= htmlspecialchars($jadwal->nama_shift ?? '-') ?></td>
                        <td><?= htmlspecialchars($jadwal->jam_mulai ?? '-') ?></td>
                        <td><?= htmlspecialchars($jadwal->jam_selesai ?? '-') ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-shift" 
                                    data-id="<?= $jadwal->id ?>" 
                                    data-tanggal="<?= $jadwal->tanggal ?>">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada jadwal shift untuk pegawai ini di bulan ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll('.delete-shift');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const tanggal = this.getAttribute('data-tanggal');

            if (confirm(`Apakah Anda yakin ingin menghapus jadwal shift pada tanggal ${tanggal}?`)) {
                fetch('<?= site_url('jadwal_shift/delete_jadwal_shift') ?>', {
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
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data.');
                });
            }
        });
    });
});
</script>
