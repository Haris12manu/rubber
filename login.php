<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - NiceAdmin Bootstrap Template</title>
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

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    <style>
        .section.dashboard {
            display: flex;
            justify-content: center;
            /* แนวนอน */
            align-items: flex-start;
            /* แนวตั้ง: ย้ายขึ้นจากกลาง */
            height: 100vh;
            /* ความสูงเต็มหน้าจอ */
        }

        .col-lg-4.col-md-6.d-flex {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            /* ปรับให้ขึ้นจากกลาง */
        }

        /* ป๊อปอัพ */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .popup.active {
            display: block;
        }

        .popup .close {
            cursor: pointer;
            float: right;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <?php include_once('header.php') ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include_once('navbar.php') ?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>เข้าสู่ระบบ</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                    <li class="breadcrumb-item active">เข้าใช้งาน</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                <div class="d-flex justify-content-center py-4">
                    <a href="index.html" class="logo d-flex align-items-center w-auto">
                        <img src="assets/img/logo12.png" alt="">
                        <span class="d-none d-lg-block">ระบบรับซื้อยางก้อนถ้วย</span>
                    </a>
                </div><!-- End Logo -->

                <div class="card mb-3">

                    <div class="card-body">

                        <div class="pt-4 pb-2">
                            <h5 class="card-title text-center pb-0 fs-4">กรอกชื่อและรหัสผ่านเพื่อเข้าสู่ระบบ</h5>
                        </div>

                        <form class="row g-3 needs-validation" action="process_login.php" method="POST" novalidate="">
                            <div class="col-12">
                                <label for="yourUsername" class="form-label">ชือ</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                    <input type="text" name="username" class="form-control" id="yourUsername" required="">
                                    <div class="invalid-feedback">โปรดป้อนชื่อผู้ใช้ของคุณ</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="yourPassword" class="form-label">รหัสผ่าน</label>
                                <input type="password" name="password" class="form-control" id="yourPassword" required="">
                                <div class="invalid-feedback">กรุณาป้อนรหัสผ่านของคุณ!!</div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">จดจำรหัส</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">เข้าสู่ระบบ</button>
                            </div>
                            <div class="col-12">
                                <p class="small mb-0">หากยังไม่มีบัญชี? <a href="registe.php">ลงทะเบียน</a></p>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="credits">
                    <!-- All the links in the footer should remain intact. -->
                    <!-- You can delete the links only if you purchased the pro version. -->
                    <!-- Licensing information: https://bootstrapmade.com/license/ -->
                    <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                </div>

            </div>
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

    <!-- ป๊อปอัพ JavaScript -->
    <script>
        function showPopup(message) {
            document.getElementById('popup-message').innerText = message;
            document.getElementById('popup').classList.add('active');
        }

        function closePopup() {
            document.getElementById('popup').classList.remove('active');
        }

        // รับข้อความจาก PHP ผ่าน JavaScript
        <?php
        if (isset($_GET['message'])) {
            echo "showPopup('" . htmlspecialchars($_GET['message']) . "');";
        }
        ?>
    </script>

    <!-- ป๊อปอัพ HTML -->
    <div id="popup" class="popup">
        <span class="close" onclick="closePopup()">&times;</span>
        <p id="popup-message"></p>
    </div>

</body>

</html>