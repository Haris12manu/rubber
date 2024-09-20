<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Table with Modals</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    .status-pending {
      color: #ffc107;
    }

    .status-active {
      color: #28a745;
    }
  </style>
</head>

<body>
  <div class="container mt-4">
    <div class="card">
      <h5 class="card-title">User Table</h5>
      <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
          <tr>
            <th>ชื่อ</th>
            <th>อีเมล์</th>
            <th>เบอร์โทร</th>
            <th>ประเภทผู้ใช้</th>
            <th>สถานะ</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          include_once('../api/fetch_data.php'); // รวมไฟล์ที่ดึงข้อมูล

          // แสดงข้อมูลในตาราง
          // ตรวจสอบและใช้ htmlspecialchars() กับค่าเริ่มต้นหากเป็น null
          foreach ($users as $user) {
            $user_id = htmlspecialchars($user['id'] ?? '');
            $username = htmlspecialchars($user['username'] ?? '');
            $email = htmlspecialchars($user['email'] ?? '');
            $phone_number = htmlspecialchars($user['phone_number'] ?? '');
            $user_type = htmlspecialchars($user['user_type'] ?? '');
            $status = htmlspecialchars($user['status'] ?? '');

            echo '<tr data-user-id="' . $user_id . '" data-username="' . $username . '" data-email="' . $email . '" data-phone="' . $phone_number . '" data-user-type="' . $user_type . '" data-status="' . $status . '">';
            echo '<td>' . $username . '</td>';
            echo '<td>' . $email . '</td>';
            echo '<td>' . $phone_number . '</td>';
            echo '<td>' . $user_type . '</td>';
            echo '<td>';
            if ($status == 'pending approval') {
              echo '<span class="status-pending">ยังไม่อนุมัติ</span>';
            } elseif ($status == 'active') {
              echo '<span class="status-active">อนุมัติแล้ว</span>';
            } else {
              echo '<span class="status-pending">สถานะไม่ทราบ</span>';
            }
            echo '</td>';
            echo '<td>
      <button type="button" class="btn btn-link btn-sm btn-rounded view-btn" data-toggle="modal" data-target="#viewModal"><i class="fas fa-eye"></i></button>
      <button type="button" class="btn btn-link btn-sm btn-rounded edit-btn" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></button>
      <button type="button" class="btn btn-link btn-sm btn-rounded delete-btn" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash-alt"></i></button>
      <button type="button" class="btn btn-link btn-sm btn-rounded reset-btn" data-toggle="modal" data-target="#resetModal"><i class="fas fa-key"></i></button>
  </td>';
            echo '</tr>';
          }

          ?>
        </tbody>
      </table>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewModalLabel">View Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <p>ชื่อ: <span id="modal_username"></span></p>
            <p>อีเมล์: <span id="modal_email"></span></p>
            <p>เบอร์โทร: <span id="modal_phone_number"></span></p>
            <p>ประเภทผู้ใช้: <span id="modal_user_type"></span></p>
            <p>สถานะ: <span id="modal_status"></span></p>
          </div>
          <div class="modal-footer">
            <form id="updateStatusForm">
              <input type="hidden" name="user_id" id="modal_user_id" value="">
              <button type="button" class="btn btn-success" id="approveBtn">อนุมัติ</button>
              <button type="button" class="btn btn-danger" id="disapproveBtn">ไม่อนุมัติ</button>
            </form>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Content for editing details -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this item?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id="deleteBtn">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="resetModalLabel">Reset Password</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Content for resetting password -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="resetPasswordBtn">Reset Password</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      // View modal
      $('.view-btn').on('click', function() {
        var row = $(this).closest('tr');
        $('#modal_user_id').val(row.data('user-id'));
        $('#modal_username').text(row.data('username'));
        $('#modal_email').text(row.data('email'));
        $('#modal_phone_number').text(row.data('phone'));
        $('#modal_user_type').text(row.data('user-type'));
        $('#modal_status').text(row.data('status'));
      });

      // Approve and Disapprove buttons
      $('#approveBtn').click(function() {
        updateStatus('อนุมัติแล้ว');
      });

      $('#disapproveBtn').click(function() {
        updateStatus('ยังไม่อนุมัติ');
      });

      function updateStatus(status) {
        var user_id = $('#modal_user_id').val();
        $.ajax({
          url: '../api/update_status.php',
          type: 'POST',
          data: {
            user_id: user_id,
            status: status
          },
          success: function(response) {
            alert('สถานะอัปเดตเรียบร้อย');
            location.reload();
          },
          error: function(xhr, status, error) {
            alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ: ' + error);
          }
        });
      }

      // Handle delete button click
      $('#deleteBtn').click(function() {
        var user_id = $(this).closest('tr').data('user-id');
        $.ajax({
          url: 'delete_user.php',
          type: 'POST',
          data: {
            user_id: user_id
          },
          success: function(response) {
            alert('ลบข้อมูลเรียบร้อย');
            location.reload();
          },
          error: function(xhr, status, error) {
            alert('เกิดข้อผิดพลาดในการลบข้อมูล: ' + error);
          }
        });
      });

      // Handle reset password button click
      $('#resetPasswordBtn').click(function() {
        var user_id = $(this).closest('tr').data('user-id');
        $.ajax({
          url: 'reset_password.php',
          type: 'POST',
          data: {
            user_id: user_id
          },
          success: function(response) {
            alert('รีเซ็ตรหัสผ่านเรียบร้อย');
            location.reload();
          },
          error: function(xhr, status, error) {
            alert('เกิดข้อผิดพลาดในการรีเซ็ตรหัสผ่าน: ' + error);
          }
        });
      });
    });
  </script>
</body>

</html>