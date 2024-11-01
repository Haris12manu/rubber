<?php
// เริ่ม session
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

// เชื่อมต่อฐานข้อมูล
require('../api/db_connect.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่า store_id ที่เชื่อมโยงกับ user_id นี้คืออะไร
$user_id = $_SESSION['user_id'];
$query = "SELECT store_id FROM purchasing_stores WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (empty($store_id)) {
    echo json_encode(['error' => 'No store found']);
    exit();
}

// รับประเภทข้อมูลจาก URL
$period = isset($_GET['period']) ? $_GET['period'] : 'monthly';

$data = [];
if ($period === 'daily') {
    // ดึงข้อมูลรายวันเฉพาะ store_id
    $sql = "SELECT DATE(purchase_date) AS date, COUNT(seller_id) AS seller_count
            FROM rubber_purchases
            WHERE store_id = ?
            GROUP BY date
            ORDER BY date";
} elseif ($period === 'yearly') {
    // ดึงข้อมูลรายปีเฉพาะ store_id
    $sql = "SELECT YEAR(purchase_date) AS year, COUNT(seller_id) AS seller_count
            FROM rubber_purchases
            WHERE store_id = ?
            GROUP BY year
            ORDER BY year";
} else {
    // ดึงข้อมูลรายเดือนเฉพาะ store_id
    $sql = "SELECT MONTH(purchase_date) AS month, COUNT(seller_id) AS seller_count
            FROM rubber_purchases
            WHERE store_id = ?
            GROUP BY month
            ORDER BY month";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

// ส่งข้อมูลในรูปแบบ JSON
echo json_encode($data);

?>

