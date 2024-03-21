<?php
include 'koneksi.php';

// Query untuk mengambil data transaksi (tanpa JOIN dengan detail_transaksi)
$sql = 'SELECT id_transaksi, total_belanja, jumlah_bayar, kembalian, tanggal_transaksi
        FROM transaksi
        ORDER BY tanggal_transaksi DESC';  // Sesuaikan dengan kebutuhan pengurutan data

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rekapan Transaksi</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Rekapan Transaksi</a>
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
    <h1 class="my-4">Rekapan Transaksi</h1>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Total Belanja</th>
                    <th>Jumlah Bayar</th>
                    <th>Kembalian</th>
                    <th>Tanggal Transaksi</th>
                    <th>Detail Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['id_transaksi'] . '</td>';
                            echo '<td>Rp ' . number_format($row['total_belanja'], 0, ',', '.') . '</td>';
                            echo '<td>Rp ' . number_format($row['jumlah_bayar'], 0, ',', '.') . '</td>';
                            echo '<td>Rp ' . number_format($row['kembalian'], 0, ',', '.') . '</td>';
                            echo '<td>' . $row['tanggal_transaksi'] . '</td>';
                            echo '<td>';
                            echo "<button class='btn btn-info btn-detail' data-id='" . $row['id_transaksi'] . "' data-toggle='modal' data-target='#detailModal'>Detail</button>";
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo "<tr><td colspan='6'>Tidak ada data transaksi</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Konten detail transaksi akan ditampilkan di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script>
    $(document).ready(function() {
        $('.btn-detail').click(function() {
            var id_transaksi = $(this).data('id');
            $.ajax({
                url: 'get_detail_transaksi.php',
                method: 'POST',
                data: { id_transaksi: id_transaksi },
                success: function(response) {
                    // Tampilkan detail transaksi di modal atau area khusus di halaman
                    $('#detailModalBody').html(response);
                    $('#detailModal').modal('show'); // Tampilkan modal jika menggunakan Bootstrap modal
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Tampilkan pesan error jika terjadi kesalahan
                }
            });
        });
    });
</script>
