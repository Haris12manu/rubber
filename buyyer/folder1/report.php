<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../api/db_connect.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ตรวจสอบว่า store_id ที่เชื่อมโยงกับ user_id นี้คืออะไร
$query = "SELECT store_id FROM purchasing_stores WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (empty($store_id)) {
    die("ไม่พบข้อมูลร้านค้า");
}

// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
$start_date = isset($_GET['start']) ? $_GET['start'] : null;
$end_date = isset($_GET['end']) ? $_GET['end'] : null;

$query = "
    SELECT pl.store_id, 
           SUM(pl.total_purchases) AS total_purchases, 
           SUM(pl.total_sales) AS total_sales, 
           SUM(pl.profit_loss) AS profit_loss, 
           SUM(rs.quantity) AS total_weight 
    FROM profit_loss pl
    INNER JOIN rubber_sales rs ON pl.store_id = rs.store_id
    WHERE pl.store_id = ?";

if ($start_date && $end_date) {
    $query .= " AND pl.record_date BETWEEN ? AND ?";
}

$query .= " GROUP BY pl.store_id";

$stmt = $conn->prepare($query);

if ($start_date && $end_date) {
    $stmt->bind_param("iss", $store_id, $start_date, $end_date);
} else {
    $stmt->bind_param("i", $store_id);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
                <form class="form" id="kt_form" action="/report/deposit/search" method="GET" enctype="multipart/form-data">
                    <div class="row" style="margin-left: 0px; margin-right: 0px">
                        <div class="form-group row col-5">
                            <label class="col-3 my-2" style="font-size: 12pt;">วันเริ่มต้น :</label>
                            <input class="form-control col-8" type="date" name="start" value="<?php echo htmlspecialchars($start_date); ?>">
                        </div>
                        <div class="form-group row col-5">
                            <label class="col-3 my-2" style="font-size: 12pt;">วันสิ้นสุด :</label>
                            <input class="form-control col-8" type="date" name="end" value="<?php echo htmlspecialchars($end_date); ?>">
                        </div>
                        <div class="form-group col-2" style="float: right;">
                            <button class="btn btn-primary col-12">ค้นหา</button>
                        </div>
                    </div>
                </form>

                <table class="table table-bordered mt-4">
                    <thead>
                        <tr style="background-color: pink">
                            <th style="text-align: center;">รายการ</th>
                            <th style="text-align: center;">น้ำหนัก (กก.)</th>
                            <th style="text-align: center;">ซื้อ (บาท)</th>
                            <th style="text-align: center;">ขาย (บาท)</th>
                            <th style="text-align: center;">กำไร (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)) : ?>
                            <?php foreach ($data as $row) : ?>
                                <tr>
                                    <td colspan="5" style="background-color: #e4e5e6"><b>ยาง</b></td>
                                </tr>
                                <tr>
                                    <td>
                                        <ul>
                                            <li>ข้อมูลสำหรับร้านค้ารหัส <?php echo htmlspecialchars($row['store_id']); ?></li>
                                        </ul>
                                    </td>
                                    <td style="text-align: center;"><?php echo number_format($row['total_weight'], 2); ?></td>
                                    <td style="text-align: center;"><?php echo number_format($row['total_purchases'], 2); ?></td>
                                    <td style="text-align: center;"><?php echo number_format($row['total_sales'], 2); ?></td>
                                    <td style="text-align: center;"><?php echo number_format($row['profit_loss'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">ไม่พบข้อมูล</td>
                            </tr>
                        <?php endif; ?>
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
