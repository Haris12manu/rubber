<?php
include_once('../api/db_connect.php');

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  // ดึงข้อมูล username และ user_type จากตาราง users สำหรับ user_id ที่กำหนด
  $sql = "SELECT username, user_type FROM users WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('prepare() failed: ' . htmlspecialchars($conn->error));
  }
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = htmlspecialchars($row['username']);
    $user_type = htmlspecialchars($row['user_type']);
  } else {
    $username = 'Unknown User';
    $user_type = 'Unknown Type';
  }
  $stmt->close();
} else {
  header("Location: ../login.php");
  exit();
}
$sql_new_users = "SELECT COUNT(*) AS new_user_count FROM users WHERE status = 'pending approval'";
$result_new_users = $conn->query($sql_new_users);
$new_user_count = 0;

if ($result_new_users->num_rows > 0) {
  $row = $result_new_users->fetch_assoc();
  $new_user_count = $row['new_user_count'];
}

// ดึงข้อมูลผู้ใช้ที่ยังไม่อนุมัติ (pending approval)
$sql_user_details = "SELECT username, created_at FROM users WHERE status = 'pending approval' ORDER BY created_at DESC";
$result_user_details = $conn->query($sql_user_details);
?>


<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <a href="index.html" class="logo d-flex align-items-center">
      <img src="assets/img/logo.png" alt="">
      <span class="d-none d-lg-block">NiceAdmin</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->


  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <!-- Notification Icon -->
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number"><?php echo $new_user_count; ?></span>
        </a><!-- End Notification Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header">
            มี <?php echo $new_user_count; ?> ผู้ใช้รอการอนุมัติ
            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">ดูทั้งหมด</span></a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <?php if ($result_user_details->num_rows > 0): ?>
            <?php while ($user = $result_user_details->fetch_assoc()): ?>
              <li class="notification-item">
                <i class="bi bi-person-plus text-success"></i>
                <div>
                  <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                  <p>ผู้ใช้ใหม่ลงทะเบียน</p>
                  <p><?php echo htmlspecialchars(date("H:i", strtotime($user['created_at']))); ?> hrs ago</p>
                </div>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
            <?php endwhile; ?>
            <li class="dropdown-footer">
              <form method="POST" action="approve_user.php">
                <button type="submit" name="mark_as_read" class="btn btn-primary">อนุมัติผู้ใช้</button>
              </form>
            </li>
          <?php else: ?>
            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>ไม่มีผู้ใช้รอการอนุมัติ</h4>
              </div>
            </li>
          <?php endif; ?>
        </ul><!-- End Notification Dropdown Items -->
      </li><!-- End Notification Nav -->


      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="../assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $username; ?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo $username; ?></h6>
            <span><?php echo $user_type; ?></span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
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

</header>