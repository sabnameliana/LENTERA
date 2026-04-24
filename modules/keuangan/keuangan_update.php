<?php
include "../../config/koneksi.php";

if (isset($_POST['update'])) {
    // 1. Ambil data dari form keuangan_edit.php
    $id_bayar  = $_POST['id_bayar']; // ID primary key dari tabel t_pembayaran_siswa
    $id_siswa  = mysqli_real_escape_string($conn, $_POST['id_siswa']);
    $bulan     = mysqli_real_escape_string($conn, $_POST['bulan']);
    $jumlah    = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tgl_bayar = $_POST['tgl_bayar'];
    $status    = $_POST['status'];

    // 2. Jalankan query UPDATE pada tabel t_pembayaran_siswa
    $query_update = "UPDATE t_pembayaran_siswa SET 
                        id_siswa  = '$id_siswa', 
                        bulan     = '$bulan', 
                        jumlah    = '$jumlah', 
                        tgl_bayar = '$tgl_bayar', 
                        status    = '$status' 
                    WHERE id_bayar = '$id_bayar'";

    // 3. Eksekusi query dan cek hasilnya
    if (mysqli_query($conn, $query_update)) {
        // Jika berhasil, tampilkan alert dan redirect ke halaman utama keuangan
        echo "<script>
                alert('Data Pembayaran Berhasil Diperbarui!'); 
                window.location='keuangan.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error database
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
} else {
    // Jika mencoba akses langsung tanpa klik tombol update
    header("location:keuangan.php");
}
?>