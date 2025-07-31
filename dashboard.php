<?php
require 'config.php';

$result = mysqli_query($conn, "
    SELECT alasan, COUNT(*) as jumlah, MAX(waktu) as waktu_terakhir
    FROM apm
    GROUP BY alasan
");
$total = mysqli_query($conn, "SELECT COUNT(*) as total FROM apm");
$data_total = mysqli_fetch_assoc($total);

// Menyusun data untuk grafik
$labels = [];
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['alasan'];
    $data[] = $row['jumlah'];
    $rows[] = $row; // simpan juga untuk tabel
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Polling</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #039d95; color: white; }
        tr:hover { background-color: #f1f1f1; }
        h2 { color: #2a2f45; }
        canvas { margin-top: 40px; background: #fff; padding: 20px; border-radius: 8px; }
    </style>
</head>
<body>

    <h2>Dashboard Polling APM</h2>
    <p><strong>Total Responden:</strong> <?= $data_total['total']; ?></p>

    <table>
        <tr>
            <th>Alasan</th>
            <th>Jumlah</th>
            <th>Waktu Vote</th>
            <th>Persentase</th>
        </tr>
        <?php foreach ($rows as $row): 
            $persentase = round(($row['jumlah'] / $data_total['total']) * 100, 1);
        ?>
        <tr>
            <td><?= htmlspecialchars($row['alasan']); ?></td>
            <td><?= $row['jumlah']; ?></td>
            <td><?= $row['waktu_terakhir']; ?></td>
            <td><?= $persentase ?>%</td>
        </tr>
        <?php endforeach; ?>
    </table>

    <canvas id="grafikPolling" width="600" height="300"></canvas>

    <script>
    const ctx = document.getElementById('grafikPolling').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Jumlah Responden',
                data: <?= json_encode($data) ?>,
                backgroundColor: '#039d95'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Grafik Alasan Tidak Menggunakan APM'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
    </script>

</body>
</html>
