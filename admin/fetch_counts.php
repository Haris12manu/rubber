<?php
require '../api/db_connect.php'; // เชื่อมต่อฐานข้อมูล



// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Queries
$queries = [
    'Purchasing Store' => "SELECT COUNT(*) AS count FROM users WHERE user_type = 'Purchasing Store'",
    'Seller' => "SELECT COUNT(*) AS count FROM users WHERE user_type = 'Seller'",
    'Employees' => "SELECT COUNT(*) AS count FROM employees"
    
];

$counts = [];
foreach ($queries as $label => $query) {
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $counts[$label] = $row['count'];
    } else {
        $counts[$label] = 0;
    }
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($counts);
?>
