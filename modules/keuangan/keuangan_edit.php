<?php
session_start();
include "../../config/koneksi.php";

$id = $_GET['id'];
$query_bayar = mysqli_query($conn, "SELECT * FROM t_pembayaran_siswa WHERE id_bayar = '$id'");
$data = mysqli_fetch_assoc($query_bayar);

$siswa_result = mysqli_query($conn, "SELECT * FROM t_siswa");
$list_siswa = [];
while($row = mysqli_fetch_assoc($siswa_result)) {
    $list_siswa[] = $row;
}
?>

<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<div class="modal-overlay">
    <div class="modal-box" style="max-width: 850px;">
        <div class="modal-header">
            <h3>Edit Pembayaran Siswa #<?php echo str_pad($id, 3, '0', STR_PAD_LEFT); ?></h3>
        </div>

        <form action="keuangan_update.php" method="POST">
            <input type="hidden" name="id_bayar" value="<?php echo $id; ?>">
            
            <h4 class="section-title">Informasi Pembayaran</h4>
            
            <div class="form-row">
                <div class="form-group">
                    <label>ID Siswa</label>
                    <select name="id_siswa" required style="width: 100%; border: 1px solid #ccc; border-radius: 10px; padding: 12px;">
                        <?php foreach($list_siswa as $s) { ?>
                            <option value="<?php echo $s['id_siswa']; ?>" <?php echo ($s['id_siswa'] == $data['id_siswa']) ? 'selected' : ''; ?>>
                                <?php echo $s['id_siswa']; ?> - <?php echo $s['nama_siswa']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Bayar</label>
                    <input type="date" name="tgl_bayar" value="<?php echo $data['tgl_bayar']; ?>" required style="border-radius: 10px; padding: 12px;">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Bulan</label>
                    <input type="text" name="bulan" value="<?php echo $data['bulan']; ?>" required style="border-radius: 10px; padding: 12px;">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required style="width: 100%; border: 1px solid #ccc; border-radius: 10px; padding: 12px;">
                        <option value="Lunas" <?php echo ($data['status'] == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
                        <option value="Belum Lunas" <?php echo ($data['status'] == 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Total Pembayaran</label>
                    <input type="number" name="jumlah" value="<?php echo $data['jumlah']; ?>" required style="border-radius: 10px; padding: 12px;">
                </div>
                <div class="form-group"></div> </div>

            <div class="modal-footer" style="margin-top: 25px; display: flex; justify-content: flex-end; gap: 15px;">
                <button type="submit" name="update" class="btn-simpan" style="background-color: #C3E9C1; color: black; border: none; padding: 12px 40px; border-radius: 10px; font-weight: bold; cursor: pointer;">SIMPAN</button>
                <a href="keuangan.php" class="btn-batal" style="background-color: #E0E0E0; color: black; text-decoration: none; padding: 12px 40px; border-radius: 10px; font-weight: bold;">BATAL</a>
            </div>
        </form>
    </div>
</div>