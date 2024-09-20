<?php
session_start(); // เริ่มต้นเซสชัน

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once './api/db_connect.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบผู้ใช้ในฐานข้อมูล
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['password'])) {
            // ตรวจสอบสถานะของผู้ใช้
            if ($user['status'] == 'active') {
                // บันทึกข้อมูลผู้ใช้ในเซสชัน
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];

                // เปลี่ยนเส้นทางตามประเภทผู้ใช้
                switch ($user['user_type']) {
                    case 'admin':
                        header("Location: ./admin/index.php");
                        break;
                    case 'Purchasing Store':
                        header("Location: ./buyyer/index.php");
                        break;
                    case 'Seller':
                        header("Location: ./seller/index.php");
                        break;
                    default:
                        echo "<script>alert('Invalid user type'); window.location.href='login.php';</script>";
                        break;
                }
                exit();
            } elseif ($user['status'] == 'pending approval') {
                echo "<script>alert('Your account is pending approval. Please contact the administrator.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Your account is deactivated. Please contact the administrator.'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid username or password'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='login.php';</script>";
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
} else {
    echo "<script>alert('Invalid request method'); window.location.href='login.php';</script>";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
