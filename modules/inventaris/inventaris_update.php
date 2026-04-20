<?php
include "../../config/koneksi.php";

if (isset($_POST['update'])) {
    $id_aset    = $_POST['id_aset'];
    $nama_aset  = mysqli_real_escape_string($conn, $_POST['nama_aset']);
    $kategori   = $_POST['kategori'];
    $stok       = $_POST['stok'];
    $harga_sewa = $_POST['harga_sewa'];

    $query = "UPDATE t_aset SET 
                nama_aset = '$nama_aset', 
                kategori = '$kategori', 
                stok = '$stok', 
                harga_sewa = '$harga_sewa' 
              WHERE id_aset = '$id_aset'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data Aset Berhasil Diperbarui!');
                window.location='inventaris.php';
              </script>";
    } else {
        echo "Error Update: " . mysqli_error($conn);
    }
} else {
    header("location:inventaris.php");
}
?>