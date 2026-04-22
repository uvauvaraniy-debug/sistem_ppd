<?php
$conn = mysqli_connect("localhost", "root", "", "sistem_ppd");

if (!$conn) {
die("Database tak kawan dengan PHP: " . mysqli_connect_error());
}

$cek = mysqli_query($conn, "SELECT * FROM buku");
$jumlah = mysqli_num_rows($cek);

echo "<h1>Status Sistem:</h1>";
echo "Jumlah buku yang dijumpai dalam database: " . $jumlah;

while($data = mysqli_fetch_array($cek)) {
echo "


Tajuk: " . $data['tajuk'];
}
?>