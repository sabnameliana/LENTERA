<?php
include "../../config/koneksi.php";
include "../../config/fungsi.php";
?>

<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<div class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Tambah Jadwal Baru</h3>
            <p>Silahkan tentukan waktu latihan dan pengajar untuk sesi baru</p>
        </div>

        <form action="jadwal_proses.php" method="POST">
            <h4 class="section-title">Waktu Latihan</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Hari Latihan</label>
                    <select name="hari" required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam" required>
                </div>
            </div>

            <h4 class="section-title">Informasi Kelas & Pelatih</h4>
            <div class="form-row">
                <div class="form-group flex-2">
                    <label>Pilih Kelas</label>
                    <select name="id_kelas" id="id_kelas" onchange="isiTingkat()" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        $kelas = mysqli_query($conn, "SELECT * FROM t_kelas");
                        while ($k = mysqli_fetch_assoc($kelas)) {
                            echo "<option value='$k[id_kelas]'>$k[nama_kelas]</option>";
                        }
                        ?>
                    </select>
                </div>
                <script>
                    function isiTingkat() {
                        var id_kelas = document.getElementById("id_kelas").value;
                        var tingkatInput = document.getElementById("tingkat");

                        if (id_kelas == "") {
                            tingkatInput.value = "";
                            return;
                        }

                        var xhr = new XMLHttpRequest();
                        xhr.open("GET", "get_tingkat.php?id_kelas=" + id_kelas, true);

                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                var hasil = xhr.responseText.trim();

                                if (hasil != "") {
                                    tingkatInput.value = hasil;
                                } else {
                                    tingkatInput.value = "Tingkat tidak ditemukan";
                                }
                            }
                        };
                        xhr.send();
                    }
                </script>

                <div class="form-group">
                    <label>Tingkat</label>
                    <input type="text" id="tingkat" name="tingkat" placeholder="Otomatis..." readonly style="background: #f4f4f4;">
                </div>
            </div>
            

            <div class="form-row">
                <div class="form-group">
                    <label>Pilih Pelatih / Instruktur</label>
                    <select name="id_pelatih" required>
                        <option value="">-- Pilih Pelatih --</option>
                        <?php
                        $pelatih = mysqli_query($conn, "SELECT * FROM t_pelatih");
                        while ($p = mysqli_fetch_assoc($pelatih)) {
                            echo "<option value='$p[id_pelatih]'>$p[nama_pelatih]</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="simpan" class="btn-simpan">SIMPAN JADWAL</button>
                <a href="jadwal.php" class="btn-batal">BATAL</a>
            </div>
        </form>
    </div>
</div>

<script>
function isiTingkatJadwal() {
    var id_kelas = document.getElementById("id_kelas").value;
    var tingkatInput = document.getElementById("tingkat_jadwal");

    if (id_kelas == "") {
        tingkatInput.value = "";
        return;
    }

    var xhr = new XMLHttpRequest();
    // Menggunakan get_tingkat.php yang sudah kita buat sebelumnya
    xhr.open("GET", "get_tingkat.php?id_kelas=" + id_kelas, true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var hasil = xhr.responseText.trim();
            tingkatInput.value = (hasil != "") ? hasil : "Tidak ditemukan";
        }
    };
    xhr.send();
}
</script>