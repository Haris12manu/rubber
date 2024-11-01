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
        <a class="nav-link collapsed" href="login.php">
          <i class="bi bi-person"></i>
          <span>เข้าส่ระบบ</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="registe.php">
          <i class="bi bi-question-circle"></i>
          <span>ลงทะเบียน</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->
    </ul>

  </aside>

  <script>
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
  </script>