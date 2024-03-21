<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang Bengkel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Memasukkan jQuery melalui CDN -->

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
<div class="row">
    <div class="col-md-12">
        <?php
            // Tampilkan pesan berhasil jika ada parameter success
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>';
            }

            // Tampilkan pesan berhasil jika ada parameter update_success
            if (isset($_GET['update_success']) && $_GET['update_success'] == 1) {
                echo '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>';
            }

            // Tampilkan pesan berhasil jika ada parameter delete_success
            if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1) {
                echo '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>';
            }
        ?>
        <div class="row">
            <div class="col-md-3">
                <a href="tambah.php" class="btn btn-primary mb-3">Tambah Barang</a>
            </div>
        </div>
    </div>
</div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Gambar</th> <!-- Kolom untuk menampilkan gambar -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Koneksi ke database dan ambil data barang
                        include 'koneksi.php';
                        $sql = 'SELECT * FROM stok_barang';
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['id_barang'] . '</td>';
                                echo '<td>' . $row['nama_barang'] . '</td>';
                                echo '<td>' . $row['stok'] . '</td>';
                                echo '<td>Rp ' . number_format($row['harga'], 0, ',', '.') . '</td>';
                                // Tampilkan gambar dengan tag <img>
                                echo "<td><img src='" . $row['gambar'] . "' width='100'></td>";
                                echo "<td><a href='edit.php?id=" . $row['id_barang'] . "' class='btn btn-warning'>Edit</a> ";
                                echo "<a href='hapus.php?id=" . $row['id_barang'] . "' class='btn btn-danger'>Hapus</a></td>";
                                echo '</tr>';
                            }
                        } else {
                            echo "<tr><td colspan='6'>Belum ada data barang</td></tr>";
                        }
                        $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>


