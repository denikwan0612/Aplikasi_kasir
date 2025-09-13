<?php
session_start();

$conn = mysqli_connect('localhost','root','','kasir');

// Ambil bulan dan tahun yang dipilih (default bulan dan tahun saat ini)
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Hitung jumlah hari dalam bulan yang dipilih
$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

// Query untuk mendapatkan penjualan per hari dalam bulan yang dipilih
$penjualan_per_hari = array();
for ($hari = 1; $hari <= $jumlah_hari; $hari++) {
    $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
    
    $query = "SELECT SUM(total) as total_harian 
              FROM penjualan 
              WHERE DATE(tanggal) = '$tanggal'";
    
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    
    $penjualan_per_hari[$hari] = $data['total_harian'] ?? 0;
}

// Hitung total penjualan bulanan
$total_bulanan = array_sum($penjualan_per_hari);

// Query untuk mendapatkan detail penjualan bulanan
$query_detail = "SELECT p.tanggal, b.nama_barang, dp.qty, dp.harga, dp.subtotal 
                 FROM detail_penjualan dp 
                 JOIN penjualan p ON dp.id_penjualan = p.id_penjualan 
                 JOIN barang_masuk b ON dp.id_barang = b.id
                 WHERE MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun'
                 ORDER BY p.tanggal DESC";

$result_detail = mysqli_query($conn, $query_detail);
?>  

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Laporan Bulanan</title>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="style.css">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            main {
                background-color: lightblue;
            }
            .print-only {
                display: none;
            }
            .card-header {
                font-weight: bold;
            }
            .table-hover tbody tr:hover {
                background-color: rgba(0, 123, 255, 0.1);
            }
            .bg-total {
                background-color: #f8f9fa;
                font-weight: bold;
            }
            .chart-container {
                height: 300px;
                margin-bottom: 20px;
            }
            @media print {
                .no-print {
                    display: none;
                }
                .print-only {
                    display: block;
                }
                body {
                    padding: 0;
                    background-color: white;
                }
                .card {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }
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
                             <a class="nav-link" href="stock.php">
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
                            <a class="nav-link active" href="laporan_bulanan.php">
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
                         <div class="container mt-4">
                            <h1 class="text-center mb-4 no-print">Laporan Penjualan Bulanan</h1>

                            <!-- Filter Bulan dan Tahun -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white no-print">
                                    <h5 class="mb-0">Filter Laporan</h5>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="">
                                        <div class="row no-print">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="bulan">Pilih Bulan:</label>
                                                    <select class="form-control" id="bulan" name="bulan">
                                                        <?php
                                                        $nama_bulan = array(
                                                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
                                                            '04' => 'April', '05' => 'Mei', '06' => 'Juni', 
                                                            '07' => 'Juli', '08' => 'Agustus', '09' => 'September', 
                                                            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                                        );
                                                        
                                                        foreach ($nama_bulan as $key => $value) {
                                                            $selected = ($key == $bulan) ? 'selected' : '';
                                                            echo "<option value='$key' $selected>$value</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="tahun">Pilih Tahun:</label>
                                                    <select class="form-control" id="tahun" name="tahun">
                                                        <?php
                                                        for ($i = 2020; $i <= date('Y'); $i++) {
                                                            $selected = ($i == $tahun) ? 'selected' : '';
                                                            echo "<option value='$i' $selected>$i</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="submit" class="btn btn-success w-100">Tampilkan</button>
                                            </div>
                                        </div>
                                    </form>
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <h5 class="text-center">Penjualan Bulan <?= $nama_bulan[$bulan] ?> <?= $tahun ?></h5>
                                            </div>
                                        <div class="card-body bg-info">
                                            <div class="row">              
                                                <div class="card-body text-center">
                                                    <h5>Total Penjualan</h5>
                                                    <h3>Rp <?= number_format($total_bulanan, 0, ',', '.') ?></h3>
                                                </div>   
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>


                            <!-- Tabel Penjualan Harian -->
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Penjualan Harian Bulan <?= $nama_bulan[$bulan] ?> <?= $tahun ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Hari</th>
                                                    <th>Tanggal</th>
                                                    <th>Total Penjualan</th>
                                                    <th class="no-print">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total_keseluruhan = 0;
                                                for ($hari = 1; $hari <= $jumlah_hari; $hari++) {
                                                    $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
                                                    $total_harian = $penjualan_per_hari[$hari];
                                                    $total_keseluruhan += $total_harian;
                                                    
                                                    $nama_hari = date('l', strtotime($tanggal));
                                                    $nama_hari_id = array(
                                                        'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                                                        'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
                                                        'Sunday' => 'Minggu'
                                                    )[$nama_hari];
                                                    ?>
                                                    <tr>
                                                        <td><?= $nama_hari_id ?>, <?= $hari ?></td>
                                                        <td><?= date('d/m/Y', strtotime($tanggal)) ?></td>
                                                        <td>Rp <?= number_format($total_harian, 0, ',', '.') ?></td>
                                                        <td class="no-print">
                                                            <a href="laporan_harian.php?tanggal=<?= $tanggal ?>" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-total">
                                                    <td colspan="2" class="text-right"><strong>Total Keseluruhan:</strong></td>
                                                    <td><strong>Rp <?= number_format($total_keseluruhan, 0, ',', '.') ?></strong></td>
                                                    <td class="no-print"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Transaksi -->
                            <div class="card mb-4 no-print">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0">Detail Transaksi Bulan <?= $nama_bulan[$bulan] ?> <?= $tahun ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Nama Barang</th>
                                                    <th>Qty</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                if ($result_detail && mysqli_num_rows($result_detail) > 0) {
                                                    while($row = mysqli_fetch_assoc($result_detail)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                                            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                                            <td><?= $row['qty'] ?></td>
                                                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                                            <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">Tidak ada transaksi pada bulan ini</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Button Kembali dan Cetak -->
                            <div class="text-center no-print mt-4 mb-2">
                                <a href="menu.php" class="btn btn-primary">Kembali ke Menu</a>
                                <button onclick="window.print()" class="btn btn-success">Cetak Laporan</button>
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
       