<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require('../api/db_connect.php');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql_store = "SELECT store_id FROM purchasing_stores WHERE user_id = ?";
$stmt_store = $conn->prepare($sql_store);
$stmt_store->bind_param("i", $user_id);
$stmt_store->execute();
$result_store = $stmt_store->get_result();
$store_data = $result_store->fetch_assoc();

if (!$store_data) {
    echo "Store not found.";
    exit();
}

$store_id = $store_data['store_id'];

$seller_id = isset($_POST['seller_id']) ? htmlspecialchars($_POST['seller_id']) : null;
$employee_id = isset($_POST['employee_id']) ? htmlspecialchars($_POST['employee_id']) : null;
$purchase_date = date("Y-m-d H:i:s");

if ($seller_id) {
    foreach ($_POST['rubber_type_id'] as $index => $rubber_type) {
        $quantity = $_POST['quantity'][$index];
        $price_per_unit = $_POST['price_per_unit'][$index];
        $total_price = $_POST['total_price'][$index];

        if ($employee_id) {
            $stmt_check = $conn->prepare("SELECT COUNT(*) FROM employees WHERE employee_id = ?");
            $stmt_check->bind_param("i", $employee_id);
            $stmt_check->execute();
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($count == 0) {
                echo "Error: Invalid Employee ID.";
                exit();
            }

            $stmt = $conn->prepare("INSERT INTO rubber_purchases (store_id, seller_id, employee_id, rubber_type, quantity, price_per_unit, total_price, purchase_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiissdds", $store_id, $seller_id, $employee_id, $rubber_type, $quantity, $price_per_unit, $total_price, $purchase_date);
        } else {
            $stmt = $conn->prepare("INSERT INTO rubber_purchases (store_id, seller_id, rubber_type, quantity, price_per_unit, total_price, purchase_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissdds", $store_id, $seller_id, $rubber_type, $quantity, $price_per_unit, $total_price, $purchase_date);
        }

        if ($stmt->execute()) {
            $purchase_id = $stmt->insert_id;

            // บันทึกข้อมูลลงในตาราง notifications
            $notification_message = "New purchase recorded: $quantity kg of rubber type $rubber_type.";
            $notification_status = 'unread'; // สถานะการแจ้งเตือน
            $created_at = date("Y-m-d H:i:s");

            $stmt_notification = $conn->prepare("INSERT INTO notifications (store_id, seller_id, purchase_id, message, notification_status, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_notification->bind_param("iiisss", $store_id, $seller_id, $purchase_id, $notification_message, $notification_status, $created_at);

            if (!$stmt_notification->execute()) {
                error_log("Error inserting notification: " . $stmt_notification->error);
            }

            $stmt_notification->close();
        } else {
            echo "Error: " . $stmt->error;
            exit();
        }

        $stmt->close();
    }
} else {
    echo "Error: Seller ID is missing.";
    exit();
}

$conn->close();

// เปลี่ยนเส้นทางไปยังหน้าใบเสร็จ
header("Location: index.php?page=folder1/baiset.php&purchase_id=" . $purchase_id);
exit();
?>
