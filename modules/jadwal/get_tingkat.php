<?php
include "../../config/koneksi.php";

if (isset($_GET['id_kelas'])) {
    $id_kelas = $_GET['id_kelas'];
    
    $query = mysqli_query($conn, "SELECT tingkat FROM t_kelas WHERE id_kelas = '$id_kelas'");
    $data = mysqli_fetch_assoc($query);
    
    if ($data) {
        echo $data['tingkat']; 
    } else {
        echo "Tingkat tidak ditemukan";
    }
}
?>