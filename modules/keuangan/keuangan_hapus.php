<?php
include "../../config/koneksi.php";

if (isset($_GET['id'])) {
    $id_bayar = $_GET['id'];

    $hapus = mysqli_query($conn, "DELETE FROM t_pembayaran_siswa WHERE id_bayar = '$id_bayar'");

    if ($hapus) {
        echo "<script>
                alert('Data pembayaran berhasil dihapus!');
                window.location='keuangan.php';
              </script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    header("location:keuangan.php");
}
?>