<?php
include "../../config/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Hapus dulu data di tabel pembayaran (karena ada relasi FK)
    mysqli_query($conn, "DELETE FROM t_pembayaran_siswa WHERE id_siswa = '$id'");

    // 2. Baru hapus data di tabel siswa
    $query_hapus = mysqli_query($conn, "DELETE FROM t_siswa WHERE id_siswa = '$id'");

    if ($query_hapus) {
        echo "<script>
                alert('Data Siswa Berhasil Dihapus!');
                window.location='siswa.php';
              </script>";
    } else {
        echo "Error Hapus: " . mysqli_error($conn);
    }
} else {
    header("location:siswa.php");
}
?>