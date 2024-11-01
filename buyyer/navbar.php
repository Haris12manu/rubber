<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    aside {
        font-family: 'Prompt', sans-serif;
    }
  </style>
</head>

<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link collapsed " href="index.php?page=home.php">
        <i class="bi bi-grid"></i>
        <span>หน้าแรก</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-heading">จัดการ</li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>การจัดการ</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="index.php?page=type.php">
            <i class="bi bi-tags"></i><span>ข้อมูลประเภทยาง</span>
          </a>
        </li>
        <li>
          <a href="index.php?page=folder1/price.php">
            <i class="bi bi-currency-dollar"></i><span>กำหนดราคารับซื้อ</span>
          </a>
        </li>
        <li>
          <a href="index.php?page=folder1/buy_s.php">
            <i class="bi bi-cart-check"></i><span>รับซื้อ / ชำระ</span>
          </a>
        </li>
        <li>
          <a href="index.php?page=folder1/sell_factory.php">
            <i class="bi bi-truck"></i><span>ขายยางให้กับโรงงาน</span>
          </a>
        </li>
      </ul>
    </li>
    <!-- End Components Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-journal-text"></i><span>ข้อมูลร้าน</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="index.php?page=folder1/profile.php">
            <i class="bi bi-circle"></i><span>ข้อมูลส่วนตัวร้าน</span>
          </a>
        </li>
      </ul>
    </li><!-- End Forms Nav -->
    <li class="nav-heading">รายงาน</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php?page=folder1/report.php">
        <i class="bi bi-person"></i>
        <span>ยอดซื้อ/ยอดขาย</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php?page=folder1/seller_fac.php">
        <i class="bi bi-card-list"></i>
        <span>ขายยางใหเกับโรงงาน</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php?page=folder1/reprt_1.php">
        <i class="bi bi-question-circle"></i>
        <span>บัญชีกำไรขาดทุน</span>
      </a>
    </li><!-- End F.A.Q Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php?page=folder1/buy_sel.php">
        <i class="bi bi-envelope"></i>
        <span>ยอดการขายของลูกค้า</span>
      </a>
    </li><!-- End Contact Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php?page=folder1/buyer_re.php">
        <i class="bi bi-card-list"></i>
        <span>ยอดรับซื้อ</span>
      </a>
    </li><!-- End Register Page Nav -->
    <li class="nav-heading">ออกจากระบบ</li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="logout.php" id="logout-link">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>ออกจากระบบ</span>
      </a>
    </li><!-- End Logout Page Nav -->
    <!-- End Login Page Nav -->
  </ul>

</aside>
<script>
  document.getElementById('logout-link').addEventListener('click', function(event) {
    event.preventDefault(); // หยุดการนำทางชั่วคราว

    // ใช้ SweetAlert2 เพื่อแสดงป๊อปอัปยืนยัน
    Swal.fire({
      title: 'คุณต้องการจะออกจากระบบไหม?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ออกจากระบบ',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {
        // ถ้าผู้ใช้ยืนยัน ให้นำทางไปยังหน้าล็อกเอาต์
        window.location.href = '../logout.php';
      }
    });
  });
</script>