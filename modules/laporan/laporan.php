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
    <title>Laporan - Sanggar Lentera</title>
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

                <a href="<?php echo $base_url; ?>modules/keuangan/keuangan.php" class="nav-item">
                    <i class="fa-solid fa-money-bill"></i> Keuangan
                </a>

                <a href="<?php echo $base_url; ?>modules/laporan/laporan.php" class="nav-item active">
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
                <h2 style="font-weight: 600;">Laporan Pembayaran Siswa</h2>
                <a href="laporan_cetak.php" style="background-color: #437677; color: white; padding: 10px 25px; text-decoration: none; border-radius: 8px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; font-weight: bold;">
                    <i class="fa-solid fa-print"></i> CETAK
                </a>
            </div>

            <div style="background: white; border-radius: 15px; padding: 15px 20px; border: 1px solid #ccc; margin-bottom: 20px;">
                <form action="" method="GET" style="display: flex; align-items: center; gap: 15px; width: 100%;">
                    <span style="white-space: nowrap;">Periode :</span>
                    <select name="bulan" style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; flex: 1; font-family: 'Poppins';">
                        <option value="">Pilih Periode</option>
                        <option value="April">April 2026</option>
                    </select>
                    <span>From</span>
                    <input type="date" name="from" style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; flex: 1; font-family: 'Poppins';">
                    <span>To</span>
                    <input type="date" name="to" style="padding: 10px; border-radius: 8px; border: 1px solid #ccc; flex: 1; font-family: 'Poppins';">
                    <button type="submit" style="background-color: #437677; color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: bold;">FILTER</button>
                </form>
            </div>

            <div style="display: flex; gap: 20px; align-items: flex-start;">
                <div style="flex: 3; background: white; border-radius: 15px; border: 1px solid #ccc; overflow: hidden; display: flex; flex-direction: column;">
                    <div class="data-box" style="padding: 0;">
                        <table class="table-custom" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #E0E0E0; text-align: left;">
                                    <th style="padding: 15px;">No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Total Pembayaran</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $total_terbayar = 0;
                                $total_belum = 0;

                                $query = mysqli_query($conn, "SELECT p.*, s.nama_siswa, k.nama_kelas 
                                        FROM t_pembayaran_siswa p 
                                        JOIN t_siswa s ON p.id_siswa = s.id_siswa 
                                        JOIN t_kelas k ON s.id_kelas = k.id_kelas 
                                        ORDER BY p.tgl_bayar DESC");

                                while ($row = mysqli_fetch_assoc($query)) {
                                    if ($row['status'] == 'Lunas') {
                                        $total_terbayar += $row['jumlah'];
                                        $bg = "#C6F6D5"; $txt = "#22543D";
                                    } else {
                                        $total_belum += $row['jumlah'];
                                        $bg = "#FDF2AF"; $txt = "#856404";
                                    }
                                ?>
                                    <tr style="border-bottom: 1px solid #EEE;">
                                        <td style="padding: 15px;"><?php echo $no++; ?></td>
                                        <td><?php echo $row['tgl_bayar']; ?></td>
                                        <td><?php echo $row['nama_siswa']; ?></td>
                                        <td><?php echo $row['nama_kelas']; ?></td>
                                        <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span style="background: <?php echo $bg; ?>; color: <?php echo $txt; ?>; padding: 5px 15px; border-radius: 8px; font-weight: bold; font-size: 12px;">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding: 15px 20px; border-top: 1px solid #ccc; display: flex; align-items: center; gap: 40px; font-size: 14px;">
                        <div style="font-weight: bold;">
                            Total Terbayar : <span style="color: #48BB78; margin-left: 5px;">Rp <?php echo number_format($total_terbayar, 0, ',', '.'); ?></span>
                        </div>
                        <div style="font-weight: bold;">
                            Total Belum Dibayar : <span style="color: #E53E3E; margin-left: 5px;">Rp <?php echo number_format($total_belum, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>

                <div style="flex: 1; background: white; border-radius: 15px; padding: 20px; border: 1px solid #ccc; height: fit-content;">
                    <div style="margin-bottom: 20px;">
                        <p style="margin: 0; font-size: 14px; font-weight: 600;">Total Terbayar :</p>
                        <h3 style="margin: 5px 0; color: #48BB78; font-weight: 700;">Rp <?php echo number_format($total_terbayar, 0, ',', '.'); ?></h3>
                    </div>
                    <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                    <div>
                        <p style="margin: 0; font-size: 14px; font-weight: 600;">Total Belum Dibayar :</p>
                        <h3 style="margin: 5px 0; color: #E53E3E; font-weight: 700;">Rp <?php echo number_format($total_belum, 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>