<?php
require 'config.php';

$mulai = $_GET['mulai'] ?? '';
$akhir = $_GET['akhir'] ?? '';

$where = '';
if ($mulai && $akhir) {
    $where = "WHERE DATE(waktu) BETWEEN '$mulai' AND '$akhir'";
}

$result = mysqli_query($conn, "
    SELECT alasan, COUNT(*) as jumlah, MAX(waktu) as waktu_terakhir
    FROM apm
    $where
    GROUP BY alasan
");
$total = mysqli_query($conn, "SELECT COUNT(*) as total FROM apm $where");
$data_total = mysqli_fetch_assoc($total);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Polling APM</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #039d95; color: white; }
        tr:hover { background-color: #f1f1f1; }
        h2 { color: #2a2f45; }
        form { margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        input[type="date"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 8px 15px;
            background-color: #039d95;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background-color: #027b78; }
    </style>
</head>
<body>

    <h2>Laporan Polling APM (Berdasarkan Tanggal)</h2>

    <form method="get" action="report.php">
        <label>Dari:
            <input type="date" name="mulai" value="<?= htmlspecialchars($mulai) ?>" required>
        </label>
        <label>Sampai:
            <input type="date" name="akhir" value="<?= htmlspecialchars($akhir) ?>" required>
        </label>
        <button type="submit">Tampilkan</button>
    </form>

    <?php if ($mulai && $akhir && $data_total['total'] > 0): ?>
        <p><strong>Total Responden:</strong> <?= $data_total['total']; ?> (<?= $mulai ?> s.d. <?= $akhir ?>)</p>

        <form method="get" action="export.php">
            <input type="hidden" name="mulai" value="<?= htmlspecialchars($mulai) ?>">
            <input type="hidden" name="akhir" value="<?= htmlspecialchars($akhir) ?>">
            <button type="submit">Export (Per Tanggal)</button>
    
</form>
        <form method="get" action="export_range.php">
            <input type="hidden" name="mulai" value="<?= htmlspecialchars($mulai) ?>">
            <input type="hidden" name="akhir" value="<?= htmlspecialchars($akhir) ?>">
            <button type="submit">Export (Range Total)</button>
        </form>

        <table>
            <tr>
                <th>Alasan</th>
                <th>Jumlah</th>
                <th>Waktu Vote Terakhir</th>
                <th>Persentase</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)):
                $persen = round(($row['jumlah'] / $data_total['total']) * 100, 1);
            ?>
            <tr>
                <td><?= htmlspecialchars($row['alasan']); ?></td>
                <td><?= $row['jumlah']; ?></td>
                <td><?= $row['waktu_terakhir']; ?></td>
                <td><?= $persen ?>%</td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif (!$mulai || !$akhir): ?>
        <p>Silakan pilih rentang tanggal untuk melihat data polling.</p>
    <?php else: ?>
        <p><em>Tidak ada data pada rentang tanggal tersebut.</em></p>
    <?php endif; ?>

</body>
</html>
