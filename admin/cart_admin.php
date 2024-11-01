<?php
require '../api/db_connect.php';  // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งปีมาจากฟอร์มหรือไม่
$year = isset($_POST['year']) ? $_POST['year'] : date('Y'); // กำหนดปีที่เลือก
if (!filter_var($year, FILTER_VALIDATE_INT) || $year < 2020) {
    $year = date('Y'); // ตั้งปีเป็นปีปัจจุบันถ้าปีไม่ถูกต้อง
}

// สร้างคำสั่ง SQL เพื่อดึงข้อมูล
$query = "SELECT MONTH(created_at) AS month, COUNT(*) AS user_count 
          FROM users 
          WHERE YEAR(created_at) = ? 
          GROUP BY MONTH(created_at) 
          ORDER BY MONTH(created_at)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['month']] = $row['user_count'];
}

// เตรียมข้อมูลสำหรับกราฟ
$months = range(1, 12);
$user_counts = [];
foreach ($months as $month) {
    $user_counts[] = isset($data[$month]) ? $data[$month] : 0; // ถ้าไม่มีผู้ลงทะเบียนในเดือนนั้น ให้ใช้ 0
}

// ปิดการเชื่อมต่อฐานข้อมูล
$stmt->close();
$conn->close();
?>

<!-- ส่วน HTML สำหรับแสดงกราฟ -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กราฟจำนวนผู้ลงทะเบียนรายปี</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        h1 {
            text-align: center;
        }

        select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            font-size: 16px;
            margin-left: 10px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        select:focus {
            border-color: #80bdff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        label {
            font-size: 16px;
        }
    </style>
</head>

<body>
    <h1>กราฟจำนวนผู้ลงทะเบียนรายปี <?= $year ?></h1>

    <!-- ฟอร์มเลือกปี -->
    <form method="POST" id="yearForm">
        <label for="year">เลือกปี:</label>
        <select name="year" id="year" onchange="document.getElementById('yearForm').submit();">
            <?php
            // สร้างปีให้เลือก (เปลี่ยนปีเป็น พ.ศ.)
            for ($i = 2020; $i <= date('Y'); $i++) {
                $buddhist_year = $i + 543; // แปลงเป็นปี พ.ศ.
                echo "<option value='$i' " . ($i == $year ? 'selected' : '') . ">$buddhist_year</option>"; // แสดงปี พ.ศ.
            }
            ?>
        </select>
    </form>
    <canvas id="registrationChart" width="400" height="200"></canvas>
    <script>
        const ctx = document.getElementById('registrationChart').getContext('2d');
        const registrationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                    'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                ],
                datasets: [{
                    label: 'จำนวนผู้ลงทะเบียน',
                    data: <?= json_encode($user_counts) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
