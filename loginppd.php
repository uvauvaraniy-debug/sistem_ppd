<?php
session_start();
include "config.php";
$error = "";

if (isset($_POST['login'])) {
    $ic_number = trim($_POST['ic_number']);
    $password = $_POST['password'];

    // Check IC number only
    $stmt = mysqli_prepare($conn, "SELECT username, ic_number, password, status_akaun, role FROM users WHERE ic_number = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $ic_number);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $db_username, $db_ic_number, $db_password, $status_akaun, $role);

        if (mysqli_stmt_fetch($stmt)) {
            // Compare using secure password_verify (bcrypt)
            if (password_verify($password, $db_password)) {
                if ($status_akaun === 'aktif') {
                    session_regenerate_id(true);
                    $_SESSION['username'] = $db_username;
                    $_SESSION['role'] = $role;
                    header("Location: dashboard.php");
                    exit();
                }
                $error = htmlspecialchars("Akaun dijumpai tetapi belum diaktifkan. Sila hubungi pentadbir.", ENT_QUOTES, 'UTF-8');
            } else {
                $error = htmlspecialchars("No. IC atau Kata Laluan salah!", ENT_QUOTES, 'UTF-8');
            }
        } else {
            $error = htmlspecialchars("No. IC atau Kata Laluan salah!", ENT_QUOTES, 'UTF-8');
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = htmlspecialchars("Ralat sistem. Sila cuba semula sebentar lagi.", ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | Sistem Perolehan</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('image_44845b.jpg') no-repeat center center fixed; 
            background-size: cover;
            height: 100vh; display: flex; justify-content: center; align-items: center; margin: 0; 
        }
        .login-box { 
            background: white; 
            padding: 40px; border-radius: 15px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.5); 
            width: 320px; text-align: center;
        }
        h2 { color: #1e3c72; margin-bottom: 5px; font-weight: 700; letter-spacing: 1px; }
        h1 { color: #333; font-size: 18px; margin-bottom: 25px; }
        input { 
            width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; 
            border-radius: 6px; box-sizing: border-box; font-size: 14px;
        }
        button { 
            width: 100%; padding: 12px; background: #1e3c72; color: white; 
            border: none; border-radius: 6px; cursor: pointer; font-weight: bold; 
            transition: 0.3s; margin-top: 10px;
        }
        button:hover { background: #3a7bd5; }
        .error { 
            color: #721c24; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb; 
            padding: 10px; 
            border-radius: 5px; 
            font-size: 13px; 
            margin-bottom: 15px; 
            font-weight: bold; 
        }
        
        .links-area {
            margin-top: 20px;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }
        .links-area a {
            color: #1e3c72;
            text-decoration: none;
            font-weight: bold;
        }
        .links-area a:hover {
            text-decoration: underline;
        }
        
        .forgot-password {
            margin-bottom: 10px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>LOGIN</h2>
        <h1>SISTEM PEROLEHAN</h1>
        
        <?php if($error) echo "<div class='error'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</div>"; ?>
        
        <form method="POST">
            <input type="text" name="ic_number" placeholder="Nama Pengguna" required>
            <input type="password" name="password" placeholder="Kata Laluan" required>
            <button type="submit" name="login">MASUK SISTEM</button>
        </form>

        <div class="links-area">
            <a href="lupa_password.php" class="forgot-password">Lupa Kata Laluan?</a>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
            
            Belum mempunyai akaun? <br>
            <a href="register.php">Daftar Di Sini</a>
        </div>
    </div>
</body>
</html>