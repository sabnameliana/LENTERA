<?php
include "../../config/koneksi.php";

// Ambil ID dari URL
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM t_siswa WHERE id_siswa = '$id'");
$d = mysqli_fetch_array($query);
?>

<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<div class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Data Siswa</h3>
            <p>Perbarui informasi siswa di bawah ini</p>
        </div>
        
        <form action="siswa_update.php" method="POST">
            <input type="hidden" name="id_siswa" value="<?php echo $d['id_siswa']; ?>">

            <h4 class="section-title">Biodata Siswa</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap Siswa</label>
                    <input type="text" name="nama_siswa" value="<?php echo $d['nama_siswa']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" name="alamat" value="<?php echo $d['alamat']; ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" name="no_hp" value="<?php echo $d['no_hp']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Daftar</label>
                    <input type="date" name="tgl_daftar" value="<?php echo $d['tgl_daftar']; ?>">
                </div>
            </div>

            <h4 class="section-title">Informasi Kelas</h4>
            <div class="form-row">
                <div class="form-group flex-2">
                    <label>Pilih Kelas</label>
                    <select name="id_kelas" id="id_kelas" onchange="isiTingkat()" required>
                        <?php
                        $kelas = mysqli_query($conn, "SELECT * FROM t_kelas");
                        while($k = mysqli_fetch_assoc($kelas)) {
                            $selected = ($k['id_kelas'] == $d['id_kelas']) ? "selected" : "";
                            echo "<option value='$k[id_kelas]' $selected>$k[nama_kelas]</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tingkat</label>
                    <input type="text" id="tingkat" readonly style="background: #f4f4f4;">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="update" class="btn-simpan">SIMPAN PERUBAHAN</button>
                <a href="siswa.php" class="btn-batal">BATAL</a>
            </div>
        </form>
    </div>
</div>

<script>
// Panggil fungsi sekali saat halaman load agar tingkat awal muncul
window.onload = function() { isiTingkat(); };

function isiTingkat() {
    var id_kelas = document.getElementById("id_kelas").value;
    var tingkatInput = document.getElementById("tingkat");
    if (id_kelas == "") return;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_tingkat.php?id_kelas=" + id_kelas, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            tingkatInput.value = xhr.responseText.trim();
        }
    };
    xhr.send();
}
</script>