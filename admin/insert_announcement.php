<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
require '../api/db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบว่าฟอร์มถูกส่งมาแล้วหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // เก็บข้อมูลที่ได้รับจากฟอร์ม
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    // กำหนดเส้นทางการอัปโหลดไฟล์
    $image_path = '';
    $file_path = '';

    // ตรวจสอบและอัปโหลดไฟล์รูปภาพ
    if (!empty($_FILES['image']['name'])) {
        $image_dir = 'uploads/images/';
        
        // ตรวจสอบว่ามีโฟลเดอร์สำหรับอัปโหลดหรือไม่ ถ้าไม่มีก็สร้างขึ้น
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true);
        }
        
        $image_path = $image_dir . basename($_FILES['image']['name']);
        
        // ตรวจสอบประเภทไฟล์รูปภาพ
        $imageFileType = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                echo "อัปโหลดไฟล์รูปภาพสำเร็จ<br>";
            } else {
                echo "การอัปโหลดรูปภาพล้มเหลว<br>";
            }
        } else {
            echo "ประเภทไฟล์รูปภาพไม่ถูกต้อง<br>";
        }
    }

    // ตรวจสอบและอัปโหลดไฟล์เอกสาร
    if (!empty($_FILES['file']['name'])) {
        $file_dir = 'uploads/files/';
        
        // ตรวจสอบว่ามีโฟลเดอร์สำหรับอัปโหลดหรือไม่ ถ้าไม่มีก็สร้างขึ้น
        if (!is_dir($file_dir)) {
            mkdir($file_dir, 0777, true);
        }
        
        $file_path = $file_dir . basename($_FILES['file']['name']);
        
        // สามารถเพิ่มการตรวจสอบประเภทไฟล์เอกสาร เช่น PDF, DOCX เป็นต้น
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            echo "อัปโหลดไฟล์เอกสารสำเร็จ<br>";
        } else {
            echo "การอัปโหลดไฟล์เอกสารล้มเหลว<br>";
        }
    }

    // สร้างคำสั่ง SQL เพื่อแทรกข้อมูลลงในตาราง announcements
    $sql = "INSERT INTO `announcements` (`title`, `content`, `image_path`, `file_path`, `created_at`, `updated_at`) 
            VALUES ('$title', '$content', '$image_path', '$file_path', '$created_at', '$updated_at')";

    // ตรวจสอบว่าบันทึกสำเร็จหรือไม่
    if ($conn->query($sql) === TRUE) {
        echo "บันทึกข้อมูลสำเร็จ";
        header("Location: index.php?page=news.php"); // เปลี่ยนเส้นทางกลับไปที่หน้าหลักหลังบันทึกสำเร็จ
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
