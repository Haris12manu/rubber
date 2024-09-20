<?php
// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require('../api/db_connect.php');

// Query สำหรับ "ตารางขายยางของตัวเอง"
$sql1 = "SELECT 
            rp.purchase_id, 
            rp.store_id, 
            ps.store_name,  
            rp.seller_id, 
            s.seller_name,  
            rp.rubber_type, 
            rp.quantity, 
            rp.price_per_unit, 
            rp.total_price, 
            rp.purchase_date, 
            rp.sale_status
         FROM 
            rubber_purchases rp
         JOIN 
            purchasing_stores ps ON rp.store_id = ps.store_id
         LEFT JOIN 
            sellers s ON rp.seller_id = s.seller_id  
         WHERE 
            rp.seller_id = ? AND rp.employee_id IS NULL";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $seller_id); // ผูก seller_id กับ query
$stmt1->execute();
$result1 = $stmt1->get_result();

// Query สำหรับ "ตารางขายยางของลูกจ้าง"
$sql2 = "SELECT 
            rp.purchase_id, 
            rp.store_id, 
            ps.store_name,  
            rp.employee_id, 
            e.name AS employee_name,  
            rp.rubber_type, 
            rp.quantity, 
            rp.price_per_unit, 
            rp.total_price, 
            rp.purchase_date, 
            rp.sale_status
         FROM 
            rubber_purchases rp
         JOIN 
            purchasing_stores ps ON rp.store_id = ps.store_id
         LEFT JOIN 
            employees e ON rp.employee_id = e.employee_id  
         WHERE 
            rp.seller_id = ? AND rp.employee_id IS NOT NULL";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $seller_id); // ผูก seller_id กับ query
$stmt2->execute();
$result2 = $stmt2->get_result();
?>

<!-- ส่วนที่เหลือของโค้ด HTML และการแสดงผลยังคงเหมือนเดิม -->


<section class="section">
    <?php include_once('total.php')
    ?>
    <hr>
    <!-- ตัวเลือกในการเลือกตาราง -->
    <div class="row mb-3">
        <div class="col-lg-12 text-center">
            <button id="showSelfSalesBtn" class="btn btn-primary">ตารางขายยางของตัวเอง</button>
            <button id="showEmployeeSalesBtn" class="btn btn-secondary">ตารางขายยางของลูกจ้าง</button>
        </div>
    </div>

    <!-- ตารางขายยางของตัวเอง -->
    <div class="row" id="selfSalesSection">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ตารางขายยางของตัวเอง</h5>
                    <div class="table-responsive">
                        <table id="selfSalesTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ผู้ขาย</th>
                                    <th>ร้านรับซื้อ</th>
                                    <th>ยางที่ขาย</th>
                                    <th>น้ำหนัก</th>
                                    <th>ราคา</th>
                                    <th>ยอด</th>
                                    <th>วันที่ขาย</th>
                                    <th>ใบเสร็จ</th> <!-- เพิ่มคอลัมน์ใหม่ -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result1->num_rows > 0) {
                                    while ($row = $result1->fetch_assoc()) {
                                        $rubberTypeDisplay = $row["rubber_type"] === 'Dry' ? 'ยางแห้ง' : ($row["rubber_type"] === 'Wet' ? 'ยางเปียก' : htmlspecialchars($row["rubber_type"]));

                                        echo "<tr>
                                                <td>" . htmlspecialchars($row["seller_name"]) . "</td>  
                                                <td>" . htmlspecialchars($row["store_name"]) . "</td>
                                                <td>" . htmlspecialchars($rubberTypeDisplay) . "</td>  
                                                <td>" . htmlspecialchars($row["quantity"]) . "</td>
                                                <td>" . htmlspecialchars($row["price_per_unit"]) . "</td>
                                                <td>" . htmlspecialchars($row["total_price"]) . "</td>
                                                <td>" . htmlspecialchars($row["purchase_date"]) . "</td>
                                                <td>
                                                <button class='btn btn-info' onclick='showReceipt(" . $row['purchase_id'] . ")'>ดูใบเสร็จ</button>
                                                </td> <!-- คอลัมน์ใบเสร็จเปล่า -->
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No data found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ตารางขายยางของลูกจ้าง -->
    <div class="row" id="employeeSalesSection" style="display: none;">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ตารางขายยางของลูกจ้าง</h5>
                    <div class="table-responsive">
                        <table id="employeeSalesTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ผู้ขาย</th>
                                    <th>ร้านรับซื้อ</th>
                                    <th>ยางที่ขาย</th>
                                    <th>น้ำหนัก</th>
                                    <th>ราคา</th>
                                    <th>ยอด</th>
                                    <th>วันที่ขาย</th>
                                    <th>ใบเสร็จ</th> <!-- เพิ่มคอลัมน์ใหม่ -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result2->num_rows > 0) {
                                    while ($row = $result2->fetch_assoc()) {
                                        $rubberTypeDisplay = $row["rubber_type"] === 'Dry' ? 'ยางแห้ง' : ($row["rubber_type"] === 'Wet' ? 'ยางเปียก' : htmlspecialchars($row["rubber_type"]));

                                        echo "<tr>
                                                <td>" . htmlspecialchars($row["employee_name"]) . "</td>  
                                                <td>" . htmlspecialchars($row["store_name"]) . "</td>
                                                <td>" . htmlspecialchars($rubberTypeDisplay) . "</td>  
                                                <td>" . htmlspecialchars($row["quantity"]) . "</td>
                                                <td>" . htmlspecialchars($row["price_per_unit"]) . "</td>
                                                <td>" . htmlspecialchars($row["total_price"]) . "</td>
                                                <td>" . htmlspecialchars($row["purchase_date"]) . "</td>
                                                <td>
                                                <button class='btn btn-info' onclick='showReceipt(" . $row['purchase_id'] . ")'>ดูใบเสร็จ</button>
                                                </td> <!-- คอลัมน์ใบเสร็จเปล่า -->
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No data found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#selfSalesTable').DataTable();
        $('#employeeSalesTable').DataTable();

        // แสดงตารางขายยางของตัวเอง
        $('#showSelfSalesBtn').on('click', function() {
            $('#selfSalesSection').show();
            $('#employeeSalesSection').hide();
            $('#showSelfSalesBtn').addClass('btn-primary').removeClass('btn-secondary');
            $('#showEmployeeSalesBtn').addClass('btn-secondary').removeClass('btn-primary');
        });

        // แสดงตารางขายยางของลูกจ้าง
        $('#showEmployeeSalesBtn').on('click', function() {
            $('#selfSalesSection').hide();
            $('#employeeSalesSection').show();
            $('#showEmployeeSalesBtn').addClass('btn-primary').removeClass('btn-secondary');
            $('#showSelfSalesBtn').addClass('btn-secondary').removeClass('btn-primary');
        });
    });

    function showReceipt(purchaseId) {
        // สร้าง URL สำหรับเรียกใบเสร็จ
        const url = `baiset.php?purchase_id=${purchaseId}`;

        // เปิดป๊อปอัพ
        const popup = window.open(url, 'ใบเสร็จ', 'width=800,height=600');

        // ตรวจสอบว่าป๊อปอัพถูกบล็อคหรือไม่
        if (!popup) {
            alert('กรุณาอนุญาตให้ป๊อปอัพในเบราว์เซอร์ของคุณ');
        }
    }
</script>