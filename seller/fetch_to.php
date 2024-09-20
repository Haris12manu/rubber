<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require('../api/db_connect.php');

// ดึง seller_id จากฐานข้อมูลตาม user_id
$user_id = $_SESSION['user_id'];
$sql_seller = "SELECT s.seller_id FROM sellers s INNER JOIN users u ON u.user_id = s.user_id WHERE u.user_id = ?";
$stmt = $conn->prepare($sql_seller);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_id);
$stmt->fetch();
$stmt->close();

// ตรวจสอบว่า seller_id ถูกดึงมาได้หรือไม่
if (!$seller_id) {
    echo "ไม่พบข้อมูลผู้ขาย";
    exit();
}

// รับค่าจากฟอร์ม (ถ้ามี)
$selected_month = isset($_POST['month']) ? $_POST['month'] : date('m'); // ค่าเดือนที่เลือก หรือใช้เดือนปัจจุบัน
$selected_year = isset($_POST['year']) ? $_POST['year'] : date('Y');    // ค่าปีที่เลือก หรือใช้ปีปัจจุบัน

// แปลงปีพุทธศักราช (พ.ศ.)
$selected_year_th = $selected_year + 543;

// Query สำหรับดึงข้อมูลเฉพาะ employee_id ที่ทำงานให้กับ seller_id ที่กำหนด และกรองตามเดือนและปี
$sql = "SELECT 
            e.responsibility_area, 
            SUM(rp.quantity) AS total_quantity 
        FROM 
            rubber_purchases rp
        JOIN 
            employees e ON rp.employee_id = e.employee_id
        WHERE 
            e.seller_id = ?  -- กรองข้อมูลตาม seller_id
            AND MONTH(rp.purchase_date) = ?  -- กรองตามเดือน
            AND YEAR(rp.purchase_date) = ?  -- กรองตามปี
        GROUP BY 
            e.responsibility_area";

// ใช้ prepared statement เพื่อป้องกัน SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $seller_id, $selected_month, $selected_year); // ผูกค่าตัวแปร seller_id, เดือน, และปี
$stmt->execute();
$result = $stmt->get_result();

// เตรียมข้อมูลสำหรับแผนภูมิ
$responsibilityAreas = [];
$quantities = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $responsibilityAreas[] = $row['responsibility_area'];
        $quantities[] = $row['total_quantity'];
    }
}

// แปลงชื่อเดือนเป็นภาษาไทย
$thai_months = array(
    1 => 'มกราคม',
    2 => 'กุมภาพันธ์',
    3 => 'มีนาคม',
    4 => 'เมษายน',
    5 => 'พฤษภาคม',
    6 => 'มิถุนายน',
    7 => 'กรกฎาคม',
    8 => 'สิงหาคม',
    9 => 'กันยายน',
    10 => 'ตุลาคม',
    11 => 'พฤศจิกายน',
    12 => 'ธันวาคม'
);
// รับค่าจากฟอร์ม (ถ้ามี)
$selected_year = isset($_POST['year']) ? $_POST['year'] : date('Y'); // ปีที่เลือก

// Query สำหรับดึงข้อมูลรายเดือนตามปีที่เลือก
$sql_yearly = "SELECT 
                    MONTH(rp.purchase_date) AS month,
                    SUM(rp.quantity) AS total_quantity 
                FROM 
                    rubber_purchases rp
                JOIN 
                    employees e ON rp.employee_id = e.employee_id
                WHERE 
                    e.seller_id = ?  -- กรองข้อมูลตาม seller_id
                    AND YEAR(rp.purchase_date) = ?  -- กรองตามปี
                GROUP BY 
                    MONTH(rp.purchase_date)";

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตารางจำนวนยางในแต่ละแปลง</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="col-xxl-12 col-xl-12">
        <div class="card">
            <h1 class="card-title">ตารางจำนวนยางที่ขายในแต่ละแปลง</h1>
            <!-- ฟอร์มสำหรับเลือกเดือนและปี -->
        </div>
        <!-- ฟอร์มสำหรับเลือกเดือนและปี -->
        <div class="container mt-4">
            <div class="card p-4">
                <h5 class="card-title">เลือกเดือนและปี (พ.ศ.)</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="month" class="form-label">เลือกเดือน:</label>
                        <select name="month" id="month" class="form-select">
                            <?php for ($i = 1; $i <= 12; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php if ($i == $selected_month) echo 'selected'; ?>><?php echo $thai_months[$i]; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">เลือกปี (พ.ศ.):</label>
                        <select name="year" id="year" class="form-select">
                            <?php for ($i = 2020; $i <= date('Y'); $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php if ($i == $selected_year) echo 'selected'; ?>><?php echo $i + 543; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">ดูข้อมูล</button>
                </form>
            </div>
        </div>
    </div>


    <h1 class="card-title">ตารางข้อมูลเดือน <?php echo $thai_months[intval($selected_month)]; ?> พ.ศ. <?php echo $selected_year_th; ?></h1>


    <!-- ฟอร์มสำหรับเลือกเดือนและปี -->


    <canvas id="barChart"></canvas>

    <script>
        // เตรียมข้อมูลสำหรับแผนภูมิ
        const data = {
            labels: <?php echo json_encode($responsibilityAreas); ?>,
            datasets: [{
                label: 'น้ำหนักยาง (กิโลกรัม)',
                data: <?php echo json_encode($quantities); ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };


        // กำหนดตัวเลือกสำหรับแผนภูมิ
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'พื้นที่รับผิดชอบ',
                            font: {
                                size: 18 // ขนาดฟอนต์แกน x
                            }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'น้ำหนักยาง (กิโลกรัม)',
                            font: {
                                size: 18 // ขนาดฟอนต์แกน y
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        };


        // แสดงแผนภูมิ
        const barChart = new Chart(
            document.getElementById('barChart'),
            config
        );
    </script>
</body>

</html>