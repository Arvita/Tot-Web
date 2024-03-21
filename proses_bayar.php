<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah_bayar = preg_replace("/[^0-9]/", "", $_POST['jumlah_bayar']);
    $total_belanja = $_SESSION['total_belanja'];  // Ambil total belanja dari session
    $kembalian = $jumlah_bayar - $total_belanja;
    $is_error = false;  // Flag untuk menandai jika ada kesalahan stok

    // Cek stok barang sebelum menyimpan transaksi
    foreach ($_SESSION['keranjang'] as $item) {
        $id_barang = $item['id_barang'];
        $jumlah = $item['jumlah'];

        // Query untuk memeriksa stok barang
        $sql_check_stok = "SELECT stok FROM stok_barang WHERE id_barang = '$id_barang'";
        $result_check_stok = $conn->query($sql_check_stok);

        if ($result_check_stok && $row = $result_check_stok->fetch_assoc()) {
            $stok_sekarang = $row['stok'];

            if ($stok_sekarang >= $jumlah) {
                // Stok mencukupi, lanjutkan dengan penambahan detail transaksi
                continue; // Lanjutkan ke barang berikutnya
            } else {
                // Stok tidak mencukupi, set flag error dan keluar dari loop
                $is_error = true;
                break;
            }
        } else {
            // Gagal memeriksa stok, set flag error dan keluar dari loop
            $is_error = true;
            break;
        }
    }

    if (!$is_error) {
        // Stok mencukupi, lanjutkan dengan penyimpanan transaksi
        // Simpan transaksi ke tabel transaksi
        $sql_transaksi = "INSERT INTO transaksi (total_belanja, jumlah_bayar, kembalian, tanggal_transaksi) 
                          VALUES ('$total_belanja', '$jumlah_bayar', '$kembalian', NOW())";
        if ($conn->query($sql_transaksi) === TRUE) {
            $last_id = $conn->insert_id;  // Dapatkan ID transaksi terakhir

            // Simpan detail transaksi ke tabel detail_transaksi
            foreach ($_SESSION['keranjang'] as $item) {
                $id_barang = $item['id_barang'];
                $jumlah = $item['jumlah'];
                $subtotal = $item['subtotal'];

                $sql_detail = "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, subtotal) 
                               VALUES ('$last_id', '$id_barang', '$jumlah', '$subtotal')";
                $conn->query($sql_detail);
            }

            // Hapus session keranjang setelah transaksi selesai
            unset($_SESSION['keranjang']);
            unset($_SESSION['total_belanja']);

            // Redirect atau tindakan lain setelah berhasil bayar
            header('Location: kasir.php?bayar_success=1');
            // Sertakan informasi kembalian dalam pesan
            $_SESSION['pesan'] = 'Pembayaran berhasil! Kembalian: Rp ' . number_format($kembalian, 0, ',', '.');

            exit();
        } else {
            echo 'Error: ' . $sql_transaksi . '<br>' . $conn->error;
        }
    } else {
        // Jika ada kesalahan stok, tampilkan pesan error
        $_SESSION['pesan'] = 'Error: Stok barang tidak mencukupi.';
        header('Location: kasir.php');
        exit();
    }
}

$conn->close();
?>
