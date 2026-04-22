<?php
session_start();
include "config.php";

// Pastikan user dah login
if (!isset($_SESSION['username'])) { header("Location: loginppd.php"); exit(); }

// --- LOGIK ASAL (SIMPAN REKOD) ---
if (isset($_POST['simpan_rekod'])) {
    $id_buku = mysqli_real_escape_string($conn, $_POST['id_buku']);
    $nama_staf = mysqli_real_escape_string($conn, $_POST['nama_staf']);
    $tarikh_baca = mysqli_real_escape_string($conn, $_POST['tarikh_baca']);
    $bil_muka_surat = mysqli_real_escape_string($conn, $_POST['bil_muka_surat']); 
    $status = mysqli_real_escape_string($conn, $_POST['status']); 

    $sql = "INSERT INTO rekod_bacaan (id_buku, nama_staf, tarikh_baca, bil_muka_surat, status) 
            VALUES ('$id_buku', '$nama_staf', '$tarikh_baca', '$bil_muka_surat', '$status')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Rekod berjaya dihantar!'); window.location='rekod_bacaan.php';</script>";
    }
}

$ambil_buku = mysqli_query($conn, "SELECT * FROM buku");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rekod Bacaan | PPD</title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            /* Ganti ke gambar teknologi yang awak bagi tadi */
            background: url('image_de1ace.png') no-repeat center center fixed; 
            background-size: cover; 
            margin: 0; 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Kotak borang yang tajam dan profesional */
        .glass-panel { 
            background: rgba(255, 255, 255, 0.95); 
            width: 100%;
            max-width: 500px; 
            padding: 35px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid #ffffff;
        }

        h2 { 
            text-align: center; 
            color: #2c3e50; 
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #00d2ff;
            padding-bottom: 10px;
        }

        label { display: block; margin-top: 15px; font-weight: 600; color: #34495e; font-size: 14px; }
        
        select, input { 
            width: 100%; padding: 12px; margin-top: 5px; border-radius: 6px; 
            border: 1px solid #ced4da; box-sizing: border-box; font-size: 14px;
            background: #ffffff;
        }

        .row { display: flex; gap: 15px; }
        .row div { flex: 1; }
        
        .btn-hantar { 
            background: #00d2ff; 
            background: linear-gradient(to right, #00d2ff, #3a7bd5);
            color: white; padding: 15px; border: none; width: 100%; 
            border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 16px; 
            margin-top: 25px; transition: 0.3s;
            text-transform: uppercase;
        }

        .btn-hantar:hover { 
            filter: brightness(1.1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(58, 123, 213, 0.4);
        }

        .back-btn { 
            display: block; text-align: center; margin-top: 20px; 
            color: #7f8c8d; text-decoration: none; font-size: 13px; font-weight: 500;
        }
        .back-btn:hover { color: #2c3e50; }
    </style>
</head>
<body>

    <div class="glass-panel">
        <h2>Borang Rekod Bacaan</h2>
        <form method="POST">
            <label>Pilih Buku:</label>
            <select name="id_buku" required>
                <option value="">-- Pilih Tajuk Buku --</option>
                <?php while($row = mysqli_fetch_assoc($ambil_buku)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['tajuk']; ?></option>
                <?php } ?>
            </select>

            <label>Nama Pembaca:</label>
            <input type="text" name="nama_staf" value="<?php echo strtoupper($_SESSION['username']); ?>" readonly style="background: #f8f9fa;">

            <div class="row">
                <div>
                    <label>Tarikh Bacaan:</label>
                    <input type="date" name="tarikh_baca" required>
                </div>
                <div>
                    <label>Bil. Muka Surat:</label>
                    <input type="number" name="bil_muka_surat" placeholder="Jumlah" required>
                </div>
            </div>

            <label>Status Semasa:</label>
            <select name="status" required>
                <option value="Sedang Dibaca">📖 Sedang Dibaca</option>
                <option value="Selesai">✅ Selesai Dibaca</option>
            </select>

            <button type="submit" name="simpan_rekod" class="btn-hantar">Hantar Rekod Sekarang</button>
        </form>
        <a href="dashboard.php" class="back-btn">← Kembali ke Dashboard</a>
    </div>

</body>
</html>