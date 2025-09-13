<?php
include 'ceklogin.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Data Barang Masuk</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
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
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
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
                        <a class="nav-link active" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-down"></i></div>
                            Barang Masuk
                        </a>
                        <div class="sb-sidenav-menu-heading">Laporan</div>
                        <a class="nav-link" href="laporan_harian.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                                Laporan Harian
                        </a>
                        <a class="nav-link" href="laporan_bulanan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
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
                    <h1 class="mt-4 text-center">Barang Masuk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Manajemen Stok Barang Masuk</li>
                    </ol>

                    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#tambahModal">
                        <i class="fas fa-plus-circle"></i> Tambah Barang Masuk
                    </button>

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <i class="fas fa-table me-1"></i>
                            Data Barang Masuk
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = mysqli_query($conn, "SELECT * FROM barang_masuk ORDER BY tanggal DESC");
                                        $i = 1;
                                        $total_keseluruhan = 0;
                                        
                                        while($data = mysqli_fetch_array($query)){
                                            $id = $data['id'];
                                            $tanggal = $data['tanggal'];
                                            $nama_barang = $data['nama_barang'];
                                            $jumlah = $data['jumlah'];
                                            $harga = $data['harga'];
                                            $total = $jumlah * $harga;
                                            $total_keseluruhan += $total;
                                        ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= date('d/m/Y', strtotime($tanggal)); ?></td>
                                            <td><?= $nama_barang; ?></td>
                                            <td><?= number_format($jumlah, 0, ',', '.'); ?></td>
                                            <td>Rp <?= number_format($harga, 0, ',', '.'); ?></td>
                                            <td>Rp <?= number_format($total, 0, ',', '.'); ?></td>
                                            <td class="action-buttons">
                                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $id; ?>">Edit
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $id; ?>">Hapus
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal<?= $id; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang Masuk</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="post">
                                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Tanggal</label>
                                                                <input type="date" name="tanggal" class="form-control" value="<?= $tanggal; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama Barang</label>
                                                                <input type="text" name="nama_barang" class="form-control" value="<?= $nama_barang; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Jumlah</label>
                                                                <input type="number" name="jumlah" class="form-control" value="<?= $jumlah; ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Harga</label>
                                                                <input type="number" name="harga" class="form-control" value="<?= $harga; ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success" name="updatebarang">Update</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus -->
                                        <div class="modal fade" id="hapusModal<?= $id; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Konfirmasi Hapus</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus data barang masuk ini?</p>
                                                        <p><strong>Tanggal:</strong> <?= date('d/m/Y', strtotime($tanggal)); ?></p>
                                                        <p><strong>Barang:</strong> <?= $nama_barang; ?></p>
                                                        <p><strong>Jumlah:</strong> <?= $jumlah; ?></p>
                                                        <p><strong>Harga:</strong> Rp <?= number_format($harga, 0, ',', '.'); ?></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="masuk.php?hapus=<?= $id; ?>" class="btn btn-danger">Hapus</a>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="total-row">
                                            <th colspan="5" class="text-right">Total Keseluruhan:</th>
                                            <th colspan="2">Rp <?= number_format($total_keseluruhan, 0, ',', '.'); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang Masuk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan Nama Barang" required>
                        </div>
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" placeholder="Masukkan Jumlah" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" name="harga" class="form-control" placeholder="Masukkan Harga" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="barangmasuk">Simpan</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
 
    <script>
        // Auto focus pada modal tambah
        $('#tambahModal').on('shown.bs.modal', function () {
            $(this).find('[name="nama_barang"]').focus();
        });
    </script>
</body>
</html>