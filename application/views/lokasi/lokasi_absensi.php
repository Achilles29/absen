<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    #map {
        height: 400px;
        width: 100%;
    }
</style>

<h1>Pengaturan Lokasi Absensi</h1>

<?php if ($this->session->flashdata('success')): ?>
    <p style="color: green;"><?= $this->session->flashdata('success'); ?></p>
<?php elseif ($this->session->flashdata('error')): ?>
    <p style="color: red;"><?= $this->session->flashdata('error'); ?></p>
<?php endif; ?>

<!-- Form Input Lokasi -->
<form method="post" action="<?= site_url('lokasi/simpan_lokasi_absen') ?>">
    <div class="form-group">
        <label>Nama Lokasi:</label>
        <input type="text" class="form-control" name="nama_lokasi" 
               value="<?= isset($lokasi->nama_lokasi) ? $lokasi->nama_lokasi : '' ?>" required>
    </div>

    <div class="form-group">
        <label>Latitude:</label>
        <input type="text" class="form-control" name="latitude" id="latitude" 
               value="<?= isset($lokasi->latitude) ? $lokasi->latitude : '' ?>" readonly required>
    </div>

    <div class="form-group">
        <label>Longitude:</label>
        <input type="text" class="form-control" name="longitude" id="longitude" 
               value="<?= isset($lokasi->longitude) ? $lokasi->longitude : '' ?>" readonly required>
    </div>

    <div class="form-group">
        <label>Range Absensi (meter):</label>
        <input type="number" class="form-control" name="range" 
               value="<?= isset($lokasi->range) ? $lokasi->range : 50 ?>" min="1" required>
    </div>

    <div class="form-group">
        <label>Aktifkan Lokasi Absensi:</label>
        <select name="status" class="form-control" required>
            <option value="1" <?= isset($lokasi->status) && $lokasi->status == 1 ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= isset($lokasi->status) && $lokasi->status == 0 ? 'selected' : '' ?>>Nonaktif</option>
        </select>
    </div>

    <!-- Peta -->
    <div id="map"></div>

    <button type="submit" class="btn btn-primary mt-3">Simpan Lokasi</button>
</form>

<script>
    // Default location (gunakan lokasi tersimpan atau lokasi default)
    const defaultLocation = {
        lat: <?= isset($lokasi->latitude) ? $lokasi->latitude : -6.7141193 ?>,
        lng: <?= isset($lokasi->longitude) ? $lokasi->longitude : 11.3341492 ?>
    };

    // Inisialisasi Peta Leaflet
    const map = L.map('map').setView([defaultLocation.lat, defaultLocation.lng], 15);

    // Tambahkan Tile Layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan Marker Draggable
    const marker = L.marker([defaultLocation.lat, defaultLocation.lng], { draggable: true }).addTo(map);

    // Update latitude dan longitude saat marker digeser
    marker.on('dragend', function(event) {
        const position = marker.getLatLng();
        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;
    });

    // Update lokasi marker saat peta diklik
    map.on('click', function(event) {
        const position = event.latlng;
        marker.setLatLng(position);
        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;
    });
</script>
