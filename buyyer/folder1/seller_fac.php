<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายงานการขายยาง</title>
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
            <div class="card-header">
                <h3 class="card-label">รายงานการขายยาง</h3>
            </div>
            <div class="card-body">
                <!-- ฟอร์มสำหรับการเลือกช่วงวันที่ -->
                <form id="dateForm" class="form-inline mb-4">
                    <label for="start_date" class="mr-2">เลือกวันที่เริ่มต้น:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control mr-2" value="<?php echo date('Y-m-d'); ?>">

                    <label for="end_date" class="mr-2">เลือกวันที่สิ้นสุด:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control mr-2" value="<?php echo date('Y-m-d'); ?>">

                    <button type="submit" class="btn btn-primary">ดูรายงาน</button>
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
            // ดึงข้อมูลทั้งหมดเมื่อหน้าโหลด
            fetchSalesData();

            // ฟังก์ชันสำหรับการดึงข้อมูลตามวันที่ที่เลือก
            $('#dateForm').submit(function(event) {
                event.preventDefault(); // ป้องกันการรีเฟรชหน้า

                var startDate = $('#start_date').val(); // รับค่า start_date
                var endDate = $('#end_date').val(); // รับค่า end_date

                fetchSalesData(startDate, endDate); // ดึงข้อมูลตามวันที่เลือก
            });

            function fetchSalesData(startDate = null, endDate = null) {
                $.ajax({
                    url: 'fetch_sales_data.php',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(data) {
                        $('#resultArea').html(data); // แสดงผลข้อมูลใน resultArea
                    },
                    error: function() {
                        $('#resultArea').html('<p class="text-center text-danger mt-4">เกิดข้อผิดพลาดในการดึงข้อมูล</p>');
                    }
                });
            }
        });
    </script>
</body>

</html>
