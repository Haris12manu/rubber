<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>สรุปรายงานยอดรับซื้อยางประจำปี <?php echo date('Y') + 543; ?></title>
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 id="reportTitle" class="card-label">สรุปรายงานยอดรับซื้อยางประจำปี <?php echo date('Y') + 543; ?></h3>
                </div>
            </div>
            <div class="card-body">
                <!-- ฟอร์มสำหรับการเลือกปี -->
                <form id="searchForm" method="GET">
                    <div class="form-group row">
                        <label for="year" class="col-sm-2 col-form-label">เลือกปี</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="year" name="year">
                                <?php
                                for ($year = date('Y'); $year >= 2000; $year--) {
                                    echo "<option value='$year'>" . htmlspecialchars($year) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                        </div>
                    </div>
                </form>

                <!-- พื้นที่สำหรับการแสดงผล -->
                <div id="resultArea">
                    <!-- เนื้อหาที่ดึงมาจาก AJAX จะแสดงตรงนี้ -->
                </div>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // ดึงข้อมูลปีล่าสุดโดยอัตโนมัติเมื่อหน้าโหลด
            fetchYearData('<?php echo date('Y'); ?>');

            $('#searchForm').submit(function(event) {
                event.preventDefault(); // ป้องกันการรีเฟรชหน้า

                var selectedYear = $('#year').val(); // รับค่าปีที่เลือก
                fetchYearData(selectedYear); // ดึงข้อมูลตามปีที่เลือก
            });

            function fetchYearData(year) {
                $.ajax({
                    url: 'fetch_data.php',
                    type: 'GET',
                    data: { year: year },
                    success: function(data) {
                        $('#resultArea').html(data); // แสดงผลข้อมูลใน resultArea
                        updateTitle(year); // อัปเดตชื่อหัวเรื่อง
                    },
                    error: function() {
                        $('#resultArea').html('<p class="text-center text-danger mt-4">เกิดข้อผิดพลาดในการดึงข้อมูล</p>');
                    }
                });
            }

            function updateTitle(year) {
                var yearInBuddhist = parseInt(year) + 543; // แปลงปีเป็น พ.ศ.
                $('#reportTitle').text('สรุปรายงานยอดรับซื้อยางประจำปี ' + yearInBuddhist);
            }
        });
    </script>
</body>

</html>
