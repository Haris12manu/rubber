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

// ตั้งค่าเริ่มต้นเป็นเดือนและปีปัจจุบัน
$start_month = date('m'); // เดือนปัจจุบัน
$start_year = date('Y'); // ปีปัจจุบัน

// รับค่าจากฟอร์มค้นหา ถ้ามี
if (isset($_GET['start_month'])) {
    $start_month = $_GET['start_month'];
}
if (isset($_GET['start_year'])) {
    $start_year = $_GET['start_year'];
}

// แปลงเดือนและปีที่เลือกให้เป็นวันที่เริ่มต้นและสิ้นสุด
$start_date = "$start_year-$start_month-01";
$end_date = date("Y-m-t", strtotime($start_date));

// Query ข้อมูลตามเดือนและปี
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

if (isset($_GET['ajax'])) {
    // ถ้าเป็นคำขอ AJAX ส่งข้อมูลกลับในรูปแบบ JSON
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit();
}
?>
