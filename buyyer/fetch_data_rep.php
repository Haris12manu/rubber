<?php
// ส่วนนี้เป็นโค้ด PHP ของคุณเหมือนเดิม

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../api/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT store_id FROM purchasing_stores WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (empty($store_id)) {
    die("ไม่พบข้อมูลร้านค้า");
}


$sql = "
    SELECT 
        COUNT(DISTINCT CASE WHEN seller_id IS NOT NULL THEN seller_id ELSE employee_id END) AS total_sellers, 
        SUM(quantity) AS total_quantity, 
        SUM(total_price) AS total_revenue
    FROM 
        rubber_purchases
    WHERE 
        store_id = ? AND DATE(purchase_date) = CURDATE()
";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

$stmt->bind_param("i", $store_id);

if (!$stmt->execute()) {
    die("Error executing SQL statement: " . $stmt->error);
}

$result = $stmt->get_result();
$daily_data = $result->fetch_assoc();

$total_sellers = $daily_data['total_sellers'] ?? 0;
$total_quantity = $daily_data['total_quantity'] ?? 0;
$total_revenue = $daily_data['total_revenue'] ?? 0;

$stmt->close();

$sql_inout = "
    SELECT 
        SUM(CASE 
                WHEN sale_status = '0' THEN quantity  
                WHEN sale_status = '1' THEN -quantity 
            END
        ) AS net_quantity
    FROM 
        rubber_purchases
    WHERE 
        store_id = ?;
";

$stmt_inout = $conn->prepare($sql_inout);

if ($stmt_inout === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

$stmt_inout->bind_param("i", $store_id);

if (!$stmt_inout->execute()) {
    die("Error executing SQL statement: " . $stmt_inout->error);
}

$result_inout = $stmt_inout->get_result();
$inout_data = $result_inout->fetch_assoc();
$net_quantity = $inout_data['net_quantity'] ?? 0;

$stmt_inout->close();

$sql_sales = "
    SELECT 
        factory_name, 
        quantity AS total_quantity, 
        total_price AS total_revenue,
        sale_date,
        RANK() OVER (PARTITION BY factory_name ORDER BY sale_date DESC) AS sale_rank
    FROM 
        rubber_sales
    WHERE 
        store_id = ? AND MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())
    ORDER BY 
        sale_date DESC
    LIMIT 4;
";

$stmt_sales = $conn->prepare($sql_sales);

if ($stmt_sales === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

$stmt_sales->bind_param("i", $store_id);

if (!$stmt_sales->execute()) {
    die("Error executing SQL statement: " . $stmt_sales->error);
}

$result_sales = $stmt_sales->get_result();
$sales_data = [];

while ($row = $result_sales->fetch_assoc()) {
    $sales_data[] = $row;
}

$stmt_sales->close();


// คำนวณยอดยางเข้า (บันทึกข้อมูลยางเข้าไว้ก่อนที่จะมีการขายออก)
$sql_in = "
    SELECT 
        SUM(quantity) AS total_in_quantity
    FROM 
        rubber_purchases
    WHERE 
        store_id = ? AND sale_status = '1';
";

$stmt_in = $conn->prepare($sql_in);

if ($stmt_in === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

$stmt_in->bind_param("i", $store_id);

if (!$stmt_in->execute()) {
    die("Error executing SQL statement: " . $stmt_in->error);
}

$result_in = $stmt_in->get_result();
$in_data = $result_in->fetch_assoc();
$total_in_quantity = $in_data['total_in_quantity'] ?? 0;

$stmt_in->close();


// คำนวณยอดยางออก (คำนวณเมื่อมีการขายออก)
$sql_out = "
    SELECT 
        SUM(quantity) AS total_out_quantity
    FROM 
        rubber_sales
    WHERE 
        store_id = ? AND DATE(sale_date) = CURDATE();
";

$stmt_out = $conn->prepare($sql_out);
$stmt_out->bind_param("i", $store_id);

if (!$stmt_out->execute()) {
    die("Error executing SQL statement: " . $stmt_out->error);
}

$result_out = $stmt_out->get_result();
$out_data = $result_out->fetch_assoc();
$total_out_quantity = $out_data['total_out_quantity'] ?? 0;

$stmt_out->close();


$sql_profit_loss = "
    SELECT 
        SUM(total_purchases) AS total_purchases, 
        SUM(total_sales) AS total_sales, 
        SUM(profit_loss) AS profit_loss,
        MONTH(record_date) AS month, 
        YEAR(record_date) AS year,
        ROW_NUMBER() OVER (ORDER BY MAX(record_date) DESC) AS record_rank
    FROM 
        profit_loss
    WHERE 
        store_id = ? 
        AND MONTH(record_date) = MONTH(CURDATE())
        AND YEAR(record_date) = YEAR(CURDATE())
    GROUP BY 
        store_id, MONTH(record_date), YEAR(record_date);
";

$stmt_profit_loss = $conn->prepare($sql_profit_loss);

if ($stmt_profit_loss === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

$stmt_profit_loss->bind_param("i", $store_id);

if (!$stmt_profit_loss->execute()) {
    die("Error executing SQL statement: " . $stmt_profit_loss->error);
}

$result_profit_loss = $stmt_profit_loss->get_result();
$profit_loss_data = $result_profit_loss->fetch_assoc();

$stmt_profit_loss->close();

// ดึงข้อมูลยางในคลังที่ยังไม่ได้ขาย
// ดึงข้อมูลยางในคลังที่ยังไม่ได้ขาย โดยเลือกข้อมูลจากเดือนล่าสุดและเดือนก่อนหน้า
$sql_inventory = "
    SELECT 
        rubber_type,
        GREATEST(0, SUM(quantity)) AS current_inventory,
        MONTH(purchase_date) AS month
    FROM 
        rubber_purchases
    WHERE 
        store_id = ? AND sale_status = '0' -- เลือกเฉพาะยางที่ยังไม่ถูกขายออกไป
        AND MONTH(purchase_date) IN (MONTH(CURDATE()), MONTH(CURDATE()) - 1)
    GROUP BY 
        rubber_type, MONTH(purchase_date);
";

$stmt_inventory = $conn->prepare($sql_inventory);

if ($stmt_inventory === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

$stmt_inventory->bind_param("i", $store_id);

if (!$stmt_inventory->execute()) {
    die("Error executing SQL statement: " . $stmt_inventory->error);
}

$result_inventory = $stmt_inventory->get_result();
$inventory_data = [
    'Dry' => 0,
    'Wet' => 0,
];
$total_inventory = 0;
$total_previous_inventory = 0;

// กำหนดชื่อเดือน
$month_names = [
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
];

while ($row = $result_inventory->fetch_assoc()) {
    $rubber_type = $row['rubber_type'];
    $current_quantity = $row['current_inventory'] ?? 0;
    $month = $row['month'];

    // จัดเก็บข้อมูลยางในคลังตามประเภทและเดือน
    if ($month == date('n')) { // เดือนปัจจุบัน
        if (isset($inventory_data[$rubber_type])) {
            $inventory_data[$rubber_type] += $current_quantity; // เพิ่มยอดจากเดือนปัจจุบัน
        }
    } elseif ($month == date('n') - 1) { // เดือนก่อนหน้า
        if (isset($inventory_data[$rubber_type])) {
            $total_previous_inventory += $current_quantity; // เพิ่มยอดจากเดือนก่อนหน้า
        }
    }

    // คำนวณจำนวนยางรวม
    $total_inventory += $current_quantity;
}

$stmt_inventory->close();

$conn->close();
