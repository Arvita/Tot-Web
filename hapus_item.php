<?php
session_start();

// Pastikan parameter item_index dikirimkan melalui metode POST
if (isset($_POST['item_index'])) {
    $item_index = $_POST['item_index'];

    // Hapus item dengan index yang diberikan dari session keranjang belanja
    unset($_SESSION['keranjang'][$item_index]);

    // Set pesan sukses
    $_SESSION['pesan'] = 'Item berhasil dihapus dari keranjang belanja.';
} else {
    // Set pesan error jika parameter item_index tidak tersedia
    $_SESSION['pesan'] = 'Gagal menghapus item dari keranjang belanja.';
}

// Redirect kembali ke halaman sebelumnya (kasir.php)
header('Location: kasir.php');
exit();
?>
