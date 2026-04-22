<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "sistem_ppd");

if (!isset($_SESSION['username'])) {
    header("Location: loginppd.php");
    exit();
}

$result = mysqli_query($conn, 
"SELECT * FROM buku 
 WHERE status='aktif'
 ORDER BY tajuk ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Rujuk Senarai Buku</title>
<style>
body {
    background: #0f2027;
    background: linear-gradient(to right, #2c5364, #203a43, #0f2027);
    color: white;
    font-family: 'Segoe UI', sans-serif;
    padding: 40px;
}

h2 {
    text-align: center;
    margin-bottom: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255,255,255,0.08);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background: rgba(0,210,255,0.4);
}

tr:nth-child(even) {
    background: rgba(255,255,255,0.05);
}

tr:hover {
    background: rgba(255,255,255,0.15);
}

.btn-baca {
    background: #27ae60;
    padding: 6px 12px;
    border-radius: 5px;
    color: white;
    text-decoration: none;
    font-size: 13px;
}

.btn-baca:hover {
    background: #1e8449;
}

.button-container {
    margin-top: 25px;
    display: flex;
    gap: 15px; /* Jarak antara dua butang */
}

.back-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #00d2ff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
}

.back-btn:hover {
    background: #00a8cc;
    transform: scale(1.05);
}

.home-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #f39c12; /* Warna oren untuk beza dengan butang rekod */
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
}

.home-btn:hover {
    background: #d35400;
    transform: scale(1.05);
}
</style>
</head>

<body>

<h2>Senarai Buku Yang Tersedia</h2>

<table>
<tr>
    <th>ID</th>
    <th>Tajuk Buku</th>
    <th>Tindakan</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['tajuk']; ?></td>
    <td>
        <a href="baca_buku.php?id=<?php echo $row['id']; ?>" 
           class="btn-baca">
           📖 Baca Buku
        </a>
    </td>
</tr>
<?php } ?>

</table>

<div class="button-container">
    <a href="rekod_bacaan.php" class="back-btn">
        ← Borang Rekod
    </a>

    <a href="dashboard.php" class="home-btn">
        🏠 Halaman Utama (Dashboard)
    </a>
</div>

</body>
</html>