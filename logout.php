<?php
session_start();
session_destroy(); // Padam semua data login
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logging Out...</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #1e3c72; 
            color: white; 
            display: flex; justify-content: center; align-items: center; 
            height: 100vh; margin: 0; text-align: center;
        }
        .logout-box {
            background: rgba(255,255,255,0.1);
            padding: 50px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #00d2ff;
            border-radius: 50%;
            width: 40px; height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
    <script>
        // Fungsi untuk tunggu 3 saat (3000ms) sebelum lompat ke login
        setTimeout(function(){
            window.location.href = "loginppd.php";
        }, 3000);
    </script>
</head>
<body>
    <div class="logout-box">
        <div class="loader"></div>
        <h2>Berjaya Log Keluar</h2>
        <p>Terima kasih. Anda akan dialihkan ke halaman utama dalam masa 3 saat...</p>
    </div>
</body>
</html>