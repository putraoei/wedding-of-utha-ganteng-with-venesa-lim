<?php
// === Konfigurasi Database ===
$host = "localhost";   // server database
$user = "root";        // username MySQL
$pass = "";            // password MySQL
$db   = "undangan_db"; // nama database

// Koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama   = $_POST['nama'];
$email  = $_POST['email'];
$wa     = $_POST['wa'];
$status = $_POST['status'];
$pesan  = $_POST['pesan'];

// Simpan ke database
$sql = "INSERT INTO rsvp (nama, email, wa, status, pesan) 
        VALUES ('$nama', '$email', '$wa', '$status', '$pesan')";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Terima kasih, $nama!</h2>";
    echo "<p>Konfirmasi kehadiranmu sudah tersimpan.</p>";
    echo "<a href='index.html'>Kembali</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>