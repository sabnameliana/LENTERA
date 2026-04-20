<?php
session_start();
include "../../config/koneksi.php";

$id = $_GET['id'];
$query_sewa = mysqli_query($conn, "SELECT * FROM t_sewa WHERE id_sewa = '$id'");
$data = mysqli_fetch_assoc($query_sewa);

$query_detail = mysqli_query($conn, "SELECT * FROM t_detail_sewa WHERE id_sewa = '$id'");

$aset_result = mysqli_query($conn, "SELECT * FROM t_aset");
$list_aset = [];
while($row = mysqli_fetch_assoc($aset_result)) {
    $list_aset[] = $row;
}
?>

<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<div class="modal-overlay">
    <div class="modal-box" style="max-width: 850px;">
        <div class="modal-header">
            <h3>Edit Transaksi Sewa #<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?></h3>
        </div>

        <form action="sewa_update.php" method="POST">
            <input type="hidden" name="id_sewa" value="<?php echo $id; ?>">
            
            <h4 class="section-title">Informasi Transaksi</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Penyewa</label>
                    <input type="text" name="nama_penyewa" value="<?php echo $data['nama_penyewa']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Total Bayar</label>
                    <input type="text" id="total_bayar_display" value="Rp. <?php echo number_format($data['total_bayar'], 0, ',', '.'); ?>" readonly style="background: #f9f9f9;">
                    <input type="hidden" name="total_bayar" id="total_bayar_input" value="<?php echo $data['total_bayar']; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Sewa</label>
                    <input type="date" name="tgl_sewa" value="<?php echo $data['tgl_sewa']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Belum Kembali" <?php echo ($data['status'] == 'Belum Kembali') ? 'selected' : ''; ?>>Belum Kembali</option>
                        <option value="Kembali" <?php echo ($data['status'] == 'Kembali') ? 'selected' : ''; ?>>Sudah Kembali</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="width: 50%;">
                    <label>Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali" value="<?php echo $data['tgl_kembali']; ?>" required>
                </div>
            </div>

            <div class="table-container" style="margin-top: 20px; background: #eee; border-radius: 10px; padding: 10px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; font-size: 14px;">
                            <th style="padding: 10px;">Nama Aset</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itemRows">
                        <?php while($d = mysqli_fetch_assoc($query_detail)) { ?>
                        <tr style="background: white; border-bottom: 2px solid #eee;">
                            <td style="padding: 10px;">
                                <select name="id_aset[]" class="select-aset" onchange="hitungSubtotal(this)" style="width: 100%; border: none;">
                                    <?php foreach($list_aset as $a) { ?>
                                        <option value="<?php echo $a['id_aset']; ?>" data-harga="<?php echo $a['harga_sewa']; ?>" <?php echo ($a['id_aset'] == $d['id_aset']) ? 'selected' : ''; ?>>
                                            <?php echo $a['nama_aset']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><input type="number" name="qty[]" value="<?php echo $d['qty']; ?>" min="1" oninput="hitungSubtotal(this)" style="width: 50px; text-align: center;"></td>
                            <td>
                                <span class="subtotal-label">Rp. <?php echo number_format($d['qty'] * 50000, 0, ',', '.'); // Contoh statis, nanti dihitung JS ?></span>
                                <input type="hidden" class="subtotal-input" value="0">
                            </td>
                            <td><button type="button" onclick="this.parentElement.parentElement.remove(); updateGrandTotal();" style="color:red; border:none; background:none;"><i class="fa-solid fa-trash"></i></button></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button type="button" onclick="addRow()" style="margin-top: 10px;">+ Tambah Aset</button>
            </div>

            <div class="modal-footer" style="margin-top: 20px;">
                <button type="submit" name="update" class="btn-simpan">UPDATE</button>
                <a href="sewa.php" class="btn-batal">BATAL</a>
            </div>
        </form>
    </div>
</div>

<script>
</script>