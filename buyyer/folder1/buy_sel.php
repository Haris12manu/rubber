<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานยอดการฝาก</title>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">รายงานยอดการขาย</h3>
                </div>
            </div>
            <div class="card-body">


                <!-- ฟอร์มสำหรับเลือกช่วงวันที่ -->
                <form id="dateForm" class="form-inline mb-4">
                    <label for="start_date" class="mr-2">เลือกวันที่เริ่มต้น:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control mr-2" value="<?php echo date('Y-m-d'); ?>">

                    <label for="end_date" class="mr-2">เลือกวันที่สิ้นสุด:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control mr-2" value="<?php echo date('Y-m-d'); ?>">

                    <button type="submit" class="btn btn-primary">ดูรายงาน</button>
                </form>

                <table class="table table-bordered mt-4">
                    <thead>
                        <tr style="background-color: pink">
                            <th style="text-align: center;">ลำดับ</th>
                            <th style="text-align: center;">ชื่อคนขาย</th>
                            <th style="text-align: center;">ชื่อนายจ้าง</th>
                            <th style="text-align: center;">น้ำหนักยาง</th>
                            <th style="text-align: center;">ปรเภทยาง</th>
                            <th style="text-align: center;">ราคาที่รับซื้อ</th>
                            <th style="text-align: center;">ราคาร่วม</th>
                            <th style="text-align: center;">วัน-เดือน-ปี</th>
                            <th style="text-align: center;">ใบเสร็จ</th>
                        </tr>
                    </thead>
                    <tbody id="reportTable">
                        <!-- ข้อมูลจะถูกแสดงที่นี่ -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // ฟังก์ชันเพื่อดึงข้อมูลใหม่ตามช่วงวันที่
            function loadReport(startDate, endDate) {
                $.ajax({
                    url: 'fetch_report.php', // ไฟล์ PHP ที่จะดึงข้อมูล
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        $('#reportTable').html(response); // อัปเดตตารางด้วยข้อมูลใหม่
                    },
                    error: function() {
                        alert('ไม่สามารถดึงข้อมูลได้');
                    }
                });
            }

            // โหลดข้อมูลครั้งแรก
            loadReport($('#start_date').val(), $('#end_date').val());

            // เมื่อส่งฟอร์มให้ดึงข้อมูลใหม่ตามช่วงวันที่
            $('#dateForm').on('submit', function(e) {
                e.preventDefault(); // ป้องกันการรีเฟรชหน้า
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                loadReport(startDate, endDate); // ดึงข้อมูลใหม่ตามวันที่เลือก
            });
        });
    </script>
</body>

</html>