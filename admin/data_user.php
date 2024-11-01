<header>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</header>
<?php
// เชื่อมต่อฐานข้อมูล
require '../api/db_connect.php';

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// query ข้อมูลจากฐานข้อมูล โดยดึงเฉพาะ user_type เป็น 'Purchasing Store'
$sql = "SELECT user_id, username, email, phone_number, user_type, status FROM users WHERE user_type = 'Purchasing Store'";
$result = $conn->query($sql);
?>

<head>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
    /* เพิ่ม CSS ที่นี่ */
    .table th {
      background-color: #ff8633; /* สีพื้นหลังหัวข้อ */
      color: white; /* สีข้อความหัวข้อ */
    }

    .table tr:nth-child(even) {
      background-color: #f2f2f2; /* สีพื้นหลังแถวคู่ */
    }

    .table tr:hover {
      background-color: #ddd; /* สีพื้นหลังเมื่อเอาเมาส์ไปวาง */
    }

    .table td, .table th {
      border: 1px solid #ddd; /* สีขอบ */
      padding: 8px; /* การเว้นระยะห่าง */
    }

    .table td a.btn-warning {
      background-color: #ff9800; /* สีพื้นหลังปุ่ม */
      color: white; /* สีข้อความปุ่ม */
    }

    .table td a.btn-danger {
      background-color: #dc3545; /* สีพื้นหลังปุ่มลบ */
      color: white; /* สีข้อความปุ่ม */
    }

    .table td a.btn-primary {
      background-color: #007bff; /* สีพื้นหลังปุ่มแก้ไข */
      color: white; /* สีข้อความปุ่ม */
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<div class="col-12">
  <div class="card">
    <div class="card-body">
      <div class="d-flex align-items-center">
        <h2 class="mb-4"><i class="fas fa-users-cog"></i> ข้อมูลร้านรับซื้อ</h2>
      </div>
      <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
        <div class="row">
          <div class="col-sm-12">
            <table id="datatable" class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" style="width: 100%;">
              <thead>
                <tr>
                  <th>ลำดับ</th>
                  <th>ชื่อ</th>
                  <th>อีเมล์</th>
                  <th>เบอร์โทร</th>
                  <th>ประเภทผู้ใช้</th>
                  <th>สถานะ</th>
                  <th>จัดการ</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row["user_id"] . "</td>";
                  echo "<td>" . $row["username"] . "</td>";
                  echo "<td>" . $row["email"] . "</td>";
                  echo "<td>" . $row["phone_number"] . "</td>";
                  echo "<td>ร้านรับซื้อ</td>";

                  if ($row["status"] == 'active') {
                    echo "<td style='color: green;'>อนุมัติ</td>";
                  } elseif ($row["status"] == 'pending approval') {
                    echo "<td style='color: red;'>ยังไม่อนุมัติ</td>";
                  } else {
                    echo "<td>" . $row["status"] . "</td>";
                  }

                  // เพิ่มปุ่มแก้ไข รีเซตรหัสผ่าน และลบ
                  echo "<td>
                          <a href='edit_user.php?user_id=" . $row['user_id'] . "' class='btn btn-primary'>แก้ไข</a>
                          <a href='delete_user.php?user_id=" . $row['user_id'] . "' class='btn btn-danger' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?\");'>ลบ</a>
                          <a href='reset_password.php?user_id=" . $row['user_id'] . "' class='btn btn-warning'>รีเซตรหัสผ่าน</a>
                        </td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#datatable').DataTable({
      "lengthChange": true, // เปิดใช้งานการเลือกจำนวนแถว
      "pageLength": 15 // จำนวนแถวเริ่มต้น
    });
  });
</script>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
