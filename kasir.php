<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kasir Pembelian Sparepart Bengkel</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Kasir</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item">
                <a class="nav-link" href="index.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="data_barang.php">Stok Barang Bengkel</a>
            </li>            
            <li class="nav-item">
                <a class="nav-link" href="rekapan_transaksi.php">Rekapan Transaksi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="kasir.php">Kasir</a>
            </li>
        </ul>
    </div>
</nav>
<br>
    <h1 class="my-4">Kasir Pembelian Sparepart Bengkel</h1>

    <form action="proses_beli.php" method="POST">
        <div class="form-group">
            <label for="barang">Pilih Barang:</label>
            <select class="form-control" id="barang" name="barang">
                <?php
                    // Koneksi ke database
                    include 'koneksi.php';

                    // Query untuk mengambil daftar barang
                    $sql = 'SELECT id_barang, nama_barang, harga, stok FROM stok_barang WHERE stok > 0';
                    $result = $conn->query($sql);

                    // Tampilkan daftar barang dalam dropdown
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id_barang'] . "'>" . $row['nama_barang'] . ' (Rp ' . number_format($row['harga'], 0, ',', '.') . ')</option>';
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="jumlah">Jumlah Beli:</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambahkan ke Keranjang</button>
    </form>
    <br>
    <?php
        session_start();  // Mulai session

        // Tampilkan pesan jika ada
        if (isset($_SESSION['pesan'])) {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['pesan'] . '</div>';
            unset($_SESSION['pesan']);  // Hapus pesan setelah ditampilkan
        }
    ?>


    <hr>

    <!-- Tempat untuk menampilkan keranjang belanja dan total belanja -->
    <?php

        // Inisialisasi total belanja
        $total_belanja = 0;

        // Tampilkan keranjang belanja jika ada barang di dalamnya
        if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) {
            echo '<h2>Keranjang Belanja</h2>';
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Nama Barang</th>';
            echo '<th scope="col">Harga</th>';
            echo '<th scope="col">Jumlah</th>';
            echo '<th scope="col">Subtotal</th>';
            echo '<th scope="col">Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Loop untuk menampilkan detail barang di keranjang
            foreach ($_SESSION['keranjang'] as $key => $item) {
                echo '<tr>';
                echo '<td>' . $item['nama_barang'] . '</td>';
                echo '<td>Rp ' . number_format($item['harga'], 0, ',', '.') . '</td>';
                echo '<td>' . $item['jumlah'] . '</td>';
                echo '<td>Rp ' . number_format($item['subtotal'], 0, ',', '.') . '</td>';
                echo '<td><form method="post" action="hapus_item.php"><input type="hidden" name="item_index" value="' . $key . '"><button type="submit" class="btn btn-danger btn-sm">Hapus</button></form></td>';
            
                echo '</tr>';
        
                // Tambahkan subtotal ke total belanja
                $total_belanja += $item['subtotal'];
        
                // Tambahkan tombol hapus untuk setiap item
                }
        

            echo '</tbody>';
            echo '</table>';

            // Tampilkan total belanja
            echo '<h4>Total Belanja: Rp ' . number_format($total_belanja, 0, ',', '.') . '</h4>';
            $_SESSION['total_belanja'] = $total_belanja;
        } else {
            echo '<p>Keranjang belanja kosong.</p>';
        }
        // Tampilkan keranjang belanja dan total belanja di sini

        echo '<form action="proses_bayar.php" method="POST">';
        echo '<div class="form-group">';
        echo '<label for="jumlah_bayar">Jumlah Bayar:</label>';
        echo '<div class="input-group">';
        echo '<div class="input-group-prepend">';
        echo '<span class="input-group-text">Rp</span>';  // Tambahkan label Rp
        echo '</div>';
        echo '<input type="text" class="form-control" id="jumlah_bayar" name="jumlah_bayar" min="' . $total_belanja . '" required>';
        echo '<input type="hidden" id="jumlah_bayar_hidden" name="jumlah_bayar_hidden">';
        echo '</div>';
        echo '</div>';
        echo '<button type="submit" class="btn btn-success">Bayar</button>';
        echo '</form>';
    ?>

</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
// Skrip jQuery untuk format ribuan pada input "jumlah_bayar"
<script>
document.addEventListener('DOMContentLoaded', function() {
    var inputBayar = document.getElementById('jumlah_bayar');
    var inputBayarHidden = document.getElementById('jumlah_bayar_hidden');

    inputBayar.addEventListener('input', function(e) {
        var input = e.target.value;
        // Hilangkan titik dan koma dari input
        var cleanInput = input.replace(/[.,]/g, '');
        // Simpan nilai mentah di input hidden
        inputBayarHidden.value = cleanInput;
        // Format dan tampilkan nilai dalam input text
        var formattedHarga = formatRupiah(cleanInput);
        e.target.value = formattedHarga;
    });
});

// Fungsi untuk format angka menjadi Rupiah
function formatRupiah(angka) {
    var number_string = angka.toString();
    var split = number_string.split(',');
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return '' + rupiah;
}

</script>