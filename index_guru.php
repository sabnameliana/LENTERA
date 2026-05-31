<?php
session_start();
require_once "config/koneksi.php";
require_once "config/fungsi.php";

// Keamanan: Kalau bukan guru (atau belum login), tendang ke login
if (!isset($_SESSION['status']) || $_SESSION['role'] != "guru") {
    header("location:login.php?pesan=belum_login");
    exit();
}

$base_url = "http://localhost/LENTERA/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru - LENTERA</title>
    <link rel="stylesheet" href="assets/css/style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="wrapper" style="display: flex;">
        <aside class="sidebar" style="width: 250px; background: #fff; min-height: 100vh;">
            <div class="logo" style="padding: 20px; font-weight: bold; font-size: 1.2rem;">
                LENTERA GURU
            </div>
            <nav class="nav-menu">
                <a href="index_guru.php" class="nav-item active">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </nav>
        </aside>

        <main class="main-content" style="flex: 1; padding: 20px; background: #f8f9fa;">
            <div class="top-bar" style="background-color: #A4BCC2; padding: 12px 25px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #333;">Halo Guru, <strong><?php echo $_SESSION['username']; ?>!</strong></span>
                <a href="<?php echo $base_url; ?>logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
                </a>
            </div>

            <div class="content-body" style="margin-top: 25px;">
                <div class="data-box" style="background: white; padding: 20px; border-radius: 15px; border: 1px solid #ddd;">
                    <h3 style="font-weight: 500; font-size: 1.1rem; color: #333;">Selamat Datang di Panel Guru</h3>
                    <p style="margin-top: 10px; color: #666; font-size: 0.9rem;">
                        Di sini Anda dapat mengatur jadwal melatih Anda secara mandiri dan melihat agenda sanggar yang aktif.
                    </p>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-weight: 600;">Jadwal Latihan Sanggar</h2>
                <a href="modules/jadwal/jadwal_tambah.php" style="background-color: #437677; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i> Tambah Jadwal Baru Saya
                </a>
            </div>

            <div class="data-box" style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <table class="table-custom" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #E0E0E0; text-align: left;">
                            <th style="padding: 15px;">ID</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Nama Pelatih</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT t_jadwal.*, t_kelas.nama_kelas, t_kelas.tingkat, t_pelatih.nama_pelatih 
                                                FROM t_jadwal 
                                                JOIN t_kelas ON t_jadwal.id_kelas = t_kelas.id_kelas 
                                                JOIN t_pelatih ON t_jadwal.id_pelatih = t_pelatih.id_pelatih
                                                ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jam ASC");

                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr style="border-bottom: 1px solid #EEE;">
                                <td style="padding: 15px;"><?php echo str_pad($row['id_jadwal'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><strong><?php echo $row['hari']; ?></strong></td>
                                <td><?php echo date('H.i', strtotime($row['jam'])); ?> - Selesai</td>
                                <td><?php echo $row['nama_kelas'] . " - " . $row['tingkat']; ?></td>
                                <td><?php echo $row['nama_pelatih']; ?></td>
                                <td>
                                    <a href="jadwal_edit.php?id=<?php echo $row['id_jadwal']; ?>" style="color: #333; margin-right: 12px; font-size: 18px;"><i class="fa-regular fa-pen-to-square"></i></a>
                                    <a href="jadwal_hapus.php?id=<?php echo $row['id_jadwal']; ?>" style="color: #333; font-size: 18px;" onclick="return confirm('Yakin hapus jadwal ini?')"><i class="fa-regular fa-trash-can"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
                
                </div>
        </main>
    </div>

</body>
</html>