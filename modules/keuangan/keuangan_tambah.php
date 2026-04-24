<?php
session_start();
include "../../config/koneksi.php";

// Ambil data siswa untuk pilihan dropdown
$siswa_result = mysqli_query($conn, "SELECT id_siswa, nama_siswa FROM t_siswa ORDER BY nama_siswa ASC");
$list_siswa = [];
while ($row = mysqli_fetch_assoc($siswa_result)) {
    $list_siswa[] = $row;
}

// Daftar bulan untuk pilihan iuran
$daftar_bulan = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];
?>

<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<div class="modal-overlay">
    <div class="modal-box" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Tambah Pembayaran Siswa</h3>
            <p>Silahkan isi formulir di bawah ini untuk menambah catatan pembayaran baru</p>
        </div>

        <form action="keuangan_proses.php" method="POST">
            <h4 class="section-title">Informasi Pembayaran</h4>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Siswa</label>
                    <select name="id_siswa" required style="width: 100%; border-radius: 5px; border: 1px solid #ddd; padding: 8px;">
                        <option value="">-- Pilih Siswa --</option>
                        <?php foreach ($list_siswa as $siswa) : ?>
                            <option value="<?= $siswa['id_siswa']; ?>"><?= $siswa['nama_siswa']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jumlah Bayar (Rp)</label>
                    <input type="number" name="jumlah" placeholder="Contoh: 50000" required style="width: 100%; border-radius: 5px; border: 1px solid #ddd; padding: 8px;">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Untuk Bulan</label>
                    <select name="bulan" required style="width: 100%; border-radius: 5px; border: 1px solid #ddd; padding: 8px;">
                        <?php 
                        $bulan_ini = date('n') - 1; // Index bulan saat ini
                        foreach ($daftar_bulan as $key => $nama_bulan) : 
                        ?>
                            <option value="<?= $nama_bulan; ?>" <?= ($key == $bulan_ini) ? 'selected' : ''; ?>>
                                <?= $nama_bulan; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Bayar</label>
                    <input type="date" name="tgl_bayar" value="<?= date('Y-m-d'); ?>" required style="width: 100%; border-radius: 5px; border: 1px solid #ddd; padding: 8px;">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="width: 50%;">
                    <label>Status</label>
                    <select name="status" required style="width: 100%; border-radius: 5px; border: 1px solid #ddd; padding: 8px;">
                        <option value="Lunas">Lunas</option>
                        <option value="Belum Lunas">Belum Lunas</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer" style="margin-top: 30px;">
                <button type="submit" name="simpan" class="btn-simpan" style="background-color: #b2dfb2; color: #2d5a2d; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                    SIMPAN
                </button>
                <a href="keuangan.php" class="btn-batal" style="background-color: #d1d1d1; color: #333; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 14px;">
                    BATAL
                </a>
            </div>
        </form>
    </div>
</div>