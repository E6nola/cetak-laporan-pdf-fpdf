<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "universitas";

$koneksi = new mysqli($host, $user, $pass, $db);

// cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
