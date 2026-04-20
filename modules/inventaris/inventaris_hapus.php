<?php
include "../../config/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM t_aset WHERE id_aset = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data Aset Berhasil Dihapus!');
                window.location='inventaris.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal Menghapus: Aset ini mungkin sedang digunakan dalam data penyewaan!');
                window.location='inventaris.php';
              </script>";
    }
} else {
    header("location:inventaris.php");
}
?>