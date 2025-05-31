<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }
    .container {
        width: 148mm; /* Lebar A5 */
        height: 210mm; /* Tinggi A5 */
        margin: 10mm auto;
        background: #fff;
        border: 1px solid #000;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 10mm; /* Padding dalam mm */
        box-sizing: border-box;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #000;
        padding-bottom: 5px;
    }
    .header img {
        max-width: 30mm; /* Lebar maksimal untuk logo */
    }
    .header .company-info {
        text-align: right;
        font-size: 12px;
    }
    .title {
        text-align: center;
        margin: 10px 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    table th, table td {
        border: 1px solid #000;
        padding: 4px;
        font-size: 12px; /* Ukuran font lebih kecil */
        text-align: left;
    }
    table th {
        background-color: #f8f9fa;
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .signature {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
    }
    .signature .signature-box {
        text-align: center;
        width: 45%; /* Lebar kotak tanda tangan */
    }
    .signature .signature-box .line {
        margin-top: 30px;
        border-top: 1px solid #000;
        padding-top: 5px;
        font-size: 12px;
    }
    .print-button {
        margin: 10px 0;
        text-align: center;
    }
    .print-button button {
        padding: 6px 12px;
        font-size: 12px;
        color: #fff;
        background: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .print-button button:hover {
        background: #0056b3;
    }
</style>

<div class="container">
    <div class="print-button">
        <button onclick="window.print()">Cetak Slip Gaji</button>
    </div>
    <div class="header">
        <img src="<?= base_url('assets/img/Logo.png') ?>" alt="Logo">
        <div class="company-info">
            <h2>NAMUA COFFEE AND EATERY</h2>
            <p>Jl. Magnolia, Ds. Kabongan Kidul, Kab. Rembang</p>
        </div>
    </div>

    <div class="title">
        <h3>SLIP GAJI</h3>
        <p>Probation</p>
    </div>

    <table>
        <tr>
            <th>Nama</th>
            <td><?= $pegawai->nama ?></td>
            <th>Tanggal</th>
            <td><?= date('d/m/Y') ?></td>
        </tr>
        <tr>
            <th>Posisi</th>
            <td><?= $pegawai->jabatan ?></td>
            <th>Periode</th>
            <td><?= date('d M Y', strtotime($range_start)) ?> - <?= date('d M Y', strtotime($range_end)) ?></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Keterangan</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Gaji Pokok</td>
                <td class="text-right">Rp <?= number_format($gaji_pokok, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Tunjangan</td>
                <td class="text-right">Rp <?= number_format($tunjangan, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Lembur</td>
                <td class="text-right">Rp <?= number_format($lembur, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Tambahan Lain</td>
                <td class="text-right">Rp <?= number_format($tambahan_lain, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Potongan</td>
                <td class="text-right">Rp <?= number_format(-$potongan, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>6</td>
                <td>Deposit (Tarik - Setor)</td>
                <td class="text-right">Rp <?= number_format($deposit, 2, ',', '.') ?></td>
            </tr>

            <tr>
                <td>7</td>
                <td>Bayar Kasbon</td>
                <td class="text-right">Rp <?= number_format(-$bayar_kasbon, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <th colspan="2" class="text-right">Total Penerimaan</th>
                <th class="text-right">Rp <?= number_format($total_gaji, 2, ',', '.') ?></th>
            </tr>
            <tr>
                <th colspan="2" class="text-right">Pembulatan Penerimaan</th>
                <th class="text-right">
                    <?php 
                    // Pembulatan total penerimaan ke ribuan terdekat
                    $pembulatan_penerimaan = ceil($total_gaji / 1000) * 1000; 
                    ?>
                    Rp <?= number_format($pembulatan_penerimaan, 2, ',', '.') ?>
                </th>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-box">
            <p>Diberikan Oleh</p>
            <div class="line">ANIS FITRIYA</div>
        </div>
        <div class="signature-box">
            <p>Diterima Oleh</p>
            <div class="line"><?= $pegawai->nama ?></div>
        </div>
    </div>
</div>
<script>
    function cetakSlipGaji() {
        window.print();
    }
</script>
