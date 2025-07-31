<?php
require 'config.php';

// Aktifkan laporan error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['alasan']) && !empty($_POST['alasan'])) {
        $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);
        $query = "INSERT INTO apm (alasan) VALUES ('$alasan')";

        if (mysqli_query($conn, $query)) {
            header("Location: thankyou.html");
            exit;
        } else {
            echo "Gagal menyimpan data: " . mysqli_error($conn);
        }
    } else {
        echo "Tidak ada data alasan yang dikirim.";
    }
} else {
    echo "Akses tidak sah.";
}
?>
