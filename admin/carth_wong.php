<?php
require '../api/db_connect.php';  // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สร้างคำสั่ง SQL เพื่อดึงข้อมูล user_type
$query = "SELECT user_type, COUNT(*) AS user_count FROM users GROUP BY user_type";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$labels = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row['user_count'];
    $labels[] = $row['user_type'];
}

// ปิดการเชื่อมต่อฐานข้อมูล
$stmt->close();
$conn->close();
?>

<!-- HTML ส่วนสำหรับกราฟ -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กราฟจำนวนผู้ใช้ตามประเภท</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>กราฟจำนวนผู้ใช้ตามประเภท</h1>

    <canvas id="userTypeChart" width="600" height="300"></canvas>
    <script>
        const ctx = document.getElementById('userTypeChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'จำนวนผู้ใช้',
                    data: <?= json_encode($data) ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } },
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'กราฟจำนวนผู้ใช้ตามประเภท' }
                }
            }
        });
    </script>
</body>
</html>
