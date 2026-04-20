<?php
include "../../config/koneksi.php";

// 1. Cek apakah ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 2. Query hapus data aset
    $query = "DELETE FROM t_aset WHERE id_aset = '$id'";

    if (mysqli_query($conn, $query)) {
        // 3. Jika berhasil, munculkan alert dan kembali ke halaman utama inventaris
        echo "<script>
                alert('Data Aset Berhasil Dihapus!');
                window.location='inventaris.php';
              </script>";
    } else {
        // Jika gagal (misal: ID sedang dipakai di tabel sewa), tampilkan pesan error
        echo "<script>
                alert('Gagal Menghapus: Aset ini mungkin sedang digunakan dalam data penyewaan!');
                window.location='inventaris.php';
              </script>";
    }
} else {
    // Jika tidak ada ID di URL, arahkan balik ke inventaris
    header("location:inventaris.php");
}
?>