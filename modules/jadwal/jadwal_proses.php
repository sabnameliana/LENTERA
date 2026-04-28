<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    $hari       = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam        = $_POST['jam'];
    $id_kelas   = $_POST['id_kelas'];
    $id_pelatih = $_POST['id_pelatih'];

    $query = "INSERT INTO t_jadwal (id_kelas, id_pelatih, hari, jam) 
              VALUES ('$id_kelas', '$id_pelatih', '$hari', '$jam')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Jadwal Latihan Baru Berhasil Ditambahkan!');
                window.location='jadwal.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("location:jadwal.php");
}
?>