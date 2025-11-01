<style>
/* Gaya untuk memperbaiki tabel */
.table-container {
    overflow-x: auto;
    border: 1px solid #ddd;
}

.table {
    white-space: nowrap;
    text-align: center;
    border-collapse: collapse;
}

.table th,
.table td {
    text-align: center;
    vertical-align: middle;
    padding: 8px;
    border: 1px solid #ddd;
}

.table th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 2;
}

.table td:first-child {
    position: sticky;
    left: 0;
    background-color: #f8f9fa;
    z-index: 1;
}

.bold-text {
    font-weight: bold;
}

.fa-pencil-alt,
.fa-trash {
    margin-left: 5px;
    cursor: pointer;
}

.fa-trash:hover {
    color: red;
}

.fa-pencil-alt:hover {
    color: #007bff;
}

/* Gaya untuk sel shift */
.shift-cell {
    background-color: #f9f9f9;
    transition: background-color 0.3s ease;
}

.shift-cell:hover {
    background-color: #e9ecef;
    cursor: pointer;
}

.summary-row {
    background-color: #dff0d8;
    font-weight: bold;
}

.text-danger {
    color: red;
}
</style>

<div class="container-fluid mt-4">
    <h2><?= $title ?></h2>

    <form method="get" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <label for="bulan">Pilih Bulan:</label>
            <select name="bulan" id="bulan" class="form-control">
                <?php
                $months = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                foreach ($months as $key => $value): ?>
                    <option value="<?= $key ?>" <?= ($selected_month == $key) ? 'selected' : '' ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="tahun">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="form-control">
                <?php for ($year = date('Y') - 5; $year <= date('Y') + 5; $year++): ?>
                    <option value="<?= $year ?>" <?= ($selected_year == $year) ? 'selected' : '' ?>><?= $year ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>


    <!-- Tabel Jadwal Shift -->
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Pegawai</th>
                    <th>Divisi</th>
                    <?php for ($i = 1; $i <= date('t', strtotime($bulan)); $i++): ?>
                        <th class="text-center"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                    <?php endfor; ?>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_days = date('t', strtotime($bulan));
                $current_divisi = '';
                $divisi_totals = [];

                // Kumpulkan data shift per pegawai dan tanggal
                foreach ($jadwal_shift as $jadwal): 
                    $shift_data[$jadwal->pegawai_id][$jadwal->tanggal] = $jadwal->kode_shift ?? '-';

                    // Hitung jumlah per divisi per hari
                    $tanggal = $jadwal->tanggal;
                    $divisi = $jadwal->nama_divisi;

                    if (!isset($divisi_totals[$divisi][$tanggal])) {
                        $divisi_totals[$divisi][$tanggal] = 0;
                    }
                    if (!empty($jadwal->kode_shift)) {
                        $divisi_totals[$divisi][$tanggal]++;
                    }
                endforeach;

                foreach ($pegawai as $p): 
                    // Jika divisi berganti, tambahkan baris jumlah per divisi
                    if ($current_divisi != $p->nama_divisi && $current_divisi != ''): ?>
                        <tr class="bg-info text-black">
                            <td colspan="2" class="bold-text">Jumlah <?= $current_divisi ?></td>
                            <?php for ($i = 1; $i <= $total_days; $i++): 
                                $current_date = $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                $jumlah = $divisi_totals[$current_divisi][$current_date] ?? 0;
                            ?>
                                <td class="text-center"><?= $jumlah ?></td>
                            <?php endfor; ?>
                            <td></td>
                        </tr>
                    <?php endif;

                    $current_divisi = $p->nama_divisi; ?>
                    <tr>
                        <td><?= $p->nama ?></td>
                        <td><?= $p->nama_divisi ?></td>
                        <?php for ($i = 1; $i <= $total_days; $i++): 
                            $current_date = $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $shift = $shift_data[$p->id][$current_date] ?? '-';
                        ?>
                        <td class="text-center">
                            <span class="bold-text"><?= $shift ?></span>
                            <a href="#" class="edit-pencil" 
                            data-pegawai="<?= $p->id ?>" 
                            data-tanggal="<?= $current_date ?>">
                            <i class="fa fa-pencil-alt"></i>
                            </a>
                            <a href="#" class="delete-shift"
                            data-pegawai="<?= $p->id ?>"
                            data-tanggal="<?= $current_date ?>">
                            <i class="fa fa-trash text-danger"></i>
                            </a>
                        </td>
                        <?php endfor; ?>
                        <td class="text-center"><?= count(array_filter($shift_data[$p->id] ?? [])) ?></td>
                    </tr>
                <?php endforeach; ?>

                <!-- Tambahkan baris total terakhir untuk divisi terakhir -->
                <tr class="bg-info text-black">
                    <td colspan="2" class="bold-text" >Jumlah <?= $current_divisi ?></td>
                    <?php for ($i = 1; $i <= $total_days; $i++): 
                        $current_date = $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

                        $jumlah = $divisi_totals[$current_divisi][$current_date] ?? 0;
                    ?>
                        <td class="text-center "  ><?= $jumlah ?></td>
                    <?php endfor; ?>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const editIcons = document.querySelectorAll('.edit-pencil');

    editIcons.forEach(icon => {
        icon.addEventListener('click', function (event) {
            event.preventDefault();
            const pegawai_id = this.getAttribute('data-pegawai');
            const tanggal = this.getAttribute('data-tanggal');

            console.log(`Klik Edit Pencil - Pegawai ID: ${pegawai_id}, Tanggal: ${tanggal}`); // Debug untuk memastikan data

            // Fetch data shift berdasarkan divisi/jabatan pegawai
            fetch(`<?= site_url('jadwal_shift/get_shift_by_divisi') ?>?pegawai_id=${pegawai_id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.length > 0) {
                        // Bangun dropdown dengan nama_shift
                        let dropdown = '<select id="shiftDropdown" class="form-control">';
                        data.forEach(shift => {
                            dropdown += `
                                <option value="${shift.id}">
                                    ${shift.nama_shift} (${shift.jam_mulai} - ${shift.jam_selesai})
                                </option>`;
                        });
                        dropdown += '</select>';

                        // Isi konten modal dengan dropdown
                        const modalContent = `
                            <div class="form-group">
                                <label for="shiftDropdown">Pilih Shift</label>
                                ${dropdown}
                            </div>
                            <button class="btn btn-primary" onclick="saveShift(${pegawai_id}, '${tanggal}')">Simpan</button>
                        `;
                        showModal("Update Shift", modalContent);
                    } else {
                        alert("Tidak ada shift yang sesuai untuk divisi/jabatan pegawai ini.");
                    }
                })
                .catch(error => {
                    console.error("Error fetching shift data:", error);
                    alert("Terjadi kesalahan saat mengambil data shift.");
                });
        });
    });
});
document.addEventListener("DOMContentLoaded", function() {
    // Event listener untuk tombol hapus
    document.querySelectorAll('.delete-shift').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const pegawai_id = this.getAttribute('data-pegawai');
            const tanggal = this.getAttribute('data-tanggal');

            if (confirm("Apakah Anda yakin ingin menghapus shift ini?")) {
                fetch('<?= site_url('jadwal_shift/delete_jadwal_shift2') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ pegawai_id, tanggal })
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
                    alert('Terjadi kesalahan saat menghapus shift.');
                });
            }
        });
    });
});

// Fungsi untuk menyimpan shift
function saveShift(pegawai_id, tanggal) {
    const shift_id = document.getElementById('shiftDropdown').value;

    fetch('<?= site_url('jadwal_shift/update_jadwal_shift') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pegawai_id, tanggal, shift_id })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert("Shift berhasil diperbarui!");
            location.reload();
        } else {
            alert("Gagal memperbarui shift: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error updating shift:", error);
        alert("Terjadi kesalahan saat menyimpan shift.");
    });
}

// Fungsi untuk menampilkan modal
function showModal(title, content) {
    const modal = `
        <div class="modal fade" id="shiftModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">${content}</div>
                </div>
            </div>
        </div>
    `;

    // Tambahkan modal ke body
    document.body.insertAdjacentHTML('beforeend', modal);

    // Tampilkan modal menggunakan Bootstrap
    $('#shiftModal').modal('show');

    // Hapus modal setelah ditutup
    $('#shiftModal').on('hidden.bs.modal', function () {
        $(this).remove();
    });
}
</script>