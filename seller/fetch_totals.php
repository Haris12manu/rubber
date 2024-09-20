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

// ดึงยอดรายวัน
$sql_daily = "SELECT SUM(total_price) as daily_total FROM rubber_purchases WHERE seller_id = ? AND DATE(purchase_date) = CURDATE()";
$stmt = $conn->prepare($sql_daily);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$stmt->bind_result($daily_total);
$stmt->fetch();
$stmt->close();

// ดึงยอดรายสัปดาห์
$sql_weekly = "SELECT SUM(total_price) as weekly_total FROM rubber_purchases WHERE seller_id = ? AND YEARWEEK(purchase_date, 1) = YEARWEEK(CURDATE(), 1)";
$stmt = $conn->prepare($sql_weekly);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$stmt->bind_result($weekly_total);
$stmt->fetch();
$stmt->close();

// ดึงยอดรายปี
$sql_yearly = "SELECT SUM(total_price) as yearly_total FROM rubber_purchases WHERE seller_id = ? AND YEAR(purchase_date) = YEAR(CURDATE())";
$stmt = $conn->prepare($sql_yearly);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$stmt->bind_result($yearly_total);
$stmt->fetch();
$stmt->close();

$conn->close();
?>
