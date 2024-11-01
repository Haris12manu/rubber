<?php
require_once('../api/db_connect.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['price'])) {
        $price = $_POST['price'];

        // SQL สำหรับการเพิ่มข้อมูล
        $sql = "INSERT INTO central_price (price) VALUES (?)";
        
        // เตรียม statement และทำการ execute
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("d", $price); // กำหนดค่า price ใน statement
            if ($stmt->execute()) {
                header("Location: index.php?message=added");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
