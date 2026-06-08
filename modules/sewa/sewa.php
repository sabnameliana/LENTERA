<?php
session_start();
require_once "../../config/koneksi.php";
require_once "../../config/fungsi.php";

$base_url = "http://localhost/LENTERA/";

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../../login.php?pesan=belum_login");
    exit();
}

$tgl_hari_ini = date('Y-m-d');
// PERBAIKAN: Mengubah status pencarian menjadi 'Belum Kembali' sesuai database kamu
$query_hitung_telat = mysqli_query($conn, "SELECT COUNT(*) as total_telat FROM t_sewa WHERE status = 'Belum Kembali' AND tgl_kembali < '$tgl_hari_ini'");
$data_telat = mysqli_fetch_assoc($query_hitung_telat);
$jumlah_telat = $data_telat['total_telat'];
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

                <a href="<?php echo $base_url; ?>modules/sewa/sewa.php" class="nav-item active">
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
                <a href="../../logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>

            <?php
            if ($jumlah_telat > 0) {
                echo "
                <div class='alert alert-danger' role='alert' style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-size: 14px;'>
                    <i class='fa-solid fa-triangle-exclamation'></i> 
                    <strong>Perhatian!</strong> Ada <strong>$jumlah_telat</strong> transaksi penyewaan yang <strong>Terlambat</strong> dikembalikan. Mohon segera hubungi penyewa!
                </div>
                ";
            }
            ?>

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
                            <th>Nomor HP</th> 
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM t_sewa ORDER BY id_sewa DESC");
                        while ($row = mysqli_fetch_assoc($query)) {
                            $status = $row['status'];
                            $tgl_kembali = $row['tgl_kembali'];
                            
                            if ($status == 'Belum Kembali' && $tgl_hari_ini > $tgl_kembali) {
                                $t1 = new DateTime($tgl_kembali);
                                $t2 = new DateTime($tgl_hari_ini);
                                $selisih = $t1->diff($t2);
                                $hari_telat = $selisih->days;

                                $no_hp_wa = $row['nomor_hp'];
                                if (substr($no_hp_wa, 0, 1) === '0') {
                                    $no_hp_wa = '62' . substr($no_hp_wa, 1);
                                }

                                $nama_target = urlencode($row['nama_penyewa']);
                                $pesan_wa = "Halo%20*{$nama_target}*,%20kami%20dari%20*Sanggar%20Lentera*%20ingin%20mengingatkan%20bahwa%20masa%20sewa%20aset/kostum%20Anda%20telah%20*terlambat%20{$hari_telat}%20hari*%20(jatuh%20tempo%20pada%20{$row['tgl_kembali']}).%20Mohon%20untuk%20segera%20melakukan%20pengembalian%20ya.%20Terima%20kasih!%20🙏";

                                $tombol_wa = "<a href='https://wa.me/{$no_hp_wa}?text={$pesan_wa}' target='_blank' style='background: #25D366; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; display: inline-block; margin-top: 3px; font-weight: normal;'>
                                                <i class='fa-brands fa-whatsapp'></i> Hubungi WA
                                              </a>";

                                $badge_status = "<span style='background: #fed7d7; color: #822727; padding: 4px 8px; border-radius: 5px; font-size: 11px; font-weight: bold; display: inline-block; margin-bottom: 3px;'><i class='fa-solid fa-clock'></i> Terlambat ($hari_telat Hari)</span><br>" . $tombol_wa . "<br>";
                            } else {
                                $badge_status = "";
                            }
                        ?>
                            <tr style="border-bottom: 1px solid #EEE;">
                                <td style="padding: 15px;"><?php echo str_pad($row['id_sewa'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $row['tgl_sewa']; ?></td>
                                <td><?php echo $row['tgl_kembali']; ?></td>
                                <td><strong><?php echo $row['nama_penyewa']; ?></strong></td>
                                <td><?php echo $row['nomor_hp']; ?></td> 
                                <td>Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    echo $badge_status; 

                                    if ($status == 'Belum Kembali') { 
                                    ?>
                                        <form action="sewa_proses.php" method="POST" style="display:inline;" onclick="return confirm('Apakah barang sudah kembali? Stok akan otomatis bertambah dan denda dihitung.')">
                                            <input type="hidden" name="id_sewa" value="<?php echo $row['id_sewa']; ?>">
                                            <button type="submit" name="proses_kembali" style="background: #ebf8ff; color: #2b6cb0; padding: 5px 10px; border: 1px solid #bee3f8; border-radius: 5px; font-size: 12px; cursor: pointer;">
                                                <i class="fa-solid fa-rotate-left"></i> Set Kembali
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <span style="color: #2f855a; font-weight: bold; font-size: 13px;"><i class="fa-solid fa-check"></i> Sudah Kembali</span>
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