<?php

require('../api/db_connect.php');

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}


// Query ยอดขายเอง
$sqlSelfSales = "SELECT SUM(total_price) AS total_self_sales FROM rubber_purchases WHERE seller_id = ? AND employee_id IS NULL";
$stmtSelfSales = $conn->prepare($sqlSelfSales);
$stmtSelfSales->bind_param("i", $seller_id);
$stmtSelfSales->execute();
$resultSelfSales = $stmtSelfSales->get_result();
$totalSelfSales = $resultSelfSales->fetch_assoc()['total_self_sales'] ?? 0;

// Query ยอดขายของลูกจ้าง
$sqlEmployeeSales = "SELECT SUM(total_price) AS total_employee_sales FROM rubber_purchases WHERE seller_id = ? AND employee_id IS NOT NULL";
$stmtEmployeeSales = $conn->prepare($sqlEmployeeSales);
$stmtEmployeeSales->bind_param("i", $seller_id);
$stmtEmployeeSales->execute();
$resultEmployeeSales = $stmtEmployeeSales->get_result();
$totalEmployeeSales = $resultEmployeeSales->fetch_assoc()['total_employee_sales'] ?? 0;

// Query ยอดเงินรวม
$totalCombinedSales = $totalSelfSales + $totalEmployeeSales;

?>

<div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
        <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
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
                    <div class="card-body">
                        <h5 class="card-title">ยอดขายเอง <span>|</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo number_format($totalSelfSales, 2); ?> บาท</h6>
                                <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1">สวนตัวเอง</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">
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
                    <div class="card-body">
                        <h5 class="card-title">ยอดเงินร่วม <span>|</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo number_format($totalCombinedSales, 2); ?> บาท</h6>
                                <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1">ร่วม</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">
                <div class="card info-card customers-card">
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
                    <div class="card-body">
                        <h5 class="card-title">ยอดขายของลูกจ้าง <span>|</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo number_format($totalEmployeeSales, 2); ?> บาท</h6>
                                <span class="text-danger small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1">ลูกจ้าง</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Customers Card -->
        </div>
    </div><!-- End Left side columns -->

</div>
