<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "config.php"; 
$msg = "";
$msg_type = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $ic_number = trim($_POST['ic_number']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    $allowed_roles = ['guru', 'murid', 'kakitangan ppd'];

    if ($username === '' || $nama === '' || $ic_number === '' || $password === '' || $role === '') {
        $msg = htmlspecialchars("Sila lengkapkan semua medan pendaftaran.", ENT_QUOTES, 'UTF-8');
        $msg_type = "error";
    } elseif (!in_array($role, $allowed_roles, true)) {
        $msg = htmlspecialchars("Peranan tidak sah. Sila pilih peranan yang dibenarkan.", ENT_QUOTES, 'UTF-8');
        $msg_type = "error";
    } elseif (strlen($password) < 6) {
        $msg = htmlspecialchars("Kata laluan mesti sekurang-kurangnya 6 aksara.", ENT_QUOTES, 'UTF-8');
        $msg_type = "error";
    } else {
        // Check for duplicate username
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $msg = htmlspecialchars("Username sudah didaftarkan!", ENT_QUOTES, 'UTF-8');
                $msg_type = "error";
            } else {
                // Check for duplicate IC number
                mysqli_stmt_close($stmt);
                $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE ic_number = ? LIMIT 1");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "s", $ic_number);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $msg = htmlspecialchars("Nombor IC sudah berdaftar!", ENT_QUOTES, 'UTF-8');
                        $msg_type = "error";
                            } else {
                        mysqli_stmt_close($stmt);
                        // Use secure bcrypt password hashing
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, nama, ic_number, password, role, status_akaun) VALUES (?, ?, ?, ?, ?, 'menunggu')");
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "sssss", $username, $nama, $ic_number, $hashed_password, $role);
                            if (mysqli_stmt_execute($stmt)) {
                                $msg = htmlspecialchars("Pendaftaran berjaya! Tunggu pengesahan Admin.", ENT_QUOTES, 'UTF-8');
                                $msg_type = "success";
                            } else {
                                $msg = htmlspecialchars("Ralat pendaftaran! Sila cuba lagi.", ENT_QUOTES, 'UTF-8');
                                $msg_type = "error";
                            }
                            mysqli_stmt_close($stmt);
                        } else {
                            $msg = htmlspecialchars("Ralat sistem. Sila cuba semula sebentar lagi.", ENT_QUOTES, 'UTF-8');
                            $msg_type = "error";
                        }
                    }
                } else {
                    $msg = htmlspecialchars("Ralat sistem. Sila cuba semula sebentar lagi.", ENT_QUOTES, 'UTF-8');
                    $msg_type = "error";
                }
            }
        } else {
            $msg = htmlspecialchars("Ralat sistem. Sila cuba semula sebentar lagi.", ENT_QUOTES, 'UTF-8');
            $msg_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pengguna | Sistem Perolehan</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('image_44845b.jpg') no-repeat center center fixed; 
            background-size: cover;
            height: 100vh; margin: 0; display: flex; justify-content: center; align-items: center; overflow: hidden;
        }

        .register-box { 
            background: white; 
            padding: 25px 40px; /* Padding atas/bawah dikurangkan untuk nampak pendek */
            border-radius: 15px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.5); 
            width: 420px; /* Kekalkan lebar yang anda minta */
            text-align: center;
            box-sizing: border-box;
            position: relative;
        }

        h2 { color: #1e3c72; margin: 0; font-size: 22px; font-weight: 700; text-transform: uppercase; }
        h1 { color: #333; font-size: 16px; margin-bottom: 15px; margin-top: 5px; }
        
        input, select { 
            width: 100%; 
            padding: 10px; /* Kecilkan padding input */
            margin: 8px 0; /* Kurangkan jarak antara input */
            border: 1px solid #ddd; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 14px;
        }
        
        button { 
            width: 100%; 
            padding: 12px; 
            background: #27ae60; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
            margin-top: 10px;
            transition: 0.3s;
        }
        
        .message { padding: 8px; border-radius: 5px; margin-bottom: 10px; font-size: 13px; font-weight: bold; }
        .error { background: #f8d7da; color: #721c24; }
        .success { background: #d4edda; color: #155724; }
        
        .login-link { margin-top: 15px; font-size: 13px; color: #666; }
        .login-link a { color: #1e3c72; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Pendaftaran</h2>
        <h1>PENGGUNA BARU</h1>

        <?php if($msg): ?>
            <div class="message <?php echo $msg_type; ?>"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="nama" placeholder="Nama Penuh" required>
            <input type="text" name="username" placeholder="Username / ID Pengguna" required>
            <input type="text" name="ic_number" placeholder="Nombor IC" required>
            <input type="password" name="password" placeholder="Kata Laluan" required>
            
            <select name="role" required>
                <option value="" disabled selected>Pilih Peranan (Role)</option>
                <option value="guru">Guru</option>
                <option value="murid">Murid</option>
                <option value="kakitangan ppd">Kakitangan PPD</option>
            </select>

            <button type="submit" name="register">Hantar Permohonan</button>
        </form>

        <div class="login-link">
            Sudah mempunyai akaun? <a href="loginppd.php">Log Masuk</a>
        </div>
    </div>
</body>
</html>