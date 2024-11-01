<?php
require_once('../api/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $price = $_POST['price'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE central_price SET price='$price' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        // อัปเดตข้อมูลสำเร็จ
        header("Location: index.php?page=price_edit.php"); // เปลี่ยนไปที่หน้า price_edit.php หลังจากบันทึก
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
