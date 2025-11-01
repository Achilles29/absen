<style>
    .dashboard-card {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        text-align: center;
    }
    .dashboard-card h3 {
        font-size: 24px;
        margin-bottom: 10px;
    }
    .dashboard-card p {
        font-size: 18px;
        margin: 0;
    }
</style>

<div class="container mt-4">
    <h2>Beranda</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card">
                <h3><?= $total_pegawai ?></h3>
                <p>Total Pegawai</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h3><?= $total_lembur ?></h3>
                <p>Total Lembur (Menit)</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h3><?= $total_kasbon ?></h3>
                <p>Total Kasbon</p>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="dashboard-card">
                <h3><?= number_format($total_gaji_berjalan, 2, ',', '.') ?></h3>
                <p>Total Gaji Berjalan (Rp)</p>
            </div>
        </div> -->
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card">
                <h3><?= $total_divisi ?></h3>
                <p>Total Divisi</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h3><?= $total_tambahan_lain ?></h3>
                <p>Total Tambahan Lain</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h3><?= $total_potongan ?></h3>
                <p>Total Potongan</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <h3><?= $total_shift ?></h3>
                <p>Total Shift Terdaftar</p>
            </div>
        </div>
    </div>
</div>
