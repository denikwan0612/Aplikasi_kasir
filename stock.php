<?php

require 'ceklogin.php';

?>



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Stock Barang</title>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            main {
                background-color: lightblue;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <h2 class="navbar-brand ps-3">Aplikasi Kasir</h2>

            <!-- Navbar Dropdown -->
        <div class="d-none d-md-inline-block ms-auto me-4">
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user me-1"></i> Admin
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="dropdownMenuButton">
                    <h6 class="dropdown-header">Menu Admin:</h6>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>

            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="menu.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                                Penjualan
                            </a>
                             <a class="nav-link active  " href="stock.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-solid fa-box"></i></div>
                                Stock Barang
                            </a>
                             <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-down"></i></div>
                                Barang Masuk
                            </a>
                            <div class="sb-sidenav-menu-heading">Laporan</div>
                            <a class="nav-link" href="laporan_harian.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                                Laporan Harian
                            </a>
                            <a class="nav-link" href="laporan_bulanan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-invoice"></i></div>
                                Laporan Bulanan
                             </a>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLaporanStok" aria-expanded="false" aria-controls="collapseLaporanStok">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                            Laporan Stok
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                             </a>
                             <div class="collapse show" id="collapseLaporanStok" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="laporan_stok_masuk.php">
                                        <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-down"></i></div>
                                        Laporan Masuk
                                    </a>
                                    <a class="nav-link" href="laporan_stok_keluar.php">
                                        <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-up"></i></div>
                                        Laporan Keluar
                                    </a>
                                    <a class="nav-link" href="laporan_keseluruhan.php">
                                        <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-up"></i></div>
                                        Laporan Keseluruhan
                                    </a>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Account</div>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Admin
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class=" text-center mt-4">STOCK BARANG</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active fw-bold">Manajemen Stock Barang</li>
                        </ol>
                       
                        <div class="card mb-4">
                            <div class="card-header bg-dark text-white">
                                <i class="fas fa-table me-1"></i>
                                Data Stock Barang
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    <?php
                                        $stock = mysqli_query($conn, "SELECT * FROM barang_masuk");
                                        $i = 1;
                                        
                                        while($data = mysqli_fetch_array($stock)){
                                            $id = $data['id'];
                                            $nama_barang = $data['nama_barang'];
                                            $jumlah = $data['jumlah'];
                                            $harga = $data['harga'];
                                        ?>
                                       
                                            <tr>
                                                <td><?=$i++;?></td>
                                                <td><?=$nama_barang;?></td>
                                                <td><?=$jumlah;?></td>
                                                <td>Rp<?=number_format($harga)?></td>
                                            </tr>
                                        <?php  
                                        }
                                        ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Website Deni Kurniawan</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>