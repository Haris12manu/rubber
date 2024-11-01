<?php
// เชื่อมต่อฐานข้อมูล
require '../api/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // อัปเดตข้อมูลผู้ใช้
    $sql = "UPDATE users SET username='$username', email='$email', phone_number='$phone_number' WHERE user_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "อัปเดตข้อมูลสำเร็จ";
        header("Location: index.php?page=data_user.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
} else {
    $user_id = $_GET['user_id'];

    // ดึงข้อมูลผู้ใช้
    $sql = "SELECT * FROM users WHERE user_id='$user_id'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขผู้ใช้</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>แก้ไขข้อมูลผู้ใช้</h2>
    <form method="POST">
        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
        <div class="form-group">
            <label>ชื่อ</label>
            <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
        </div>
        <div class="form-group">
            <label>อีเมล์</label>
            <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label>เบอร์โทร</label>
            <input type="text" class="form-control" name="phone_number" value="<?php echo $user['phone_number']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">อัปเดต</button>
    </form>
</div>
</body>
</html>

<?php $conn->close(); ?>
