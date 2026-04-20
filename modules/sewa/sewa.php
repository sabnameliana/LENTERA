<?php
session_start();
require_once "../../config/koneksi.php";

// Cek Login (Pastikan session sudah ada)
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../../login.php?pesan=belum_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sewa - Sanggar Lentera</title>
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
                <div class="nav-item"><i class="fa-solid fa-database"></i> Master Data</div>
                <a href="../siswa/siswa.php" class="nav-item sub"><i class="fa-solid fa-user-group"></i> Siswa</a>
                <a href="../jadwal/jadwal.php" class="nav-item sub"><i class="fa-solid fa-calendar-days"></i> Jadwal</a>
                <a href="../inventaris/inventaris.php" class="nav-item sub"><i class="fa-solid fa-box"></i> Inventaris</a>
                <a href="sewa.php" class="nav-item active"><i class="fa-solid fa-cart-shopping"></i> Sewa</a>
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
                <h2 style="font-weight: 600;">Kelola Transaksi Sewa</h2>
                <a href="sewa_tambah.php" style="background-color: #437677; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i> Tambah Sewa Baru
                </a>
            </div>

            <div class="data-box" style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <table class="table-custom" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #E0E0E0; text-align: left;">
                            <th style="padding: 15px;">ID</th>
                            <th>Tanggal</th>
                            <th>Tgl Kembali</th>
                            <th>Penyewa</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil data dari tabel t_sewa
                        $query = mysqli_query($conn, "SELECT * FROM t_sewa ORDER BY id_sewa DESC");
                        while ($row = mysqli_fetch_assoc($query)) {
                            // Warna Badge Status
                            $status_color = ($row['status'] == 'Lunas') ? '#C6F6D5' : '#FED7D7';
                            $text_color = ($row['status'] == 'Lunas') ? '#22543D' : '#822727';
                        ?>
                            <tr style="border-bottom: 1px solid #EEE;">
                                <td style="padding: 15px;"><?php echo str_pad($row['id_sewa'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $row['tgl_sewa']; ?></td>
                                <td><?php echo $row['tgl_kembali']; ?></td>
                                <td><strong><?php echo $row['nama_penyewa']; ?></strong></td>
                                <td>Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Belum Kembali') { ?>
                                        <a href="sewa_kembali.php?id=<?php echo $row['id_sewa']; ?>"
                                            class="btn-kembali"
                                            onclick="return confirm('Apakah barang sudah kembali? Stok akan otomatis bertambah.')"
                                            style="background: #ebf8ff; color: #2b6cb0; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px;">
                                            <i class="fa-solid fa-rotate-left"></i> Set Kembali
                                        </a>
                                    <?php } else { ?>
                                        <span style="color: #2f855a; font-weight: bold;"><i class="fa-solid fa-check"></i> Sudah Kembali</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="sewa_edit.php?id=<?php echo $row['id_sewa']; ?>" style="color: #333;"><i class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="sewa_hapus.php?id=<?php echo $row['id_sewa']; ?>" onclick="return confirm('Hapus data sewa?')" style="color: #333;"><i class="fa-regular fa-trash-can"></i></a>
                                        <a href="sewa_cetak.php?id=<?php echo $row['id_sewa']; ?>" style="color: #333; border: 1px solid #ddd; padding: 2px 8px; border-radius: 5px; font-size: 12px; text-decoration: none;">
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