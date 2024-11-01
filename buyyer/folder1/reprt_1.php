<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานยอดซื้อ/ขาย</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
        }

        .table thead th {
            border-bottom: 2px solid #000;
        }

        .card-custom {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 30px;
        }

        .input-icon i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">รายงานยอดซื้อ/ขาย</h3>
                </div>
            </div>
            <div class="card-body">

                <!-- ฟอร์มเลือกเดือนและปี -->
                <form id="searchForm" method="GET" class="form-inline mb-4">
                    <div class="form-group">
                        <label for="start_month">เดือน:</label>
                        <select id="start_month" name="start_month" class="form-control mx-sm-2">
                            <?php
                            $months = [
                                '01' => 'มกราคม',
                                '02' => 'กุมภาพันธ์',
                                '03' => 'มีนาคม',
                                '04' => 'เมษายน',
                                '05' => 'พฤษภาคม',
                                '06' => 'มิถุนายน',
                                '07' => 'กรกฎาคม',
                                '08' => 'สิงหาคม',
                                '09' => 'กันยายน',
                                '10' => 'ตุลาคม',
                                '11' => 'พฤศจิกายน',
                                '12' => 'ธันวาคม'
                            ];

                            foreach ($months as $month_value => $month_name) {
                                echo "<option value='$month_value'" . ($month_value == $start_month ? ' selected' : '') . ">$month_name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group mx-sm-3">
                        <label for="start_year">ปี:</label>
                        <select id="start_year" name="start_year" class="form-control mx-sm-2">
                            <?php for ($y = date('Y'); $y >= 2000; $y--) {
                                $thai_year = $y + 543; // แปลงปี ค.ศ. เป็น พ.ศ.
                                echo "<option value='$y'" . ($y == $start_year ? ' selected' : '') . ">$thai_year</option>";
                            } ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </form>


                <!-- ตารางแสดงผล -->
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr style="background-color: pink">
                            <th style="text-align: center;">ลำดับ</th>
                            <th style="text-align: center;">รายการ</th>
                            <th style="text-align: center;">ยอดขาย (บาท)</th>
                            <th style="text-align: center;">ยอดซื้อ (บาท)</th>
                            <th style="text-align: center;">กำไรขาดทุน (บาท)</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        <?php
                        // ตัวแปรสำหรับการนับลำดับการขายในแต่ละเดือน
                        $index = 1;
                        $current_month = "";

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $record_month = $row['sale_month'];

                                // รีเซ็ตลำดับการขายถ้าเป็นเดือนใหม่
                                if ($current_month !== $record_month) {
                                    $current_month = $record_month;
                                    $index = 1;
                                }

                                // ตรวจสอบประเภทของยางและเปลี่ยนข้อความให้แสดงในตาราง
                                $rubber_types_display = '';
                                if (strpos($row['rubber_types'], 'Mixed (Dry, Wet)') !== false) {
                                    $rubber_types_display = 'ยางรวม';
                                } elseif (strpos($row['rubber_types'], 'Dry') !== false) {
                                    $rubber_types_display = 'ยางแห้ง';
                                } elseif (strpos($row['rubber_types'], 'Wet') !== false) {
                                    $rubber_types_display = 'ยางเปียก';
                                }

                                // แสดงข้อมูลในตาราง
                                echo "<tr>";
                                echo "<td style='text-align: center;'>{$index}</td>";
                                echo "<td>" . htmlspecialchars($rubber_types_display) . "</td>";
                                echo "<td style='text-align: center;'>" . htmlspecialchars($row['total_sales']) . "</td>";
                                echo "<td style='text-align: center;'>" . htmlspecialchars($row['total_purchases']) . "</td>";
                                echo "<td style='text-align: center;'>" . htmlspecialchars($row['profit_loss']) . "</td>";
                                echo "</tr>";

                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align: center;'>ไม่พบข้อมูล</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // ส่งคำขอ AJAX เพื่อแสดงข้อมูลของเดือนปัจจุบันโดยอัตโนมัติ
            var start_month = new Date().getMonth() + 1; // เดือนปัจจุบัน (0-11) + 1
            var start_year = new Date().getFullYear(); // ปีปัจจุบัน

            // ฟอร์แมตเดือนเป็น 2 หลัก
            start_month = ('0' + start_month).slice(-2);
            const currentMonth = new Date().getMonth() + 1; // เดือนปัจจุบัน (0-11) + 1
            const monthSelect = document.getElementById('start_month');
            monthSelect.value = currentMonth < 10 ? '0' + currentMonth : currentMonth; // ฟอร์แมตเดือนให้เป็น 2 หลัก

            $.ajax({
                url: 'report.php?ajax=1&start_month=' + start_month + '&start_year=' + start_year,
                method: 'GET',
                success: function(data) {
                    var reports = JSON.parse(data);
                    var tbody = $('#reportTableBody');
                    tbody.empty(); // ล้างตารางก่อน

                    if (reports.length > 0) {
                        reports.forEach(function(report, index) {
                            tbody.append(`<tr>
                        <td style='text-align: center;'>${index + 1}</td>
                        <td>${report.rubber_types}</td>
                        <td style='text-align: center;'>${report.total_sales}</td>
                        <td style='text-align: center;'>${report.total_purchases}</td>
                        <td style='text-align: center;'>${report.profit_loss}</td>
                    </tr>`);
                        });
                    } else {
                        tbody.append("<tr><td colspan='5' style='text-align: center;'>ไม่พบข้อมูล</td></tr>");
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการค้นหา โปรดลองใหม่อีกครั้ง');
                }
            });

            $('#searchForm').submit(function(e) {
                e.preventDefault(); // ป้องกันการส่งฟอร์มตามปกติ

                // รับค่าจากฟอร์ม
                var start_month = $('#start_month').val();
                var start_year = $('#start_year').val();

                // ส่งคำขอ AJAX
                $.ajax({
                    url: 'report.php?ajax=1&start_month=' + start_month + '&start_year=' + start_year,
                    method: 'GET',
                    success: function(data) {
                        var reports = JSON.parse(data);
                        var tbody = $('#reportTableBody');
                        tbody.empty(); // ล้างตารางก่อน

                        if (reports.length > 0) {
                            reports.forEach(function(report, index) {
                                var rubber_types_display = '';
                                if (report.rubber_types.includes('Mixed (Dry, Wet)')) {
                                    rubber_types_display = 'ยางรวม';
                                } else if (report.rubber_types.includes('Dry')) {
                                    rubber_types_display = 'ยางแห้ง';
                                } else if (report.rubber_types.includes('Wet')) {
                                    rubber_types_display = 'ยางเปียก';
                                } else {
                                    rubber_types_display = 'ไม่ทราบประเภท';
                                }

                                tbody.append(`<tr>
                            <td style='text-align: center;'>${index + 1}</td>
                            <td>${rubber_types_display}</td>
                            <td style='text-align: center;'>${report.total_sales}</td>
                            <td style='text-align: center;'>${report.total_purchases}</td>
                            <td style='text-align: center;'>${report.profit_loss}</td>
                        </tr>`);
                            });
                        } else {
                            tbody.append("<tr><td colspan='5' style='text-align: center;'>ไม่พบข้อมูล</td></tr>");
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดในการค้นหา โปรดลองใหม่อีกครั้ง');
                    }
                });
            });
        });
    </script>
</body>

</html>