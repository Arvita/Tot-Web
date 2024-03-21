<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_transaksi'])) {
    $id_transaksi = $_POST['id_transaksi'];

    // Query untuk mengambil detail transaksi berdasarkan ID transaksi
    $sql = "SELECT dt.id_barang, dt.jumlah, dt.subtotal, sb.nama_barang, sb.harga
            FROM detail_transaksi dt
            INNER JOIN stok_barang sb ON dt.id_barang = sb.id_barang
            WHERE dt.id_transaksi = $id_transaksi";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>Nama Barang: " . $row['nama_barang'] . "</p>";
            echo "<p>Harga: Rp " . number_format($row['harga'], 0, ',', '.') . "</p>";
            echo "<p>Jumlah: " . $row['jumlah'] . "</p>";
            echo "<p>Subtotal: Rp " . number_format($row['subtotal'], 0, ',', '.') . "</p>";
            echo "<hr>";
        }
    } else {
        echo "Detail transaksi tidak ditemukan.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
