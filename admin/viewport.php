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

// query ข้อมูลจากฐานข้อมูล โดยดึงเฉพาะ user_type เป็น 'Seller' และ 'Purchasing Store'
$sql = "SELECT user_id, username, email, phone_number, user_type, status FROM users WHERE user_type IN ('Seller', 'Purchasing Store')";
$result = $conn->query($sql);
?>

<div class="col-12">
  <div class="card">
    <div class="card-body">
      <div class="d-flex align-items-center">
        <h2 class="mb-4"><i class="fas fa-users-cog"></i> ข้อมูลผู้ดูแลระบบ</h2>
      </div>
      <hr>
      <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
        <div class="row">
          <div class="col-sm-12">
            <table id="datatable" class="table table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" style="width: 100%;">
              <thead>
                <tr>
                  <th>ลำดัล</th>
                  <th>ชื่อ</th>
                  <th>อีเมล์</th>
                  <th>เบอร์โทร</th>
                  <th>ประเภทผู้ใช้</th>
                  <th>สถานะ</th>
                  <th>เครื่องมือ</th>
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

                  $user_type_text = ($row["user_type"] == 'Seller') ? 'ผู้ขาย' : 'ร้านรับซื้อ';
                  echo "<td>" . $user_type_text . "</td>";

                  if ($row["status"] == 'active') {
                    echo "<td style='color: green;'>อนุมัติ</td>";
                  } elseif ($row["status"] == 'pending approval') {
                    echo "<td style='color: red;'>ยังไม่อนุมัติ</td>";
                  } else {
                    echo "<td>" . $row["status"] . "</td>";
                  }

                  // เพิ่มปุ่มอนุมัติและไม่อนุมัติ
                  echo "<td>
                                        <a href='update_status.php?user_id=" . $row['user_id'] . "&status=active' class='btn btn-success'>อนุมัติ</a>
                                        <a href='update_status.php?user_id=" . $row['user_id'] . "&status=pending approval' class='btn btn-danger'>ไม่อนุมัติ</a>
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