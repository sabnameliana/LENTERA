<?php
include "../../config/koneksi.php";

if (isset($_GET['id'])) {
    $id_sewa = $_GET['id'];

    $query_detail = mysqli_query($conn, "SELECT * FROM t_detail_sewa WHERE id_sewa = '$id_sewa'");

    while ($detail = mysqli_fetch_array($query_detail)) {
        $id_aset = $detail['id_aset'];
        $qty     = $detail['qty'];

        mysqli_query($conn, "UPDATE t_aset SET stok = stok + $qty WHERE id_aset = '$id_aset'");
    }

    $update_status = mysqli_query($conn, "UPDATE t_sewa SET status = 'Kembali' WHERE id_sewa = '$id_sewa'");

    if ($update_status) {
        echo "<script>
                alert('Barang telah kembali dan stok sudah diperbarui!');
                window.location='sewa.php';
              </script>";
    }
}
?>