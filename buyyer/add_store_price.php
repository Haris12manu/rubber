<?php
session_start(); // เริ่มต้นเซสชัน

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // ถ้าไม่ได้ล็อกอินให้กลับไปที่หน้า login
    exit();
}

require ('../api/db_connect.php');
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// รับค่าจากฟอร์ม
$user_id = $_SESSION['user_id']; // ใช้ user_id จากเซสชัน
$rubber_type_id = $_POST['id'];
$adjusted_price = $_POST['adjusted_price'];

// ตรวจสอบว่าค่าที่รับมาทั้งหมดมีอยู่หรือไม่
if (isset($user_id) && isset($rubber_type_id) && isset($adjusted_price)) {
    // ตรวจสอบว่าค่าที่รับมาถูกต้องหรือไม่
    if (is_numeric($rubber_type_id) && is_numeric($adjusted_price)) {
        // ดึง user_type ของผู้ใช้ที่ล็อกอินอยู่
        $sql = "SELECT user_type FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($user_type);
        $stmt->fetch();
        $stmt->close();

        // ตรวจสอบ user_type และดึง store_id ถ้าเป็น Purchasing Store
        if ($user_type == 'Purchasing Store') {
            $sql = "SELECT store_id FROM purchasing_stores WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($store_id);
            $stmt->fetch();
            $stmt->close();

            // ถ้าเจอ store_id ให้บันทึกข้อมูล
            if ($store_id) {
                $sql = "INSERT INTO store_prices (store_id, rubber_type_id, adjusted_price) VALUES (?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("iid", $store_id, $rubber_type_id, $adjusted_price);
                    if ($stmt->execute()) {
                        header("Location: index.php?page=folder1/price.php");
                        exit();
                    } else {
                        echo "เกิดข้อผิดพลาด: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
                }
            } else {
                echo "ไม่พบ store_id สำหรับผู้ใช้ที่ล็อกอินอยู่";
            }
        } else {
            echo "ผู้ใช้ที่ล็อกอินไม่ใช่ Purchasing Store";
        }
    } else {
        echo "ข้อมูลไม่ถูกต้อง";
    }
} else {
    echo "ข้อมูลไม่ครบถ้วน";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
