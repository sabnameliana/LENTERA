<?php
include "../../config/koneksi.php";

// 1. Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 2. Query hapus data sesuai struktur tabel t_jadwal
    $query = "DELETE FROM t_jadwal WHERE id_jadwal = '$id'";

    if (mysqli_query($conn, $query)) {
        // 3. Jika berhasil, munculkan alert dan balik ke halaman jadwal
        echo "<script>
                alert('Jadwal Latihan Berhasil Dihapus!');
                window.location='jadwal.php';
              </script>";
    } else {
        // Jika gagal karena ada relasi (foreign key), tampilkan error
        echo "<script>
                alert('Gagal Menghapus: Data ini masih terhubung dengan tabel lain!');
                window.location='jadwal.php';
              </script>";
    }
} else {
    // Jika tidak ada ID, langsung balik ke jadwal
    header("location:jadwal.php");
}
?>