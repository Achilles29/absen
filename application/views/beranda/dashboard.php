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
    <h2 class="mb-4">Dashboard</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Pegawai</div>
                <div class="card-body">
                    <h5 class="card-title display-4"><?= $total_pegawai ?></h5>
                    <p class="card-text">Jumlah seluruh pegawai terdaftar.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Lembur</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format((float)$total_lembur, 2, ',', '.') ?></h5>
                    <p class="card-text">Akumulasi seluruh Lembur pegawai pada bulan ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Total Kasbon</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_kasbon, 2, ',', '.') ?></h5>
                    <p class="card-text">Jumlah kasbon pegawai saat ini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Potongan</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format((float)$total_potongan, 2, ',', '.') ?></h5>
                    <p class="card-text">Akumulasi seluruh potongan pegawai pada bulan ini.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total Tambahan Lain</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_tambahan_lain, 2, ',', '.') ?></h5>
                    <p class="card-text">Total tambahan lainnya.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">Total Deposit</div>
                <div class="card-body">
                <h5 class="card-title">
                    Rp <?= isset($total_deposit) ? number_format((float)$total_deposit, 2, ',', '.') : '0,00' ?>
                </h5>
            <p class="card-text">Total deposit (setor - tarik) dari seluruh pegawai.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Total Gaji Berjalan</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_gaji_berjalan, 2, ',', '.') ?></h5>
                    <p class="card-text">Total gaji berjalan dikurangi Kasbon.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark2 mb-3">
                <div class="card-header">Total Gaji Berjalan</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($total_gaji_berjalan2, 2, ',', '.') ?></h5>
                    <p class="card-text">Total gaji Termasuk Kasbon.</p>
                </div>
            </div>
        </div>        
    </div>
</div>

