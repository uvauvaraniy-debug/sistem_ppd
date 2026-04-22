<?php
// 1. KOD SAMBUNGAN TERUS (Jangan guna include dah)
$conn = mysqli_connect("localhost", "root", "", "sistem_ppd");

if (!$conn) {
die("Gagal sambung ke database: " . mysqli_connect_error());
}

// 2. LOGIK PADAM
if (isset($_GET['hapus'])) {
$id_padam = $_GET['hapus'];
mysqli_query($conn, "DELETE FROM buku WHERE id = '$id_padam'");
header("Location: senarai_perolehan.php");
}

// 3. AMBIL DATA
$ambil = mysqli_query($conn, "SELECT * FROM buku");
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<title>Senarai Perolehan</title>
<style>
body { background: #121212; color: white; font-family: Arial, sans-serif; padding: 30px; }
.kad { background: #222; padding: 20px; border: 1px solid #444; border-radius: 8px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; color: black; }
th, td { border: 1px solid black; padding: 12px; text-align: left; }
th { background: #00d2ff; }
.btn-padam { background: red; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-weight: bold; }
</style>
</head>
<body>

<div class="kad">
<h1>SENARAI BUKU (ADMIN)</h1>
<a href="dashboard.php" style="color: #00d2ff;"> <-- Kembali ke Dashboard</a>
<hr>

</div>

</body>
</html>