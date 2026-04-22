<?php
// 1. Maklumat sambungan database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistem_ppd"; // Nama database ikut gambar phpMyAdmin awak

// 2. Melakukan sambungan
$conn = mysqli_connect($host, $user, $pass, $db);

// 3. Semak jika sambungan gagal
if (!$conn) {
die("Sambungan ke database gagal: " . mysqli_connect_error());
}
?>