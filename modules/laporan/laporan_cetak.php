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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran Siswa</title>
    <style>
        body { font-family: 'Poppins', sans-serif; color: #333; margin: 0; padding: 20px; }
        .nota-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #437677; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #437677; }
        .info-sewa { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f4f4f4; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { text-align: right; margin-top: 20px; font-weight: bold; font-size: 16px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="background: #437677; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fa-solid fa-print"></i> Klik untuk Cetak Laporan
        </button>
        <a href="laporan.php" style="text-decoration: none; color: #666; margin-left: 10px;">Kembali</a>
    </div>

    <div class="nota-box">
        <div class="header">
            <h2>SANGGAR LENTERA</h2>
            <p>Kertosari, Singorojo, Kendal</p>
            <p><strong>LAPORAN PEMBAYARAN SISWA</strong></p>
        </div>

        <div class="info-sewa">
            <div>
                <strong>Dicetak Oleh:</strong> <?php echo $_SESSION['nama_admin']; ?><br>
                <strong>Status:</strong> Laporan Resmi
            </div>
            <div style="text-align: right;">
                <strong>Tanggal Cetak:</strong> <?php echo date('d/m/Y'); ?><br>
                <strong>Periode:</strong> April 2026
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th style="text-align: right;">Jumlah</th>
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
                    } else {
                        $total_belum += $row['jumlah'];
                    }
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                    <td><?php echo $row['nama_siswa']; ?></td>
                    <td><?php echo $row['nama_kelas']; ?></td>
                    <td style="text-align: right;">Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="total-section">
            <div style="color: #48BB78;">Total Terbayar: Rp <?php echo number_format($total_terbayar, 0, ',', '.'); ?></div>
            <div style="color: #E53E3E; margin-top: 5px;">Total Belum Dibayar: Rp <?php echo number_format($total_belum, 0, ',', '.'); ?></div>
        </div>

        <div class="footer">
            <p>Laporan ini dihasilkan secara otomatis oleh Sistem Sanggar Lentera.</p>
            <p><em>Dicetak pada: <?php echo date('d/m/Y H:i:s'); ?></em></p>
        </div>
    </div>

</body>
</html>