<?php
require_once('../api/db_connect.php'); // เชื่อมต่อฐานข้อมูล

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // SQL สำหรับการลบข้อมูล
    $sql = "DELETE FROM central_price WHERE id = ?";
    
    // เตรียม statement และทำการ execute
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); // กำหนดค่า id ใน statement
        if ($stmt->execute()) {
            // หากลบสำเร็จ กลับไปยังหน้าหลัก
            header("Location: index.php?message=deleted");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "ID ไม่ถูกต้อง";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
