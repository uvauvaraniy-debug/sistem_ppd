<?php
session_start();
include "config.php";
if ($_SESSION['role'] != 'admin') { header("Location: dashboard.php"); exit(); }

if (isset($_POST['simpan_buku'])) {
    $tajuk = mysqli_real_escape_string($conn, $_POST['tajuk']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $jenis_bahan = mysqli_real_escape_string($conn, $_POST['jenis_bahan']);
    $pengarang = mysqli_real_escape_string($conn, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $muka_surat = mysqli_real_escape_string($conn, $_POST['muka_surat']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $no_pesanan = mysqli_real_escape_string($conn, $_POST['no_pesanan']);
    $tarikh = mysqli_real_escape_string($conn, $_POST['tarikh']);

    $sql = "INSERT INTO buku (tajuk, isbn, jenis_bahan, pengarang, penerbit, muka_surat, harga, no_pesanan, tarikh) 
            VALUES ('$tajuk', '$isbn', '$jenis_bahan', '$pengarang', '$penerbit', '$muka_surat', '$harga', '$no_pesanan', '$tarikh')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Maklumat berjaya disimpan!'); window.location='perolehan.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <title>Perolehan PPD | Compact Pro</title>
    <style>
        body { 
            font-family: 'Inter', 'Segoe UI', sans-serif; 
            background: 
                linear-gradient(rgba(30,60,114,0.6), rgba(30,60,114,0.6)),
                url('image_44849b.png') no-repeat center center fixed; 
            background-size: cover; 
            margin: 0; 
            padding: 20px;
            display: flex; 
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .form-card { 
            background: rgba(255, 255, 255, 0.75); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            width: 100%; 
            max-width: 820px; 
            padding: 30px 35px; 
            border-radius: 18px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.35);
            border: 1px solid rgba(255,255,255,0.4);
            max-height: 95vh;
        }

        h2 { 
            text-align: center; 
            color: #1e3c72; 
            margin-bottom: 20px; 
            font-size: 22px; 
            border-bottom: 2px solid #1e3c72;
            padding-bottom: 10px;
        }

        .input-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 15px 25px; 
        }

        .input-group { 
            display: flex; 
            flex-direction: column; 
        }

        label { 
            font-weight: 600; 
            color: #333; 
            margin-bottom: 5px; 
            font-size: 12px;
            text-transform: uppercase;
        }

        input, select { 
            padding: 9px 12px; 
            border: 1.5px solid rgba(0,0,0,0.1); 
            border-radius: 10px; 
            font-size: 13px; 
            transition: 0.3s;
            background: rgba(255,255,255,0.95); 
            outline: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        input:focus, select:focus { 
            border-color: #1e3c72; 
            box-shadow: 0 6px 15px rgba(30, 60, 114, 0.25);
            transform: translateY(-1px);
        }

        .full-width { 
            grid-column: span 2; 
        }

        .btn-submit { 
            background: linear-gradient(135deg, #27ae60, #219150);
            color: white; 
            border: none; 
            padding: 12px; 
            width: 100%; 
            border-radius: 12px; 
            font-weight: bold; 
            font-size: 14px; 
            margin-top: 18px; 
            cursor: pointer; 
            transition: 0.3s; 
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.4);
        }

        .btn-submit:hover { 
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(39, 174, 96, 0.6);
        }

        .footer-link { 
            text-align: center; 
            margin-top: 12px; 
        }

        .footer-link a { 
            color: #1e3c72; 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 13px; 
        }

        .footer-link a:hover { 
            text-decoration: underline; 
        }

        @media(max-width: 768px){
            .input-grid{
                grid-template-columns: 1fr;
            }
            .full-width{
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>

<div class="form-card">
    <h2>Daftar Perolehan Baru</h2>
    <form method="POST">
        <div class="input-grid">
            <div class="input-group full-width">
                <label>Tajuk / Nama Bahan</label>
                <input type="text" name="tajuk" placeholder="Masukkan tajuk penuh..." required>
            </div>

            <div class="input-group">
                <label>Jenis Bahan (Konsep PPD)</label>
                <select name="jenis_bahan">
                    <option value="Buku">📖 Buku</option>
                    <option value="Bukan Buku">💿 Bahan Bukan Buku (Media/Kit)</option>
                </select>
            </div>

            <div class="input-group">
                <label>ISBN / No. Kod</label>
                <input type="text" name="isbn" placeholder="Contoh: 978-967-xxx">
            </div>

            <div class="input-group">
                <label>Pengarang / Pembuat</label>
                <input type="text" name="pengarang" placeholder="Nama penuh">
            </div>

            <div class="input-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" placeholder="Syarikat Penerbitan">
            </div>

            <div class="input-group">
                <label>Muka Surat</label>
                <input type="number" name="muka_surat" placeholder="0">
            </div>

            <div class="input-group">
                <label>Harga (RM)</label>
                <input type="text" name="harga" placeholder="0.00">
            </div>

            <div class="input-group">
                <label>No. Pesanan Tempatan</label>
                <input type="text" name="no_pesanan" placeholder="Contoh: PPD/2026/001">
            </div>

            <div class="input-group">
                <label>Tarikh Terima</label>
                <input type="date" name="tarikh" required>
            </div>
        </div>

        <button type="submit" name="simpan_buku" class="btn-submit">Simpan Maklumat Perolehan</button>
    </form>
    
    <div class="footer-link">
        <a href="dashboard.php">← Kembali ke Dashboard Utama</a>
    </div>
</div>

</body>
</html>