<?php

$conn = mysqli_connect('localhost','root','','kasir');

if(!isset($_GET['id'])) {
    header("Location: menu.php");
    exit();
}

$id_penjualan = $_GET['id'];
$penjualan = mysqli_query($conn, "SELECT * FROM penjualan WHERE id_penjualan = '$id_penjualan'");
$data_penjualan = mysqli_fetch_array($penjualan);

$detail = mysqli_query($conn, "SELECT * FROM detail_penjualan 
                              JOIN barang_masuk ON detail_penjualan.id_barang = barang_masuk.id 
                              WHERE id_penjualan = '$id_penjualan'");




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Struk Penjualan</title>
     <style>
        /* CSS Internal untuk Struk */
        body { 
            font-family: 'Courier New', monospace; 
            margin: 0;  
            padding: 0;
            font-size: 14px;
            background-color: #f5f5f5;
        }
        .struk { 
            width: 280px; 
            margin: 40px auto; 
            padding: 15px; 
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header { 
            text-align: center; 
            margin-bottom: 15px; 
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        .item { 
            margin-bottom: 5px; 
            display: flex;
            justify-content: space-between;
        }
        .item p {
            margin: 2px 0;
        }
        .total { 
            border-top: 1px dashed #000; 
            padding-top: 10px; 
            margin-top: 15px; 
        }
        .text-right { 
            text-align: right; 
        }
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .no-print { 
            text-align: center; 
            margin-top: 20px; 
        }
        .btn {
            padding: 8px 15px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        /* Style untuk printing */
        @media print {
            body { 
                padding: 0; 
                background-color: white;
            }
            .no-print { 
                display: none; 
            }
            .struk { 
                width: 100%; 
                margin: 0;
                padding: 10px;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="struk">
        <div class="header">
            <h2>Toko Deni jaya</h2>
            <p>Jl. Abusamah No. 0612</p>
            <p>Telp: 081273165827</p>
        </div>
        
        <p>No. Transaksi: <?= $id_penjualan ?></p>
        <p>Tanggal: <?= $data_penjualan['tanggal'] ?></p>
        
        <hr>
        
        <?php while($row = mysqli_fetch_array($detail)): ?>
        <div class="item">
            <p><?= $row['nama_barang'] ?> (<?= $row['qty'] ?> x Rp <?= number_format($row['harga']) ?>)</p>
            <p class="text-right">Rp <?= number_format($row['subtotal']) ?></p>
        </div>
        <?php endwhile; ?>
        
        <div class="total">
            <p>Total: Rp <?= number_format($data_penjualan['total']) ?></p>
            <p>Bayar: Rp <?= number_format($data_penjualan['bayar']) ?></p>
            <p>Kembali: Rp <?= number_format($data_penjualan['kembali']) ?></p>
        </div>
        
        <hr>
        
        <p>Terima kasih atas kunjungannya</p>
    </div>
    
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">Cetak Struk</button>
        <a href="menu.php" class="btn btn-secondary">Kembali ke Penjualan</a>
    </div>
</body>
</html>