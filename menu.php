<?php

session_start();

$conn = mysqli_connect('localhost','root','','kasir');


// Jika tombol bayar ditekan
if(isset($_POST['bayar'])){
    // Konversi ke float untuk memastikan nilai numerik
    $total = floatval(str_replace(['Rp', ',', ' '], '', $_POST['total']));
    $bayar = floatval($_POST['bayar']);
    
    // Validasi input
    if($bayar < $total) {
        $_SESSION['error'] = "Pembayaran kurang dari total!";
        header("Location: menu.php");
        exit();
    }
    
    $kembali = $bayar - $total; 
    
    // Simpan transaksi ke database
    $insert = mysqli_query($conn, "INSERT INTO penjualan (total, bayar, kembali, tanggal) VALUES ('$total', '$bayar', '$kembali', NOW())");
    
    if($insert){
        $id_penjualan = mysqli_insert_id($conn);
        
        // Simpan detail transaksi
        foreach($_SESSION['cart'] as $key => $value){
            $id_barang = $value['id'];
            $qty = $value['qty'];
            $harga = $value['harga'];
            $subtotal = $value['qty'] * $value['harga'];
            
            $insert_detail = mysqli_query($conn, "INSERT INTO detail_penjualan (id_penjualan, id_barang, qty, harga, subtotal) 
                        VALUES ('$id_penjualan', '$id_barang', '$qty', '$harga', '$subtotal')");
    
    if (!$insert_detail) {
        // Handle error
        $_SESSION['error'] = "Gagal menyimpan detail transaksi: " . mysqli_error($conn);
        header("Location: menu.php");
        exit();
    }
            
            // Kurangi stok barang
               $update_stock = mysqli_query($conn, "UPDATE barang_masuk SET jumlah = jumlah - $qty WHERE id = '$id_barang'");
    
    if (!$update_stock) {
        $_SESSION['error'] = "Gagal update stok barang: " . mysqli_error($conn);
        header("Location: index.php");
        exit();
    }
        }
        
        // Kosongkan keranjang
        unset($_SESSION['cart']);
        
        // Redirect ke halaman struk
        header("Location: struk.php?id=$id_penjualan");
    }
}

// Jika tombol tambah ke keranjang ditekan
    if(isset($_POST['tambah_keranjang'])){
        $idbarang = $_POST['idbarang'];
        $qty = $_POST['qty'];
        
        // Ambil data barang dari database
        $barang = mysqli_query($conn, "SELECT * FROM barang_masuk WHERE id='$idbarang'");
        $data = mysqli_fetch_array($barang);
        

        if($data['jumlah'] < $qty) {
            $_SESSION['error'] = "Stok tidak cukup! Stok tersedia: " . $data['jumlah'];
            header("Location: menu.php");
            exit();
        }


        // Inisialisasi keranjang jika belum ada
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
        
        // Cek apakah barang sudah ada di keranjang
        $index = -1;
        foreach($_SESSION['cart'] as $key => $value){
            if($value['id'] == $idbarang){
                $index = $key;
                break;
            }
        }
        
        if($index == -1){
            // Tambah barang baru ke keranjang
            $_SESSION['cart'][] = array(
                'id' => $idbarang,
                'nama' => $data['nama_barang'],
                'harga' => $data['harga'],
                'qty' => $qty
            );
        } else {
            // Update quantity jika barang sudah ada
            $_SESSION['cart'][$index]['qty'] += $qty;
        }
}

// Jika tombol hapus dari keranjang ditekan
    if(isset($_GET['hapus'])){
        $index = $_GET['hapus'];
        unset($_SESSION['cart'][$index]);
        // Reindex array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        header("Location: menu.php");
    }

// Hitung total belanja
    $total = 0;
    if(isset($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $key => $value){
            $total += $value['harga'] * $value['qty'];
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Penjualan</title>
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
                        <a class="nav-link active" href="menu.php">
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
                    <h1 class="mt-2 text-center">Penjualan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Manajemen Penjualan</li>
                    </ol>
  
                    <div class="row">
                        <!-- Daftar Produk -->
                        <div class="col-md-5 mb-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-box me-1"></i>
                                    Daftar Produk
                                </div>
                                <div class="card-body product-list">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Cari produk..." id="searchProduct">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="list-group">
                                        <?php
                                        $barang = mysqli_query($conn, "SELECT * FROM barang_masuk ORDER BY nama_barang");
                                        while($data = mysqli_fetch_array($barang)){
                                        ?>
                                        <a href="#" class="list-group-item list-group-item-action product-item" data-id="<?php echo $data['id']; ?>" data-nama="<?php echo $data['nama_barang']; ?>" data-harga="<?php echo $data['harga']; ?>">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo $data['nama_barang']; ?></h6>
                                                <small>Rp <?php echo number_format($data['harga']); ?></small>
                                            </div>
                                            <small class="text-muted">Stok: <?php echo $data['jumlah']; ?></small>
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Keranjang Belanja -->
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    Keranjang Belanja
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Barang</th>
                                                    <th width="100">Qty</th>
                                                    <th width="120">Harga</th>
                                                    <th width="120">Subtotal</th>
                                                    <th width="60">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
                                                    foreach($_SESSION['cart'] as $key => $value){
                                                ?>
                                                <tr>
                                                    <td><?php echo $value['nama']; ?></td>
                                                    <td><?php echo $value['qty']; ?></td>
                                                    <td>Rp <?php echo number_format($value['harga']); ?></td>
                                                    <td>Rp <?php echo number_format($value['harga'] * $value['qty']); ?></td>
                                                    <td class="text-center">
                                                        <a href="?hapus=<?php echo $key; ?>" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Keranjang belanja kosong</td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-right">Total</th>
                                                    <th colspan="2">Rp <?php echo number_format($total); ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    
                                    <!-- Form Pembayaran -->
                                    <form method="post" action="">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Total</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="total" id="total" value="<?php echo $total; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-2">
                                            <label class="col-sm-3 col-form-label">Bayar</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" name="bayar" id="bayar" required min="<?php echo $total; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-2">
                                            <label class="col-sm-3 col-form-label">Kembali</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="kembali" id="kembali" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12 text-right mt-3">
                                                <button type="submit" name="kasir" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-check-circle me-1"></i> Bayar
                                                </button>
                                                <a href="?reset=true" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times-circle me-1"></i> Batal
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
    
    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah ke Keranjang</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" id="modalNama" readonly>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="text" class="form-control" id="modalHarga" readonly>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control" name="qty" id="modalQty" value="1" min="1" required>
                        </div>
                        <input type="hidden" name="idbarang" id="modalId">
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_keranjang" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        $(document).ready(function(){
            // Ketika produk dipilih
            $('.product-item').click(function(){
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var harga = $(this).data('harga');
                
                $('#modalId').val(id);
                $('#modalNama').val(nama);
                $('#modalHarga').val('Rp ' + harga.toLocaleString());
                
                $('#modalTambah').modal('show');
            });
            
            // Hitung kembalian
            $('#bayar').keyup(function(){
                var total = parseFloat($('#total').val());
                var bayar = parseFloat($(this).val());
                
                if(bayar >= total){
                    var kembali = bayar - total;
                    $('#kembali').val('Rp ' + kembali.toLocaleString());
                } else {
                    $('#kembali').val('Rp 0');
                }
            });
            
            // Pencarian produk
            $('#searchProduct').keyup(function(){
                var value = $(this).val().toLowerCase();
                $('.product-item').filter(function(){
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</body>
</html>