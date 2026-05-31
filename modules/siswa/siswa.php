<?php
session_start();
require_once "../../config/koneksi.php";
require_once "../../config/fungsi.php";

/** @var mysqli $conn */ // Menghilangkan garis merah di VS Code

$base_url = "http://localhost/LENTERA/";

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit();
}

// 1. Logika Menangkap Input Pencarian
$where = "WHERE 1=1";
if (isset($_GET['cari']) && $_GET['cari'] != "") {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
    $where .= " AND (t_siswa.nama_siswa LIKE '%$cari%' OR t_siswa.id_siswa LIKE '%$cari%')";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Siswa - Sanggar Lentera</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="brand">
                <img src="../../assets/img/tari_logo.png" alt="Logo">
                <h3>Sanggar Tari</h3>
            </div>
            <nav class="nav-menu">
                <a href="<?php echo $base_url; ?>dashboard_admin.php" class="nav-item">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>

                <div class="nav-item active">
                    <i class="fa-solid fa-database"></i> Master Data
                    <i class="fa-solid fa-chevron-down" style="margin-left: auto; font-size: 0.7rem;"></i>
                </div>

                <a href="<?php echo $base_url; ?>modules/siswa/siswa.php" class="nav-item active">
                    <i class="fa-solid fa-user-group"></i> Siswa
                </a>

                <a href="<?php echo $base_url; ?>modules/jadwal/jadwal.php" class="nav-item sub">
                    <i class="fa-solid fa-calendar-days"></i> Jadwal
                </a>

                <a href="<?php echo $base_url; ?>modules/inventaris/inventaris.php" class="nav-item">
                    <i class="fa-solid fa-box"></i> Inventaris
                </a>

                <a href="<?php echo $base_url; ?>modules/sewa/sewa.php" class="nav-item">
                    <i class="fa-solid fa-cart-shopping"></i> Sewa
                </a>

                <a href="<?php echo $base_url; ?>modules/keuangan/keuangan.php" class="nav-item">
                    <i class="fa-solid fa-money-bill"></i> Keuangan
                </a>

                <a href="<?php echo $base_url; ?>modules/laporan/laporan.php" class="nav-item">
                    <i class="fa-solid fa-file-lines"></i> Laporan
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <span>Halo, <strong><?php echo $_SESSION['nama_admin']; ?> !</strong></span>
                <a href="../../logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-weight: 600;">Data Siswa Sanggar</h2>
                <a href="siswa_tambah.php" style="background-color: #437677; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i> Tambah Siswa Baru
                </a>
            </div>

            <form method="GET" action="" style="margin-bottom: 20px; display: flex; gap: 10px;">
                <input type="text" name="cari" placeholder="Cari nama siswa..." 
                       value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>" 
                       style="padding: 10px 15px; width: 300px; border: 1px solid #ccc; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 14px;">
                
                <button type="submit" style="background-color: #437677; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-magnifying-glass"></i> CARI
                </button>

                <?php if (isset($_GET['cari']) && $_GET['cari'] != ""): ?>
                    <a href="siswa.php" style="background-color: #666; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center;">
                        RESET
                    </a>
                <?php endif; ?>
            </form>

            <div class="data-box">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Siswa</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Kelas</th>
                            <th>Tgl. Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // 3. Query yang Sudah Memakai Kondisi $where
                        $query = mysqli_query($conn, "SELECT t_siswa.*, t_kelas.nama_kelas, t_kelas.tingkat 
                                                        FROM t_siswa 
                                                        JOIN t_kelas ON t_siswa.id_kelas = t_kelas.id_kelas 
                                                        $where 
                                                        ORDER BY id_siswa ASC");

                        if (mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo str_pad($row['id_siswa'], 3, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo $row['nama_siswa']; ?></td>
                                    <td><?php echo $row['alamat']; ?></td>
                                    <td><?php echo $row['no_hp']; ?></td>
                                    <td><?php echo $row['nama_kelas'] . " - " . $row['tingkat']; ?></td>
                                    <td><?php echo tgl_indo($row['tgl_daftar']); ?></td>
                                    <td>
                                        <a href="edit_siswa.php?id=<?php echo $row['id_siswa']; ?>" style="color: #333; margin-right: 10px;"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="hapus_siswa.php?id=<?php echo $row['id_siswa']; ?>" style="color: #333;" onclick="return confirm('Yakin hapus data ini?')"><i class="fa-regular fa-trash-can"></i></a>
                                    </td>
                                </tr>
                            <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center; padding: 20px; color: #666;'>Data siswa tidak ditemukan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>