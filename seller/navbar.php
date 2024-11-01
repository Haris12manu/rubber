<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link collapsed " href="index.php">
        <i class="bi bi-grid"></i>
        <span>หน้าแรก</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-heading">Pages</li>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>ข้อมูลผู้ขาย</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="index.php?page=profile.php">
            <i class="bi bi-circle"></i><span>ข้อมูลส่วนตัว</span>
          </a>
        </li>
        <li>
          <a href="index.php?page=reset_pass.php">
            <i class="bi bi-circle"></i><span>เปลี่ยนรหัสผ่าน</span>
          </a>
        </li>
        <li>
          <a href="index.php?page=employee.php">
            <i class="bi bi-circle"></i><span>รายชื่อลูกจ้าง</span>
          </a>
        </li>
      </ul>
    </li><!-- End Components Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-journal-text"></i><span>การขายยาง</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="index.php?page=report.php">
            <i class="bi bi-circle"></i><span>ขายยางเอง</span>
          </a>
        </li>
        <li>
          <a href="index.php?page=report.php">
            <i class="bi bi-circle"></i><span>ขายยางของลูกจ้าง</span>
          </a>
        </li>
      </ul>
    </li><!-- End Forms Nav -->
    
    <li class="nav-heading">ออกจากระบบ</li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="logout.php" id="logout-link">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>ออกจากระบบ</span>
      </a>
    </li><!-- End Logout Page Nav -->
    <!-- End Login Page Nav -->
  </ul>
  <!-- End Blank Page Nav -->

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