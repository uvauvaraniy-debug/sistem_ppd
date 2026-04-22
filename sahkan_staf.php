<?php
session_start();
include "config.php";

// Pastikan hanya ADMIN yang boleh buka page ni
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: loginppd.php");
    exit();
}

// PROSES 1: Jika Admin klik butang "SAHKAN"
if (isset($_GET['approve_id'])) {
    $id = intval($_GET['approve_id']);
    // Tukar status dari 'menunggu' kepada 'aktif'
    mysqli_query($conn, "UPDATE users SET status_akaun='aktif' WHERE id='$id'");
    echo "<script>alert('Akaun staf telah diaktifkan!'); window.location='sahkan_staf.php';</script>";
}

// PROSES 2: Jika Admin klik butang "PADAM" (Jika pendaftaran tu palsu)
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    echo "<script>alert('Permohonan telah dipadam.'); window.location='sahkan_staf.php';</script>";
}

// --- PINDAAN DI SINI ---
// I buang syarat 'AND role=staf' supaya semua pendaftaran 'menunggu' akan muncul
$result = mysqli_query($conn, "SELECT * FROM users WHERE status_akaun='menunggu'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sahkan Staf Baru | Admin</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            /* Guna imej yang awak minta: image2_44847a.jpg */
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('image2_44847a.jpg') no-repeat center center fixed; 
            background-size: cover;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center; /* Tengah secara mendatar */
            align-items: center;     /* Tengah secara menegak */
            min-height: 100vh;       /* Guna ketinggian penuh skrin */
            box-sizing: border-box;
        }

        .container { 
            background: rgba(255, 255, 255, 0.98); /* Warna putih yang lebih terang sikit */
            backdrop-filter: blur(10px);
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.5); 
            max-width: 900px; 
            width: 100%;
            animation: fadeIn 0.8s ease; /* Tambah efek masuk lembut */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { 
            color: #1e3c72; 
            border-bottom: 3px solid #1e3c72; 
            padding-bottom: 15px; 
            margin-top: 0;
            font-weight: 700;
            letter-spacing: 1px;
            text-align: center; /* Tajuk pun kita bagi tengah */
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 25px; 
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        th { 
            background: #1e3c72; 
            color: white; 
            padding: 18px;
            text-align: left;
            text-transform: uppercase;
            font-size: 14px;
        }

        td { 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            color: #333;
            font-size: 15px;
        }

        tr:hover { background: #f9fafb; }

        .btn-approve { 
            background: #27ae60; 
            color: white; 
            padding: 10px 18px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: bold; 
            transition: 0.3s;
            display: inline-block;
        }

        .btn-approve:hover { background: #219150; transform: translateY(-2px); box-shadow: 0 5px 10px rgba(39,174,96,0.3); }

        .btn-delete { 
            background: #d9534f; 
            color: white; 
            padding: 10px 18px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: bold; 
            margin-left: 5px; 
            transition: 0.3s;
            display: inline-block;
        }

        .btn-delete:hover { background: #c9302c; transform: translateY(-2px); box-shadow: 0 5px 10px rgba(217,83,79,0.3); }

        .btn-back { 
            display: block; 
            margin-top: 30px; 
            color: #1e3c72; 
            text-decoration: none; 
            font-weight: bold; 
            transition: 0.3s;
            text-align: center;
        }

        .btn-back:hover { color: #3a7bd5; text-decoration: underline; }

        .status-empty {
            text-align: center;
            padding: 50px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔔 PENGESAHAN PENDAFTARAN STAF BARU</h2>
    
    <table>
        <thead>
            <tr>
                <th>Nama Penuh</th>
                <th>Username</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result) > 0) { ?>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><strong><?php echo strtoupper($row['nama']); ?></strong></td>
                    <td><?php echo $row['username']; ?></td>
                    <td>
                        <a href="?approve_id=<?php echo $row['id']; ?>" class="btn-approve" onclick="return confirm('Aktifkan akaun ini?')">✅ SAHKAN</a>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Padam permohonan ini?')">🗑️ PADAM</a>
                    </td>
                </tr>
                <?php } ?>
            <?php if ($row_count = mysqli_num_rows($result)): ?>
                <?php endif; ?>
            <?php } else { ?>
                <tr>
                    <td colspan="3" class="status-empty">
                        Tiada permohonan baru buat masa ini.
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard Utama</a>
</div>

</body>
</html>