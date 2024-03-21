<?php
include 'koneksi.php';

$nama_barang = $_POST['nama_barang'];
$stok = $_POST['stok'];
$harga = preg_replace("/[^0-9]/", "", $_POST['harga']);

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    // Proses upload gambar
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $folder = "uploads/";

    if (move_uploaded_file($gambar_tmp, $folder.$gambar)) {
        // Gambar berhasil di-upload, lanjutkan proses simpan ke database
        $sql = "INSERT INTO stok_barang (nama_barang, stok, harga, gambar) VALUES ('$nama_barang', '$stok', '$harga', '$folder$gambar')";

        if ($conn->query($sql) === TRUE) {
            // Redirect kembali ke halaman utama dengan pesan berhasil
            header("Location: data_barang.php?success=1");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Upload gambar gagal";
    }
} else {
    echo "Error dalam upload gambar";
}

$conn->close();
?>
