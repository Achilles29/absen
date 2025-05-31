<div class="container mt-4">
    <h2><?= $title ?></h2>
    
    <!-- Notifikasi -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('pegawai/absen') ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label>Shift Anda Hari Ini:</label>
            <?php if ($shift): ?>
                <input type="text" class="form-control" value="<?= $shift->nama_shift ?> (<?= $shift->jam_mulai ?> - <?= $shift->jam_selesai ?>)" readonly>
                <input type="hidden" name="shift_id" value="<?= $shift->shift_id ?>">
            <?php else: ?>
                <input type="text" class="form-control" value="Shift belum diatur" readonly>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Jenis Absen:</label>
            <select name="jenis_absen" class="form-control" required>
                <option value="masuk">Absen Masuk</option>
                <option value="pulang">Absen Pulang</option>
            </select>
        </div>

        <div class="form-group">
            <label>Lokasi Absensi:</label>
            <select name="lokasi_id" class="form-control" required>
                <option value="">-- Pilih Lokasi --</option>
                <?php foreach ($lokasi as $row): ?>
                    <option value="<?= $row->id ?>"><?= $row->nama_lokasi ?></option>
                <?php endforeach; ?>
            </select>
        </div>

            <div class="form-group">
                <label>Foto Selfie (Opsional):</label>
                <input type="file" name="foto" class="form-control" id="foto" accept="image/*">
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
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil lokasi GPS otomatis
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

        // Preview foto sebelum diunggah
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('foto-preview');

        fotoInput.addEventListener('change', function () {
            const file = fotoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    fotoPreview.src = e.target.result;
                    fotoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                fotoPreview.style.display = 'none';
            }
        });
    });
</script>
