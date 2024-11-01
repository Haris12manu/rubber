<?php
$currentDir = basename(dirname($_SERVER['PHP_SELF'])); // Get current directory name
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// ตรวจสอบ user_type
if ($_SESSION['user_type'] !== 'Admin') {
    echo "<p>คุณไม่มีสิทธิ์เข้าถึงหน้านี้</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>แดชบอร์ด - NiceAdmin Bootstrap Template</title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- ======= ส่วนหัวของหน้าเว็บ ======= -->
    <?php include_once('header.php'); ?>
    <!-- สิ้นสุดส่วนหัวของหน้าเว็บ -->

    <!-- ======= แถบด้านข้าง ======= -->
    <?php include_once('navbar.php'); ?>
    <!-- สิ้นสุดแถบด้านข้าง -->

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>แดชบอร์ด</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=home_admin.php">หน้าแรก</a></li>
                    <li class="breadcrumb-item active">แดชบอร์ด</li>
                </ol>
            </nav>
        </div><!-- สิ้นสุดหัวข้อหน้า -->

        <!-- แสดงเนื้อหาที่ดึงจากไฟล์ -->
        <?php
        // ตรวจสอบว่ามีการส่งค่า 'page' หรือไม่
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            // ป้องกันการเรียกไฟล์ที่ไม่ปลอดภัย
            $allowed_pages = ['home_admin.php', 'viewport.php', 'price.php', 'price_edit.php', 'buy_s.php', 'news.php', 'create_news.php', 'data_admin.php', 'data_user.php','data_seller.php', 'edit_user.php', 'type.php', 'aad_type.php']; // ไฟล์ที่อนุญาตให้แสดง
            $page = basename($page); // ป้องกัน Directory Traversal
            if (in_array($page, $allowed_pages)) {
                include($page);
            } else {
                echo "<p>ไม่พบหน้าที่คุณต้องการ</p>";
            }
        } else {
            include('home_admin.php'); // แสดงหน้า home.php เมื่อไม่มีการส่งค่า 'page'
        }
        ?>
    </main><!-- สิ้นสุด #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
