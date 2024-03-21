<?php
session_start();

// Koneksi ke database
include 'koneksi.php';

if (isset($_POST['barang']) && isset($_POST['jumlah'])) {
    $id_barang = $_POST['barang'];
    $jumlah = $_POST['jumlah'];

    // Query untuk mengambil informasi barang berdasarkan ID
    $sql = "SELECT id_barang, nama_barang, harga, stok FROM stok_barang WHERE id_barang = $id_barang";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Cek apakah jumlah yang diminta melebihi stok
        if ($jumlah > $row['stok']) {
            $_SESSION['pesan'] = 'Stok barang tidak mencukupi.';
        } else {
            // Tambahkan item ke keranjang belanja
            $item = array(
                'id_barang' => $row['id_barang'],
                'nama_barang' => $row['nama_barang'],
                'harga' => $row['harga'],
                'jumlah' => $jumlah,
                'subtotal' => $row['harga'] * $jumlah
            );

            // Tambahkan item ke session keranjang belanja
            $_SESSION['keranjang'][] = $item;
            $_SESSION['pesan'] = 'Item berhasil ditambahkan ke keranjang belanja.';
        }
    } else {
        $_SESSION['pesan'] = 'Barang tidak ditemukan.';
    }
} else {
    $_SESSION['pesan'] = 'Gagal menambahkan item ke keranjang belanja.';
}

// Redirect kembali ke halaman sebelumnya (kasir.php)
header('Location: kasir.php');
exit();
?>
