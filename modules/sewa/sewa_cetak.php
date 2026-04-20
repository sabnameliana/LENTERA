<?php
session_start();
include "../../config/koneksi.php";
include "../../config/fungsi.php";

$id = $_GET['id'];

$query_sewa = mysqli_query($conn, "SELECT * FROM t_sewa WHERE id_sewa = '$id'");
$data = mysqli_fetch_assoc($query_sewa);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Sewa #<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?></title>
    <style>
        body { font-family: 'Poppins', sans-serif; color: #333; margin: 0; padding: 20px; }
        .nota-box { max-width: 600px; margin: auto; border: 1px solid #eee; padding: 30px; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #437677; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #437677; }
        .info-sewa { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f4f4f4; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-section { text-align: right; margin-top: 20px; font-weight: bold; font-size: 18px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        
        @print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="background: #437677; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fa-solid fa-print"></i> Klik untuk Cetak Nota
        </button>
        <a href="sewa.php" style="text-decoration: none; color: #666; margin-left: 10px;">Kembali</a>
    </div>

    <div class="nota-box">
        <div class="header">
            <h2>SANGGAR LENTERA</h2>
            <p>Kertosari, Singorojo, Kendal</p>
        </div>

        <div class="info-sewa">
            <div>
                <strong>Penyewa:</strong> <?php echo $data['nama_penyewa']; ?><br>
                <strong>Status:</strong> <?php echo $data['status']; ?>
            </div>
            <div style="text-align: right;">
                <strong>No. Nota:</strong> #<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?><br>
                <strong>Tgl Sewa:</strong> <?php echo date('d/m/Y', strtotime($data['tgl_sewa'])); ?><br>
                <strong>Tgl Kembali:</strong> <?php echo date('d/m/Y', strtotime($data['tgl_kembali'])); ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama Aset</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query_item = mysqli_query($conn, "SELECT t_detail_sewa.*, t_aset.nama_aset, t_aset.harga_sewa 
                                                 FROM t_detail_sewa 
                                                 JOIN t_aset ON t_detail_sewa.id_aset = t_aset.id_aset 
                                                 WHERE t_detail_sewa.id_sewa = '$id'");
                
                while ($item = mysqli_fetch_assoc($query_item)) {
                    $subtotal = $item['harga_sewa'] * $item['qty'];
                ?>
                <tr>
                    <td><?php echo $item['nama_aset']; ?></td>
                    <td style="text-align: center;"><?php echo $item['qty']; ?></td>
                    <td style="text-align: right;">Rp <?php echo number_format($item['harga_sewa'], 0, ',', '.'); ?></td>
                    <td style="text-align: right;">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="total-section">
            Total Bayar: Rp <?php echo number_format($data['total_bayar'], 0, ',', '.'); ?>
        </div>

        <div class="footer">
            <p>Terima kasih telah mempercayakan kebutuhan seni Anda pada kami.</p>
            <p><em>Nota ini adalah bukti sah penyewaan di Sanggar Lentera.</em></p>
        </div>
    </div>

</body>
</html>