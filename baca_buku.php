<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "sistem_ppd");

if (!isset($_SESSION['username'])) {
    header("Location: loginppd.php");
    exit();
}

$id_buku = intval($_GET['id']);
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Ambil tajuk buku
$buku_query = mysqli_query($conn, "SELECT tajuk FROM buku WHERE id='$id_buku'");
$buku = mysqli_fetch_assoc($buku_query);

// Ambil halaman semasa
$halaman_query = mysqli_query($conn, "
    SELECT * FROM buku_halaman 
    WHERE id_buku='$id_buku' AND no_halaman='$page'
");
$halaman = mysqli_fetch_assoc($halaman_query);

// Kira jumlah halaman
$total_query = mysqli_query($conn, "
    SELECT COUNT(*) as total FROM buku_halaman 
    WHERE id_buku='$id_buku'
");
$total_data = mysqli_fetch_assoc($total_query);
$total_page = $total_data['total'];

if (!$halaman) {
    echo "Halaman tidak dijumpai.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Baca Buku | <?php echo $buku['tajuk']; ?></title>
<style>
/* 1. Kunci skrin utama supaya tak naik turun */
body {
    font-family: 'Segoe UI', sans-serif;
    background: url('image_211a79.png') no-repeat center center fixed; 
    background-size: cover;
    background-color: #001f3f; 
    margin: 0;
    padding: 20px;
    height: 100vh;
    overflow: hidden; 
}

/* 2. Container ikut saiz asal 800px */
.container {
    background: white; 
    padding: 40px;
    border-radius: 15px;
    max-width: 800px; 
    margin: 30px auto; 
    box-shadow: 0 10px 40px rgba(0,0,0,0.6);
    border: 3px solid #0078d7; 
    position: relative;
    max-height: 80vh;
    overflow-y: scroll; /* Boleh scroll dalam kotak sahaja */
    
    /* Sorokkan scrollbar untuk semua browser */
    scrollbar-width: none; 
    -ms-overflow-style: none; 
}

/* Sorokkan scrollbar untuk Chrome/Safari */
.container::-webkit-scrollbar {
    display: none;
}

h2 {
    text-align: center;
    color: #0078d7; 
    margin-bottom: 30px;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
}

.content {
    line-height: 1.8;
    text-align: justify;
    min-height: 300px;
    color: #333;
    font-size: 18px;
}

.nav {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Style butang navigasi biru */
.nav a {
    text-decoration: none;
    background: #0078d7; 
    color: white;
    padding: 8px 25px;
    border-radius: 5px;
    font-weight: bold;
}

/* Ruang kosong untuk sorok kotak biru pelik */
.nav .empty-space {
    visibility: hidden;
    width: 100px;
}

.nav a:hover {
    background: #005a9e;
}

.btn-selesai {
    background: #27ae60 !important;
}

.page-info {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #666;
}

.back-link {
    display: inline-block;
    margin-bottom: 10px;
    color: white;
    text-decoration: none;
    font-weight: bold;
    background: rgba(0,0,0,0.5);
    padding: 8px 18px;
    border-radius: 5px;
}
</style>
</head>
<body>

<div style="max-width: 800px; margin: auto;">
    <a href="rujuk_buku.php" class="back-link">← Kembali ke Senarai</a>
</div>

<div class="container">
    <h2><?php echo $buku['tajuk']; ?></h2>

    <div class="content">
        <?php echo nl2br($halaman['isi_halaman']); ?>
    </div>

    <div class="nav">
        <?php if ($page > 1) { ?>
            <a href="baca_buku.php?id=<?php echo $id_buku; ?>&page=<?php echo $page-1; ?>">← SEBELUM</a>
        <?php } else { ?>
            <div class="empty-space"></div> 
        <?php } ?>

        <?php if ($page < $total_page) { ?>
            <a href="baca_buku.php?id=<?php echo $id_buku; ?>&page=<?php echo $page+1; ?>">SELEPAS →</a>
        <?php } else { ?>
            <a href="rekod_bacaan.php?id_buku=<?php echo $id_buku; ?>" class="btn-selesai">✔ SELESAI</a>
        <?php } ?>
    </div>

    <div class="page-info">
        Halaman <?php echo $page; ?> daripada <?php echo $total_page; ?>
    </div>
</div>

</body>
</html>