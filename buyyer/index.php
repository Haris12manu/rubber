<?php
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">


<?php include_once('scrip.php'); ?>

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
                    <li class="breadcrumb-item"><a href="index.php?page=home.php">หน้าแรก</a></li>
                    <li class="breadcrumb-item active">
                        <?php
                        // ตรวจสอบว่ามีการส่งค่า 'page' หรือไม่
                        if (isset($_GET['page'])) {
                            $page = basename($_GET['page']); // ดึงชื่อไฟล์ที่ส่งมา

                            // กำหนดชื่อหน้าตามไฟล์ที่เปิด
                            switch ($page) {
                                case 'home.php':
                                    echo "แดชบอร์ด";
                                    break;
                                case 'price_creat.php':
                                    echo "สร้างราคา";
                                    break;
                                case 'price.php':
                                    echo "รายการราคา";
                                    break;
                                case 'type.php':
                                    echo "ประเภท";
                                    break;
                                case 'buy_s.php':
                                    echo "การซื้อ";
                                    break;
                                case 'report.php':
                                    echo "รายงาน";
                                    break;
                                case 'buyer_re.php':
                                    echo "รายงานผู้ซื้อ";
                                    break;
                                case 'buy_sel.php':
                                    echo "เลือกซื้อ";
                                    break;
                                case 'reprt_1.php':
                                    echo "รายงาน 1";
                                    break;
                                case 'rabseer.php':
                                    echo "รับซื้อ";
                                    break;
                                case 'aad_type.php':
                                    echo "เพิ่มประเภท";
                                    break;
                                case 'add_price.php':
                                    echo "เพิ่มราคา";
                                    break;
                                case 'receipt.php': // สำหรับหน้าใบเสร็จ
                                    echo "ใบเสร็จ";
                                case 'profile.php': // สำหรับหน้าใบเสร็จ
                                    echo "ข้อมูลร้าน";
                                    break;
                                default:
                                    echo "แดชบอร์ด";
                                    break;
                            }
                        } else {
                            echo "แดชบอร์ด"; // ค่าเริ่มต้น
                        }
                        ?>
                    </li>
                </ol>
            </nav>
        </div><!-- สิ้นสุดหัวข้อหน้า -->

        <!-- แสดงเนื้อหาที่ดึงจากไฟล์ -->
        <?php
        // ตรวจสอบว่ามีการส่งค่า 'page' หรือไม่
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            // ป้องกันการเรียกไฟล์ที่ไม่ปลอดภัย
            $allowed_pages = [
                'folder1/home.php',
                'folder1/price_creat.php',
                'folder1/price.php',
                'folder1/type.php',
                'folder1/buy_s.php',
                'folder1/report.php',
                'folder1/buyer_re.php',
                'folder1/buy_sel.php',
                'folder1/reprt_1.php',
                'folder1/rabseer.php',
                'folder1/aad_type.php',
                'folder1/add_price.php',
                'folder1/baiset.php', // เพิ่มหน้าสำหรับใบเสร็จ
                'folder1/sell_factory.php',
                'folder1/profile.php',
                'folder1/seller_fac.php'
            ]; // ไฟล์ที่อนุญาตให้แสดง

            $page = basename($page); // ป้องกัน Directory Traversal
            $folder = 'folder1/'; // โฟลเดอร์ที่ต้องการเรียกไฟล์

            if (in_array($folder . $page, $allowed_pages)) {
                include($folder . $page);
            } else {
                echo "<p>ไม่พบหน้าที่คุณต้องการ</p>";
            }
        } else {
            include('folder1/home.php'); // แสดงหน้า home.php เมื่อไม่มีการส่งค่า 'page'
        }
        ?>

    </main><!-- สิ้นสุด #main -->

    <!-- ======= ส่วนท้ายของหน้าเว็บ ======= -->
    <!-- สิ้นสุดส่วนท้ายของหน้าเว็บ -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

</body>

</html>