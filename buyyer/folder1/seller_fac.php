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
                <form id="searchForm">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="startDate">วันที่เริ่มต้น</label>
                            <input type="date" class="form-control" id="startDate" name="start">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="endDate">วันที่สิ้นสุด</label>
                            <input type="date" class="form-control" id="endDate" name="end">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </form>
                <div id="resultArea" class="mt-4">
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
            // ดึงข้อมูลเมื่อฟอร์มถูกส่ง
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                fetchSalesData($('#startDate').val(), $('#endDate').val());
            });

            // ดึงข้อมูลทั้งหมดเมื่อหน้าโหลด
            fetchSalesData();
        });

        function fetchSalesData(startDate = null, endDate = null) {
            $.ajax({
                url: 'fetch_sales_data.php',
                method: 'GET',
                data: {
                    start: startDate,
                    end: endDate
                },
                success: function(data) {
                    $('#resultArea').html(data); // แสดงผลข้อมูลใน resultArea
                },
            });
        }
    </script>
</body>

</html>
