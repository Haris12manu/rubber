<?php
// เชื่อมต่อฐานข้อมูล
require '../api/db_connect.php';

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง rubber_types
$sql = "SELECT * FROM rubber_types";
$result = $conn->query($sql);
?>
<div class="container mt-5">
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">ประเภทยาง</h3>
            </div>
            <div class="card-toolbar">
                <a href="index.php?page=aad_type.php" class="btn btn-success" id="addTypeButton">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <circle fill="#ffffff" cx="12" cy="12" r="10"></circle>
                                <path d="M12 7v10M7 12h10" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg>
                    </span> เพิ่มประเภท
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="datatable datatable-default datatable-bordered datatable-loaded">
                <table class="datatable-bordered datatable-head-custom datatable-table" id="kt_datatable">
                    <thead class="datatable-head">
                        <tr class="datatable-row">
                            <th class="datatable-cell datatable-toggle-detail"><span></span></th>
                            <th data-field="ลำดับ" class="datatable-cell datatable-cell-sort"><span style="width: 155px;">ลำดับ</span></th>
                            <th data-field="ชื่อประเภท" class="datatable-cell datatable-cell-sort"><span style="width: 155px;">ชื่อประเภท</span></th>
                            <th data-field="รายละเอียด" class="datatable-cell datatable-cell-sort"><span style="width: 155px;">รายละเอียด</span></th>
                            <th data-field="แก้ไข" class="datatable-cell datatable-cell-sort"><span style="width: 155px;">แก้ไข</span></th>
                            <th data-field="ลบ" class="datatable-cell datatable-cell-sort"><span style="width: 155px;">ลบ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // แสดงข้อมูลในตาราง
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='datatable-row'>";
                                echo "<td class='datatable-cell'><span></span></td>";
                                echo "<td class='datatable-cell'><span style='width: 155px;'>" . $row["id"] . "</span></td>";
                                echo "<td class='datatable-cell'><span style='width: 155px;'>" . $row["type_name"] . "</span></td>";
                                echo "<td class='datatable-cell'><span style='width: 155px;'>" . $row["description"] . "</span></td>";
                                echo "<td class='datatable-cell'><span style='width: 155px;'><a href='edit_type.php?id=" . $row["id"] . "' class='btn btn-warning'>แก้ไข</a></span></td>";
                                echo "<td class='datatable-cell'><span style='width: 155px;'><a href='delete_type.php?id=" . $row["id"] . "' class='btn btn-danger'>ลบ</a></span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr class='datatable-row'><td colspan='6' class='datatable-cell'><span style='width: 100%;'>ไม่มีข้อมูล</span></td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" role="dialog" aria-labelledby="addTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTypeModalLabel">เพิ่มประเภท</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addTypeForm">
                    <div class="form-group">
                        <label for="type_name">ชื่อประเภท:</label>
                        <input type="text" class="form-control" id="type_name" name="type_name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">คำอธิบาย:</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#addTypeButton').click(function() {
            $('#addTypeModal').modal('show');
        });

        $('#addTypeForm').submit(function(event) {
            event.preventDefault();
            var typeName = $('#type_name').val();
            var description = $('#description').val();

            $.ajax({
                url: 'add_type.php',
                type: 'POST',
                data: {
                    type_name: typeName,
                    description: description
                },
                success: function(response) {
                    alert('เพิ่มประเภทเรียบร้อยแล้ว');
                    $('#addTypeModal').modal('hide');
                    // รีเฟรชตารางหรือเพิ่มแถวใหม่ในตารางที่นี่
                    location.reload(); // รีเฟรชหน้าเพื่อแสดงข้อมูลใหม่
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการเพิ่มประเภท');
                }
            });
        });
    });
</script>
<!-- Favicons -->
<link href="../assets/img/favicon.png" rel="icon">
<link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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