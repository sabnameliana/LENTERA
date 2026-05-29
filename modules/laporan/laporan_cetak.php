<?php
session_start();
require_once "../../config/koneksi.php";

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../../login.php?pesan=belum_login");
    exit();
}

// Menentukan jenis laporan yang akan dicetak (default: siswa)
$jenis_laporan = isset($_GET['jenis_laporan']) ? $_GET['jenis_laporan'] : 'siswa';

$where = "WHERE 1=1";
$info_periode = "Semua Periode";

// 1. Kondisi Filter untuk LAPORAN PEMBAYARAN SISWA
if ($jenis_laporan == 'siswa') {
    if (isset($_GET['bulan']) && $_GET['bulan'] != "") {
        $bulan = $_GET['bulan'];
        $where .= " AND p.bulan = '$bulan'";
        $info_periode = $bulan . " 2026";
    }

    if (isset($_GET['from']) && $_GET['from'] != "" && isset($_GET['to']) && $_GET['to'] != "") {
        $from = $_GET['from'];
        $to = $_GET['to'];
        $where .= " AND p.tgl_bayar BETWEEN '$from' AND '$to'";
        $info_periode = date('d/m/Y', strtotime($from)) . " s/d " . date('d/m/Y', strtotime($to));
    }

    if (isset($_GET['search_nama']) && $_GET['search_nama'] != "") {
        $search_nama = mysqli_real_escape_string($conn, $_GET['search_nama']);
        $where .= " AND s.nama_siswa LIKE '%$search_nama%'";
    }
} 
// 2. Kondisi Filter untuk LAPORAN KEUANGAN SEWA
else {
    if (isset($_GET['from']) && $_GET['from'] != "" && isset($_GET['to']) && $_GET['to'] != "") {
        $from = $_GET['from'];
        $to = $_GET['to'];
        $where .= " AND tgl_sewa BETWEEN '$from' AND '$to'";
        $info_periode = date('d/m/Y', strtotime($from)) . " s/d " . date('d/m/Y', strtotime($to));
    }

    if (isset($_GET['search_nama']) && $_GET['search_nama'] != "") {
        $search_nama = mysqli_real_escape_string($conn, $_GET['search_nama']);
        $where .= " AND nama_penyewa LIKE '%$search_nama%'";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - <?php echo $jenis_laporan == 'siswa' ? 'Pembayaran Siswa' : 'Keuangan Sewa'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>

    <div class="no-print" style="margin-top: 50px; margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="background: #437677; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            CETAK
        </button>
    </div>

    <div class="nota-box">
        <div class="header-nota">
            <h2>SANGGAR LENTERA</h2>
            <p>Desa Kertosari, Kec. Singorojo, Kab. Kendal</p>
            <p><strong>LAPORAN <?php echo $jenis_laporan == 'siswa' ? 'PEMBAYARAN SISWA' : 'KEUANGAN SEWA'; ?></strong></p>
        </div>

        <div class="info-laporan">
            <div>
                <strong>Dicetak Oleh:</strong> <?php echo $_SESSION['nama_admin']; ?>
            </div>
            <div style="text-align: right;">
                <strong>Periode Laporan:</strong> <?php echo $info_periode; ?>
            </div>
        </div>

        <table class="table-cetak">
            <?php if ($jenis_laporan == 'siswa') { ?>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Bayar</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Bulan</th>
                        <th style="text-align: right;">Jumlah</th>
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
                                                    $where 
                                                    ORDER BY p.tgl_bayar DESC");

                    if (mysqli_num_rows($query) > 0) {
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
                                <td><?php echo $row['bulan']; ?></td>
                                <td style="text-align: right;">Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>Data tidak ditemukan untuk periode ini.</td></tr>";
                    }
                    ?>
                </tbody>

            <?php } else { ?>
                
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Sewa</th>
                        <th>Tgl Kembali</th>
                        <th>Nama Penyewa</th>
                        <th>No. HP</th>
                        <th style="text-align: right;">Total Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total_terbayar = 0;
                    $total_belum = 0;

                    $query_sewa = mysqli_query($conn, "SELECT * FROM t_sewa $where ORDER BY tgl_sewa DESC");

                    if (mysqli_num_rows($query_sewa) > 0) {
                        while ($row = mysqli_fetch_assoc($query_sewa)) {
                            if ($row['status'] == 'Kembali' || $row['status'] == 'Lunas') {
                                $total_terbayar += $row['total_bayar'];
                            } else {
                                $total_belum += $row['total_bayar'];
                            }
                    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tgl_sewa'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tgl_kembali'])); ?></td>
                                <td><?php echo $row['nama_penyewa']; ?></td>
                                <td><?php echo $row['nomor_hp']; ?></td>
                                <td style="text-align: right;">Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>Data tidak ditemukan untuk periode ini.</td></tr>";
                    }
                    ?>
                </tbody>
            <?php } ?>
        </table>

        <div class="total-section">
            <div class="total-box">
                <div class="total-row" style="color: #2f855a;">
                    <span>Total Terbayar/Selesai:</span>
                    <span>Rp <?php echo number_format($total_terbayar, 0, ',', '.'); ?></span>
                </div>
                <div class="total-row" style="color: #e53e3e;">
                    <span>Total Belum/Tertunggak:</span>
                    <span>Rp <?php echo number_format($total_belum, 0, ',', '.'); ?></span>
                </div>
                <div class="total-row" style="border-top: 2px solid #333; margin-top: 10px; padding-top: 10px;">
                    <span>GRAND TOTAL:</span>
                    <span>Rp <?php echo number_format($total_terbayar + $total_belum, 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>