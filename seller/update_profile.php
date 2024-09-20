
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require('../api/db_connect.php');

$user_id = $_SESSION['user_id'];

// รับค่าจากฟอร์ม
$seller_name = $_POST['sellerName'];
$address = $_POST['address'];
$phone_number = $_POST['phone'];

// อัปเดตตาราง sellers
$sql_sellers = "UPDATE sellers SET seller_name = ?, address = ? WHERE user_id = ?";
$stmt_sellers = $conn->prepare($sql_sellers);
$stmt_sellers->bind_param("ssi", $seller_name, $address, $user_id);
$stmt_sellers->execute();
$stmt_sellers->close();

// อัปเดตตาราง users
$sql_users = "UPDATE users SET phone_number = ? WHERE user_id = ?";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->bind_param("si", $phone_number, $user_id);
$stmt_users->execute();
$stmt_users->close();

$conn->close();

// หลังจากอัปเดตเสร็จ กลับไปที่หน้า profile.php
header("Location: index.php?page=profile.php");
exit();
?>
