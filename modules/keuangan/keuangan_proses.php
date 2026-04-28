<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    $id_siswa  = mysqli_real_escape_string($conn, $_POST['id_siswa']);
    $bulan     = mysqli_real_escape_string($conn, $_POST['bulan']);
    $jumlah    = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tgl_bayar = $_POST['tgl_bayar'];
    $status    = $_POST['status'];

    $query_bayar = "INSERT INTO t_pembayaran_siswa (id_siswa, bulan, jumlah, tgl_bayar, status) 
                    VALUES ('$id_siswa', '$bulan', '$jumlah', '$tgl_bayar', '$status')";

    if (mysqli_query($conn, $query_bayar)) {
        echo "<script>
                alert('Berhasil! Data pembayaran siswa telah tersimpan.');
                window.location='keuangan.php';
              </script>";
    } else {
        echo "Error t_pembayaran_siswa: " . mysqli_error($conn);
    }
} else {
    header("location:keuangan.php");
}
?>