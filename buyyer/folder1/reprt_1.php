<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../api/db_connect.php');

if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT store_id FROM purchasing_stores WHERE user_id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("การเตรียมคำสั่ง SQL ล้มเหลว: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (empty($store_id)) {
    die("ไม่พบข้อมูลร้านค้า");
}

// รับค่าจากฟอร์มค้นหา
$start_month = isset($_GET['start_month']) ? $_GET['start_month'] : date('m');
$start_year = isset($_GET['start_year']) ? $_GET['start_year'] : date('Y');
$end_month = isset($_GET['end_month']) ? $_GET['end_month'] : date('m');
$end_year = isset($_GET['end_year']) ? $_GET['end_year'] : date('Y');

// แปลงเดือนและปีที่เลือกให้เป็นวันที่เริ่มต้นและสิ้นสุด
$start_date = "$start_year-$start_month-01";
$end_date = date("Y-m-t", strtotime("$end_year-$end_month-01"));

$query = "
    SELECT 
        pl.record_id, 
        pl.store_id, 
        pl.total_purchases, 
        pl.total_sales, 
        pl.profit_loss, 
        pl.record_date, 
        GROUP_CONCAT(rs.rubber_type SEPARATOR ', ') AS rubber_types,
        DATE_FORMAT(pl.record_date, '%Y-%m') AS sale_month
    FROM 
        profit_loss pl
    INNER JOIN 
        rubber_sales rs 
    ON 
        pl.store_id = rs.store_id 
        AND pl.record_date = rs.sale_date
    WHERE 
        pl.store_id = ?
        AND pl.record_date BETWEEN ? AND ?
    GROUP BY 
        pl.record_id
    ORDER BY 
        pl.record_date ASC
";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("การเตรียมคำสั่ง SQL ล้มเหลว: " . $conn->error);
}
$stmt->bind_param("iss", $store_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานยอดซื้อ/ขาย</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
        }

        .table thead th {
            border-bottom: 2px solid #000;
        }

        .card-custom {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 30px;
        }

        .input-icon i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">รายงานยอดซื้อ/ขาย</h3>
                </div>
            </div>
            <div class="card-body">
                <form class="form" id="kt_form" action="" method="GET">
                    <div class="row">
                        <div class="form-group row col-5">
                            <label class="col-4 my-2">เดือนเริ่มต้น :</label>
                            <select class="form-control col-4" name="start_month">
                                <?php for ($m = 1; $m <= 12; $m++) : ?>
                                    <option value="<?php echo $m; ?>" <?php if ($start_month == $m) echo 'selected'; ?>>
                                        <?php echo date('F', mktime(0, 0, 0, $m, 10)); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <select class="form-control col-4" name="start_year">
                                <?php for ($y = date('Y'); $y >= date('Y') - 10; $y--) : ?>
                                    <option value="<?php echo $y; ?>" <?php if ($start_year == $y) echo 'selected'; ?>>
                                        <?php echo $y; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group row col-5">
                            <label class="col-4 my-2">เดือนสิ้นสุด :</label>
                            <select class="form-control col-4" name="end_month">
                                <?php for ($m = 1; $m <= 12; $m++) : ?>
                                    <option value="<?php echo $m; ?>" <?php if ($end_month == $m) echo 'selected'; ?>>
                                        <?php echo date('F', mktime(0, 0, 0, $m, 10)); ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <select class="form-control col-4" name="end_year">
                                <?php for ($y = date('Y'); $y >= date('Y') - 10; $y--) : ?>
                                    <option value="<?php echo $y; ?>" <?php if ($end_year == $y) echo 'selected'; ?>>
                                        <?php echo $y; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group col-2">
                            <button class="btn btn-primary col-12" type="submit">ค้นหา</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered mt-4">
                    <thead>
                        <tr style="background-color: pink">
                            <th style="text-align: center;">ลำดับ</th>
                            <th style="text-align: center;">รายการ</th>
                            <th style="text-align: center;">ยอดขาย (บาท)</th>
                            <th style="text-align: center;">ยอดซื้อ (บาท)</th>
                            <th style="text-align: center;">กำไรขาดทุน (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // ตัวแปรสำหรับการนับลำดับการขายในแต่ละเดือน
                        $index = 1;
                        $current_month = "";

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $record_month = $row['sale_month'];

                                // รีเซ็ตลำดับการขายถ้าเป็นเดือนใหม่
                                if ($current_month !== $record_month) {
                                    $current_month = $record_month;
                                    $index = 1;
                                }

                                // ตรวจสอบประเภทของยางและเปลี่ยนข้อความให้แสดงในตาราง
                                $rubber_types_display = '';
                                if (strpos($row['rubber_types'], 'Mixed (Dry, Wet)') !== false) {
                                    $rubber_types_display = 'ยางรวม';
                                } elseif (strpos($row['rubber_types'], 'Dry') !== false) {
                                    $rubber_types_display = 'ยางแห้ง';
                                } elseif (strpos($row['rubber_types'], 'Wet') !== false) {
                                    $rubber_types_display = 'ยางเปียก';
                                }

                                // แสดงข้อมูลในตาราง
                                echo "<tr>";
                                echo "<td style='text-align: center;'>{$index}</td>";
                                echo "<td>" . htmlspecialchars($rubber_types_display) . "</td>";
                                echo "<td style='text-align: center;'>" . htmlspecialchars($row['total_sales']) . "</td>";
                                echo "<td style='text-align: center;'>" . htmlspecialchars($row['total_purchases']) . "</td>";
                                echo "<td style='text-align: center;'>" . htmlspecialchars($row['profit_loss']) . "</td>";
                                echo "</tr>";

                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align: center;'>ไม่พบข้อมูล</td></tr>";
                        }

                        // ปิดการเชื่อมต่อฐานข้อมูล
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
