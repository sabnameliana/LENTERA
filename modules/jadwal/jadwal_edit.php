<?php
include "../../config/koneksi.php";

// Ambil ID dari URL
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM t_jadwal WHERE id_jadwal = '$id'");
$d = mysqli_fetch_array($query);
?>

<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<div class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Jadwal Latihan</h3>
            <p>Perbarui waktu dan instruktur latihan di bawah ini</p>
        </div>
        
        <form action="jadwal_update.php" method="POST">
            <input type="hidden" name="id_jadwal" value="<?php echo $d['id_jadwal']; ?>">

            <h4 class="section-title">Waktu Latihan</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Hari Latihan</label>
                    <select name="hari" required>
                        <option value="Senin" <?php if($d['hari'] == 'Senin') echo 'selected'; ?>>Senin</option>
                        <option value="Selasa" <?php if($d['hari'] == 'Selasa') echo 'selected'; ?>>Selasa</option>
                        <option value="Rabu" <?php if($d['hari'] == 'Rabu') echo 'selected'; ?>>Rabu</option>
                        <option value="Kamis" <?php if($d['hari'] == 'Kamis') echo 'selected'; ?>>Kamis</option>
                        <option value="Jumat" <?php if($d['hari'] == 'Jumat') echo 'selected'; ?>>Jumat</option>
                        <option value="Sabtu" <?php if($d['hari'] == 'Sabtu') echo 'selected'; ?>>Sabtu</option>
                        <option value="Minggu" <?php if($d['hari'] == 'Minggu') echo 'selected'; ?>>Minggu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam" value="<?php echo $d['jam']; ?>" required>
                </div>
            </div>
            
            <h4 class="section-title">Detail Kelas & Pelatih</h4>
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

            <div class="form-row">
                <div class="form-group">
                    <label>Instruktur / Pelatih</label>
                    <select name="id_pelatih" required>
                        <?php
                        $pelatih = mysqli_query($conn, "SELECT * FROM t_pelatih");
                        while($p = mysqli_fetch_assoc($pelatih)) {
                            $selected = ($p['id_pelatih'] == $d['id_pelatih']) ? "selected" : "";
                            echo "<option value='$p[id_pelatih]' $selected>$p[nama_pelatih]</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="update" class="btn-simpan">SIMPAN PERUBAHAN</button>
                <a href="jadwal.php" class="btn-batal">BATAL</a>
            </div>
        </form>
    </div>
</div>

<script>
// Panggil fungsi saat load agar tingkat kelas muncul otomatis
window.onload = function() { isiTingkatJadwal(); };

function isiTingkatJadwal() {
    var id_kelas = document.getElementById("id_kelas").value;
    var tingkatInput = document.getElementById("tingkat");
    if (id_kelas == "") return;

    var xhr = new XMLHttpRequest();
    // Kamu bisa pakai file get_tingkat.php yang sama dengan milik siswa
    xhr.open("GET", "get_tingkat.php?id_kelas=" + id_kelas, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            tingkatInput.value = xhr.responseText.trim();
        }
    };
    xhr.send();
}
</script>