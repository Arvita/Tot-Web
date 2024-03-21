<?php
include 'koneksi.php';

// Tentukan rentang tanggal mulai dan selesai untuk bulan ini
$tanggal_sekarang = date('Y-m-d');
$tanggal_awal_bulan = date('Y-m-01');
$tanggal_akhir_bulan = date('Y-m-t', strtotime($tanggal_sekarang));

// Query untuk menghitung total pendapatan bulan ini
$sql_total_pendapatan = "SELECT SUM(total_belanja) AS total_pendapatan FROM transaksi WHERE tanggal_transaksi BETWEEN '$tanggal_awal_bulan' AND '$tanggal_akhir_bulan'";
$result_total_pendapatan = $conn->query($sql_total_pendapatan);

$total_pendapatan = 0;
if ($result_total_pendapatan->num_rows > 0) {
    $row_total_pendapatan = $result_total_pendapatan->fetch_assoc();
    $total_pendapatan = $row_total_pendapatan['total_pendapatan'];
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bengkel 88</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Dashboard Bengkel 88</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
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

        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Barang Keseluruhan</h5>
                        <?php
                            include 'koneksi.php';

                            // Query untuk menghitung jumlah barang keseluruhan
                            $sql_total = 'SELECT COUNT(*) AS total_barang FROM stok_barang';
                            $result_total = $conn->query($sql_total);

                            if ($result_total->num_rows > 0) {
                                $row_total = $result_total->fetch_assoc();
                                echo "<p class='card-text'>Total Barang: " . $row_total['total_barang'] . '</p>';
                            } else {
                                echo "<p class='card-text'>Belum ada data barang</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Barang dengan Stok Terendah</h5>
                        <?php
                            include 'koneksi.php';

                            // Query untuk mendapatkan jumlah stok terendah
                            $sql_min_stok = 'SELECT MIN(stok) AS min_stok FROM stok_barang';
                            $result_min_stok = $conn->query($sql_min_stok);

                            if ($result_min_stok->num_rows > 0) {
                                $row_min_stok = $result_min_stok->fetch_assoc();
                                $min_stok = $row_min_stok['min_stok'];

                                // Query untuk mendapatkan nama barang dengan stok terendah
                                $sql_barang_terendah = "SELECT nama_barang, stok FROM stok_barang WHERE stok='$min_stok'";
                                $result_barang_terendah = $conn->query($sql_barang_terendah);

                                if ($result_barang_terendah->num_rows > 0) {
                                    $row_barang_terendah = $result_barang_terendah->fetch_assoc();
                                    echo "<p class='card-text'>Nama Barang : " . $row_barang_terendah['nama_barang'] . '</p>';
                                    echo "<p class='card-text'>Jumlah Stok: " . $row_barang_terendah['stok'] . '</p>';
                                } else {
                                    echo "<p class='card-text'>Belum ada barang dengan stok terendah</p>";
                                }
                            } else {
                                echo "<p class='card-text'>Belum ada data barang</p>";
                            }

                            $conn->close();
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Pendapatan Bulan Ini</h5>
                        <p class="card-text">Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
