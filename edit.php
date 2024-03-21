<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Barang</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body>
    <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Data Stok</a>
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
        <h1 class="mt-5">Edit Data Barang</h1>
        <?php
        include 'koneksi.php';

        // Ambil ID barang dari parameter URL
        $id_barang = $_GET['id'];

        // Query untuk mengambil data barang berdasarkan ID
        $sql = "SELECT * FROM stok_barang WHERE id_barang='$id_barang'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        ?>
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">
            <div class="form-group">
                <label for="nama_barang">Nama Barang:</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?php echo $row['nama_barang']; ?>" required>
            </div>
            <div class="form-group">
                <label for="stok">Stok:</label>
                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $row['stok']; ?>" required>
            </div>
            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?php echo 'Rp ' . number_format($row['harga'], 0, ',', '.'); ?>" required>
            </div>
            <div class="form-group">
                <img src="<?php echo $row['gambar']; ?>" alt="Gambar Barang" style="max-width: 200px;">
                <input type="file" class="form-control-file mt-2" id="gambar" name="gambar">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="data_barang.php" class="btn btn-warning ml-2">Kembali</a>
        </form>
        <?php
        } else {
            echo "Data tidak ditemukan";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
<script>
// Fungsi untuk menambahkan format mata uang Rupiah saat input harga diubah
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
    return 'Rp ' + rupiah;
}

// Event listener saat nilai input harga diubah
document.getElementById('harga').addEventListener('input', function(e) {
    var input = e.target.value;
    // Hapus karakter non-angka dari input
    var cleanInput = input.replace(/\D/g, '');
    // Ubah format harga menjadi Rupiah
    var formattedHarga = formatRupiah(cleanInput);
    // Update nilai input dengan format Rupiah
    e.target.value = formattedHarga;
});
</script>
