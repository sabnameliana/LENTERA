<?php
include "../../config/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM t_jadwal WHERE id_jadwal = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Jadwal Latihan Berhasil Dihapus!');
                window.location='jadwal.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal Menghapus: Data ini masih terhubung dengan tabel lain!');
                window.location='jadwal.php';
              </script>";
    }
} else {
    header("location:jadwal.php");
}
?>