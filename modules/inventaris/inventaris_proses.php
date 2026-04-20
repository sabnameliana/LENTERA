<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {

    $nama_aset  = mysqli_real_escape_string($conn, $_POST['nama_aset']);
    $kategori   = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok       = $_POST['stok'];
    $harga_sewa = $_POST['harga_sewa'];

    $query = "INSERT INTO t_aset (nama_aset, kategori, stok, harga_sewa) 
              VALUES ('$nama_aset', '$kategori', '$stok', '$harga_sewa')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data Aset Berhasil Ditambahkan!');
                window.location='inventaris.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("location:inventaris.php");
}
?>