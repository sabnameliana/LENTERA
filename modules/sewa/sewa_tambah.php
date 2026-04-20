<?php
session_start();
include "../../config/koneksi.php";

$aset_result = mysqli_query($conn, "SELECT * FROM t_aset WHERE stok > 0");
$list_aset = [];
while ($row = mysqli_fetch_assoc($aset_result)) {
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
            <h3>Tambah Transaksi Sewa</h3>
            <p>Silahkan isi formulir di bawah ini untuk menambah transaksi sewa baru</p>
        </div>

        <form action="sewa_proses.php" method="POST">
            <h4 class="section-title">Informasi Transaksi</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Penyewa</label>
                    <input type="text" name="nama_penyewa" placeholder="Nama Penyewa" required>
                </div>
                <div class="form-group">
                    <label>Total Bayar</label>
                    <input type="text" id="total_bayar_display" name="total_bayar_view" value="Rp. 0" readonly style="background: #f9f9f9;">

                    <input type="hidden" name="total_bayar" id="total_bayar_input" value="0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Sewa</label>
                    <input type="date" name="tgl_sewa" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Belum Kembali">Belum Kembali</option>
                        <option value="Kembali">Sudah Kembali</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali" required>
                </div>
            </div>

            <div class="table-container" style="margin-top: 20px; background: #eee; border-radius: 10px; padding: 10px;">
                <table style="width: 100%; border-collapse: collapse;" id="tabelAset">
                    <thead>
                        <tr style="text-align: left; font-size: 14px;">
                            <th style="padding: 10px;">Nama Aset</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="itemRows">
                    </tbody>
                </table>
                <button type="button" onclick="addRow()" style="margin-top: 10px; background: white; border: 1px solid #ccc; padding: 5px 15px; border-radius: 5px; cursor: pointer;">
                    + Tambah Aset
                </button>
            </div>

            <div style="text-align: right; margin-top: 15px; font-weight: bold;">
                Total: <span id="grandTotalLabel">Rp. 0</span>
            </div>

            <div class="modal-footer" style="margin-top: 20px;">
                <button type="submit" name="simpan" class="btn-simpan" style="background-color: #b2dfb2; color: #2d5a2d;">SIMPAN</button>
                <a href="sewa.php" class="btn-batal" style="background-color: #d1d1d1;">BATAL</a>
            </div>
        </form>
    </div>
</div>

<script>
    const dataAset = <?php echo json_encode($list_aset); ?>;

    function addRow() {
        const tbody = document.getElementById('itemRows');
        const tr = document.createElement('tr');
        tr.style.background = "white";
        tr.style.borderBottom = "2px solid #eee";

        let options = '<option value="">-- Pilih --</option>';
        dataAset.forEach(aset => {
            options += `<option value="${aset.id_aset}" data-harga="${aset.harga_sewa}">${aset.nama_aset}</option>`;
        });

        tr.innerHTML = `
        <td style="padding: 10px;">
            <select name="id_aset[]" class="select-aset" onchange="hitungSubtotal(this)" style="width: 100%; border: none; outline: none;">
                ${options}
            </select>
        </td>
        <td><input type="number" name="qty[]" value="1" min="1" oninput="hitungSubtotal(this)" style="width: 50px; border: 1px solid #ddd; border-radius: 5px; text-align: center;"></td>
        <td><span class="subtotal-label">Rp. 0</span><input type="hidden" class="subtotal-input" value="0"></td>
        <td><button type="button" onclick="this.parentElement.parentElement.remove(); updateGrandTotal();" style="border:none; background:none; cursor:pointer; color:red;"><i class="fa-solid fa-trash"></i></button></td>
    `;
        tbody.appendChild(tr);
    }

    function hitungSubtotal(element) {
        const row = element.closest('tr');
        const select = row.querySelector('.select-aset');
        const qty = row.querySelector('input[name="qty[]"]').value;
        const harga = select.options[select.selectedIndex].getAttribute('data-harga') || 0;

        const subtotal = harga * qty;
        row.querySelector('.subtotal-label').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(subtotal);
        row.querySelector('.subtotal-input').value = subtotal;

        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        const allSubtotals = document.querySelectorAll('.subtotal-input');

        allSubtotals.forEach(input => {
            total += parseInt(input.value) || 0;
        });

        document.getElementById('grandTotalLabel').innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(total);

        const displayTotal = document.getElementById('total_bayar_display');
        if (displayTotal) {
            displayTotal.value = 'Rp. ' + new Intl.NumberFormat('id-ID').format(total);
        }

        document.getElementById('total_bayar_input').value = total;
    }

    addRow();
</script>