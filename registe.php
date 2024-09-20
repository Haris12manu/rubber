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
            <h1>ลงทะเบียน</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                    <li class="breadcrumb-item active">ลงทะเบียน</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="card-body">
                <h5 class="card-title">Pills Tabs</h5>

                <!-- Pills Tabs -->
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">สำหรับผู้ขาย</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">สำหรับร้านรับซื้อ</button>
                    </li>
                </ul>
                <div class="tab-content pt-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="pt-4 pb-2">
                            <h5 class="card-title text-center pb-0 fs-4">ลงทะเบียนสำหรับผู้ขาย</h5>
                            <p class="text-center small">Enter your personal details to create account</p>
                        </div>

                        <!-- แก้ไขชื่อของฟิลด์ให้ตรงกับที่ใช้ใน PHP -->
                        <form class="row g-3 needs-validation" action="create_regissel.php" method="post" novalidate>
                            <div class="col-12">
                                <label for="yourName" class="form-label">ชื่อ</label>
                                <input type="text" name="username" class="form-control" id="yourName" required>
                                <div class="invalid-feedback">Please, enter your name!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourEmail" class="form-label">อีเมล์</label>
                                <input type="email" name="email" class="form-control" id="yourEmail" required>
                                <div class="invalid-feedback">Please enter a valid Email address!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourPhone" class="form-label">หมายเลขโทรศัพท์</label>
                                <input type="text" name="phone_number" class="form-control" id="yourPhone" required>
                                <div class="invalid-feedback">Please enter your phone number!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourPassword" class="form-label">รหัสผ่าน</label>
                                <input type="password" name="password" class="form-control" id="yourPassword" required>
                                <div class="invalid-feedback">Please enter your password!</div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                                    <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
                                    <div class="invalid-feedback">You must agree before submitting.</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">ลงทะเบียน</button>
                            </div>
                            <div class="col-12">
                                <p class="small mb-0">Already have an account? <a href="pages-login.html">Log in</a></p>
                            </div>
                        </form>

                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="row justify-content-center">
                            <!-- เนื้อหาสำหรับ profile-tab ที่นี่ -->
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">ลงทะเบียนสำหรับร้านรับซื้อ</h5>
                                    <p class="text-center small">Enter your personal details to create account</p>
                                </div>

                                <form class="row g-3 needs-validation" action="create_regisbuy.php" method="post" novalidate>
                                    <div class="col-12">
                                        <label for="yourName" class="form-label">ชื่อร้าน</label>
                                        <input type="text" name="username" class="form-control" id="yourName" required>
                                        <div class="invalid-feedback">Please, enter your name!</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourEmail" class="form-label">อีเมล์</label>
                                        <input type="email" name="email" class="form-control" id="yourEmail" required>
                                        <div class="invalid-feedback">Please enter a valid Email address!</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPhone" class="form-label">หมายเลขโทรศัพท์</label>
                                        <input type="text" name="phone_number" class="form-control" id="yourPhone" required>
                                        <div class="invalid-feedback">Please enter your phone number!</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">รหัสผ่าน</label>
                                        <input type="password" name="password" class="form-control" id="yourPassword" required>
                                        <div class="invalid-feedback">Please enter your password!</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                                            <label class="form-check-label" for="acceptTerms">ฉันยอมรับและยอมรับข้อกําหนดและเงื่อนไข <a href="#">ข้อกําหนดและเงื่อนไข</a></label>
                                            <div class="invalid-feedback">You must agree before submitting.</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">ลงทะเบียน</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">มีบัญชีอยู่แล้วใช่ไหม? <a href="login.php">เข้าสู่ระบบ</a></p>
                                    </div>
                                </form>



                            </div>
                        </div>
                    </div>
                </div><!-- End Pills Tabs -->
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
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

</body>

</html>