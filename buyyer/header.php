<?php
include_once('../api/db_connect.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  // ดึง store_name จากตาราง purchasing_stores ตาม user_id
  $sql = "SELECT store_name FROM purchasing_stores WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $store_name = $row['store_name'];
  } else {
    // กรณีที่ไม่มีข้อมูล store_name ในตาราง
    $store_name = 'Unknown User';
  }
} else {
  // ถ้าผู้ใช้ยังไม่ได้ล็อกอิน ให้เปลี่ยนไปหน้า login
  header("Location: ../login.php");
  exit();
}
?>
<style>
  .header .logo {
    height: 200%;
    /* ให้ความสูงของโลโก้เป็น 100% ของ Header */
  }

  .header .logo img.logo-img {
    height: 200%;
    /* ให้ภาพโลโก้มีความสูงเต็ม 100% ของพื้นที่ที่มีอยู่ */
    max-height: 70px;
    /* กำหนดขนาดสูงสุดของโลโก้ตามความสูงที่ต้องการ (เช่น 60px) */
    width: auto;
    /* รักษาอัตราส่วนของภาพ */
  }
</style>
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="index.html" class="logo d-flex align-items-center">
      <img src="../assets/img/logo12.png" alt="" class="img-fluid logo-img">
      <span class="d-none d-lg-block">Ruber cup</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <!-- End Search Bar -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <!-- End Search Icon-->  
      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="../assets/img/store.png" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($store_name); ?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo htmlspecialchars($store_name); ?></h6>
            <span>Web Designer</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
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
            <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
              <i class="bi bi-question-circle"></i>
              <span>Need Help?</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="../logout.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header>