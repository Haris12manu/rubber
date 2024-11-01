<?php
// เริ่ม session และนำเข้าการเชื่อมต่อฐานข้อมูล
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// นำเข้าฟังก์ชันดึงข้อมูลจากฐานข้อมูล
require('fetch_data_rep.php');

?>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;700&display=swap');

    /* การ์ด */
    body {
        font-family: 'Prompt', sans-serif;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .card-title {
        font-weight: 500;
        font-size: 1.5rem;
    }

    .display-6 {
        font-weight: 700;
        font-size: 2rem;
    }

    /* ปุ่มไอคอน */
    .icon {
        font-size: 1.75rem;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* ปรับฟอนต์และขนาดในการ์ดแต่ละอัน */
    .bg-dark .card-title {
        font-size: 1.25rem;
    }

    .bg-primary .display-6,
    .bg-success .display-6,
    .bg-danger .display-6,
    .bg-info .display-6,
    .bg-warning .display-6 {
        font-size: 1.75rem;
    }

    /* ปรับฟอนต์ให้กับไทม์ */
    #current-date,
    #current-time {
        font-size: 2rem;
        font-weight: 500;
    }

    /* ปรับการ์ดให้เท่ากัน */
    .row-equal-height .col-lg-6 {
        display: flex;
    }
</style>
<div class="d-flex">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">แดชบอร์ด</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">รายงาน</button>
        </li>
    </ul>
</div>
<hr>
<div class="tab-content pt-2" id="myTabContent">
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

        <section class="section dashboard">
            <div class="container-fluid">
                <div class="row gy-4">
                    <!-- Date and Time Card -->

                    <div class="col-6">
                        <div class="card text-center bg-dark text-white border-0 shadow-lg rounded-lg">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-light">วันที่และเวลา</h5>
                                <h6 id="current-date" class="display-6 fw-bold text-light"></h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card text-center bg-dark text-white border-0 shadow-lg rounded-lg">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-light"></h5>
                                <h6 id="price-container" class="display-6 fw-bold text-light"></h6>
                                <p>อ้างอิงจาก <a href="https://www.thainr.com/th/?detail=pr-local" target="_blank">สมาคมยางพาราไทย</a></p>
                            </div>
                        </div>
                    </div>

                    <!-- End Date and Time Card -->

                    <!-- Daily Summary Section -->

                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <span class="badge rounded-pill bg-secondary fs-4 p-3">แดชบอร์ด | รายวัน</span>
                    </div>



                    <!-- Sales Card -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-lg rounded-lg bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-light">จำนวนผู้ขายยางวันนี้</h5>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-light text-primary rounded-circle d-flex justify-content-center align-items-center">
                                        <i class="bi bi-person fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 display-6 text-light"><?= htmlspecialchars($total_sellers) ?></h6>
                                        <span class="text-light">คน</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Card -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-lg rounded-lg bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-light">จำนวนน้ำหนักยางวันนี้ (กิโลกรัม)</h5>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-light text-success rounded-circle d-flex justify-content-center align-items-center">
                                        <i class="bi bi-balance fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 display-6 text-light"><?= htmlspecialchars($total_quantity) ?></h6>
                                        <span class="text-light">กก</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Card -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card border-0 shadow-lg rounded-lg bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-light">จำนวนเงินที่จ่ายไปวันนี้ (บาท)</h5>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-light text-danger rounded-circle d-flex justify-content-center align-items-center">
                                        <i class="bi bi-currency-dollar fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 display-6 text-light"><?= htmlspecialchars($total_revenue) ?></h6>
                                        <span class="text-light">บาท</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Summary Section -->
                    <div class="col-12">
                        <span class="badge rounded-pill bg-secondary fs-4 p-3">แดชบอร์ด | รายเดือน</span>
                    </div>


                    <!-- Inventory Card -->
                    <div class="col-lg- col-md-6 row-equal-height">
                        <div class="card border-0 shadow-lg rounded-lg bg-info text-white">
                            <div class="card-body">
                                <?php
                                // สร้างอาร์เรย์ชื่อเดือนในภาษาไทย
                                $months_thai = [
                                    1 => 'มกราคม',
                                    2 => 'กุมภาพันธ์',
                                    3 => 'มีนาคม',
                                    4 => 'เมษายน',
                                    5 => 'พฤษภาคม',
                                    6 => 'มิถุนายน',
                                    7 => 'กรกฎาคม',
                                    8 => 'สิงหาคม',
                                    9 => 'กันยายน',
                                    10 => 'ตุลาคม',
                                    11 => 'พฤศจิกายน',
                                    12 => 'ธันวาคม'
                                ];

                                // ดึงชื่อเดือนและปีในรูปแบบ พ.ศ.
                                $current_month = date('n'); // เดือนปัจจุบัน (1-12)
                                $current_year = date('Y') + 543; // ปีปัจจุบัน + 543 เพื่อให้เป็น พ.ศ.
                                $month_name = $months_thai[$current_month]; // ดึงชื่อเดือนจากอาร์เรย์

                                // คำนวณเดือนก่อนหน้าและปี
                                $previous_month = ($current_month == 1) ? 12 : $current_month - 1; // ถ้าเดือนปัจจุบันคือ มกราคม ให้เดือนก่อนหน้าคือ ธันวาคม
                                $previous_year = ($current_month == 1) ? $current_year - 1 : $current_year; // ปีถ้าหากเดือนปัจจุบันคือ มกราคม
                                $previous_month_name = $months_thai[$previous_month]; // ดึงชื่อเดือนก่อนหน้าจากอาร์เรย์
                                ?>

                                <h5 class="card-title text-uppercase text-light">จำนวนยางในคลังเดือน |<?= $month_name . ' ' . $current_year ?>|</h5>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-light text-info rounded-circle d-flex justify-content-center align-items-center">
                                        <i class="bi bi-box-seam fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <?php
                                        // ตรวจสอบว่าตัวแปรมีการกำหนดหรือไม่ หากไม่ให้กำหนดค่าเริ่มต้น
                                        $inventory_data = $inventory_data ?? [];
                                        $total_inventory = $total_inventory ?? 0;

                                        foreach ($inventory_data as $rubber_type => $quantity):
                                            $rubber_type_name = ($rubber_type === 'Dry') ? 'ยางแห้ง' : (($rubber_type === 'Wet') ? 'ยางเปียก' : htmlspecialchars($rubber_type));
                                            $formatted_quantity = number_format($quantity, 2); // จัดรูปแบบจำนวนให้มีทศนิยม 2 ตำแหน่ง
                                        ?>
                                            <h6 class="mb-0 display-6 text-light"><?= $rubber_type_name . ': ' . ($quantity == 0 ? '0.00' : $formatted_quantity) . ' กก' ?></h6>
                                        <?php endforeach; ?>
                                        <h6 class="mb-0 display-6 text-light">ยางรวม: <?= number_format($total_inventory, 2) ?> กก</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-light">ยางคงเหลือของเดือน </h5>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-light text-info rounded-circle d-flex justify-content-center align-items-center">
                                        <i class="bi bi-box-seam fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <?php
                                        // ตรวจสอบว่าตัวแปรสำหรับเดือนก่อนหน้ามีการกำหนดหรือไม่
                                        $previous_inventory_data = $previous_inventory_data ?? []; // สมมุติให้ $previous_inventory_data มีข้อมูลยางเดือนก่อนหน้า
                                        $total_previous_inventory = $total_previous_inventory ?? 0; // สมมุติให้ $total_previous_inventory มีข้อมูลยอดรวมยางเดือนก่อนหน้า

                                        foreach ($previous_inventory_data as $rubber_type => $quantity):
                                            $rubber_type_name = ($rubber_type === 'Dry') ? 'ยางแห้ง' : (($rubber_type === 'Wet') ? 'ยางเปียก' : htmlspecialchars($rubber_type));
                                            $formatted_quantity = number_format($quantity, 2); // จัดรูปแบบจำนวนให้มีทศนิยม 2 ตำแหน่ง
                                        ?>
                                            <h6 class="mb-0 display-6 text-light"><?= $rubber_type_name . ': ' . ($quantity == 0 ? '0.00' : $formatted_quantity) . ' กก' ?></h6>
                                        <?php endforeach; ?>
                                        <h6 class="mb-0 display-6 text-light">จำนวน: <?= number_format($total_previous_inventory, 2) ?> กก</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profit-Loss Card -->
                    <div class="col-lg-6 col-md-6 row-equal-height">
                        <div class="card border-0 shadow-lg rounded-lg bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-light">
                                    กำไร-ขาดทุน (เดือน|<?= htmlspecialchars($profit_loss_data['month'] ?? '') ?>-<?= htmlspecialchars($profit_loss_data['year'] ?? '') ?>)
                                </h5>
                                <h6 class="mb-0 display-6 text-light">
                                    ขายครั้งที่: <?= htmlspecialchars($profit_loss_data['record_rank'] ?? '') ?>
                                </h6>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-light text-success rounded-circle d-flex justify-content-center align-items-center">
                                        <i class="bi bi-graph-up-arrow fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 display-6 text-light">
                                            ยอดซื้อ: <?= htmlspecialchars($profit_loss_data['total_purchases'] ?? 0) ?> บาท
                                        </h6>
                                        <h6 class="mb-0 display-6 text-light">
                                            ยอดขาย: <?= htmlspecialchars($profit_loss_data['total_sales'] ?? 0) ?> บาท
                                        </h6>
                                        <h6 class="mb-0 display-6 text-light">
                                            กำไร/ขาดทุน: <?= htmlspecialchars($profit_loss_data['profit_loss'] ?? 0) ?> บาท
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Rubber In-Out Card -->
                    <div class="col-lg-6 col-md-6 row-equal-height">
                        <div class="card border-0 shadow-lg rounded-lg bg-warning text-dark">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-dark">ยางเข้า-ยางออก</h5>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-light text-warning rounded-circle d-flex justify-content-center align-items-center">
                                        <i class="bi bi-arrow-left-right fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 display-6 text-dark">ยางที่ขาย | <?= htmlspecialchars($total_in_quantity) ?> กก</h6>
                                        <?php if ($total_out_quantity > 0): ?>
                                            <h6 class="mb-0 display-6 text-dark">ยางออก | <?= htmlspecialchars($total_out_quantity) ?> กก</h6>
                                        <?php else: ?>
                                            <h6 class="mb-0 display-6 text-dark">ยางคงเหลือ: <?= number_format($total_inventory, 2) ?> กก</h6>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Sales to Factory Card -->
                    <!-- Sales to Factory Card -->
                    <div class="col-lg-6 col-md-6 row-equal-height">
                        <div class="card border-0 shadow-lg rounded-lg bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title text-uppercase text-light">ขายยางให้กับโรงงาน</h5>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-lg bg-light text-info rounded-circle d-flex justify-content-center align-items-center">
                                        <i class="bi bi-truck fs-3"></i>
                                    </div>
                                    <div class="ms-3">
                                        <?php foreach ($sales_data as $index => $sale): ?>
                                            <h6 class="mb-0 display-6 text-light">ขายครั้งที่ <?= htmlspecialchars($sale['sale_rank']) ?>: <?= htmlspecialchars($sale['factory_name']) ?> | <?= htmlspecialchars($sale['total_quantity']) ?> กก, <?= htmlspecialchars($sale['total_revenue']) ?> บาท (<?= htmlspecialchars(date('d M Y', strtotime($sale['sale_date']))) ?>)</h6>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- ส่วนของ Dashboard -->
            <div class="col-12" id="dashboardSection">
            </div>
        </section>
    </div>

    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <?php
                    include('Linechart.php')
                    ?>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <?php
                    include_once('carth2.php')
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    fetch('fetch_price.php')
        .then(response => response.json())
        .then(data => {
            const priceContainer = document.getElementById('price-container');
            // ล้างเนื้อหาก่อนหน้า
            priceContainer.innerHTML = '';
            // วนลูปแสดงข้อมูลราคาและวันที่อัปเดต
            data.forEach(item => {
                const p = document.createElement('p');
                p.textContent = 'ราคายางตลาด: ' + item.price;
                priceContainer.appendChild(p);

                const updateDate = document.createElement('p');
                updateDate.textContent = 'อัปเดทเมื่อ: ' + item.updated_at;
                updateDate.style.fontSize = '0.9rem'; // ปรับขนาดตัวอักษรสำหรับวันที่
                updateDate.style.color = '#ffffff'; // สีตัวอักษรสำหรับวันที่
                priceContainer.appendChild(updateDate);
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });

    function updateDateTime() {
        const now = new Date();
        const options = {
            dateStyle: 'full',
            timeStyle: 'long'
        };
        const dateTimeString = now.toLocaleString('th-TH', options);
        const [datePart, timePart] = dateTimeString.split('เวลา');

        document.getElementById('current-date').textContent = datePart.trim();
        document.getElementById('current-time').textContent = timePart.trim();
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>