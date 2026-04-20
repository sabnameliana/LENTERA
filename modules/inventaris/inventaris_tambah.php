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
            <h3>Tambah Aset Baru</h3>
            <p>Silahkan isi formulir di bawah ini untuk menambah inventaris sanggar</p>
        </div>

        <form action="inventaris_proses.php" method="POST">
            <h4 class="section-title">Informasi Barang</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Aset</label>
                    <input type="text" name="nama_aset" placeholder="Masukkan nama aset baru" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Paket">Paket</option>
                        <option value="Satuan">Satuan</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="number" name="stok" placeholder="Masukkan jumlah stok" required>
                </div>
                <div class="form-group">
                    <label>Harga Sewa ( Rp )</label>
                    <input type="number" name="harga_sewa" placeholder="Contoh: 150000" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="simpan" class="btn-simpan">SIMPAN ASET</button>
                <a href="inventaris.php" class="btn-batal">BATAL</a>
            </div>
        </form>
    </div>
</div>