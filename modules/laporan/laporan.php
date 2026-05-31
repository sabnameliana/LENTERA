<?php
session_start();
require_once "../../config/koneksi.php";
require_once "../../config/fungsi.php";

$base_url = "http://localhost/LENTERA/";

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../../login.php?pesan=belum_login");
    exit();
}

// Menentukan halaman laporan aktif (default: siswa)
$jenis_laporan = isset($_GET['jenis_laporan']) ? $_GET['jenis_laporan'] : 'siswa';

$where = "WHERE 1=1";

if (isset($_GET['bulan']) && $_GET['bulan'] != "") {
    $bulan = $_GET['bulan'];
    $where .= " AND p.bulan = '$bulan'";
}

if (isset($_GET['from']) && $_GET['from'] != "" && isset($_GET['to']) && $_GET['to'] != "") {
    $from = $_GET['from'];
    $to = $_GET['to'];
    if ($jenis_laporan == 'siswa') {
        $where .= " AND p.tgl_bayar BETWEEN '$from' AND '$to'";
    } else {
        $where .= " AND p.tgl_sewa BETWEEN '$from' AND '$to'";
    }
}

// Filter pencarian nama siswa
if (isset($_GET['search_nama']) && $_GET['search_nama'] != "" && $jenis_laporan == 'siswa') {
    $search_nama = mysqli_real_escape_string($conn, $_GET['search_nama']);
    $where .= " AND s.nama_siswa LIKE '%$search_nama%'";
}

// Filter khusus Laporan Sewa agar tidak bentrok dengan variabel $where asli milik siswa
$where_sewa = "WHERE 1=1";
if ($jenis_laporan == 'sewa') {
    if (isset($_GET['search_nama']) && $_GET['search_nama'] != "") {
        $search_nama_sewa = mysqli_real_escape_string($conn, $_GET['search_nama']);
        $where_sewa .= " AND nama_penyewa LIKE '%$search_nama_sewa%'";
    }
    if (isset($_GET['from']) && $_GET['from'] != "" && isset($_GET['to']) && $_GET['to'] != "") {
        $from_sewa = $_GET['from'];
        $to_sewa = $_GET['to'];
        $where_sewa .= " AND tgl_sewa BETWEEN '$from_sewa' AND '$to_sewa'";
    }
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
    
    <style>
        /* Gaya Tampilan Navigasi Tab Modern */
        .tab-container {
            display: flex;
            gap: 5px;
            margin-bottom: -1px;
            position: relative;
            z-index: 2;
        }
        .tab-item {
            padding: 12px 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            color: #666;
            background: #E8EBEB;
            border: 1px solid #ccc;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease;
        }
        .tab-item:hover {
            background: #DCE2E2;
            color: #333;
        }
        .tab-item.active {
            background: #ffffff;
            color: #437677;
            border-bottom: 2px solid #ffffff;
            padding-bottom: 13px;
        }
        .content-card {
            background: white; 
            border-radius: 0 15px 15px 15px; 
            padding: 24px; 
            border: 1px solid #ccc; 
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .form-inline {
            display: flex; 
            align-items: center; 
            flex-wrap: wrap;
            gap: 15px; 
            width: 100%;
        }
        .form-group-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }
        .form-group-custom label {
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
            color: #444;
        }
        .input-custom {
            width: 100%;
            padding: 10px 12px; 
            border-radius: 8px; 
            border: 1px solid #ccc; 
            font-family: 'Poppins';
            font-size: 14px;
            outline: none;
        }
        .input-custom:focus {
            border-color: #437677;
        }
        .btn-filter {
            background-color: #437677; 
            color: white; 
            border: none; 
            padding: 11px 25px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold;
            font-family: 'Poppins';
            font-size: 14px;
        }
        .btn-reset {
            background-color: #E53E3E; 
            color: white; 
            text-decoration: none; 
            padding: 10px 20px; 
            border-radius: 8px; 
            font-weight: bold; 
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="brand">
                <img src="../../assets/img/tari_logo.png" alt="Logo">
                <h3>Sanggar Tari</h3>
            </div>
            <nav class="nav-menu">
                <a href="<?php echo $base_url; ?>dashboard_admin.php" class="nav-item">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>

                <div class="nav-item">
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
                <h2 style="font-weight: 600; margin: 0;">Modul Laporan Keuangan</h2>
                <a href="laporan_cetak.php?jenis_laporan=<?php echo $jenis_laporan; ?>&search_nama=<?php echo isset($_GET['search_nama']) ? urlencode($_GET['search_nama']) : ''; ?>&bulan=<?php echo isset($_GET['bulan']) ? urlencode($_GET['bulan']) : ''; ?>&from=<?php echo isset($_GET['from']) ? urlencode($_GET['from']) : ''; ?>&to=<?php echo isset($_GET['to']) ? urlencode($_GET['to']) : ''; ?>"
                    target="_blank"
                    style="background-color: #437677; color: white; padding: 10px 25px; text-decoration: none; border-radius: 8px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fa-solid fa-print"></i> CETAK LAPORAN
                </a>
            </div>

            <div class="tab-container">
                <a href="laporan.php?jenis_laporan=siswa" class="tab-item <?php echo $jenis_laporan == 'siswa' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-graduate" style="margin-right: 6px;"></i> Pembayaran Siswa
                </a>
                <a href="laporan.php?jenis_laporan=sewa" class="tab-item <?php echo $jenis_laporan == 'sewa' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-receipt" style="margin-right: 6px;"></i> Penyewaan Aset / Kostum
                </a>
            </div>

            <div class="content-card">
                
                <div style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px dashed #eee;">
                    <form action="" method="GET" class="form-inline">
                        <input type="hidden" name="jenis_laporan" value="<?php echo $jenis_laporan; ?>">
                        
                        <div class="form-group-custom" style="flex: 1.5;">
                            <label><i class="fa-solid fa-magnifying-glass"></i> Nama:</label>
                            <input type="text" name="search_nama" class="input-custom" placeholder="<?php echo $jenis_laporan == 'siswa' ? 'Cari nama siswa...' : 'Cari nama penyewa...'; ?>" value="<?php echo isset($_GET['search_nama']) ? htmlspecialchars($_GET['search_nama']) : ''; ?>">
                        </div>

                        <?php if ($jenis_laporan == 'siswa') { ?>
                            <div class="form-group-custom">
                                <label><i class="fa-solid fa-calendar-minus"></i> Bulan:</label>
                                <select name="bulan" class="input-custom" style="cursor: pointer;">
                                    <option value="">-- Semua Bulan --</option>
                                    <?php
                                    $list_bulan = mysqli_query($conn, "SELECT DISTINCT bulan FROM t_pembayaran_siswa");
                                    while ($b = mysqli_fetch_array($list_bulan)) {
                                        $sel = (isset($_GET['bulan']) && $_GET['bulan'] == $b['bulan']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($b['bulan']) . "' $sel>" . htmlspecialchars($b['bulan']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php } ?>

                        <div class="form-group-custom">
                            <label>Dari:</label>
                            <input type="date" name="from" class="input-custom" value="<?php echo isset($_GET['from']) ? htmlspecialchars($_GET['from']) : ''; ?>">
                        </div>
                        
                        <div class="form-group-custom">
                            <label>Sampai:</label>
                            <input type="date" name="to" class="input-custom" value="<?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : ''; ?>">
                        </div>
                        
                        <div style="display: flex; gap: 8px; margin-left: auto;">
                            <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> FILTER</button>
                            <a href="laporan.php?jenis_laporan=<?php echo $jenis_laporan; ?>" class="btn-reset"><i class="fa-solid fa-rotate-left"></i></a>
                        </div>
                    </form>
                </div>

                <div style="display: flex; gap: 24px; align-items: flex-start;">
                    
                    <div style="flex: 3; border: 1px solid #ccc; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; background: #fafafa;">
                        <div class="data-box" style="padding: 0; background: white;">
                            
                            <?php if ($jenis_laporan == 'siswa') { ?>
                                <table class="table-custom" style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background-color: #EBF1F1; text-align: left; border-bottom: 2px solid #ccc;">
                                            <th style="padding: 15px; font-weight: 700; color: #333;">No</th>
                                            <th style="font-weight: 700; color: #333;">Tanggal</th>
                                            <th style="font-weight: 700; color: #333;">Nama Siswa</th>
                                            <th style="font-weight: 700; color: #333;">Kelas</th>
                                            <th style="font-weight: 700; color: #333;">Total Pembayaran</th>
                                            <th style="font-weight: 700; color: #333;">Status</th>
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

                                        if (mysqli_num_rows($query) == 0) {
                                            echo "<tr><td colspan='6' style='text-align:center; padding:30px; color:#999; font-style:italic;'>Data tidak ditemukan</td></tr>";
                                        }

                                        while ($row = mysqli_fetch_assoc($query)) {
                                            if ($row['status'] == 'Lunas') {
                                                $total_terbayar += $row['jumlah'];
                                                $bg = "#C6F6D5"; $txt = "#22543D";
                                            } else {
                                                $total_belum += $row['jumlah'];
                                                $bg = "#FED7D7"; $txt = "#822727";
                                            }
                                        ?>
                                            <tr style="border-bottom: 1px solid #EEE;">
                                                <td style="padding: 14px 15px;"><?php echo $no++; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['tgl_bayar'])); ?></td>
                                                <td style="font-weight: 600;"><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                                                <td><?php echo htmlspecialchars($row['nama_kelas']); ?></td>
                                                <td style="font-weight: 600;">Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <span style="background: <?php echo $bg; ?>; color: <?php echo $txt; ?>; padding: 4px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; display: inline-block;">
                                                        <?php echo htmlspecialchars($row['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            
                            <?php } else { ?>
                                
                                <table class="table-custom" style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background-color: #EBF1F1; text-align: left; border-bottom: 2px solid #ccc;">
                                            <th style="padding: 15px; font-weight: 700; color: #333;">No</th>
                                            <th style="font-weight: 700; color: #333;">Tgl Sewa</th>
                                            <th style="font-weight: 700; color: #333;">Tgl Kembali</th>
                                            <th style="font-weight: 700; color: #333;">Nama Penyewa</th>
                                            <th style="font-weight: 700; color: #333;">No. HP</th>
                                            <th style="font-weight: 700; color: #333;">Total Bayar</th>
                                            <th style="font-weight: 700; color: #333;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $total_terbayar = 0;
                                        $total_belum = 0;

                                        $query_sewa = mysqli_query($conn, "SELECT * FROM t_sewa $where_sewa ORDER BY tgl_sewa DESC");

                                        if (mysqli_num_rows($query_sewa) == 0) {
                                            echo "<tr><td colspan='7' style='text-align:center; padding:30px; color:#999; font-style:italic;'>Data tidak ditemukan</td></tr>";
                                        }

                                        while ($row = mysqli_fetch_assoc($query_sewa)) {
                                            if ($row['status'] == 'Kembali' || $row['status'] == 'Lunas') {
                                                $total_terbayar += $row['total_bayar'];
                                                $bg = "#C6F6D5"; $txt = "#22543D";
                                            } else {
                                                $total_belum += $row['total_bayar'];
                                                $bg = "#FED7D7"; $txt = "#822727";
                                            }
                                        ?>
                                            <tr style="border-bottom: 1px solid #EEE;">
                                                <td style="padding: 14px 15px;"><?php echo $no++; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['tgl_sewa'])); ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($row['tgl_kembali'])); ?></td>
                                                <td style="font-weight: 600;"><?php echo htmlspecialchars($row['nama_penyewa']); ?></td>
                                                <td><?php echo htmlspecialchars($row['nomor_hp']); ?></td>
                                                <td style="font-weight: 600;">Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <span style="background: <?php echo $bg; ?>; color: <?php echo $txt; ?>; padding: 4px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; display: inline-block;">
                                                        <?php echo htmlspecialchars($row['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                        
                        <div style="padding: 15px 20px; background: #fdfdfd; border-top: 1px solid #ccc; display: flex; align-items: center; gap: 30px; font-size: 13px;">
                            <div>
                                <i class="fa-solid fa-circle-check" style="color: #48BB78;"></i> Total Terbayar/Selesai: <strong style="color: #2F855A; margin-left: 2px;">Rp <?php echo number_format($total_terbayar, 0, ',', '.'); ?></strong>
                            </div>
                            <div>
                                <i class="fa-solid fa-circle-exclamation" style="color: #E53E3E;"></i> Total Belum/Tertunggak: <strong style="color: #9B2C2C; margin-left: 2px;">Rp <?php echo number_format($total_belum, 0, ',', '.'); ?></strong>
                            </div>
                        </div>
                    </div>

                    <div style="flex: 1; background: #F8FAFA; border-radius: 12px; padding: 20px; border: 1px solid #ccc; box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);">
                        <div style="margin-bottom: 18px;">
                            <p style="margin: 0; font-size: 13px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Total Terbayar</p>
                            <h3 style="margin: 5px 0; color: #48BB78; font-weight: 700; font-size: 20px;">Rp <?php echo number_format($total_terbayar, 0, ',', '.'); ?></h3>
                        </div>
                        <hr style="border: 0; border-top: 1px solid #E2E8F0; margin: 15px 0;">
                        <div>
                            <p style="margin: 0; font-size: 13px; font-weight: 600; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">Total Belum Dibayar</p>
                            <h3 style="margin: 5px 0; color: #E53E3E; font-weight: 700; font-size: 20px;">Rp <?php echo number_format($total_belum, 0, ',', '.'); ?></h3>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>

</body>
</html>