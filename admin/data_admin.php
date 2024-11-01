<?php
// เชื่อมต่อฐานข้อมูล
require '../api/db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง users เฉพาะผู้ที่เป็น Admin
$sql = "SELECT `user_id`, `username`, `email`, `phone_number` FROM `users` WHERE `user_type` = 'Admin'";
$result = $conn->query($sql);
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- เพิ่ม Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- เพิ่ม jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- เพิ่ม Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<!-- ส่วนแสดงตารางข้อมูล -->
<div class="card-body">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h2 class="mb-4"><i class="fas fa-users-cog"></i> ข้อมูลผู้ดูแลระบบ</h2>
        </div>
        <div class="mb-3">
            <button class="btn btn-success" data-toggle="modal" data-target="#addUserModal"><i class="fas fa-plus-circle"></i> เพิ่มข้อมูล</button>
        </div>
    </div>
    <!-- ตารางข้อมูล -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ชื่อ</th>
                <th>อีเมล์</th>
                <th>เบอร์โทร</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) : ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone_number']; ?></td>
                        <td>
                            <!-- ปุ่มแก้ไข -->
                            <button class="btn btn-warning" onclick="editUser(<?php echo $row['user_id']; ?>, '<?php echo $row['username']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['phone_number']; ?>')"><i class="fas fa-edit"></i> แก้ไข</button>
                            <!-- ปุ่มลบ -->
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['user_id']; ?>)"><i class="fas fa-trash-alt"></i> ลบ</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">ไม่พบข้อมูล</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal เพิ่มข้อมูล -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">เพิ่มข้อมูลผู้ใช้</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- ฟอร์มเพิ่มข้อมูล -->
                <form id="addUserForm">
                    <div class="form-group">
                        <label for="username">ชื่อ</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">อีเมล์</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">เบอร์โทร</label>
                        <input type="phone_number" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group text-end">
                        <button type="button" class="btn btn-secondary" onclick="cancelForm()">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ป๊อปอัพสำหรับการแก้ไขข้อมูล -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">แก้ไขข้อมูลผู้ใช้</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="form-group">
                        <label for="editUsername">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="editUsername" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">อีเมล์</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editPhoneNumber">เบอร์โทร</label>
                        <input type="text" class="form-control" id="editPhoneNumber" name="phone_number" required>
                    </div>
                    <div class="form-group text-end">
                        <button type="button" class="btn btn-secondary" onclick="cancelForm()">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // เมื่อส่งฟอร์ม
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault(); // ป้องกันการรีเฟรชหน้า

        // ส่งข้อมูลผ่าน AJAX
        $.ajax({
            url: 'add_user_process.php', // ไฟล์ PHP สำหรับบันทึกข้อมูล
            type: 'POST',
            data: $('#addUserForm').serialize(), // ส่งข้อมูลในฟอร์ม
            success: function(response) {
                // เมื่อบันทึกข้อมูลสำเร็จ
                if (response == 'success') {
                    Swal.fire({
                        title: 'เพิ่มข้อมูลสำเร็จ!',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload(); // รีเฟรชหน้าเพื่อแสดงข้อมูลใหม่
                    });
                } else {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด',
                        text: response,
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            }
        });
    });

    function confirmDelete(userId) {
        // ใช้ SweetAlert2 เพื่อแสดงป๊อปอัปยืนยันการลบ
        Swal.fire({
            title: 'คุณต้องการลบผู้ใช้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // นำทางไปยังหน้าลบผู้ใช้
                window.location.href = 'delete_user.php?user_id=' + userId;
            }
        });
    }

    function cancelForm() {
        // รีเฟรชหน้าเว็บเพื่อกลับสู่สถานะเริ่มต้น
        location.reload();
    }

    function editUser(userId, username, email, phoneNumber) {
        $('#editUserId').val(userId);
        $('#editUsername').val(username);
        $('#editEmail').val(email);
        $('#editPhoneNumber').val(phoneNumber);
        $('#editUserModal').modal('show');
    }

    $('#editUserForm').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: 'edit_user_process.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + response);
                }
            }
        });
    });

    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ

        // ส่งข้อมูลฟอร์มผ่าน AJAX
        var formData = new FormData(this);

        fetch('edit_user_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    alert('แก้ไขสำเร็จ');
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data);
                }
            })
            .catch(error => {
                alert('เกิดข้อผิดพลาด: ' + error);
            });
    });
</script>


<?php
$conn->close();
?>