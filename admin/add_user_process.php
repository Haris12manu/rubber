<?php
require '../api/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number']; // เพิ่มการรับค่า phone_number
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสผ่าน

    // ตรวจสอบและบันทึกข้อมูลลงฐานข้อมูล
    // เพิ่มคอลัมน์ status ด้วยค่า 'active'
    $sql = "INSERT INTO `users` (`username`, `email`, `phone_number`, `password`, `user_type`, `status`) VALUES (?, ?, ?, ?, 'Admin', 'active')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $phone_number, $password); // แก้ไขการเรียกใช้ bind_param

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $conn->error;
    }
    

    $stmt->close();
    $conn->close();
}
?>
