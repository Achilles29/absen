<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    #map {
        height: 400px;
        width: 100%;
    }
</style>

<div class="container mt-4">
    <h2><?= $title ?></h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Nama Lokasi:</label>
            <input type="text" name="nama_lokasi" class="form-control" value="<?= $lokasi->nama_lokasi ?>" required>
        </div>

        <div class="form-group">
            <label>Latitude:</label>
            <input type="text" id="latitude" name="latitude" class="form-control" value="<?= $lokasi->latitude ?>" readonly required>
        </div>

        <div class="form-group">
            <label>Longitude:</label>
            <input type="text" id="longitude" name="longitude" class="form-control" value="<?= $lokasi->longitude ?>" readonly required>
        </div>

        <div class="form-group">
            <label>Range Absensi (meter):</label>
            <input type="number" name="range" class="form-control" value="<?= $lokasi->range ?>" required>
        </div>

        <div class="form-group">
            <label>Status Aktif:</label>
            <select name="is_active" class="form-control">
                <option value="1" <?= $lokasi->is_active ? 'selected' : '' ?>>Aktif</option>
                <option value="0" <?= !$lokasi->is_active ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>

        <!-- Peta -->
        <div id="map"></div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        <a href="<?= site_url('admin/lokasi_absen') ?>" class="btn btn-secondary mt-3">Kembali</a>
    </form>
</div>

<script>
    const defaultLocation = {
        lat: <?= $lokasi->latitude ?>,
        lng: <?= $lokasi->longitude ?>
    };

    const map = L.map('map').setView([defaultLocation.lat, defaultLocation.lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([defaultLocation.lat, defaultLocation.lng], { draggable: true }).addTo(map);

    marker.on('dragend', function (event) {
        const position = marker.getLatLng();
        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;
    });

    map.on('click', function (event) {
        const position = event.latlng;
        marker.setLatLng(position);
        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;
    });
</script>
