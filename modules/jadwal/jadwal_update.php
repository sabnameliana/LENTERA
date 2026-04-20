<?php
include "../../config/koneksi.php";

if (isset($_POST['update'])) {
    // Ambil data dari form jadwal_edit.php
    $id_jadwal  = $_POST['id_jadwal'];
    $hari       = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam        = $_POST['jam'];
    $id_kelas   = $_POST['id_kelas'];
    $id_pelatih = $_POST['id_pelatih'];

    // Query Update untuk tabel t_jadwal sesuai database db_lentera
    $query = "UPDATE t_jadwal SET 
                id_kelas   = '$id_kelas', 
                id_pelatih = '$id_pelatih', 
                hari       = '$hari', 
                jam        = '$jam' 
              WHERE id_jadwal = '$id_jadwal'";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, munculkan alert dan kembali ke halaman jadwal
        echo "<script>
                alert('Jadwal Latihan Berhasil Diperbarui!');
                window.location='jadwal.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error Update Jadwal: " . mysqli_error($conn);
    }
} else {
    // Jika diakses tanpa menekan tombol simpan, arahkan balik ke jadwal
    header("location:jadwal.php");
}
?>