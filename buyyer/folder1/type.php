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
