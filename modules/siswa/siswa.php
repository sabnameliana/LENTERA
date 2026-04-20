<?php
session_start();
require_once "../../config/koneksi.php";
require_once "../../config/fungsi.php";

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit();
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
                <a href="../../index.php" class="nav-item"><i class="fa-solid fa-house"></i> Dashboard</a>
                <div class="nav-item active"><i class="fa-solid fa-database"></i> Master Data</div>
                <a href="siswa.php" class="nav-item sub active"><i class="fa-solid fa-user-group"></i> Siswa</a>
                <a href="../../modules/jadwal/jadwal.php" class="nav-item sub"><i class="fa-solid fa-calendar-days"></i> Jadwal</a>
                <a href="#" class="nav-item"><i class="fa-solid fa-box"></i> Inventaris</a>
                <a href="#" class="nav-item"><i class="fa-solid fa-cart-shopping"></i> Sewa</a>
                <a href="#" class="nav-item"><i class="fa-solid fa-money-bill"></i> Keuangan</a>
                <a href="#" class="nav-item"><i class="fa-solid fa-file-lines"></i> Laporan</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <span>Halo, <strong><?php echo $_SESSION['nama_admin']; ?> !</strong></span>
                <a href="../../logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Data Siswa Sanggar</h2>
                <a href="siswa_tambah.php" style="background-color: #437677; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i> Tambah Siswa Baru
                </a>
            </div>

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
                        $query = mysqli_query($conn, "SELECT t_siswa.*, t_kelas.nama_kelas, t_kelas.tingkat 
                                                FROM t_siswa 
                                                JOIN t_kelas ON t_siswa.id_kelas = t_kelas.id_kelas 
                                                ORDER BY id_siswa ASC");

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
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>