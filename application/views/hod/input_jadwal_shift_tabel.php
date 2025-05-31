<style>
.edit-pencil {
    margin-left: 5px;
    font-size: 10px;
    color: blue;
    cursor: pointer;
}
.table-container {
    overflow-x: auto;
}
.bold-text {
    font-weight: bold;
}
</style>

<div class="container-fluid mt-4">
    <h2><?= $title ?></h2>
    <form method="get" class="mb-3">
        <label for="bulan">Pilih Bulan:</label>
        <select name="bulan" class="form-control" style="width: 200px; display: inline-block;">
            <?php for ($i = 0; $i < 12; $i++): 
                $month = date('Y-m', strtotime("-$i month"));
                $selected = ($bulan == $month) ? 'selected' : '';
            ?>
                <option value="<?= $month ?>" <?= $selected ?>><?= date('F Y', strtotime($month)) ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
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
                        <tr class="bg-info text-white">
                            <td colspan="2" class="bold-text">Jumlah <?= $current_divisi ?></td>
                            <?php for ($i = 1; $i <= $total_days; $i++): 
                                $current_date = date('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
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
                            $current_date = date('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $shift = $shift_data[$p->id][$current_date] ?? '-';
                        ?>
                            <td class="text-center" >
                                <span class="bold-text"><?= $shift ?></span>
                                <a href="#" class="edit-pencil" 
                                   data-pegawai="<?= $p->id ?>" 
                                   data-tanggal="<?= $current_date ?>">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                            </td>
                        <?php endfor; ?>
                        <td class="text-center"><?= count(array_filter($shift_data[$p->id] ?? [])) ?></td>
                    </tr>
                <?php endforeach; ?>

                <!-- Tambahkan baris total terakhir untuk divisi terakhir -->
                <tr class="bg-info text-white">
                    <td colspan="2" class="bold-text" >Jumlah <?= $current_divisi ?></td>
                    <?php for ($i = 1; $i <= $total_days; $i++): 
                        $current_date = date('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
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
document.addEventListener("DOMContentLoaded", function() {
    const editIcons = document.querySelectorAll('.edit-pencil');

    editIcons.forEach(icon => {
        icon.addEventListener('click', function(event) {
            event.preventDefault();
            const pegawai_id = this.getAttribute('data-pegawai');
            const tanggal = this.getAttribute('data-tanggal');

            console.log("Klik Edit Pencil:", pegawai_id, tanggal); // Debug untuk memastikan data

            fetch('<?= site_url('jadwal_shift/get_shift_by_divisi') ?>?pegawai_id=' + pegawai_id)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let dropdown = '<select id="shiftDropdown" class="form-control">';
                        data.forEach(shift => {
                            dropdown += `<option value="${shift.id}">${shift.kode_shift} (${shift.jam_mulai} - ${shift.jam_selesai})</option>`;
                        });
                        dropdown += '</select>';

                        const modalContent = `
                            <div class="form-group">
                                <label for="shiftDropdown">Pilih Shift</label>
                                ${dropdown}
                            </div>
                            <button class="btn btn-primary" onclick="saveShift(${pegawai_id}, '${tanggal}')">Simpan</button>
                        `;
                        showModal("Update Shift", modalContent);
                    } else {
                        alert("Tidak ada shift untuk divisi pegawai ini.");
                    }
                })
                .catch(error => {
                    console.error("Error fetching shift data:", error);
                    alert("Terjadi kesalahan saat mengambil data shift.");
                });
        });
    });
});

function saveShift(pegawai_id, tanggal) {
    const shift_id = document.getElementById('shiftDropdown').value;

    fetch('<?= site_url('jadwal_shift/update_jadwal_shift') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pegawai_id, tanggal, shift_id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
           // alert("Shift berhasil diperbarui!");
            location.reload();
        } else {
            alert("Gagal memperbarui shift.");
        }
    })
    .catch(error => {
        console.error("Error updating shift:", error);
        alert("Terjadi kesalahan saat menyimpan shift.");
    });
}

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

    document.body.insertAdjacentHTML('beforeend', modal);
    $('#shiftModal').modal('show');
    $('#shiftModal').on('hidden.bs.modal', function () {
        $(this).remove();
    });
}
</script>