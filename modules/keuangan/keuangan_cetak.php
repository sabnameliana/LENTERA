<?php
session_start();
include "../../config/koneksi.php";

$id = $_GET['id'];

// Query untuk mengambil data pembayaran join dengan tabel siswa untuk mendapatkan nama siswa
$query_bayar = mysqli_query($conn, "SELECT t_pembayaran_siswa.*, t_siswa.nama_siswa 
                                    FROM t_pembayaran_siswa 
                                    JOIN t_siswa ON t_pembayaran_siswa.id_siswa = t_siswa.id_siswa 
                                    WHERE t_pembayaran_siswa.id_bayar = '$id'");
$data = mysqli_fetch_assoc($query_bayar);

if (!$data) {
    echo "Data pembayaran tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran #<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?></title>
    <style>
        body { font-family: 'Poppins', sans-serif; color: #333; margin: 0; padding: 20px; }
        .nota-box { max-width: 600px; margin: auto; border: 1px solid #eee; padding: 30px; border-radius: 10px; position: relative; }
        .header { text-align: center; border-bottom: 2px solid #437677; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #437677; }
        .info-pembayaran { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f4f4f4; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { text-align: right; margin-top: 20px; font-weight: bold; font-size: 18px; border-top: 2px solid #eee; padding-top: 10px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        .stempel-lunas { 
            position: absolute; 
            border: 3px double #d9534f; 
            color: #d9534f; 
            font-weight: bold; 
            padding: 5px 15px; 
            transform: rotate(-15deg); 
            right: 50px; 
            bottom: 100px; 
            font-size: 20px;
            opacity: 0.7;
        }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .nota-box { border: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="background: #437677; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            Klik untuk Cetak Kwitansi
        </button>
        <a href="keuangan.php" style="text-decoration: none; color: #666; margin-left: 10px;">Kembali</a>
    </div>

    <div class="nota-box">
        <div class="header">
            <h2>SANGGAR LENTERA</h2>
            <p>Kertosari, Singorojo, Kendal</p>
        </div>

        <div class="info-pembayaran">
            <div>
                <strong>Nama Siswa:</strong> <?php echo $data['nama_siswa']; ?><br>
                <strong>Bulan Iuran:</strong> <?php echo $data['bulan']; ?>
            </div>
            <div style="text-align: right;">
                <strong>No. Kwitansi:</strong> #<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?><br>
                <strong>Tgl Bayar:</strong> <?php echo date('d/m/Y', strtotime($data['tgl_bayar'])); ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th style="text-align: right;">Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pembayaran Iuran Siswa - Bulan <?php echo $data['bulan']; ?></td>
                    <td style="text-align: right;">Rp <?php echo number_format($data['jumlah'], 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            TOTAL: Rp <?php echo number_format($data['jumlah'], 0, ',', '.'); ?>
        </div>

        <?php if ($data['status'] == 'Lunas') : ?>
            <div class="stempel-lunas">LUNAS</div>
        <?php endif; ?>

        <div class="footer">
            <p>Terima kasih atas dukungannya dalam melestarikan seni budaya.</p>
            <p><em>Kwitansi ini adalah bukti pembayaran yang sah di Sanggar Lentera.</em></p>
        </div>
    </div>

</body>
</html>