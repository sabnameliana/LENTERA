<?php
include "../../config/koneksi.php";
include "../../config/fungsi.php";

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM t_aset WHERE id_aset = '$id'");
$d = mysqli_fetch_array($query);
?>

<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<div class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Data Aset</h3>
            <p>Perbarui informasi inventaris sanggar di bawah ini</p>
        </div>

        <form action="inventaris_update.php" method="POST">
            <input type="hidden" name="id_aset" value="<?php echo $d['id_aset']; ?>">

            <h4 class="section-title">Informasi Barang</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Aset / Barang</label>
                    <input type="text" name="nama_aset" value="<?php echo $d['nama_aset']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="Paket" <?php if($d['kategori'] == 'Paket') echo 'selected'; ?>>Paket</option>
                        <option value="Satuan" <?php if($d['kategori'] == 'Satuan') echo 'selected'; ?>>Satuan</option>                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Stok</label>
                    <input type="number" name="stok" value="<?php echo $d['stok']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Harga Sewa ( Rp )</label>
                    <input type="number" name="harga_sewa" value="<?php echo $d['harga_sewa']; ?>" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="update" class="btn-simpan">SIMPAN PERUBAHAN</button>
                <a href="inventaris.php" class="btn-batal">BATAL</a>
            </div>
        </form>
    </div>
</div>