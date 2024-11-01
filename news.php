<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ประกาศ</title>
    <style>
        /* สไตล์พื้นฐาน */
        body { font-family: Arial, sans-serif; }
        .announcement { border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; }
        .date { color: #999; }
        .content { margin-top: 10px; }
        .qr-code { width: 100px; height: 100px; }
    </style>
</head>
<body>

<?php
// เชื่อมต่อกับฐานข้อมูล
include_once('./api/db_connect.php');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT id, title, content, created_at FROM announcements WHERE 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='announcement'>";
        echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>"; // ป้องกัน XSS
        echo "<p class='date'>" . htmlspecialchars($row["created_at"]) . "</p>";

        echo "<div class='content'>" . nl2br(htmlspecialchars($row["content"])) . "</div>"; // ใช้ nl2br เพื่อแสดง line breaks
        echo "</div>";
    }
} else {
    echo "<p>ไม่มีประกาศ</p>";
}

$conn->close();
?>

</body>
</html>
