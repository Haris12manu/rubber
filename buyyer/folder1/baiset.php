<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../api/db_connect.php');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$purchase_id = isset($_GET['purchase_id']) ? intval($_GET['purchase_id']) : 0;
if ($purchase_id === 0) {
    echo "Invalid purchase ID.";
    exit();
}

$sql = "SELECT rp.*, ps.store_name, s.seller_name, e.name AS employee_name, e.commission_percentage
        FROM rubber_purchases rp
        LEFT JOIN purchasing_stores ps ON rp.store_id = ps.store_id
        LEFT JOIN sellers s ON rp.seller_id = s.seller_id
        LEFT JOIN employees e ON rp.employee_id = e.employee_id
        WHERE rp.purchase_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $purchase_id);
$stmt->execute();
$result = $stmt->get_result();
$purchase = $result->fetch_assoc();

if (!$purchase) {
    echo "Purchase not found.";
    exit();
}

$total_price = $purchase['total_price'];
if (!is_null($purchase['employee_id'])) {
    // ใช้ค่า commission_percentage จากตาราง employees หากไม่พบให้ตั้งค่าเป็น 50%
    $commission_percentage = $purchase['commission_percentage'] ?? 50;

    // คำนวณส่วนแบ่ง
    $employee_share = $total_price * ($commission_percentage / 100);
    $employer_share = $total_price - $employee_share;
} else {
    $employer_share = $total_price;
    $employee_share = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .receipt-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e4e4e4;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e4e4e4;
            padding-bottom: 10px;
        }

        .receipt-header img {
            max-width: 100px; /* ปรับขนาดโลโก้ให้พอดี */
            height: auto;
            margin-bottom: 10px;
        }

        .receipt-header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .receipt-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .receipt-details th,
        .receipt-details td {
            padding: 8px 12px;
            border: 1px solid #e4e4e4;
            text-align: left;
        }

        .receipt-details th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }

        .receipt-footer p {
            margin: 0;
        }

        .action-buttons {
            text-align: right;
            margin-bottom: 20px;
        }

        .action-buttons button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-buttons button:last-child {
            background-color: #dc3545;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="action-buttons">
        <button onclick="window.print()">พิมพ์</button>
        <button onclick="window.close()">ปิด</button>
    </div>
    <div class="receipt-container">
        <div class="receipt-header">
            <img src="../assets/img/logo12.png" alt="">
            <h2>ใบเสร็จ</h2>
            <p>เลขที่ใบเสร็จ: <strong><?= htmlspecialchars($purchase_id) ?></strong></p>
            <p>วันที่: <?= htmlspecialchars(date("Y-m-d H:i:s")) ?></p>
            <p>ชื่อร้าน <?= htmlspecialchars($purchase['store_name'] ?? '') ?></p>
        </div>
        <table class="receipt-details">
            <tr>
                <th>ชื่อผู้ขาย</th>
                <td><?= htmlspecialchars($purchase['employee_id'] ? $purchase['employee_name'] : $purchase['seller_name']) ?></td>
            </tr>
            <?php if ($purchase['employee_id']) { ?>
                <tr>
                    <th>นายจ้าง</th>
                    <td><?= htmlspecialchars($purchase['seller_name']) ?></td>
                </tr>
            <?php } ?>
            <tr>
                <th>รายการ</th>
                <th>จำนวน (กิโลกรัม)</th>
                <th>ราคา / หน่วย (บาท)</th>
                <th>รวมเงิน (บาท)</th>
            </tr>
            <tr>
                <td>
                    <?php
                    // แปลง rubber_type เป็นภาษาไทย
                    $rubber_type_th = '';
                    switch ($purchase['rubber_type']) {
                        case 'Dry':
                            $rubber_type_th = 'ยางแห้ง';
                            break;
                        case 'Wet':
                            $rubber_type_th = 'ยางเปียก';
                            break;
                            // เพิ่มเงื่อนไขเพิ่มเติมได้ตามต้องการ
                        default:
                            $rubber_type_th = htmlspecialchars($purchase['rubber_type']);
                            break;
                    }
                    echo $rubber_type_th;
                    ?>
                </td>
                <td><?= htmlspecialchars($purchase['quantity']) ?></td>
                <td><?= htmlspecialchars($purchase['price_per_unit']) ?></td>
                <td><?= htmlspecialchars($purchase['total_price']) ?></td>
            </tr>
            <?php if ($purchase['employee_id']) { ?>
                <tr>
                    <td colspan="3">ส่วนแบ่งของนายจ้าง</td>
                    <td><?= htmlspecialchars($employer_share) ?> บาท</td>
                </tr>
                <tr>
                    <td colspan="3">ส่วนแบ่งของลูกจ้าง</td>
                    <td><?= htmlspecialchars($employee_share) ?> บาท</td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3">รวมเงินทั้งหมด</td>
                <td><?= htmlspecialchars($total_price) ?> บาท</td>
            </tr>
        </table>
        <div class="receipt-footer">
            <p>ขอบคุณที่ใช้บริการ!</p>
        </div>
    </div>
</body>

</html>