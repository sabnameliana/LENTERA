<?php
include "../../config/koneksi.php";

if (isset($_POST['update'])) {
    $id_siswa   = $_POST['id_siswa'];
    $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
    $alamat     = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp      = $_POST['no_hp'];
    $tgl_daftar = $_POST['tgl_daftar'];
    $id_kelas   = $_POST['id_kelas'];

    $query = "UPDATE t_siswa SET 
                id_kelas = '$id_kelas', 
                nama_siswa = '$nama_siswa', 
                alamat = '$alamat', 
                no_hp = '$no_hp', 
                tgl_daftar = '$tgl_daftar' 
              WHERE id_siswa = '$id_siswa'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data Siswa Berhasil Diperbarui!');
                window.location='siswa.php';
              </script>";
    } else {
        echo "Error Update: " . mysqli_error($conn);
    }
} else {
    header("location:siswa.php");
}
?>