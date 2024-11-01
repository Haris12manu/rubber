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

// ดึงข้อมูล seller_id และ employee_id จาก purchase_id เพื่อดึงรายการอื่น ๆ ที่เกี่ยวข้อง
$sql = "SELECT seller_id, employee_id, purchase_date FROM rubber_purchases WHERE purchase_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $purchase_id);
$stmt->execute();
$result = $stmt->get_result();
$purchase = $result->fetch_assoc();

if (!$purchase) {
    echo "Purchase not found.";
    exit();
}

$seller_id = $purchase['seller_id'];
$employee_id = $purchase['employee_id'];
$purchase_time = $purchase['purchase_date']; // ใช้ purchase_date แทน created_at
// ดึงข้อมูลการขายที่มี seller_id และ employee_id เดียวกัน หรือแค่ seller_id เท่านั้น
$sql = "SELECT rp.*, ps.store_name, ps.address, s.seller_name, e.name AS employee_name, e.commission_percentage
        FROM rubber_purchases rp
        LEFT JOIN purchasing_stores ps ON rp.store_id = ps.store_id
        LEFT JOIN sellers s ON rp.seller_id = s.seller_id
        LEFT JOIN employees e ON rp.employee_id = e.employee_id
        WHERE rp.seller_id = ? AND (rp.employee_id = ? OR rp.employee_id IS NULL) 
        AND DATE(rp.purchase_date) = DATE(?) AND TIME(rp.purchase_date) = TIME(?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $seller_id, $employee_id, $purchase_time, $purchase_time);
$stmt->execute();
$result = $stmt->get_result();

// เก็บรายการขายทั้งหมดใน array
$purchases = [];
while ($row = $result->fetch_assoc()) {
    $purchases[] = $row;
}

if (count($purchases) === 0) {
    echo "No purchases found for this seller and employee.";
    exit();
}

// ตรวจสอบว่ามีเพียง seller_id เท่านั้นหรือไม่
if (count($purchases) === 1 && is_null($purchases[0]['employee_id'])) {
    // แสดงเฉพาะ seller_id

} else {
    // คำนวณยอดรวมสำหรับใบเสร็จทั้งหมด
    $total_price_all = 0;
    $employee_share_all = 0;
    $employer_share_all = 0;

    foreach ($purchases as $purchase) {
        $total_price = $purchase['total_price'];
        $total_price_all += $total_price;

        if (!is_null($purchase['employee_id'])) {
            $commission_percentage = $purchase['commission_percentage'] ?? 50;
            $employee_share = $total_price * ($commission_percentage / 100);
            $employer_share = $total_price - $employee_share;
            $employee_share_all += $employee_share;
            $employer_share_all += $employer_share;
        } else {
            $employer_share_all += $total_price;
        }
    }

    $employer_percentage = $total_price_all > 0 ? ($employer_share_all / $total_price_all) * 100 : 0;
    $employee_percentage = $total_price_all > 0 ? ($employee_share_all / $total_price_all) * 100 : 0;
    // โค้ด HTML สำหรับแสดงข้อมูลใบเสร็จ
    // ...

    // โค้ด HTML สำหรับแสดงข้อมูลใบเสร็จ
    // ...
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        /* สไตล์ตามโค้ดเดิม */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* เพิ่มตัวแปรเพื่อจัดการซ้ำ */
        :root {
            --main-bg-color: #f4f4f4;
            --main-font-color: #333;
            --border-color: #e4e4e4;
            --highlight-color: #007bff;
            --danger-color: #dc3545;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--main-bg-color);
            margin: 0;
            padding: 0;
            color: var(--main-font-color);
        }

        .receipt-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
        }

        .receipt-header img {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .receipt-header h2 {
            margin: 0;
            font-size: 24px;
            color: var(--main-font-color);
        }

        .receipt-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .receipt-details th,
        .receipt-details td {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
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
            background-color: var(--highlight-color);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-buttons button:last-child {
            background-color: var(--danger-color);
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
            <p>วันที่ขาย <?= htmlspecialchars(date("Y-m-d", strtotime($purchase_time))) ?></p> <!-- ปรับที่นี่ -->
            <p>ชื่อร้าน <?= htmlspecialchars($purchases[0]['store_name'] ?? '') ?></p>
            <p>ที่อยู่ <?= htmlspecialchars($purchases[0]['address'] ?? '') ?></p>
        </div>

        <div>
            <p>ชื่อผู้ขาย <?= htmlspecialchars($purchases[0]['employee_id'] ? $purchases[0]['employee_name'] : $purchases[0]['seller_name']) ?></p>
            <?php if ($purchases[0]['employee_id']) { ?>
                <p>นายจ้าง <?= htmlspecialchars($purchases[0]['seller_name']) ?></p>
            <?php } ?>
        </div>

        <table class="receipt-details">
            <tr>
                <th>รายการ</th>
                <th>จำนวน (กิโลกรัม)</th>
                <th>ราคา / หน่วย (บาท)</th>
                <th>รวมเงิน (บาท)</th>
            </tr>
            <?php foreach ($purchases as $purchase) { ?>
                <tr>
                    <td>
                        <?php
                        $rubber_type_th = match ($purchase['rubber_type']) {
                            'Dry' => 'ยางแห้ง',
                            'Wet' => 'ยางเปียก',
                            default => htmlspecialchars($purchase['rubber_type']),
                        };
                        echo $rubber_type_th;
                        ?>
                    </td>
                    <td><?= htmlspecialchars($purchase['quantity']) ?></td>
                    <td><?= htmlspecialchars(number_format($purchase['price_per_unit'], 2)) ?></td>
                    <td><?= htmlspecialchars(number_format($purchase['total_price'], 2)) ?></td>
                </tr>
            <?php } ?>

            <!-- เพิ่มยอดเงินรวมสำหรับทุกใบเสร็จ -->
            <tr>
                <td colspan="3" style="font-weight: bold;">ยอดเงินรวม</td>
                <td style="font-weight: bold;"><?= htmlspecialchars(number_format($total_price_all ?? 0, 2)) ?> บาท</td>
            </tr>

            <?php if (!is_null($purchases[0]['employee_id'])) { ?>
                <tr>
                    <td colspan="3">ส่วนแบ่งของนายจ้าง (<?= htmlspecialchars(number_format($employer_percentage, 2)) ?>%)</td>
                    <td><?= htmlspecialchars(number_format($employer_share_all ?? 0, 2)) ?> บาท</td>
                </tr>
                <tr>
                    <td colspan="3">ส่วนแบ่งของลูกจ้าง (<?= htmlspecialchars(number_format($employee_percentage, 2)) ?>%)</td>
                    <td><?= htmlspecialchars(number_format($employee_share_all ?? 0, 2)) ?> บาท</td>
                </tr>
            <?php } ?>
        </table>

        <div class="receipt-footer">
            <p>ขอบคุณที่ใช้บริการ!</p>
        </div>
    </div>
</body>

</html>