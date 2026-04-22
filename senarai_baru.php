<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "sistem_ppd");

if ($_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Mesej Alert
$alert = "";
if(isset($_SESSION['msg'])) {
    $alert = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

/* ===============================
    PROSES UPDATE (MODAL)
=================================*/
if (isset($_POST['update_buku'])) {
    $id = intval($_POST['id_buku']);
    $tajuk = mysqli_real_escape_string($conn, $_POST['tajuk']);
    mysqli_query($conn, "UPDATE buku SET tajuk='$tajuk' WHERE id='$id'");
    $_SESSION['msg'] = "success_edit";
    header("Location: senarai_baru.php");
    exit();
}

/* ===============================
    SOFT DELETE & RESTORE
=================================*/
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "UPDATE buku SET status='dipadam' WHERE id='$id'");
    $_SESSION['msg'] = "success_delete";
    header("Location: senarai_baru.php");
    exit();
}

if (isset($_GET['restore'])) {
    $id = intval($_GET['restore']);
    mysqli_query($conn, "UPDATE buku SET status='aktif' WHERE id='$id'");
    $_SESSION['msg'] = "success_restore";
    header("Location: senarai_baru.php");
    exit();
}

$search = "";
$where = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where = "AND b.tajuk LIKE '%$search%'";
}

$query = "SELECT b.*, GROUP_CONCAT(r.nama_staf SEPARATOR '<br>') AS senarai_pembaca 
          FROM buku b
          LEFT JOIN rekod_bacaan r ON b.id = r.id_buku 
          WHERE 1=1 $where 
          GROUP BY b.id
          ORDER BY b.id DESC";

$result = mysqli_query($conn, $query);
$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Senarai & Pengurusan Buku</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { 
            background: url('image_dd9b53.png') no-repeat center center fixed; 
            background-size: cover; 
            color: white; 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            padding: 20px; 
        }

        .container { 
            background: rgba(10, 25, 47, 0.88); 
            backdrop-filter: blur(15px); 
            padding: 30px; 
            border-radius: 15px; 
            border: 1px solid rgba(0, 210, 255, 0.3); 
            width: 100%; 
            max-width: 1200px; 
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.7); 
        }

        h2 { text-align: center; color: #00d2ff; text-transform: uppercase; border-bottom: 2px solid #00d2ff; padding-bottom: 10px; }

        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; background: rgba(255, 255, 255, 0.05); padding: 15px; border-radius: 10px; }

        .search-box input { padding: 10px; border-radius: 5px 0 0 5px; border: 1px solid #00d2ff; background: rgba(0, 0, 0, 0.5); color: white; width: 200px; outline: none; }
        .search-box button { padding: 10px 15px; background: #00d2ff; border: 1px solid #00d2ff; border-radius: 0 5px 5px 0; cursor: pointer; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: center; border-bottom: 1px solid rgba(0, 210, 255, 0.15); }
        th { background: rgba(0, 210, 255, 0.2); color: #00d2ff; font-size: 13px; }

        /* BUTANG AKSI (WARNA LEMBUT) */
        .btn { padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 14px; color: white; cursor: pointer; border: none; transition: 0.3s; display: inline-block; margin: 2px; }
        
        .btn-edit { background: rgba(243, 156, 18, 0.25); border: 1px solid #f39c12; color: #f39c12; }
        .btn-edit:hover { background: #f39c12; color: white; }

        .btn-delete { background: rgba(255, 75, 43, 0.25); border: 1px solid #ff4b2b; color: #ff4b2b; }
        .btn-delete:hover { background: #ff4b2b; color: white; }

        .btn-restore { background: rgba(39, 174, 96, 0.25); border: 1px solid #27ae60; color: #27ae60; }
        .btn-restore:hover { background: #27ae60; color: white; }

        /* TOGGLE PEMBACA */
        .toggle-btn { background: rgba(0, 210, 255, 0.1); border: 1px solid #00d2ff; color: #00d2ff; padding: 4px 12px; border-radius: 4px; cursor: pointer; font-size: 11px; }
        .nama-content { display: none; margin-top: 10px; font-size: 11px; color: #f39c12; background: rgba(0,0,0,0.3); padding: 8px; border-radius: 5px; text-align: left; }

        .status-aktif { color: #2ecc71; border: 1px solid #2ecc71; padding: 4px 10px; border-radius: 20px; font-size: 10px; }
        .status-dipadam { color: #ff4b2b; border: 1px solid #ff4b2b; padding: 4px 10px; border-radius: 20px; font-size: 10px; }
        
        .back-btn { display: inline-block; margin-top: 25px; padding: 10px 20px; color: #00d2ff; text-decoration: none; border: 1px solid #00d2ff; border-radius: 5px; font-weight: bold; }

        /* MODAL */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); }
        .modal-content { background: #0a192f; border: 1px solid #00d2ff; margin: 10% auto; padding: 25px; border-radius: 15px; width: 400px; box-shadow: 0 0 20px #00d2ff; position: relative; }
        .modal input[type="text"] { width: 100%; padding: 10px; margin: 15px 0; border-radius: 5px; border: 1px solid #00d2ff; background: rgba(255,255,255,0.05); color: white; box-sizing: border-box; }
        .close-modal { position: absolute; top: 10px; right: 15px; color: white; cursor: pointer; font-size: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Pusat Pengurusan Data Buku</h2>

    <div class="top-bar">
        <div>Jumlah Rekod: <b><?php echo $total; ?></b> unit</div>
        <div class="search-box">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari tajuk..." value="<?php echo $search; ?>">
                <button type="submit">CARI</button>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tajuk Buku</th>
                <th>Pembaca</th>
                <th>Status</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { 
                $id = $row['id'];
                $tj = htmlspecialchars($row['tajuk']);
            ?>
            <tr>
                <td><?php echo $id; ?></td>
                <td style="text-align: left; max-width: 350px;"><?php echo $tj; ?></td>
                <td>
                    <?php if(!empty($row['senarai_pembaca'])) { ?>
                        <button class="toggle-btn" onclick="toggleNama(<?php echo $id; ?>)">⌄ Lihat</button>
                        <div id="box-<?php echo $id; ?>" class="nama-content">
                            <?php echo strtoupper($row['senarai_pembaca']); ?>
                        </div>
                    <?php } else { echo '<i style="color:gray;">Tiada</i>'; } ?>
                </td>
                <td>
                    <?php echo ($row['status'] == 'aktif') ? '<span class="status-aktif">AKTIF</span>' : '<span class="status-dipadam">ARCHIVED</span>'; ?>
                </td>
                <td>
                    <button class="btn btn-edit" title="Edit" onclick="openEditModal('<?php echo $id; ?>', '<?php echo $tj; ?>')">✏️</button>
                    
                    <?php if($row['status'] == 'aktif') { ?>
                        <button class="btn btn-delete" title="Padam" onclick="confirmAction('?hapus=<?php echo $id; ?>', 'Padam rekod ini?')">🗑️</button>
                    <?php } else { ?>
                        <button class="btn btn-restore" title="Restore" onclick="confirmAction('?restore=<?php echo $id; ?>', 'Aktifkan semula?')">♻️</button>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-btn">← KEMBALI</a>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h3 style="color:#00d2ff; text-align:center;">KEMAS KINI TAJUK</h3>
        <form method="POST">
            <input type="hidden" name="id_buku" id="edit_id">
            <input type="text" name="tajuk" id="edit_tajuk" required>
            <button type="submit" name="update_buku" class="btn-restore" style="width:100%; padding:10px; border-radius:5px; cursor:pointer;">SIMPAN PERUBAHAN</button>
        </form>
    </div>
</div>

<script>
function toggleNama(id) {
    var box = document.getElementById("box-" + id);
    if (box.style.display === "block") { box.style.display = "none"; event.target.innerHTML = "⌄ Lihat"; }
    else { box.style.display = "block"; event.target.innerHTML = "⌃ Tutup"; }
}

function openEditModal(id, tajuk) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_tajuk").value = tajuk;
    document.getElementById("editModal").style.display = "block";
}

function closeModal() { document.getElementById("editModal").style.display = "none"; }

function confirmAction(url, msg) {
    Swal.fire({
        title: 'Anda pasti?', text: msg, icon: 'question',
        showCancelButton: true, confirmButtonColor: '#00d2ff', cancelButtonColor: '#ff4b2b',
        confirmButtonText: 'Ya!', background: '#0a192f', color: '#fff'
    }).then((result) => { if (result.isConfirmed) { window.location.href = url; } });
}

<?php if($alert == "success_delete") { ?>
    Swal.fire({ icon: 'success', title: 'Padam!', text: 'Rekod telah dipadam.', background: '#0a192f', color: '#fff', timer: 2000 });
<?php } elseif($alert == "success_restore") { ?>
    Swal.fire({ icon: 'success', title: 'Berjaya!', text: 'Rekod diaktifkan.', background: '#0a192f', color: '#fff', timer: 2000 });
<?php } elseif($alert == "success_edit") { ?>
    Swal.fire({ icon: 'success', title: 'Berjaya!', text: 'Tajuk dikemaskini.', background: '#0a192f', color: '#fff', timer: 2000 });
<?php } ?>
</script>

</body>
</html>