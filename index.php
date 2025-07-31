<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Polling Alasan Tidak Menggunakan APM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Alasan Pasien Tidak Menggunakan APM</h2>
    <form method="POST" action="proses.php">
        <div class="grid">
        <?php
        $options = [
            "Tidak tahu APM / Tidak mengerti cara pakai",
            "Perlu Validasi Biometric Wajah",
            "Jadwal dokter berubah / Tidak Sesuai",
            "Lebih terbiasa interaksi dengan petugas",
            "Belum waktu checkin",
            "Pemeriksaan penunjang",
            "Fisioterapi",
            "Pasien Baru",
            "APM Tidak Berfungsi",
        ];
        foreach ($options as $opt) {
            echo "<button type='submit' name='alasan' value='$opt'>$opt</button>";
        }
        ?>
        </div>
    </form>
    <br><br>
    <div class="button-group">
        <a href="dashboard.php" target="_blank">
            <button type="button">Lihat Hasil</button>
        </a>
        <a href="report.php" target="_blank">
            <button type="button">Laporan</button>
        </a>
    </div>
</body>
</html>
