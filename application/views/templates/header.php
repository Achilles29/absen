<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/favicon.ico">

    <title><?= isset($title) ? $title : 'Admin Panel' ?></title> <!-- Title dinamis -->

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <?php if ($this->session->userdata('role') === 'admin'): ?>
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url(); ?>">
                <?php endif; ?>
                <?php if ($this->session->userdata('role') !== 'admin'): ?>
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url(); ?>/pegawai/dashboard">
                    <?php endif; ?>
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">NAMUA <br> E-PRESENSI</div>
                    </a>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">

                    <!-- Nav Item - Dashboard -->
                    <li class="nav-item active">
                        <?php if (in_array($this->session->userdata('role'), ['admin', 'spv'])): ?>
                            <a class="nav-link" href="<?php echo base_url(); ?>beranda">
                            <?php endif; ?>

                            <?php if (in_array($this->session->userdata('role'), ['hod', 'pegawai'])): ?>
                                <a class="nav-link" href="<?php echo base_url(); ?>/pegawai/dashboard">
                                <?php endif; ?>
                                <i class="fas fa-fw fa-tachometer-alt"></i>
                                <span>Dashboard</span></a>
                    </li>

                    <!-- Divider -->
                    <hr class="sidebar-divider">

                    <!-- Heading -->
                    <div class="sidebar-heading">
                        Interface
                    </div>

                    <!-- Menu ADMIN -->
                    <li class="nav-item">
                        <?php if ($this->session->userdata('role') !== 'admin'): ?>
                            <a class="nav-link" href="/pegawai/absen">
                                <i class="fas fa-fw fa-chart-area"></i>
                                <span>Absensi</span>
                            </a>
                        <?php endif; ?>
                        <?php if (in_array($this->session->userdata('role'), ['admin', 'spv'])): ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-fw fa-cog"></i>
                                <span>Master</span>
                            </a>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <a class="collapse-item" <a href="<?= site_url('admin/master_pegawai') ?>"><i class="fas fa-user-cog"></i> Master Pegawai</a>
                                    <a class="collapse-item" <a href="<?= site_url('admin/master_kode_user') ?>"><i class="fas fa-user-cog"></i> Kode User
                                        <a class="collapse-item" <a href="<?= site_url('lokasi_absen') ?>"><i class="fas fa-map-marker-alt"></i> Lokasi Absen</a>
                                        <a class="collapse-item" <a href="<?= site_url('lokasi_absensi') ?>"><i class="fas fa-map-marker-alt"></i> Tambah Lokasi Absen</a>
                                        <a class="collapse-item" <a href="<?= site_url('divisi') ?>"><i class="far fa-id-card"></i> Divisi</a>
                                        <a class="collapse-item" <a href="<?= site_url('divisi/jabatan') ?>"><i class="far fa-id-card"></i> Jabatan</a>
                                        <a class="collapse-item" <a href="<?= site_url('shift') ?>"><i class="far fa-id-card"></i> Jadwal Shift</a>
                                        <a class="collapse-item" <a href="<?= site_url('lembur/master') ?>"><i class="fas fa-user-cog"></i> Nilai Lembur</a>
                                        <a class="collapse-item" <a href="<?= site_url('admin/generate_rekap_absensi_harian') ?>"><i class="far fa-id-card"></i> Generate</a>
                                        <a class="collapse-item" <a href="<?= site_url('bank') ?>"><i class="far fa-id-card"></i> Bank</a>

                                </div>
                            </div>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('role'), ['admin', 'spv', 'hod'])): ?>
                    <li class="nav-item">

                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix"
                            aria-expanded="true" aria-controls="collapseSix">
                            <i class="fas fa-user-tie"></i>
                            <span>Jadwal Pegawai</span>
                        </a>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" <a href="<?= site_url('jadwal_shift') ?>"><i class="far fa-id-card"></i> Log Jadwal</a>
                                <a class="collapse-item" <a href="<?= site_url('jadwal_shift/input_jadwal_shift_tabel') ?>"><i class="far fa-id-card"></i> Jadwal Tabel</a>
                                <a class="collapse-item" <a href="<?= site_url('jadwal_shift/jadwal_tabel') ?>"><i class="far fa-id-card"></i> Jadwal Tabel Ver 2</a>
                                <a class="collapse-item" <a href="<?= site_url('jadwal_shift/jadwal_shift_bulanan') ?>"><i class="far fa-id-card"></i> Jadwal Bulanan</a>
                                <a class="collapse-item" <a href="<?= site_url('jadwal_shift/input_jadwal_shift') ?>"><i class="fas fa-user-cog"></i> Input Manual</a>
                                <a class="collapse-item" <pitul href="<?= site_url('jadwal_shift/rekap_jadwal') ?>"><i class="far fa-id-card"></i> Rekapitulasi Jadwal</a>


                            </div>
                        </div>
                    </li>

                <?php endif; ?>

                <?php if (in_array($this->session->userdata('role'), ['admin', 'spv'])): ?>

                    <li class="nav-item">

                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                            aria-expanded="true" aria-controls="collapseTwo">
                            <i class="fas fa-user-tie"></i>
                            <span>Absen</span>
                        </a>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <!-- <a class="collapse-item" <a href="<?= site_url('admin/absen_pegawai_pending') ?>"><i class="far fa-id-card"></i> Absen Pegawai</a> -->
                                <a class="collapse-item" <a href="<?= site_url('admin/verifikasi_absen') ?>"><i class="far fa-id-card"></i> Absen Pending</a>
                                <a class="collapse-item" <a href="<?= site_url('admin/rekap_absensi_bulanan') ?>"><i class="far fa-id-card"></i> Rekapitulasi Absen</a>
                                <a class="collapse-item" <a href="<?= site_url('admin/log_absensi') ?>"><i class="fas fa-user-cog"></i> Log Absensi</a>
                                <a class="collapse-item" <a href="<?= site_url('admin/log_absensi_total') ?>"><i class="fas fa-user-cog"></i> Log Absensi Total</a>

                            </div>
                        </div>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
                            aria-expanded="true" aria-controls="collapseThree">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Lembur</span>
                        </a>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" <a href="<?= site_url('lembur') ?>"><i class="far fa-id-card"></i> Lembur</a>
                                <a class="collapse-item" <a href="<?= site_url('lembur/master') ?>"><i class="fas fa-user-cog"></i> Nilai Lembur</a>
                                <a class="collapse-item" <a href="<?= site_url('lembur/input') ?>"><i class="fas fa-user-cog"></i> Input Lembur</a>
                                <a class="collapse-item" <a href="<?= site_url('lembur/laporan') ?>"><i class="far fa-id-card"></i> Laporan Lembur</a>

                            </div>
                        </div>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree1"
                            aria-expanded="true" aria-controls="collapseThree1">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Tambahan Lain</span>
                        </a>
                        <div id="collapseThree1" class="collapse" aria-labelledby="headingThree1" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" <a href="<?= site_url('tambahan_lain/log_tambahan') ?>"><i class="far fa-id-card"></i> Input</a>
                                <a class="collapse-item" <a href="<?= site_url('tambahan_lain') ?>"><i class="far fa-id-card"></i> Rekap Tambahan</a>

                            </div>
                        </div>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree2"
                            aria-expanded="true" aria-controls="collapseThree2">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Kasbon</span>
                        </a>
                        <div id="collapseThree2" class="collapse" aria-labelledby="headingThree2" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" <a href="<?= site_url('kasbon/log_kasbon') ?>"><i class="far fa-id-card"></i> Input</a>
                                <a class="collapse-item" <a href="<?= site_url('kasbon') ?>"><i class="far fa-id-card"></i> Rekap Tambahan</a>

                            </div>
                        </div>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree3"
                            aria-expanded="true" aria-controls="collapseThree3">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Potongan</span>
                        </a>
                        <div id="collapseThree3" class="collapse" aria-labelledby="headingThree3" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" <a href="<?= site_url('potongan/log') ?>"><i class="far fa-id-card"></i> Input</a>
                                <a class="collapse-item" <a href="<?= site_url('potongan') ?>"><i class="far fa-id-card"></i> Rekap Tambahan</a>

                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive"
                            aria-expanded="true" aria-controls="collapseFive">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Deposit</span>
                        </a>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" <a href="<?= site_url('deposit/log') ?>"><i class="fas fa-user-cog"></i> Input</a>
                                <a class="collapse-item" <a href="<?= site_url('deposit') ?>"><i class="fas fa-user-cog"></i> Deposit</a>

                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour"
                            aria-expanded="true" aria-controls="collapseFour">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Laporan</span>
                        </a>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" <a href="<?= site_url('admin/laporan_gaji') ?>"><i class="far fa-id-card"></i> Laporan Gaji</a>
                                <a class="collapse-item" <a href="<?= site_url('lembur/laporan') ?>"><i class="fas fa-user-cog"></i> Laporan Lembur</a>
                                <a class="collapse-item" <a href="<?= site_url('admin/rekap_absensi_bulanan') ?>"><i class="far fa-id-card"></i> Rekapitulasi Absen</a>
                                <a class="collapse-item" <a href="<?= site_url('admin/rekap_absensi_harian') ?>"><i class="far fa-id-card"></i> Rekap Absen Harian</a>
                                <a class="collapse-item" <a href="<?= site_url('admin/arsip_gaji') ?>"><i class="far fa-id-card"></i> Arsip Gaji</a>

                            </div>
                        </div>
                    <?php endif; ?>

                    </li>
                    <li class="nav-item">
                        <?php if ($this->session->userdata('role') !== 'admin'): ?>

                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSeven"
                                aria-expanded="true" aria-controls="collapseSeven">
                                <i class="fas fa-user-tie"></i>
                                <span>Laporan Pegawai</span>
                            </a>
                            <div id="collapseSeven" class="collapse" aria-labelledby="headingSSeven" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/laporan_gaji') ?>"><i class="far fa-id-card"></i> Laporan Gaji</a>
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/log_absensi') ?>"><i class="far fa-id-card"></i> Log Absensi</a>
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/log_absensi_detail') ?>"><i class="far fa-id-card"></i> Log Absensi Detail</a>
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/jadwal_detail') ?>"><i class="far fa-id-card"></i> Jadwal Detail</a>
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/lembur') ?>"><i class="fas fa-user-cog"></i> Lembur</a>
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/kasbon') ?>"><i class="far fa-id-card"></i> Kasbon</a>
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/potongan') ?>"><i class="fas fa-user-cog"></i> Potongan</a>
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/tambahan') ?>"><i class="fas fa-user-cog"></i> Tambahan Lain</a>
                                    <a class="collapse-item" <a href="<?= site_url('pegawai/deposit') ?>"><i class="fas fa-user-cog"></i> Deposit</a>

                                </div>
                            </div>
                        <?php endif; ?>

                    </li>

                    <!-- Nav Item -->
                    <li class="nav-item">
                        <a class="nav-link" href="/profil">
                            <i class="fas fa-fw fa-chart-area"></i>
                            <span>Profil</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/logout">
                            <i class="fas fa-fw fa-chart-area"></i>
                            <span>Logout</span></a>
                    </li>


                    <!-- Divider -->
                    <hr class="sidebar-divider d-none d-md-block">

                    <!-- Sidebar Toggler (Sidebar) -->
                    <div class="text-center d-none d-md-inline">
                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                    </div>

                    <!-- Sidebar Message -->
                    <div class="sidebar-card d-none d-lg-flex">
                        <img class="sidebar-card-illustration mb-2" src="<?php echo base_url(); ?>assets/img/undraw_rocket.svg" alt="...">
                        <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                        <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
                    </div>

        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="<?php echo base_url(); ?>assets/img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="<?php echo base_url(); ?>assets/img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="<?php echo base_url(); ?>assets/img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= $this->session->userdata('nama'); ?> <!-- Nama User -->
                                </span>
                                <img class="img-profile rounded-circle" src="<?= base_url('uploads/' . $this->session->userdata('avatar')); ?>" width="30" height="30">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="/profil">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= site_url('auth/logout') ?>">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">