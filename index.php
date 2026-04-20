<?php
session_start();
require_once "config/koneksi.php";
require_once "config/fungsi.php";

$base_url = "http://localhost/LENTERA/";

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit();
}

$query_siswa = mysqli_query($conn, "SELECT * FROM t_siswa");
$total_siswa = $query_siswa ? mysqli_num_rows($query_siswa) : 0;

$query_pelatih = mysqli_query($conn, "SELECT * FROM t_pelatih");
$total_pelatih = $query_pelatih ? mysqli_num_rows($query_pelatih) : 0;

$query_sewa = mysqli_query($conn, "SELECT * FROM t_sewa WHERE status = 'Belum Kembali'");
$total_sewa = $query_sewa ? mysqli_num_rows($query_sewa) : 0;

$q_pemasukan_siswa = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM t_pembayaran_siswa");
$row_siswa = mysqli_fetch_assoc($q_pemasukan_siswa);

$q_pemasukan_sewa = mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM t_sewa");
$row_sewa = mysqli_fetch_assoc($q_pemasukan_sewa);

$total_duit = ($row_siswa['total'] ?? 0) + ($row_sewa['total'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sanggar Lentera</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="brand">
                <img src="assets/img/tari_logo.png" alt="Logo">
                <h3>Sanggar Tari</h3>
            </div>

            <nav class="nav-menu">
                <a href="<?php echo $base_url; ?>index.php" class="nav-item active">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>

                <div class="nav-item">
                    <i class="fa-solid fa-database"></i> Master Data
                    <i class="fa-solid fa-chevron-down" style="margin-left: auto; font-size: 0.7rem;"></i>
                </div>

                <a href="<?php echo $base_url; ?>modules/siswa/siswa.php" class="nav-item sub">
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
                <a href="<?php echo $base_url; ?>logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
            </div>

            <h2>Ringkasan Cepat</h2>
            <div class="stat-grid">
                <div class="stat-card">
                    <h3>Total Siswa</h3>
                    <div class="value"><?php echo $total_siswa; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Pelatih</h3>
                    <div class="value"><?php echo $total_pelatih; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Penyewaan Aktif</h3>
                    <div class="value"><?php echo $total_sewa; ?></div>
                </div>
            </div>

            <h2>Aktivitas Hari Ini & Shortcut</h2>
            <hr>

            <div class="content-grid">
                <div class="data-box">
                    <h3>Jadwal Latihan Hari Ini</h3>
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Kelas</th>
                                <th>Pelatih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_jadwal = mysqli_query($conn, "SELECT t_jadwal.*, t_kelas.nama_kelas, t_pelatih.nama_pelatih 
                                             FROM t_jadwal 
                                             JOIN t_kelas ON t_jadwal.id_kelas = t_kelas.id_kelas
                                             JOIN t_pelatih ON t_jadwal.id_pelatih = t_pelatih.id_pelatih");
                            while ($d = mysqli_fetch_array($sql_jadwal)) {
                            ?>
                                <tr>
                                    <td><?php echo date('H:i', strtotime($d['jam'])); ?></td>
                                    <td><?php echo $d['nama_kelas']; ?></td>
                                    <td><?php echo $d['nama_pelatih']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="data-box" style="margin-bottom: 20px;">
                    <h3>Penyewaan Belum Kembali</h3>
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Penyewa</th>
                                <th>Aset</th>
                                <th>Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_sewa = mysqli_query($conn, "SELECT t_sewa.nama_penyewa, t_sewa.tgl_kembali, t_aset.nama_aset 
                            FROM t_sewa 
                            JOIN t_detail_sewa ON t_sewa.id_sewa = t_detail_sewa.id_sewa
                            JOIN t_aset ON t_detail_sewa.id_aset = t_aset.id_aset
                            WHERE t_sewa.status = 'Belum Kembali' 
                            ORDER BY t_sewa.tgl_kembali ASC 
                            LIMIT 5");

                            if (mysqli_num_rows($sql_sewa) > 0) {
                                while ($s = mysqli_fetch_array($sql_sewa)) {
                            ?>
                                    <tr>
                                        <td><strong><?php echo $s['nama_penyewa']; ?></strong></td>
                                        <td><?php echo $s['nama_aset']; ?></td>
                                        <td>
                                            <span style="color: #e53e3e; font-weight: 600;">
                                                <?php echo tgl_indo($s['tgl_kembali']); ?>
                                            </span>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='3' style='text-align:center; padding: 20px;'>✅ Semua aset sudah kembali</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="data-box">
                    <h3>Pemasukan Bulan Ini</h3>
                    <div style="font-size: 1.5rem; font-weight: 700; margin-top: 10px;">
                        <?php echo formatRupiah($total_duit); ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>