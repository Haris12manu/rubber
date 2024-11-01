<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link" href="index.php" id="link-home">
        <i class="bi bi-grid"></i>
        <span>หน้าหลัก</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-heading">จัดการต่างๆ</li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#" id="link-forms">
        <i class="bi bi-journal-text"></i><span>อนุมัติ</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="index.php?page=viewport.php">
            <i class="bi bi-circle"></i><span>อนุมัติบัญชี้ของผู้ใช้</span>
          </a>
        </li>
      </ul>
    </li><!-- End Forms Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#" id="link-tables">
        <i class="bi bi-layout-text-window-reverse"></i><span>การจัดการ</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="index.php?page=news.php">
            <i class="bi bi-circle"></i><span>ข่าวประชาสัมพันธ์</span>
          </a>
        </li>
        <li>
          <a href="index.php?page=price_edit.php">
            <i class="bi bi-circle"></i><span>กำหนดราคายาง</span>
          </a>
        </li>
        <li>
          <a href="index.php?page=type.php">
            <i class="bi bi-circle"></i><span>เพิ่มประเภทยาง</span>
          </a>
        </li>
      </ul>
    </li><!-- End Tables Nav -->

    <li class="nav-heading">จัดการข้อมูลผู้ใช้</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php?page=data_admin.php" id="link-data-admin">
        <i class="bi bi-person"></i>
        <span>ข้อมูลผู้ดูแลระบบ</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php?page=data_user.php" id="link-data-user">
        <i class="bi bi-person"></i>
        <span>ข้อมูลร้านรับซื้อ</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php?page=data_seller.php" id="link-data-seller">
        <i class="bi bi-person"></i>
        <span>ข้อมูลผู้ขาย</span>
      </a>
    </li>

    <!-- End Profile Page Nav -->

    <li class="nav-heading">ออกจากระบบ</li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="../logout.php" id="logout-link">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>ออกจากระบบ</span>
      </a>
    </li>
  </ul>
</aside>

<script>
  // ฟังก์ชันนี้จะใช้เพื่อเปลี่ยนคลาสของเมนู
  function setActiveLink() {
    const links = document.querySelectorAll('.nav-link'); // หาลิงก์ทั้งหมด
    const currentPage = window.location.href; // หาหน้าปัจจุบัน

    links.forEach(link => {
      const linkPage = link.getAttribute('href'); // หาหน้าในลิงก์

      // ถ้าลิงก์ตรงกับหน้าปัจจุบัน
      if (currentPage.includes(linkPage)) {
        link.classList.remove('collapsed'); // ลบคลาส collapsed
      } else {
        link.classList.add('collapsed'); // เพิ่มคลาส collapsed
      }
    });
  }

  // เรียกใช้ฟังก์ชันเมื่อโหลดหน้า
  window.onload = setActiveLink;

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