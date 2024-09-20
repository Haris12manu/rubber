<?php

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // ถ้าไม่ได้ล็อกอินให้กลับไปที่หน้า login
    exit();
}

// เชื่อมต่อกับฐานข้อมูล
include_once('../api/db_connect.php');

// ดึง user_id จาก session
$user_id = $_SESSION['user_id'];

// ดึงข้อมูลพนักงานจากฐานข้อมูลโดยใช้ seller_id
$sql = "SELECT employee_id, seller_id, name, phone_number, commission_percentage, responsibility_area, created_at, updated_at 
        FROM employees 
        WHERE seller_id = (SELECT seller_id FROM sellers WHERE user_id = ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}
?>

<div class="container">
    <!-- ปุ่มสำหรับแสดงฟอร์มการเพิ่มลูกจ้าง -->
    <button id="showFormButton" class="btn btn-success mt-4">เพิ่มลูกจ้าง</button>
    
    <!-- ส่วนของการเพิ่มพนักงาน (ซ่อนฟอร์มตั้งแต่แรก) -->
    <div id="addEmployeeForm" class="card card-custom mt-4" style="display: none;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-label">เพิ่มลูกจ้าง</h3>
        </div>
        <div class="card-body">
            <form id="employeeForm" action="save_employee.php" method="POST">
                <div class="form-group">
                    <label for="name">ชื่อ-สกุล</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="กรอกชื่อ-สกุล" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">เบอร์โทร</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="กรอกเบอร์โทร" required>
                </div>
                <div class="form-group">
                <label for="commission_percentage">เปอร์เซ็นแบ่งส่วน</label>
                <input type="text" class="form-control" id="commission_percentage" name="commission_percentage" placeholder="กรอกเปอร์เซ็นแบ่งส่วน" required>
            </div>
            <div class="form-group">
                <label for="responsibility_area">เขตที่รับผิดชอบ</label>
                <input type="text" class="form-control" id="responsibility_area" name="responsibility_area" placeholder="กรอกเขตที่รับผิดชอบ" required>
            </div>
                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                    <button type="button" id="hideFormButton" class="btn btn-danger">ยกเลิก</button>
                </div>
                
            </form>
        </div>
    </div>

    <!-- ส่วนของการแสดงตารางข้อมูลพนักงาน -->
    <div class="card">
        <h5 class="card-header">ตารางลูกจ้าง</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ชื่อ-นามสกุล</th>
                        <th>เบอร์โทร</th>
                        <th>เปอร์เซ็นแบ่งส่วน</th>
                        <th>เขตที่รับผิดชอบ</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['phone_number'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['commission_percentage'] ?? ''); ?>%</td>
                        <td><?php echo htmlspecialchars($row['responsibility_area'] ?? ''); ?></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item edit-button" href="#" data-id="<?php echo $row['employee_id']; ?>" data-name="<?php echo htmlspecialchars($row['name'] ?? ''); ?>" data-phone_number="<?php echo htmlspecialchars($row['phone_number'] ?? ''); ?>" data-commission_percentage="<?php echo htmlspecialchars($row['commission_percentage'] ?? ''); ?>" data-responsibility_area="<?php echo htmlspecialchars($row['responsibility_area'] ?? ''); ?>"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                    <a class="dropdown-item" href="delete_employee.php?id=<?php echo $row['employee_id']; ?>"><i class="bx bx-trash me-2"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal สำหรับแก้ไขข้อมูล -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูลพนักงาน</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update_employee.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="employee_id" id="edit_employee_id">
                    <div class="form-group">
                        <label for="edit_name">ชื่อ-สกุล</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone_number">เบอร์โทร</label>
                        <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_commission_percentage">เปอร์เซ็นแบ่งส่วน</label>
                        <input type="text" class="form-control" id="edit_commission_percentage" name="commission_percentage" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_responsibility_area">เขตที่รับผิดชอบ</label>
                        <input type="text" class="form-control" id="edit_responsibility_area" name="responsibility_area" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var showFormButton = document.getElementById('showFormButton');
    var hideFormButton = document.getElementById('hideFormButton');
    var addEmployeeForm = document.getElementById('addEmployeeForm');
    var employeeForm = document.getElementById('employeeForm');

    // แสดงฟอร์มเมื่อกดปุ่ม "เพิ่มลูกจ้าง"
    showFormButton.addEventListener('click', function() {
        addEmployeeForm.style.display = 'block';
        showFormButton.style.display = 'none';
    });

    // ซ่อนฟอร์มเมื่อกดปุ่ม "ยกเลิก"
    hideFormButton.addEventListener('click', function() {
        addEmployeeForm.style.display = 'none';
        showFormButton.style.display = 'block';
    });

    // ซ่อนฟอร์มหลังจากบันทึกข้อมูล
    employeeForm.addEventListener('submit', function() {
        addEmployeeForm.style.display = 'none';
        showFormButton.style.display = 'block';
    });

    // Code for the edit modal
    var editButtons = document.querySelectorAll('.edit-button');
    
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var employee_id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name');
            var phone_number = this.getAttribute('data-phone_number');
            var commission_percentage = this.getAttribute('data-commission_percentage');
            var responsibility_area = this.getAttribute('data-responsibility_area');

            document.getElementById('edit_employee_id').value = employee_id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_phone_number').value = phone_number;
            document.getElementById('edit_commission_percentage').value = commission_percentage;
            document.getElementById('edit_responsibility_area').value = responsibility_area;

            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });
    });
});
</script>



<?php
$stmt->close();
$conn->close();
?>
