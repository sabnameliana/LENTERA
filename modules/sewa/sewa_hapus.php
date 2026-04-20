<?php
include "../../config/koneksi.php";

if (isset($_GET['id'])) {
    $id_sewa = $_GET['id'];

    $cek_sewa = mysqli_query($conn, "SELECT status FROM t_sewa WHERE id_sewa = '$id_sewa'");
    $data = mysqli_fetch_assoc($cek_sewa);

    if ($data['status'] == 'Belum Kembali') {
        $query_detail = mysqli_query($conn, "SELECT * FROM t_detail_sewa WHERE id_sewa = '$id_sewa'");
        
        while ($detail = mysqli_fetch_array($query_detail)) {
            $id_aset = $detail['id_aset'];
            $qty     = $detail['qty'];

            mysqli_query($conn, "UPDATE t_aset SET stok = stok + $qty WHERE id_aset = '$id_aset'");
        }
    }

    mysqli_query($conn, "DELETE FROM t_detail_sewa WHERE id_sewa = '$id_sewa'");

    $hapus = mysqli_query($conn, "DELETE FROM t_sewa WHERE id_sewa = '$id_sewa'");

    if ($hapus) {
        echo "<script>
                alert('Data sewa berhasil dihapus dan stok telah disesuaikan!');
                window.location='sewa.php';
              </script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    header("location:sewa.php");
}
?>