<div class="container mt-4">
    <h2><?= $title ?></h2>
    <form method="post" action="<?= site_url('pegawai/absen') ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label>Shift:</label>
            <select name="shift_id" class="form-control" required>
                <option value="">-- Pilih Shift --</option>
                <?php if (!empty($shift)): ?>
                    <?php foreach ($shift as $row): ?>
                        <option value="<?= $row->id ?>"><?= $row->kode_shift ?> (<?= $row->jam_mulai ?> - <?= $row->jam_selesai ?>)</option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">Shift tidak tersedia</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Absen:</label>
            <select name="jenis_absen" class="form-control" required>
                <option value="masuk">Absen Masuk</option>
                <option value="pulang">Absen Pulang</option>
            </select>
        </div>

        <div class="form-group">
            <label>Foto Selfie:</label>
            <input type="file" name="foto" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Latitude:</label>
            <input type="text" name="latitude" class="form-control" id="latitude" readonly required>
        </div>

        <div class="form-group">
            <label>Longitude:</label>
            <input type="text" name="longitude" class="form-control" id="longitude" readonly required>
        </div>

        <button type="submit" class="btn btn-primary">Absen</button>
    </form>
</div>

<script>
    // Ambil lokasi GPS otomatis
    document.addEventListener('DOMContentLoaded', function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            }, function (error) {
                alert('Gagal mendapatkan lokasi GPS: ' + error.message);
            });
        } else {
            alert('Geolocation tidak didukung oleh browser ini.');
        }
    });
</script>
