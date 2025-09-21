<?php


session_start();

// untuk koneksi
$conn = mysqli_connect('localhost','root','','kasir');


// // Jika tombol bayar ditekan
// if(isset($_POST['bayar'])){
//     // Konversi ke float untuk memastikan nilai numerik
//     $total = floatval(str_replace(['Rp', ',', ' '], '', $_POST['total']));
//     $bayar = floatval($_POST['bayar']);
    
//     // Validasi input
//     if($bayar < $total) {
//         $_SESSION['error'] = "Pembayaran kurang dari total!";
//         header("Location: menu.php");
//         exit();
//     }
    
//     $kembali = $bayar - $total; 
    
//     // Simpan transaksi ke database
//     $insert = mysqli_query($conn, "INSERT INTO penjualan (total, bayar, kembali, tanggal) VALUES ('$total', '$bayar', '$kembali', NOW())");
    
//     if($insert){
//         $id_penjualan = mysqli_insert_id($conn);
        
//         // Simpan detail transaksi
//         foreach($_SESSION['cart'] as $key => $value){
//             $id_barang = $value['id'];
//             $qty = $value['qty'];
//             $harga = $value['harga'];
//             $subtotal = $value['qty'] * $value['harga'];
            
//             $insert_detail = mysqli_query($conn, "INSERT INTO detail_penjualan (id_penjualan, id_barang, qty, harga, subtotal) 
//                         VALUES ('$id_penjualan', '$id_barang', '$qty', '$harga', '$subtotal')");
    
//     if (!$insert_detail) {
//         // Handle error
//         $_SESSION['error'] = "Gagal menyimpan detail transaksi: " . mysqli_error($conn);
//         header("Location: menu.php");
//         exit();
//     }
            
//             // Kurangi stok barang
//                $update_stock = mysqli_query($conn, "UPDATE barang_masuk SET jumlah = jumlah - $qty WHERE id = '$id_barang'");
    
//     if (!$update_stock) {
//         $_SESSION['error'] = "Gagal update stok barang: " . mysqli_error($conn);
//         header("Location: index.php");
//         exit();
//     }
//         }
        
//         // Kosongkan keranjang
//         unset($_SESSION['cart']);
        
//         // Redirect ke halaman struk
//         header("Location: struk.php?id=$id_penjualan");
//     }
// }

// // Jika tombol tambah ke keranjang ditekan
//     if(isset($_POST['tambah_keranjang'])){
//         $idbarang = $_POST['idbarang'];
//         $qty = $_POST['qty'];
        
//         // Ambil data barang dari database
//         $barang = mysqli_query($conn, "SELECT * FROM barang_masuk WHERE id='$idbarang'");
//         $data = mysqli_fetch_array($barang);
        

//         if($data['jumlah'] < $qty) {
//             $_SESSION['error'] = "Stok tidak cukup! Stok tersedia: " . $data['jumlah'];
//             header("Location: menu.php");
//             exit();
//         }


//         // Inisialisasi keranjang jika belum ada
//         if(!isset($_SESSION['cart'])){
//             $_SESSION['cart'] = array();
//         }
        
//         // Cek apakah barang sudah ada di keranjang
//         $index = -1;
//         foreach($_SESSION['cart'] as $key => $value){
//             if($value['id'] == $idbarang){
//                 $index = $key;
//                 break;
//             }
//         }
        
//         if($index == -1){
//             // Tambah barang baru ke keranjang
//             $_SESSION['cart'][] = array(
//                 'id' => $idbarang,
//                 'nama' => $data['nama_barang'],
//                 'harga' => $data['harga'],
//                 'qty' => $qty
//             );
//         } else {
//             // Update quantity jika barang sudah ada
//             $_SESSION['cart'][$index]['qty'] += $qty;
//         }
// }

// // Jika tombol hapus dari keranjang ditekan
//     if(isset($_GET['hapus'])){
//         $index = $_GET['hapus'];
//         unset($_SESSION['cart'][$index]);
//         // Reindex array
//         $_SESSION['cart'] = array_values($_SESSION['cart']);
//         header("Location: menu.php");
//     }

// // Hitung total belanja
//     $total = 0;
//     if(isset($_SESSION['cart'])){
//         foreach($_SESSION['cart'] as $key => $value){
//             $total += $value['harga'] * $value['qty'];
//         }
//     }

?> 