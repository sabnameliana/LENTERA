<?php
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    // Ambil data dari form
    $hari       = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam        = $_POST['jam'];
    $id_kelas   = $_POST['id_kelas'];
    $id_pelatih = $_POST['id_pelatih'];

    // Query untuk memasukkan data ke tabel t_jadwal
    // Sesuai dengan struktur: id_jadwal (AI), id_kelas, id_pelatih, hari, jam
    $query = "INSERT INTO t_jadwal (id_kelas, id_pelatih, hari, jam) 
              VALUES ('$id_kelas', '$id_pelatih', '$hari', '$jam')";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, tampilkan pesan sukses dan kembali ke halaman utama jadwal
        echo "<script>
                alert('Jadwal Latihan Baru Berhasil Ditambahkan!');
                window.location='jadwal.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error database
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika mencoba akses langsung tanpa submit form, arahkan kembali
    header("location:jadwal.php");
}
?>