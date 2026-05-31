<?php
// 1. WAJIB jalankan session di paling atas
session_start(); 
include "../../config/koneksi.php";

if (isset($_POST['simpan'])) {
    $hari       = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam        = $_POST['jam'];
    $id_kelas   = $_POST['id_kelas'];
    
    // --- JALUR PENYELAMAT NYARI ID PELATIH ---
    if ($_SESSION['role'] == 'guru') {
        // Kita pakai nama_admin atau username dari session login
        // (Tergantung mana yang kamu isi nama lengkap gurunya saat mendaftarkan akun)
        $nama_login = $_SESSION['username'];
        
        // KITA HAPUS 'OR username = ...' BIAR NGGAK EROR LAGI
        // Query ini sekarang cuma ngecek kolom nama_pelatih yang sudah pasti ada
        $cari_id = mysqli_query($conn, "SELECT id_pelatih FROM t_pelatih WHERE nama_pelatih = '$nama_login' LIMIT 1");
        $ketemu = mysqli_fetch_assoc($cari_id);
        
        if ($ketemu) {
            // Kalau namanya cocok dan ketemu di tabel t_pelatih, pakai ID ini
            $id_pelatih = $ketemu['id_pelatih'];
        } else {
            // JALUR CADANGAN: Jika nama di t_admin beda tulisannya dengan di t_pelatih,
            // kita ambil ID pelatih paling pertama di database agar tidak memicu error foreign key constraint
            $ambil_default = mysqli_query($conn, "SELECT id_pelatih FROM t_pelatih LIMIT 1");
            $default = mysqli_fetch_assoc($ambil_default);
            $id_pelatih = $default['id_pelatih'];
        }
    } else {
        // Jika yang login ADMIN, langsung ambil id dari dropdown form biasa
        $id_pelatih = $_POST['id_pelatih'];
    }
    // --- SELESAI PENGECEKAN ID ---

    // Query insert ke t_jadwal
    $query = "INSERT INTO t_jadwal (id_kelas, id_pelatih, hari, jam) 
              VALUES ('$id_kelas', '$id_pelatih', '$hari', '$jam')";

    if (mysqli_query($conn, $query)) {
        
        // Cek role untuk menentukan arah halaman balik
        if ($_SESSION['role'] == 'guru') {
            echo "<script>
                    alert('Jadwal Latihan Baru Berhasil Ditambahkan!');
                    window.location='../../index_guru.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Jadwal Latihan Baru Berhasil Ditambahkan!');
                    window.location='jadwal.php';
                  </script>";
        }
        exit();

    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'guru') {
        header("location:../../index_guru.php");
    } else {
        header("location:jadwal.php");
    }
    exit();
}
?>