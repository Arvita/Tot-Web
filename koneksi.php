<?php
$servername = "localhost"; // Ganti dengan nama server jika perlu
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$database = "bengkel"; // Ganti dengan nama database Anda
$port = 8111; 
// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database, $port);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}
?>
