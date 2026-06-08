<?php
include "../../config/koneksi.php";

// =========================================================================
// ACTION 1: PROSES TAMBAH SEWA BARU (Kodingan Asli Kamu + Perbaikan no_hp)
// =========================================================================
if (isset($_POST['simpan'])) {
    $nama_penyewa = mysqli_real_escape_string($conn, $_POST['nama_penyewa']);
    $tgl_sewa     = $_POST['tgl_sewa'];
    $tgl_kembali  = $_POST['tgl_kembali'];
    $total_bayar  = $_POST['total_bayar'];
    $status       = $_POST['status'];
    $no_hp        = $_POST['no_hp']; // Sudah ada di post kamu

    $id_aset_array = $_POST['id_aset'];
    $qty_array     = $_POST['qty'];

    // Perbaikan: menambahkan kolom nomor_hp ke dalam query INSERT sesuai database kamu
    $query_sewa = "INSERT INTO t_sewa (tgl_sewa, tgl_kembali, nama_penyewa, total_bayar, status, nomor_hp) 
                   VALUES ('$tgl_sewa', '$tgl_kembali', '$nama_penyewa', '$total_bayar', '$status', '$no_hp')";

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

// =========================================================================
// ACTION 2: PROSES PENGEMBALIAN SEWA + HITUNG DENDA OTOMATIS (Rp 5.000 / Hari)
// =========================================================================
else if (isset($_POST['proses_kembali'])) {
    $id_sewa = $_POST['id_sewa'];
    $tgl_kembali_asli = date('Y-m-d');
    
    $query_sewa = mysqli_query($conn, "SELECT tgl_kembali, total_bayar FROM t_sewa WHERE id_sewa = '$id_sewa'");
    $data_sewa  = mysqli_fetch_assoc($query_sewa);
    
    $tgl_harus_kembali = $data_sewa['tgl_kembali']; 
    $total_bayar_awal  = $data_sewa['total_bayar']; 
    
    $tarif_denda = 5000; 
    $total_denda = 0;
    $jumlah_hari_telat = 0;

    if (strtotime($tgl_kembali_asli) > strtotime($tgl_harus_kembali)) {
        $tanggal1 = new DateTime($tgl_harus_kembali);
        $tanggal2 = new DateTime($tgl_kembali_asli);
        
        $selisih = $tanggal1->diff($tanggal2);
        $jumlah_hari_telat = $selisih->days;
        
        $total_denda = $jumlah_hari_telat * $tarif_denda;
    }

    $total_bayar_baru = $total_bayar_awal + $total_denda;

    $update = mysqli_query($conn, "UPDATE t_sewa SET 
        total_bayar = '$total_bayar_baru',
        status      = 'Kembali'
        WHERE id_sewa = '$id_sewa'");

    if ($update) {
        $query_detail = mysqli_query($conn, "SELECT id_aset, qty FROM t_detail_sewa WHERE id_sewa = '$id_sewa'");
        while ($detail = mysqli_fetch_assoc($query_detail)) {
            $id_aset  = $detail['id_aset'];
            $qty_sewa = $detail['qty'];
            mysqli_query($conn, "UPDATE t_aset SET stok = stok + $qty_sewa WHERE id_aset = '$id_aset'");
        }

        if ($total_denda > 0) {
            echo "<script>
                    alert('Pengembalian Berhasil! Terlambat $jumlah_hari_telat hari. Denda Rp " . number_format($total_denda, 0, ',', '.') . " (Rp 5.000/hari) otomatis ditambahkan ke Total Bayar. Stok aset telah dikembalikan.');
                    window.location='sewa.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Pengembalian Berhasil! Tepat waktu, status diperbarui dan stok aset telah dikembalikan.');
                    window.location='sewa.php';
                  </script>";
        }
    } else {
        echo "Error saat update pengembalian: " . mysqli_error($conn);
    }
}
?>