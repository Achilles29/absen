<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['divisi'] = 'divisi/index';
$route['jabatan'] = 'jabatan/jabatan';


$route['admin/shift'] = 'admin/shift';
$route['admin/tambah_shift'] = 'admin/tambah_shift';
$route['admin/edit_shift/(:num)'] = 'admin/edit_shift/$1';
$route['admin/hapus_shift/(:num)'] = 'admin/hapus_shift/$1';
$route['admin/log_absensi'] = 'admin/log_absensi';
$route['admin/detail_log_absensi/(:num)'] = 'admin/detail_log_absensi/$1';
$route['admin/slip_gaji'] = 'admin/cetak_slip_gaji';
$route['admin/export_arsip_gaji_csv'] = 'admin/export_arsip_gaji_csv';

// $route['admin/simpan_lokasi_absen'] = 'admin/simpan_lokasi_absen';
// $route['admin/lokasi_absen'] = 'admin/lokasi_absen';
// $route['admin/lokasi_absen/hapus/(:num)'] = 'admin/hapus_lokasi/$1';
// $route['admin/lokasi_absen/edit/(:num)'] = 'admin/edit_lokasi/$1';

$route['lokasi_absen'] = 'lokasi/lokasi_absen';
$route['lokasi_absensi'] = 'lokasi/lokasi_absensi';
$route['lokasi_absen/hapus/(:num)'] = 'lokasi/hapus_lokasi/$1';
$route['lokasi_absen/edit/(:num)'] = 'lokasi/edit_lokasi/$1';

$route['shift'] = 'shift/index';
$route['shift/tambah_shift'] = 'shift/tambah_shift';
$route['shift/edit_shift/(:num)'] = 'shift/edit_shift/$1';
$route['shift/hapus_shift/(:num)'] = 'shift/hapus_shift/$1';

$route['profil/edit'] = 'profil/edit';
$route['profil/update'] = 'profil/update';
$route['profil/ganti_password'] = 'profil/ganti_password';

$route['lembur'] = 'lembur/index';
$route['lembur/add'] = 'lembur/add';
$route['tambahan_lain'] = 'tambahan_lain/index';
$route['tambahan_lain/input'] = 'tambahan_lain/input';
$route['tambahan_lain/detail'] = 'tambahan_lain/detail';

$route['jadwal_shift'] = 'jadwal_shift/index';
$route['jadwal_shift/form'] = 'jadwal_shift/form';
$route['jadwal_shift/input_jadwal_shift'] = 'jadwal_shift/input_jadwal_shift';
$route['beranda'] = 'beranda/dashboard';
$route['halaman'] = 'halaman';

$route['generatetabel'] = 'GenerateTabel';
$route['pegawai/laporan_gaji'] = 'Gaji/index';
$route['pegawai/log_absensi'] = 'LogAbsensi/index';
$route['pegawai/log_absensi_detail'] = 'LogAbsensi/detail';
$route['pegawai/jadwal_detail'] = 'EmployeeDetails/jadwal_detail';
$route['pegawai/lembur'] = 'EmployeeDetails/lembur';
$route['pegawai/kasbon'] = 'EmployeeDetails/kasbon';
$route['pegawai/potongan'] = 'EmployeeDetails/potongan';
$route['pegawai/tambahan'] = 'EmployeeDetails/tambahan';
$route['pegawai/deposit'] = 'EmployeeDetails/deposit';
$route['pegawai/dashboard'] = 'EmployeeDashboard/index';
$route['default_controller'] = 'beranda/dashboard';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;