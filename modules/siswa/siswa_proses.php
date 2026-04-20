<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
    $alamat     = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp      = $_POST['no_hp'];
    $tgl_daftar = $_POST['tgl_daftar'];
    $id_kelas   = $_POST['id_kelas'];
    
    $bulan  = $_POST['bulan_spp']; 

    $jumlah_angka = str_replace('.', '', $_POST['jumlah_bayar']);

    $query_siswa = "INSERT INTO t_siswa (id_kelas, nama_siswa, alamat, no_hp, tgl_daftar) 
                    VALUES ('$id_kelas', '$nama_siswa', '$alamat', '$no_hp', '$tgl_daftar')";

    if (mysqli_query($conn, $query_siswa)) {
        $id_siswa_baru = mysqli_insert_id($conn);

        $query_bayar = "INSERT INTO t_pembayaran_siswa (id_siswa, bulan, jumlah, tgl_bayar, status) 
                        VALUES ('$id_siswa_baru', '$bulan', '$jumlah_angka', '$tgl_daftar', 'Lunas')";
        
        mysqli_query($conn, $query_bayar);

        echo "<script>
                alert('Data Siswa dan Pembayaran Berhasil Disimpan!');
                window.location='siswa.php';
              </script>";
    } else {
        echo "Error Siswa: " . mysqli_error($conn);
    }
} else {
    header("location:siswa.php");
}
?>