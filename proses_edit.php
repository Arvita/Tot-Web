<?php
include 'koneksi.php';

$id_barang = $_POST['id_barang'];
$nama_barang = $_POST['nama_barang'];
$stok = $_POST['stok'];
$harga = preg_replace("/[^0-9]/", "", $_POST['harga']);

// Check apakah ada file gambar yang diupload
if ($_FILES['gambar']['name'] != "") {
    // Upload gambar baru
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $folder = "uploads/"; // Folder tempat menyimpan gambar

    // Pindahkan gambar baru ke folder uploads
    move_uploaded_file($gambar_tmp, $folder.$gambar);

    // Update path gambar di database
    $sql = "UPDATE stok_barang SET nama_barang='$nama_barang', stok='$stok', harga='$harga', gambar='$folder$gambar' WHERE id_barang=$id_barang";
} else {
    // Jika tidak ada gambar yang diupload, update data tanpa mengubah path gambar
    $sql = "UPDATE stok_barang SET nama_barang='$nama_barang', stok='$stok', harga='$harga' WHERE id_barang=$id_barang";
}
if ($conn->query($sql) === TRUE) {
    // Redirect kembali ke halaman utama dengan pesan berhasil
    header("Location: data_barang.php?update_success=1");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>
