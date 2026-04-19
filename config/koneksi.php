<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_lentera";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>