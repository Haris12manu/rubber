<?php
include_once('../api/db_connect.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // ดึงข้อมูล seller_name จากตาราง sellers ตาม user_id
    $sql = "SELECT seller_name, seller_id FROM sellers WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $seller_name = $row['seller_name'];
        $seller_id = $row['seller_id'];
    } else {
        $seller_name = 'Unknown User';
        $seller_id = null;
    }

    // ดึงข้อมูลการรับซื้อยางพาราที่เกี่ยวข้องกับ seller_id นี้
    $sql_purchases = "SELECT rp.*, s.seller_name, e.name AS employee_name, e.responsibility_area, ps.store_name 
                      FROM rubber_purchases rp 
                      LEFT JOIN sellers s ON rp.seller_id = s.seller_id 
                      LEFT JOIN employees e ON rp.employee_id = e.employee_id 
                      LEFT JOIN purchasing_stores ps ON rp.store_id = ps.store_id 
                      WHERE rp.seller_id = ? OR e.seller_id = ?
                      ORDER BY rp.purchase_date DESC LIMIT 5";
    $stmt_purchases = $conn->prepare($sql_purchases);
    $stmt_purchases->bind_param("ii", $seller_id, $seller_id);
    $stmt_purchases->execute();
    $result_purchases = $stmt_purchases->get_result();

    // ดึงการแจ้งเตือนใหม่
    $sql_notifications = "SELECT * FROM notifications WHERE seller_id = ? AND notification_status = 'unread'";
    $stmt_notifications = $conn->prepare($sql_notifications);
    $stmt_notifications->bind_param("i", $seller_id);
    $stmt_notifications->execute();
    $result_notifications = $stmt_notifications->get_result();

    // นับจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน
    $notification_count = $result_notifications->num_rows;

    // เมื่อกดดูการแจ้งเตือน
    if (isset($_GET['reset_notifications']) && $_GET['reset_notifications'] == 'true') {
        // อัปเดตสถานะการแจ้งเตือนเป็น 'read'
        $sql_update_notifications = "UPDATE notifications SET notification_status = 'read' WHERE seller_id = ?";
        $stmt_update = $conn->prepare($sql_update_notifications);
        $stmt_update->bind_param("i", $seller_id);
        $stmt_update->execute();

        // อัปเดตจำนวนการแจ้งเตือน
        $notification_count = 0; // เซ็ตจำนวนแจ้งเตือนให้เป็น 0
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>

<style>
    .header .logo {
        height: 200%;
    }

    .header .logo img.logo-img {
        height: 200%;
        max-height: 70px;
        width: auto;
    }
</style>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="../assets/img/logo12.png" alt="" class="img-fluid logo-img">
            <span class="d-none d-lg-block">Ruberr cup</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="?reset_notifications=true" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number"><?php echo $notification_count; ?></span>
                </a><!-- End Notification Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        มีการขายยาง <?php echo $notification_count; ?> มีการขายยางใหม่
                        <a href="?reset_notifications=true"><span class="badge rounded-pill bg-primary p-2 ms-2">กดดู</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <!-- แสดงรายการการแจ้งเตือน -->
                    <?php
                    while ($row_purchase = $result_purchases->fetch_assoc()) {
                        echo '<li class="notification-item">';
                        echo '<i class="bi bi-check-circle text-success"></i>';
                        echo '<div>';
                        if ($row_purchase['employee_name']) {
                            // แจ้งเตือนเมื่อ employee ขาย
                            echo '<h4>' . htmlspecialchars($row_purchase['employee_name']) . ' (ลูกจ้าง)</h4>';
                            echo '<p>ขายให้กับร้าน: ' . htmlspecialchars($row_purchase['store_name']) . '</p>';
                            echo '<p>จำนวน: ' . htmlspecialchars($row_purchase['quantity']) . ' กก. เขต: ' . htmlspecialchars($row_purchase['responsibility_area']) . '</p>';
                        } else {
                            // แจ้งเตือนเมื่อ seller ขายเอง
                            echo '<h4>' . htmlspecialchars($row_purchase['seller_name']) . '</h4>';
                            echo '<p>ขายให้กับร้าน: ' . htmlspecialchars($row_purchase['store_name']) . '</p>';
                            echo '<p>ประเภท: ' . htmlspecialchars($row_purchase['rubber_type']) . ' จำนวน: ' . htmlspecialchars($row_purchase['quantity']) . ' กก.</p>';
                        }
                        echo '<p>' . htmlspecialchars($row_purchase['purchase_date']) . '</p>';
                        echo '</div>';
                        echo '</li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                    ?>

                    <li class="dropdown-footer">
                        <a href="#">Show all notifications</a>
                    </li>
                </ul><!-- End Notification Dropdown Items -->
            </li><!-- End Notification Nav -->

            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="../assets/img/seller.png" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($seller_name); ?></span>
                </a><!-- End Profile Image Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php echo htmlspecialchars($seller_name); ?></h6>
                        <span>Web Designer</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="index.php?page=profile.php">
                            <i class="bi bi-gear"></i>
                            <span>Account Settings</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="../logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>ออกจากระบบ</span>
                        </a>
                    </li>
                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->
        </ul>
    </nav><!-- End Icons Navigation -->
</header><!-- End Header -->