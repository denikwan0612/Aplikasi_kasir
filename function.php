<?php


session_start();

// untuk koneksi
$conn = mysqli_connect('localhost','root','','kasir');







// Untuk login

// if(isset($_POST['login'])){
//     // untuk variabel
//     $username =$_POST['username'];
//     $password =$_POST['password'];

//     $check = mysqli_query($conn,"SELECT * FROM user WHERE username='$username' and password='$password'");
//     $hitung = mysqli_num_rows($check);

//     if($hitung>0){
//         // jika datanya ditemukan
//         // berhasil login

//         $_SESSION['login'] = 'true';
//         header('location:login.php');
//     } else{
//         // data tidak ditemukan
//         // gagal login
//         echo '
//         <script>alert("username atau password salah");
//         windows.location.href="login.php"
//         </script>
//         ';
//     }
// }






//Untuk Masuk.php


if(isset($_POST['barangmasuk'])){
    $tanggal = $_POST['tanggal'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    
    $insert = mysqli_query($conn, "INSERT INTO barang_masuk (tanggal, nama_barang, jumlah, harga) 
                                  VALUES ('$tanggal', '$nama_barang', '$jumlah', '$harga')");
    
    if($insert){
        echo "<script>alert('Data berhasil ditambahkan');</script>";
        echo "<script>window.location.href='masuk.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data');</script>";
    }
}

// Proses hapus barang masuk
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $delete = mysqli_query($conn, "DELETE FROM barang_masuk WHERE id='$id'");
    
    if($delete){
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>window.location.href='masuk.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}

// Proses update barang masuk
if(isset($_POST['updatebarang'])){
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    
    $update = mysqli_query($conn, "UPDATE barang_masuk SET 
                                tanggal='$tanggal', 
                                nama_barang='$nama_barang', 
                                jumlah='$jumlah', 
                                harga='$harga' 
                                WHERE id='$id'");
    
    if($update){
        echo "<script>alert('Data berhasil diupdate');</script>";
        echo "<script>window.location.href='masuk.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data');</script>";
    }
}


// if(!isset($_GET['id'])) {
//     header("Location: menu.php");
//     exit();
// }

// $id_penjualan = $_GET['id'];
// $penjualan = mysqli_query($conn, "SELECT * FROM penjualan WHERE id_penjualan = '$id_penjualan'");
// $data_penjualan = mysqli_fetch_array($penjualan);

// $detail = mysqli_query($conn, "SELECT * FROM detail_penjualan 
//                               JOIN barang_masuk ON detail_penjualan.id_barang = barang_masuk.id 
//                               WHERE id_penjualan = '$id_penjualan'");



?> 