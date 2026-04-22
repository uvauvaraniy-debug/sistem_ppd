<?php
session_start();
include "config.php";

// Kira Buku
$q_buku = mysqli_query($conn, "SELECT COUNT(*) as total FROM buku WHERE jenis_bahan = 'Buku'");
$d_buku = mysqli_fetch_assoc($q_buku);

// Kira Bahan Bukan Buku
$q_bukan = mysqli_query($conn, "SELECT COUNT(*) as total FROM buku WHERE jenis_bahan = 'Bukan Buku'");
$d_bukan = mysqli_fetch_assoc($q_bukan);

// Kira Rekod Bacaan
$q_rekod = mysqli_query($conn, "SELECT COUNT(*) as total FROM rekod_bacaan");
$d_rekod = mysqli_fetch_assoc($q_rekod);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Analisis Sistem PPD</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('image_449412.png') no-repeat center center fixed; background-size: cover; color: white; text-align: center; }
        .container { padding: 50px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1000px; margin: 40px auto; }
        .card { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 40px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.2); }
        .num { font-size: 50px; font-weight: bold; color: #00d2ff; }
        .label { font-size: 14px; letter-spacing: 1px; margin-top: 10px; display: block; }
    </style>
</head>
<body>
<div class="container">
    <h1>RINGKASAN EKSEKUTIF PEROLEHAN PPD</h1>
    <div class="grid">
        <div class="card">
            <span class="num"><?php echo $d_buku['total']; ?></span>
            <span class="label">ANALISIS PEROLEHAN BUKU</span>
        </div>
        <div class="card">
            <span class="num"><?php echo $d_rekod['total']; ?></span>
            <span class="label">ANALISIS REKOD BACAAN</span>
        </div>
        <div class="card">
            <span class="num"><?php echo $d_bukan['total']; ?></span>
            <span class="label">BAHAN BUKAN BUKU</span>
        </div>
    </div>
    <a href="dashboard.php" style="color:white; text-decoration:none; border:1px solid white; padding:10px 20px; border-radius:20px;">Kembali ke Dashboard</a>
</div>
</body>
</html>