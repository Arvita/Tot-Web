<?php
include 'koneksi.php';

// Periksa apakah parameter id tersedia dari URL
if(isset($_GET['id'])) {
    // Ambil id barang dari parameter URL
    $id_barang = $_GET['id'];

    // Query untuk menghapus data berdasarkan id_barang
    $sql = "DELETE FROM stok_barang WHERE id_barang='$id_barang'";

    if ($conn->query($sql) === TRUE) {
        // Redirect kembali ke halaman utama dengan pesan berhasil
        header("Location: data_barang.php?delete_success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "ID Barang tidak ditemukan.";
}

$conn->close();
?>
