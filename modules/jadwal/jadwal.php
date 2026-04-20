<?php
session_start();
require_once "../../config/koneksi.php";
require_once "../../config/fungsi.php";

// Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../../login.php?pesan=belum_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Jadwal Latihan - Sanggar Lentera</title>
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
                <a href="../../modules/siswa/siswa.php" class="nav-item sub"><i class="fa-solid fa-user-group"></i> Siswa</a>
                <a href="jadwal.php" class="nav-item sub active"><i class="fa-solid fa-calendar-days"></i> Jadwal</a>
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
                <h2 style="font-weight: 600;">Jadwal Latihan Sanggar</h2>
                <a href="jadwal_tambah.php" style="background-color: #437677; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i> Tambah Jadwal Baru
                </a>
            </div>

            <div class="data-box" style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <table class="table-custom" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #E0E0E0; text-align: left;">
                            <th style="padding: 15px;">ID</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Nama Pelatih</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query JOIN sesuai database db_lentera (Tanpa t_tarian)
                        $query = mysqli_query($conn, "SELECT t_jadwal.*, t_kelas.nama_kelas, t_kelas.tingkat, t_pelatih.nama_pelatih 
                                                FROM t_jadwal 
                                                JOIN t_kelas ON t_jadwal.id_kelas = t_kelas.id_kelas 
                                                JOIN t_pelatih ON t_jadwal.id_pelatih = t_pelatih.id_pelatih
                                                ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jam ASC");

                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr style="border-bottom: 1px solid #EEE;">
                                <td style="padding: 15px;"><?php echo str_pad($row['id_jadwal'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><strong><?php echo $row['hari']; ?></strong></td>
                                <td><?php echo date('H.i', strtotime($row['jam'])); ?> - Selesai</td>
                                <td><?php echo $row['nama_kelas'] . " - " . $row['tingkat']; ?></td>
                                <td><?php echo $row['nama_pelatih']; ?></td>
                                <td>
                                    <a href="jadwal_edit.php?id=<?php echo $row['id_jadwal']; ?>" style="color: #333; margin-right: 12px; font-size: 18px;"><i class="fa-regular fa-pen-to-square"></i></a>
                                    <a href="jadwal_hapus.php?id=<?php echo $row['id_jadwal']; ?>" style="color: #333; font-size: 18px;" onclick="return confirm('Yakin hapus jadwal ini?')"><i class="fa-regular fa-trash-can"></i></a>
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