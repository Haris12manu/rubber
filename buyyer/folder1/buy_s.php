<?php
include_once('../api/db_connect.php');

// กำหนดค่าเริ่มต้นให้กับตัวแปร $search_query
$search_query = '';

// ตรวจสอบว่ามีการส่งค่าค้นหาหรือไม่ และเก็บค่าลงในตัวแปร $search_query
if (isset($_POST['search_query'])) {
    $search_query = $_POST['search_query'];
}
?>
<header>
    <style>
         section {
        font-family: 'Prompt', sans-serif;
    }

    </style>
</header>
<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ค้นหาผู้ขายและพนักงาน</h5>

                    <!-- Search Form -->
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="search_query">ค้นหา</label>
                            <input type="text" class="form-control" id="search_query" name="search_query" placeholder="Search by name" value="<?php echo htmlspecialchars($search_query, ENT_QUOTES); ?>">
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">ค้นหารายชื่อ</button>
                    </form>

                    <?php if ($search_query !== ''): ?>
                        <!-- Table with stripped rows -->
                        <table class="table datatable mt-4">
                            <thead>
                                <tr>
                                    <th>ชื่อ</th>
                                    <th>เบอร์</th>
                                    <th>ประเภท</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // ปรับคำสั่ง SQL ให้ค้นหาทั้งผู้ขายและลูกจ้าง
                                $sql = "SELECT s.seller_id, s.seller_name AS name, 'N/A' AS phone_number, 'Seller' AS role 
                                        FROM sellers s 
                                        WHERE s.seller_name LIKE ?
                                        UNION ALL
                                        SELECT e.employee_id, e.name AS name, COALESCE(e.phone_number, 'N/A') AS phone_number, CONCAT('Employee of ', s.seller_name) AS role 
                                        FROM employees e 
                                        JOIN sellers s ON e.seller_id = s.seller_id 
                                        WHERE e.name LIKE ?";

                                $stmt = $conn->prepare($sql);
                                $search_term = '%' . $search_query . '%';
                                $stmt->bind_param("ss", $search_term, $search_term);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $id = isset($row['seller_id']) ? $row['seller_id'] : $row['employee_id'];
                                        $name = htmlspecialchars($row['name']);
                                        $phone_number = htmlspecialchars($row['phone_number']);
                                        $role = htmlspecialchars($row['role']);

                                        echo '<tr>';
                                        echo '<td>' . $name . '</td>';
                                        echo '<td>' . $phone_number . '</td>';
                                        echo '<td>' . $role . '</td>';
                                        echo '<td>
                                        <form method="POST" action="index.php?page=folder1/rabseer.php" style="display: inline-block;">
                                            <input type="hidden" name="seller_id" value="' . htmlspecialchars($id) . '">
                                            <input type="hidden" name="name" value="' . htmlspecialchars($name) . '">
                                            <input type="hidden" name="role" value="' . htmlspecialchars($role) . '">
                                            <button type="submit" class="btn btn-outline-primary">รับซื้อ</button>
                                        </form>
                                        <form method="POST" action="index.php?page=folder1/sales_history.php" style="display: inline-block;">
                                            <input type="hidden" name="seller_id" value="' . htmlspecialchars($id) . '">
                                            <input type="hidden" name="role" value="' . htmlspecialchars($role) . '">
                                        </form>
                                    </td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4">No data found</td></tr>';
                                }

                                $stmt->close();
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</section>
<?php include('scrip.php'); ?>
