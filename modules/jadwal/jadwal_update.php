<?php
// Wajib tambah session_start() di paling atas untuk tahu role yang login
session_start(); 
include "../../config/koneksi.php";

if (isset($_POST['update'])) {
    $id_jadwal  = $_POST['id_jadwal'];
    $hari       = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam        = $_POST['jam'];
    $id_kelas   = $_POST['id_kelas'];
    $id_pelatih = $_POST['id_pelatih'];

    $query = "UPDATE t_jadwal SET 
                id_kelas   = '$id_kelas', 
                id_pelatih = '$id_pelatih', 
                hari       = '$hari', 
                jam        = '$jam' 
              WHERE id_jadwal = '$id_jadwal'";

    if (mysqli_query($conn, $query)) {
        
        // --- INI DIUBAH PAKE IF ---
        if ($_SESSION['role'] == 'guru') {
            // Jika guru, arahkan balik ke dashboard guru
            echo "<script>
                    alert('Jadwal Latihan Berhasil Diperbarui!');
                    window.location='../../dashboard_guru.php';
                  </script>";
        } else {
            // Jika admin, arahkan balik ke data tabel jadwal
            echo "<script>
                    alert('Jadwal Latihan Berhasil Diperbarui!');
                    window.location='jadwal.php';
                  </script>";
        }
        
    } else {
        echo "Error Update Jadwal: " . mysqli_error($conn);
    }
} else {
    header("location:jadwal.php");
}
?>