<?php
session_start();
include "config.php";

// Jika tak login, tendang balik ke loginppd.php
if (!isset($_SESSION['username'])) { 
    header("Location: loginppd.php"); 
    exit(); 
}

$role = $_SESSION['role'];
$count_pending = 0;

// Logik untuk kira staf yang menunggu pengesahan (Hanya untuk Admin)
if ($role == 'admin') {
    $sql_count = "SELECT COUNT(*) as total FROM users WHERE status_akaun='menunggu'";
    $res_count = mysqli_query($conn, $sql_count);
    if ($res_count) {
        $data_count = mysqli_fetch_assoc($res_count);
        $count_pending = $data_count['total'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard | Sistem PPD</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif; 
    margin: 0;
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                url('image_44847a.jpg') no-repeat center center fixed;
    background-size: cover; 
    color: white;
    overflow-x: hidden;
}

.overlay { min-height: 100vh; width: 100%; display: flex; flex-direction: column; }

.top-nav {
    background: rgba(255, 255, 255, 0.05); 
    backdrop-filter: blur(15px);
    padding: 15px 50px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.container {
    max-width: 1200px; 
    margin: auto;
    display: grid; 
    grid-template-columns: repeat(3, 1fr); 
    gap: 40px; 
    padding: 40px 20px;
    justify-items: center;
}

.card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    padding: 40px 0; 
    border-radius: 20px; 
    text-align: center;
    text-decoration: none; 
    color: white;
    border: 1px solid rgba(255,255,255,0.15);
    transition: all 0.4s ease;
    display: flex; 
    flex-direction: column; 
    align-items: center;
    width: 220px;
    height: 90px;
    position: relative;
}

.card:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
}

.card h3 {
    margin-top: 20px; 
    font-size: 14px; 
    letter-spacing: 1px; 
    font-weight: 600;
    text-transform: uppercase; 
    color: #ffffff;
}

.icon { font-size: 45px; }

.badge {
    position: absolute;
    top: 10px;
    right: 15px;
    background: #ff4b2b;
    color: white;
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 50%;
    font-weight: bold;
    box-shadow: 0 0 10px rgba(255,75,43,0.5);
}

.btn-logout {
    background: #ff4b2b; 
    color: white; 
    padding: 10px 25px; 
    border-radius: 8px;
    text-decoration: none; 
    font-weight: bold;
}

.welcome-text { text-align:center; margin-top: 60px; }
.welcome-text h1 { font-size: 35px; font-weight: 700; margin: 0; text-shadow: 0 5px 15px rgba(0,0,0,0.5); }
.welcome-text p { color: #ddd; font-size: 16px; margin-top: 10px; }

@media(max-width:900px){ .container{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:600px){ .container{ grid-template-columns: 1fr; } }
</style>
</head>
<body>
<div class="overlay">

<div class="top-nav">
    <div style="font-weight: bold; font-size: 22px; letter-spacing: 2px;">
    PPD <span style="color:#00d2ff;">CONTROL</span>
    </div>
    <div>
    <span style="margin-right:20px; font-size: 14px;">
    👤 Logged as: <b><?php echo strtoupper($_SESSION['username']); ?></b>
    </span>
    <a href="logout.php" class="btn-logout">LOGOUT</a>
    </div>
</div>

<div class="welcome-text">
    <h1>PANEL UTAMA SISTEM</h1>
    <p>Sila pilih modul pengurusan untuk meneruskan tugas</p>
</div>

<div class="container">

    <a href="rekod_bacaan.php" class="card">
        <div class="icon">📖</div>
        <h3>Rekod Bacaan</h3>
    </a>

    <a href="analisis.php" class="card">
        <div class="icon">📊</div>
        <h3>Analisis Laporan</h3>
    </a>

    <a href="rujuk_buku.php" class="card">
        <div class="icon">📚</div>
        <h3>Rujuk Buku</h3>
    </a>

    <?php if ($role == 'admin'): ?>
    
    <a href="perolehan.php" class="card">
        <div class="icon">📘</div>
        <h3>Pengurusan Buku</h3>
    </a>

    <a href="sahkan_staf.php" class="card">
        <div class="icon">👥</div>
        <?php if ($count_pending > 0): ?>
            <div class="badge"><?php echo $count_pending; ?></div>
        <?php endif; ?>
        <h3>Sahkan Staf</h3>
    </a>

    <a href="senarai_baru.php" class="card">
        <div class="icon">🗑️</div>
        <h3>Senarai & Padam</h3>
    </a>

    <?php endif; ?>

</div>
</div>
</body>
</html>