<?php include_once('select.php')
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Rubber</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/dMq3etKASEn8FsQGr79M.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    .product-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .product-image-section,
    .product-info-section {
      flex: 1;
      margin: 10px;
    }

    .product-image-size {
      display: flex;
      justify-content: space-around;
      margin-bottom: 10px;
    }

    .product-image-size-item {
      padding: 5px 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      cursor: pointer;
    }

    .product-image-size-item.active {
      background-color: #007bff;
      color: white;
    }

    .product-image img {
      border-radius: 10px;
    }

    .section-title h3 {
      margin-bottom: 20px;
    }

    .product-card {
      flex: 1;
      margin: 10px;
    }

    .card-style {
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .text-red {
      color: red;
    }

    .text-xs-center {
      text-align: center;
    }

    .p-t-32 {
      padding-top: 32px;
    }

    .date-box {
      display: inline-block;
      padding: 5px 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .card-overlay:before {
      content: "";
      position: absolute;
      height: 70%;
      width: 100%;
      bottom: 0;
      background-image: -webkit-gradient(linear, left top, left bottom, from(transparent), to(rgb(0, 0, 0)));
      background-image: linear-gradient(180deg, transparent, rgb(0, 0, 0));
      z-index: 1;
    }

    .card-overlay .card-img-overlay {
      z-index: 2;
    }

    .card-overlay .card-image-full {
      max-height: 400px;
      object-fit: cover;
    }

    .card-overlay .card-text {
      -webkit-transition: all 0.2s ease-in-out;
      transition: all 0.2s ease-in-out;
    }

    .card-overlay:hover .card-text {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <?php include_once('header.php')
  ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include_once('navbar.php')
  ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-md-6">
              <a href="registe.php" style="text-decoration: none; color: inherit;">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">สำหรับผู้ขาย</h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-person"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-md-6">
            <a href="registe.php" style="text-decoration: none; color: inherit;">
              <div class="card info-card revenue-card">

                <div class="card-body">
                  <h5 class="card-title">สำหรับร้านรับซ์้อ</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-person"></i>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Reports -->
            <div class="col-12">
              <hr>
              <div class="card">
              </div>
            </div><!-- End Reports -->

          </div>
        </div><!-- End Left side columns -->
      </div>
      <div class="row">
        <?php include_once('news.php');
        ?>
      </div>
      <div class="product-info-section" style="border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 15px; background-color: white;">
        <div class="section-title">
          <h3><strong>ยางที่รับซื้อ</strong></h3>
          <div class="col-lg-12">
            <div class="row">

              <!-- Sales Card -->
              <div class="col-md-6">
                <div class="card info-card sales-card">
                  <div class="card-body d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h5 class="card-title" style="font-size: 1.5rem;">ยางแห้ง</h5>
                    </div>
                  </div>
                </div>
              </div><!-- End Sales Card -->

              <div class="col-md-6">
                <div class="card info-card revenue-card">
                  <div class="card-body d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h5 class="card-title" style="font-size: 1.5rem;">ยางเปียก</h5>
                    </div>
                  </div>
                </div>
              </div><!-- End Revenue Card -->
              <!-- End Revenue Card -->
              <!-- End Revenue Card -->
              <!-- End Revenue Card -->

              <!-- Reports -->
              <div class="col-12">
                <hr>
                <div class="card">
                </div>
              </div><!-- End Reports -->

            </div>
          </div>
        </div>

        <div class="row">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filter</h6>
              </li>
              <li><a class="dropdown-item" href="#">Today</a></li>
              <li><a class="dropdown-item" href="#">This Month</a></li>
              <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
          </div>
        </div>
      </div>


      </div>
      <div class="row">
        <div class="container mt-12">
          <div class="row">
            <div class="product-container">
              <div class="product-info-section" style="border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 15px; background-color: white;">
                <h3><strong>รายชื่อร้านรับซื้อ</strong></h3>
                <div class="card">
                  <h5 class="card-header">ข้อมูลราคายาง</h5>
                  <div class="table-responsive text-nowrap">
                    <table class="table">
                      <thead class="table-dark">
                        <tr>
                          <th>ชื่อร้าน</th>
                          <th>ราคายางแห้ง</th>
                          <th>ราคายางเปียก</th>
                        </tr>
                      </thead>
                      <tbody class="table-border-bottom-0">
                        <?php if (!empty($store_data)): ?>
                          <?php foreach ($store_data as $store_name => $prices): ?>
                            <tr>
                              <td><strong><?php echo htmlspecialchars($store_name); ?></strong></td>
                              <td><?php echo $prices['กลาง']; ?></td>
                              <td><?php echo $prices['ยางแห้ง']; ?></td>
                              <td><?php echo $prices['ยางเปียก']; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="4">ไม่มีข้อมูล</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>

    </section>

  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    // Function to format the date as required (e.g., วันเสาร์ที่ 10 สิงหาคม 2567)
    function formatDate(date) {
      const days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
      const months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
        'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
      ];

      const dayOfWeek = days[date.getDay()];
      const day = date.getDate();
      const month = months[date.getMonth()];
      const year = date.getFullYear() + 543; // Convert to Buddhist Era year

      return `วัน${dayOfWeek}ที่ ${day} ${month} ${year}`;
    }

    function updateDate() {
      const now = new Date();
      const formattedDate = formatDate(now);
      document.getElementById('date-box').innerText = formattedDate;
    }

    // Update date on page load
    updateDate();

    // Set a timer to update the date at midnight
    const now = new Date();
    const millisTillMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0, 0) - now;

    setTimeout(function() {
      updateDate();
      setInterval(updateDate, 24 * 60 * 60 * 1000); // Update every 24 hours
    }, millisTillMidnight);
  </script>

</body>

</html>