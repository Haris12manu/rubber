<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
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
            <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
                <div class="d-flex flex-column-fluid">
                    <div class=" container ">
                        <style type="text/css">
                            .card1 {
                                width: 100%;
                                border: none;
                                border-radius: 10px;
                                background-color: #fff;

                            }

                            .stats {
                                background: #f2f5f8 !important;
                                color: #000 !important
                            }

                            .articles {
                                font-size: 14px;
                                color: #a1aab9
                            }

                            .number1 {
                                font-weight: 500
                            }

                            .followers {
                                font-size: 14px;
                                color: #a1aab9
                            }

                            .number2 {
                                font-weight: 500
                            }

                            .rating {
                                font-size: 14px;
                                color: #a1aab9
                            }

                            .number3 {
                                font-weight: 500
                            }
                        </style>
                        <div class="card1 p-8 ">
                        <div class="card1 p-4 bg-white border rounded shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="image me-3">
                            <img src="../assets/img/apple-touch-icon.png" class="rounded" width="155">
                        </div>
                        <div class="w-100">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h4 class="mb-0">รหัสผู้ขาย <?php echo htmlspecialchars($seller['seller_id']); ?></h4>
                                    <h4 class="text-primary">ชื่อ|<?php echo htmlspecialchars($seller['name']); ?></h4>
                                </div>
                                <div class="text-end">
                                    <span class="d-block">ยอดเงินคงเหลือ (บาท)</span>
                                    <h2 class="mb-0 text-success">0</h2>
                                </div>
                            </div>
                            <div class="p-3 bg-primary text-white rounded d-flex flex-wrap justify-content-between">
                                <div class="d-flex flex-column me-3 mb-2">
                                    <span class="fw-bold">รหัสบัญชีผู้ขาย</span>
                                    <span><?php echo htmlspecialchars($seller['seller_id']); ?></span>
                                </div>
                                <div class="d-flex flex-column me-3 mb-2">
                                    <span class="fw-bold">เบอร์โทร</span>
                                    <span><?php echo htmlspecialchars($seller['phone']); ?></span>
                                </div>
                                <div class="d-flex flex-column me-3 mb-2">
                                    <span class="fw-bold">อีเมล</span>
                                    <span><?php echo htmlspecialchars($seller['email']); ?></span>
                                </div>
                                <div class="d-flex flex-column mb-2">
                                    <span class="fw-bold">วันที่สร้าง</span>
                                    <span><?php echo htmlspecialchars($seller['created_at']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>

                        <form class="form" id="kt_form" action="https://rebank.yru.ac.th/admin/withdraw/store" method="POST" enctype="multipart/form-data">
                            <div class="card card-custom">
                                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                                    <div class="card-title">
                                        <h3 class="card-label">
                                            ถอนเงิน
                                        </h3>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <input type="hidden" name="_token" value="taORf7qFTqwyWePgfiXtrMvheLwJiR6IAEgzgJ57">
                                    <div class="row">
                                        <div class="col-xl-2"></div>
                                        <div class="col-xl-8">
                                            <div class="my-5">
                                                <input type="hidden" value="100001" name="account_id">
                                                <input type="hidden" value="1" name="admin_id">

                                                <div class="form-group row">
                                                    <label class="col-2">จำนวนเงิน</label>
                                                    <div class="col-8">
                                                        <input class="form-control" name="withdraw" type="text" placeholder="กรอกจำนวนเงิน" value="">
                                                    </div>
                                                    <label class="col-1">บาท</label>
                                                </div>
                                                <hr>
                                                <div class="form-group row">
                                                    <label class="col-4"></label>
                                                    <div class="col-5" style="float: center">
                                                        

                                                        <button type="submit" id="save_form" class="btn btn-primary font-weight-bolder">
                                                            <i class="ki ki-check icon-sm"></i>
                                                            ถอน
                                                        </button>
                                                        <a href="/admin/deposit/search?search=100001" class="btn btn-danger"> ยกเลิก</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

</body>

</html>
<?php include('scrip.php')
?>