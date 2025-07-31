<?php
require 'config.php';

$mulai = $_GET['mulai'] ?? '';
$akhir = $_GET['akhir'] ?? '';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_polling_apm_{$mulai}_sd_{$akhir}.xls");

$where = '';
if ($mulai && $akhir) {
    $where = "WHERE DATE(waktu) BETWEEN '$mulai' AND '$akhir'";
}

$result = mysqli_query($conn, "
    SELECT DATE(waktu) as tanggal, alasan, COUNT(*) as jumlah
    FROM apm
    $where
    GROUP BY tanggal, alasan
    ORDER BY tanggal ASC
");

$total = mysqli_query($conn, "SELECT COUNT(*) as total FROM apm $where");
$data_total = mysqli_fetch_assoc($total);
?>

<table border="1">
    <thead>
        <tr>
            <th colspan="4">Laporan Polling APM</th>
        </tr>
        <tr>
            <th>Tanggal</th>
            <th>Alasan</th>
            <th>Jumlah</th>
            <th>Persentase</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): 
            $tanggal = date('d/m/y', strtotime($row['tanggal']));
            $persen = $data_total['total'] > 0 ? round(($row['jumlah'] / $data_total['total']) * 100, 1) : 0;
        ?>
        <tr>
            <td><?= $tanggal; ?></td>
            <td><?= htmlspecialchars($row['alasan']); ?></td>
            <td><?= $row['jumlah']; ?></td>
            <td><?= $persen; ?>%</td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
