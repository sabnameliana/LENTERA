<?php
include "../../config/koneksi.php";

// Mengecek apakah ada parameter id yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id_bayar = $_GET['id'];

    // Melakukan query hapus data berdasarkan id_bayar
    // Nama tabel disesuaikan dengan database Anda: t_pembayaran_siswa
    $hapus = mysqli_query($conn, "DELETE FROM t_pembayaran_siswa WHERE id_bayar = '$id_bayar'");

    if ($hapus) {
        // Jika berhasil, tampilkan alert dan kembali ke halaman utama keuangan
        echo "<script>
                alert('Data pembayaran berhasil dihapus!');
                window.location='keuangan.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error dari MySQL
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    // Jika tidak ada ID yang dipilih, lempar kembali ke halaman keuangan
    header("location:keuangan.php");
}
?>