<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once('../api/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $commission_percentage = $_POST['commission_percentage'];
    $responsibility_area = $_POST['responsibility_area'];

    $sql = "UPDATE employees 
            SET name = ?, phone_number = ?, commission_percentage = ?, responsibility_area = ?, updated_at = NOW() 
            WHERE employee_id = ? AND seller_id = (SELECT seller_id FROM sellers WHERE user_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsii", $name, $phone_number, $commission_percentage, $responsibility_area, $employee_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        // แสดงข้อความแจ้งเตือนแล้วรีเฟรชหน้าเดิม
        echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location.href = '".$_SERVER['HTTP_REFERER']."';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
