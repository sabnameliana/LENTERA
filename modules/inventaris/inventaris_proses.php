<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    // Ambil data dari form
    $nama_aset  = mysqli_real_escape_string($conn, $_POST['nama_aset']);
    $kategori   = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok       = $_POST['stok'];
    $harga_sewa = $_POST['harga_sewa'];

    // Query INSERT ke tabel t_aset sesuai struktur db_lentera
    $query = "INSERT INTO t_aset (nama_aset, kategori, stok, harga_sewa) 
              VALUES ('$nama_aset', '$kategori', '$stok', '$harga_sewa')";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, tampilkan alert dan kembali ke halaman inventaris
        echo "<script>
                alert('Data Aset Berhasil Ditambahkan!');
                window.location='inventaris.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika diakses tanpa submit form, arahkan balik
    header("location:inventaris.php");
}
?>