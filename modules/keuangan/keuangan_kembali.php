<?php
include "../../config/koneksi.php";

if (isset($_GET['id'])) {
    $id_bayar = $_GET['id'];

    // Update status pembayaran menjadi 'Lunas' berdasarkan id_bayar
    $update_status = mysqli_query($conn, "UPDATE t_pembayaran_siswa SET status = 'Lunas' WHERE id_bayar = '$id_bayar'");

    if ($update_status) {
        echo "<script>
                alert('Status pembayaran telah diperbarui menjadi Lunas!');
                window.location='keuangan.php';
              </script>";
    } else {
        echo "Gagal memperbarui status: " . mysqli_error($conn);
    }
} else {
    header("location:keuangan.php");
}
?>