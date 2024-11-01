<?php
require '../api/db_connect.php'; // เชื่อมต่อฐานข้อมูล

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Queries เพื่อดึงข้อมูลจากฐานข้อมูล
$queries = [
    'approved' => "SELECT COUNT(*) AS count FROM users WHERE status = 'active'",
    'pending' => "SELECT COUNT(*) AS count FROM users WHERE status = 'pending approval'"
];

$counts = [];
foreach ($queries as $label => $query) {
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $counts[$label] = $row['count'];
    } else {
        $counts[$label] = 0;
    }
}

$conn->close();

// ส่งผลลัพธ์ออกเป็น JSON
header('Content-Type: application/json');
echo json_encode($counts);
?>
