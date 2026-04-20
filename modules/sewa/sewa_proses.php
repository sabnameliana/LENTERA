<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    $nama_penyewa = mysqli_real_escape_string($conn, $_POST['nama_penyewa']);
    $tgl_sewa     = $_POST['tgl_sewa'];
    $tgl_kembali  = $_POST['tgl_kembali'];
    $total_bayar = $_POST['total_bayar'];
    $status       = $_POST['status'];

    $id_aset_array = $_POST['id_aset'];
    $qty_array     = $_POST['qty'];

    $query_sewa = "INSERT INTO t_sewa (tgl_sewa, tgl_kembali, nama_penyewa, total_bayar, status) 
                   VALUES ('$tgl_sewa', '$tgl_kembali', '$nama_penyewa', '$total_bayar', '$status')";

    if (mysqli_query($conn, $query_sewa)) {
        $id_sewa_baru = mysqli_insert_id($conn);

        foreach ($id_aset_array as $key => $id_aset) {
            if (!empty($id_aset)) {
                $qty = $qty_array[$key];

                $query_detail = "INSERT INTO t_detail_sewa (id_sewa, id_aset, qty) 
                                 VALUES ('$id_sewa_baru', '$id_aset', '$qty')";
                mysqli_query($conn, $query_detail);

                $query_stok = "UPDATE t_aset SET stok = stok - $qty WHERE id_aset = '$id_aset'";
                mysqli_query($conn, $query_stok);
            }
        }

        echo "<script>
                alert('Berhasil! Data tersimpan. Stok juga berkurang.');
                window.location='sewa.php';
              </script>";
    } else {
        echo "Error t_sewa: " . mysqli_error($conn);
    }
}
?>