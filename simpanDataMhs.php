<?php
include "koneksi.php";

// cek apakah $koneksi ada dan valid
if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    die("Koneksi database gagal. Periksa file koneksi.php — pastikan variabel koneksi bernama \$koneksi dan berhasil terhubung.");
}

// ambil data dan sanitasi
function bersih($data){
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

//Ambil password asli dari input form
$xnim = bersih($_POST['nim'] ?? '');
$xnama = bersih($_POST['nama'] ?? '');
$xtempatLahir = bersih($_POST['tempatLahir'] ?? '');
$xtanggalLahir = bersih($_POST['tanggalLahir'] ?? '');
$xjmlSaudara = bersih($_POST['jmlSaudara'] ?? '');
$xalamat = bersih($_POST['alamat'] ?? '');
$xkota = bersih($_POST['kota'] ?? '');
$xjk = bersih($_POST['jk'] ?? ''); // L / P
$xstatusKeluarga = bersih($_POST['statusKeluarga'] ?? ''); // K / B
$xhobi = isset($_POST['hobi']) ? implode(",", $_POST['hobi']) : "";
$xemail = bersih($_POST['email'] ?? '');
$xraw_password = bersih($_POST['password'] ?? ''); // Misalnya dari input form

//Lakukan validasi pada password asli (panjang, kompleksitas, dll.)
if (empty($xraw_password) || strlen($xraw_password) < 10) {
    die("Password minimal 10 karakter.");
}

// Hashing password menggunakan password_hash()
$hashed_password = password_hash($xraw_password, PASSWORD_BCRYPT);

$sql1 = "INSERT INTO mhs (nim,nama,tempatLahir,tanggalLahir,jmlSaudara,alamat,kota,jenisKelamin,statusKeluarga,hobi,email,pass)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

// coba prepare dan tangani jika gagal
if ($stmt = $koneksi->prepare($sql1)) {
    $stmt->bind_param("ssssssssssss",
        $xnim, $xnama, $xtempatLahir, $xtanggalLahir, $xjmlSaudara,
        $xalamat, $xkota, $xjk, $xstatusKeluarga, $xhobi, $xemail, $hashed_password
    );

    if ($stmt->execute()) {
        echo "Data berhasil disimpan! <br>";
        echo "<a href='tampilDataMhs.php'>Lihat Data</a>";
    } else {
        // lebih spesifik: error pada statement
        echo "Error saat mengeksekusi statement: " . $stmt->error;
    }

    $stmt->close(); // tutup statement dulu
} else {
    // jika prepare gagal, tampilkan error koneksi (SQL sintaks atau lainnya)
    echo "Prepare gagal: " . $koneksi->error;
}

$koneksi->close();
?>
