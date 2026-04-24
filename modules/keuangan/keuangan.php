<?php
session_start();
require_once "../../config/koneksi.php";
require_once "../../config/fungsi.php";

$base_url = "http://localhost/LENTERA/";

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../../login.php?pesan=belum_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keuangan - Sanggar Tari</title>
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
                <a href="<?php echo $base_url; ?>index.php" class="nav-item">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>

                <div class="nav-item active">
                    <i class="fa-solid fa-database"></i> Master Data
                    <i class="fa-solid fa-chevron-down" style="margin-left: auto; font-size: 0.7rem;"></i>
                </div>

                <a href="<?php echo $base_url; ?>modules/siswa/siswa.php" class="nav-item sub">
                    <i class="fa-solid fa-user-group"></i> Siswa
                </a>

                <a href="<?php echo $base_url; ?>modules/jadwal/jadwal.php" class="nav-item">
                    <i class="fa-solid fa-calendar-days"></i> Jadwal
                </a>

                <a href="<?php echo $base_url; ?>modules/inventaris/inventaris.php" class="nav-item">
                    <i class="fa-solid fa-box"></i> Inventaris
                </a>

                <a href="<?php echo $base_url; ?>modules/sewa/sewa.php" class="nav-item">
                    <i class="fa-solid fa-cart-shopping"></i> Sewa
                </a>

                <a href="<?php echo $base_url; ?>modules/keuangan/keuangan.php" class="nav-item active">
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
                <h2 style="font-weight: 600;">Kelola Pembayaran Siswa</h2>
                <a href="keuangan_tambah.php" style="background-color: #437677; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i> Tambah Pembayaran
                </a>
            </div>

            <div class="data-box" style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <table class="table-custom" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #E0E0E0; text-align: left;">
                            <th style="padding: 15px;">ID</th>
                            <th>Nama Siswa</th>
                            <th>Bulan</th>
                            <th>Total</th>
                            <th>Tgl Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Perbaikan Query: Menggunakan t_pembayaran_siswa sesuai file SQL Anda
                        $query = mysqli_query($conn, "SELECT t_pembayaran_siswa.*, t_siswa.nama_siswa 
                                                      FROM t_pembayaran_siswa 
                                                      JOIN t_siswa ON t_pembayaran_siswa.id_siswa = t_siswa.id_siswa 
                                                      ORDER BY id_bayar DESC");
                        
                        while ($row = mysqli_fetch_assoc($query)) {
                            // Badge color logic
                            $status = strtolower($row['status']);
                            $bg_status = ($status == 'lunas') ? '#C6F6D5' : '#FED7D7'; 
                            $text_status = ($status == 'lunas') ? '#22543D' : '#822727';
                        ?>
                            <tr style="border-bottom: 1px solid #EEE;">
                                <td style="padding: 15px;"><?php echo str_pad($row['id_bayar'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><strong><?php echo $row['nama_siswa']; ?></strong></td>
                                <td><?php echo $row['bulan']; ?></td>
                                <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['tgl_bayar']; ?></td>
                                <td>
                                    <span style="background: <?php echo $bg_status; ?>; color: <?php echo $text_status; ?>; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: bold;">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="keuangan_edit.php?id=<?php echo $row['id_bayar']; ?>" style="color: #333;"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="keuangan_hapus.php?id=<?php echo $row['id_bayar']; ?>" onclick="return confirm('Hapus data?')" style="color: #333;"><i class="fa-regular fa-trash-can"></i></a>
                                        <a href="keuangan_cetak.php?id=<?php echo $row['id_bayar']; ?>" style="color: #333; border: 1px solid #ddd; padding: 2px 8px; border-radius: 5px; font-size: 12px; text-decoration: none;">
                                            <i class="fa-solid fa-print"></i> Cetak
                                        </a>
                                    </div>
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