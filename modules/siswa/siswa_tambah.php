<?php
include "../../config/koneksi.php";
include "../../config/fungsi.php";
?>

<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<div class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Tambah Siswa Baru</h3>
            <p>Silahkan isi formulir di bawah ini untuk mendaftarkan siswa baru</p>
        </div>

        <form action="siswa_proses.php" method="POST">
            <h4 class="section-title">Biodata Siswa</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap Siswa</label>
                    <input type="text" name="nama_siswa" placeholder="Masukkan nama" required>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" name="alamat" placeholder="Masukkan alamat" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" name="no_hp" placeholder="Masukkan no.telp" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" required>
                </div>
            </div>

            <h4 class="section-title">Informasi Kelas</h4>
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
                <div class="form-group">
                    <label>Tanggal Daftar</label>
                    <input type="date" name="tgl_daftar" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <h4 class="section-title">Pembayaran Awal</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Bulan Pertama SPP</label>
                    <select name="bulan_spp">
                        <option>Januari</option>
                        <option>Februari</option>
                        <option>Maret</option>
                        <option>April</option>
                        <option>Mei</option>
                        <option>Juni</option>
                        <option>Juli</option>
                        <option>Agustus</option>
                        <option>September</option>
                        <option>Oktober</option>
                        <option>November</option>
                        <option>Desember</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jumlah Pembayaran Awal ( Rp )</label>
                    <input type="number" name="jumlah_bayar" placeholder="150.000">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="simpan" class="btn-simpan">SIMPAN</button>
                <a href="siswa.php" class="btn-batal">BATAL</a>
            </div>
        </form>
    </div>
</div>