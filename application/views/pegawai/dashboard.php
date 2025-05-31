<style>
body {
    font-family: Arial, sans-serif;
    background-color: #e9ecef;
    color: #212529;
}

/* Card Header */
.card-header {
    font-weight: bold;
    text-align: center;
    font-size: 16px;
    text-transform: uppercase;
    color: #ffffff; /* Kontras dengan background */
    background-color: rgba(0, 0, 0, 0.85); /* Latar belakang header lebih gelap */
    padding: 10px;
}

/* Card Text */
.card-text {
    font-size: 14px;
    color: #f8f9fa; /* Warna teks lebih kontras */
}

/* Header Styling */
h2 {
    color: #212529;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
}

/* Card Layout */
.card {
    border-radius: 10px;
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: none; /* Hilangkan border */
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
}

/* Card Body */
.card-body {
    text-align: center;
    padding: 15px;
}

.card-body h5 {
    font-size: 28px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #ffffff; /* Warna teks utama */
}

.card-body p {
    font-size: 14px;
    color: #f8f9fa;
}

/* Card Colors */
.bg-primary {
    background-color: #0056b3 !important; /* Biru lebih gelap */
}

.bg-success {
    background-color: #218838 !important; /* Hijau lebih tegas */
}

.bg-danger {
    background-color: #c82333 !important; /* Merah lebih tegas */
}

.bg-warning {
    background-color: #e0a800 !important; /* Kuning lebih tajam */
    color: #212529; /* Teks lebih gelap */
}

.bg-info {
    background-color: #138496 !important; /* Biru kehijauan */
}

.bg-dark {
    background-color: #343a40 !important; /* Abu-abu gelap */
    color: #f8f9fa;
}

.bg-dark2 {
    background-color: #007bff !important; /* Biru terang untuk kontras */
    color: #ffffff;
}

/* Container Spacing */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Row Spacing */
.row {
    margin: 0 -10px;
}

.row .col-md-4 {
    padding: 10px;
}
</style>
<div class="container mt-4">
    <h2 class="mb-4">Dashboard Pegawai</h2>
    <div class="row">
        <!-- Jumlah Jadwal Shift -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Jumlah Jadwal Shift Bulan Ini</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $jumlah_shift ?> Shift</h5>
                    <p class="card-text">Jumlah jadwal shift Anda bulan ini.</p>
                </div>
            </div>
        </div>
        <!-- Gaji Pokok Berdasarkan Kehadiran -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Gaji Pokok Berdasarkan Kehadiran</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_gaji, 2, ',', '.') ?></h5>
                    <p class="card-text">Gaji pokok Anda berdasarkan kehadiran bulan ini.</p>
                </div>
            </div>
        </div>

    <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Kehadiran Bulan Ini</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $total_kehadiran ?> Hari</h5>
                    <p class="card-text">Jumlah kehadiran Anda bulan ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Lembur Bulan Ini</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_lembur ?? 0, 2, ',', '.') ?></h5>
                    <p class="card-text">Total gaji lembur yang Anda terima bulan ini.</p>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Total Kasbon Bulan Ini</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_kasbon ?? 0, 2, ',', '.') ?></h5>
                    <p class="card-text">Jumlah kasbon aktif Anda bulan ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Potongan Bulan Ini</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_potongan ?? 0, 2, ',', '.') ?></h5>
                    <p class="card-text">Jumlah potongan gaji Anda bulan ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total Tambahan Lain Bulan Ini</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_tambahan_lain ?? 0, 2, ',', '.') ?></h5>
                    <p class="card-text">Total tambahan gaji Anda bulan ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">Total Deposit Bulan Ini</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_deposit ?? 0, 2, ',', '.') ?></h5>
                    <p class="card-text">Total deposit (setor - tarik) Anda bulan ini.</p>
                </div>
            </div>
        </div>
        <!-- Gaji Berjalan -->
        <div class="col-md-4">
            <div class="card text-white bg-dark2 mb-3">
                <div class="card-header">Gaji Berjalan Bulan Ini</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($gaji_berjalan, 2, ',', '.') ?></h5>
                    <p class="card-text">Total gaji berjalan Anda bulan ini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
