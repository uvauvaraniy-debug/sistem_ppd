<?php
session_start();
include "config.php";
if ($_SESSION['role'] != 'admin') { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pengguna | PPD</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: url('image_449452.jpg') no-repeat center center fixed; 
            background-size: cover; height: 100vh; display: flex; justify-content: center; align-items: center; margin: 0;
        }
        .glass-box { 
            background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(15px);
            padding: 40px; border-radius: 25px; width: 380px;
            border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        h2 { color: white; text-align: center; margin-bottom: 30px; }
        input, select { 
            width: 100%; padding: 12px; margin: 10px 0; border-radius: 10px; border: none; 
            background: rgba(255,255,255,0.9); box-sizing: border-box;
        }
        button { 
            width: 100%; padding: 12px; background: #2ecc71; color: white; border: none; 
            border-radius: 10px; cursor: pointer; font-weight: bold; margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="glass-box">
        <h2>Pendaftaran Staf</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username Baru" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="user">Peranan: Staf Biasa</option>
                <option value="admin">Peranan: Admin (Boss)</option>
            </select>
            <button type="submit" name="register">DAFTAR PENGGUNA</button>
        </form>
        <center><br><a href="dashboard.php" style="color:white; text-decoration:none; font-size:13px;">← Batal & Kembali</a></center>
    </div>
</body>
</html>