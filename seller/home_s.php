<?php
require('fetch_totals.php');
require('fetch_to_1.php');
?>
<section class="section dashboard">
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
                            <h5 class="card-title">ยอดเงินวันนี้</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $daily_total; ?>.บาท</h6>
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
                            <h5 class="card-title">ยอดสัปดาห์นี้</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?php echo $weekly_total; ?>.บาท</h6>
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
                                <li>
                                    <form id="filter-form">
                                        <label for="year" class="form-label">เลือกปี:</label>
                                        <select name="year" id="year" class="form-select">
                                            <?php for ($i = 2020; $i <= date('Y'); $i++) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" class="dropdown-item">ค้นหา</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">ยอดเงินร่วม <span id="selected-year"></span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 id="yearly-total">0 บาท</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Customers Card -->
            <div>
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo !isset($_POST['year']) ? 'active' : ''; ?>" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">รายเดือน</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo isset($_POST['year']) ? 'active' : ''; ?>" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">รายปี</button>
                    </li>
                </ul>
            </div>

            <div class="tab-content pt-2" id="myTabContent">
                <!-- Tab รายเดือน -->
                <div class="tab-pane fade <?php echo !isset($_POST['year']) ? 'show active' : ''; ?>" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="col-lg-12 mb-">
                        <div class="card">
                            <?php
                            include('fetch_to.php')
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Tab รายปี -->
                <div class="tab-pane fade <?php echo isset($_POST['year']) ? 'show active' : ''; ?>" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="card">
                        <h1 class="card-title">ข้อมูลรายปี</h1>

                        <!-- ฟอร์มสำหรับเลือกปี -->
                        <form method="POST" action="" class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="year" class="col-form-label">เลือกปี:</label>
                            </div>
                            <div class="col-auto">
                                <select name="year" id="year" class="form-select">
                                    <?php
                                    // สร้างตัวเลือกปี
                                    for ($i = 2020; $i <= 2030; $i++) {
                                        $selected = (isset($_POST['year']) && $_POST['year'] == $i) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">ค้นหา</button>
                            </div>
                        </form>


                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>
            </div>

            <?php
            $year = isset($_POST['year']) ? intval($_POST['year']) : date("Y");

            // คำสั่ง SQL เพื่อดึงข้อมูลตามปีที่เลือก
            $sql = "
        SELECT MONTH(rp.purchase_date) AS month, SUM(rp.total_price) AS total_price, SUM(rp.quantity) AS total_quantity
        FROM rubber_purchases rp
        LEFT JOIN employees e ON rp.employee_id = e.employee_id
        WHERE (rp.seller_id = ? OR e.seller_id = ?) AND YEAR(rp.purchase_date) = ?
        GROUP BY MONTH(rp.purchase_date)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $seller_id, $seller_id, $year);
            $stmt->execute();
            $result = $stmt->get_result();

            // เตรียมตัวแปรสำหรับข้อมูลแต่ละเดือน
            $quantities_current_year = array_fill(0, 12, null);
            $prices_current_year = array_fill(0, 12, null);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $month_index = $row['month'] - 1;
                    $quantities_current_year[$month_index] = $row['total_quantity'];
                    $prices_current_year[$month_index] = $row['total_price'];
                }
            }

            $filtered_months = json_encode($months);
            $filtered_quantities = json_encode($quantities_current_year);
            $filtered_prices = json_encode($prices_current_year);
            ?>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                const yearlyData = {
                    labels: <?php echo $filtered_months; ?>,
                    datasets: [{
                            label: 'น้ำหนักยาง (กิโลกรัม)',
                            data: <?php echo $filtered_quantities; ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            fill: false,
                            tension: 0.1 // ความนุ่มนวลของเส้น
                        },
                        {
                            label: 'ราคาทั้งหมด (บาท)',
                            data: <?php echo $filtered_prices; ?>,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            fill: false,
                            tension: 0.1 // ความนุ่มนวลของเส้น
                        }
                    ]
                };

                const yearlyConfig = {
                    type: 'line', // เปลี่ยนจาก 'bar' เป็น 'line'
                    data: yearlyData,
                    options: {
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'เดือน',
                                    font: {
                                        size: 18
                                    }
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'ข้อมูล (กิโลกรัม / บาท)',
                                    font: {
                                        size: 18
                                    }
                                },
                                beginAtZero: true
                            }
                        }
                    }
                };

                const yearlyChart = new Chart(
                    document.getElementById('yearlyChart'),
                    yearlyConfig
                );

                $(document).ready(function() {
                    $('#filter-form').on('submit', function(e) {
                        e.preventDefault();

                        var year = $('#year').val();

                        $.ajax({
                            url: 'fetch_totals.php',
                            type: 'POST',
                            data: {
                                year: year
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.error) {
                                    alert(response.error);
                                } else {
                                    $('#selected-year').text(`ปี ${year}`);
                                    $('#yearly-total').text(`${response.yearly_total} บาท`);
                                }
                            },
                            error: function() {
                                alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
                            }
                        });
                    });
                });
            </script>


        </div>
        <!-- Add Chart.js Line Chart -->

    </div>
    </div><!-- End Left side columns -->

    <!-- Right side columns -->
    <!-- End Right side columns -->

    </div>
</section>