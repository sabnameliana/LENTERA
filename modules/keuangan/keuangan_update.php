<?php
include "../../config/koneksi.php";

if (isset($_POST['update'])) {
    $id_bayar  = $_POST['id_bayar'];
    $id_siswa  = mysqli_real_escape_string($conn, $_POST['id_siswa']);
    $bulan     = mysqli_real_escape_string($conn, $_POST['bulan']);
    $jumlah    = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tgl_bayar = $_POST['tgl_bayar'];
    $status    = $_POST['status'];

    $query_update = "UPDATE t_pembayaran_siswa SET 
                        id_siswa  = '$id_siswa', 
                        bulan     = '$bulan', 
                        jumlah    = '$jumlah', 
                        tgl_bayar = '$tgl_bayar', 
                        status    = '$status' 
                    WHERE id_bayar = '$id_bayar'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>
                alert('Data Pembayaran Berhasil Diperbarui!'); 
                window.location='keuangan.php';
              </script>";
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
} else {
    header("location:keuangan.php");
}
?>