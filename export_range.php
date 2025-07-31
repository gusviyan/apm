<?php
require 'config.php';

$mulai = $_GET['mulai'] ?? '';
$akhir = $_GET['akhir'] ?? '';

// Format tanggal untuk ditampilkan
$tgl_mulai = $mulai ? date('d/m/Y', strtotime($mulai)) : '';
$tgl_akhir = $akhir ? date('d/m/Y', strtotime($akhir)) : '';

// Header Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=apm_{$mulai}_sd_{$akhir}.xls");

// Query data
$where = '';
if ($mulai && $akhir) {
    $where = "WHERE DATE(waktu) BETWEEN '$mulai' AND '$akhir'";
}

$result = mysqli_query($conn, "
    SELECT alasan, COUNT(*) as jumlah
    FROM apm
    $where
    GROUP BY alasan
");

$total = mysqli_query($conn, "SELECT COUNT(*) as total FROM apm $where");
$data_total = mysqli_fetch_assoc($total);
$total_responden = $data_total['total'] ?? 0;
?>

<table border="1">
    <tr>
        <th colspan="4" style="font-size:16pt;">Laporan Polling APM</th>
    </tr>
    <tr>
        <th colspan="4">Periode: <?= $tgl_mulai ?> s.d. <?= $tgl_akhir ?></th>
    </tr>
    <tr>
        <th colspan="4">Total Responden: <?= $total_responden ?></th>
    </tr>
    <tr>
        <th>Alasan</th>
        <th>Jumlah</th>
        <th>Persentase</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)): 
        $persen = $total_responden > 0 ? round(($row['jumlah'] / $total_responden) * 100, 1) : 0;
    ?>
    <tr>
        <td><?= htmlspecialchars($row['alasan']); ?></td>
        <td><?= $row['jumlah']; ?></td>
        <td><?= $persen ?>%</td>
    </tr>
    <?php endwhile; ?>
</table>
