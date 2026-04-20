<?php
include "../../config/koneksi.php";

if (isset($_POST['update'])) {
    $id_sewa = $_POST['id_sewa'];
    $nama_penyewa = $_POST['nama_penyewa'];
    $tgl_sewa = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $total_bayar = $_POST['total_bayar'];
    $status = $_POST['status'];

    $query_lama = mysqli_query($conn, "SELECT * FROM t_detail_sewa WHERE id_sewa = '$id_sewa'");
    while ($lama = mysqli_fetch_array($query_lama)) {
        mysqli_query($conn, "UPDATE t_aset SET stok = stok + {$lama['qty']} WHERE id_aset = '{$lama['id_aset']}'");
    }

    mysqli_query($conn, "DELETE FROM t_detail_sewa WHERE id_sewa = '$id_sewa'");

    mysqli_query($conn, "UPDATE t_sewa SET 
        nama_penyewa = '$nama_penyewa', 
        tgl_sewa = '$tgl_sewa', 
        tgl_kembali = '$tgl_kembali', 
        total_bayar = '$total_bayar', 
        status = '$status' 
        WHERE id_sewa = '$id_sewa'");

    $id_aset_array = $_POST['id_aset'];
    $qty_array = $_POST['qty'];

    foreach ($id_aset_array as $key => $id_aset) {
        if (!empty($id_aset)) {
            $qty = $qty_array[$key];
            mysqli_query($conn, "INSERT INTO t_detail_sewa (id_sewa, id_aset, qty) VALUES ('$id_sewa', '$id_aset', '$qty')");
            mysqli_query($conn, "UPDATE t_aset SET stok = stok - $qty WHERE id_aset = '$id_aset'");
        }
    }

    echo "<script>alert('Data Sewa Berhasil Diperbarui!'); window.location='sewa.php';</script>";
}
?>