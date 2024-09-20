<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../api/db_connect.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึง store_id ที่เกี่ยวข้องกับ user_id จากฐานข้อมูล
$stmt = $conn->prepare("SELECT store_id FROM purchasing_stores WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (empty($store_id)) {
    die("ไม่พบข้อมูลร้านค้า");
}

// ดึงข้อมูลยางในคลังที่ยังไม่ได้ขาย
$sql_inventory = "
    SELECT 
        rubber_type,
        GREATEST(0, SUM(quantity)) AS current_inventory
    FROM 
        rubber_purchases
    WHERE 
        store_id = ? AND sale_status = '0' -- เลือกเฉพาะยางที่ยังไม่ถูกขายออกไป
    GROUP BY 
        rubber_type;
";

$stmt_inventory = $conn->prepare($sql_inventory);

if ($stmt_inventory === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

$stmt_inventory->bind_param("i", $store_id);

if (!$stmt_inventory->execute()) {
    die("Error executing SQL statement: " . $stmt_inventory->error);
}

$result_inventory = $stmt_inventory->get_result();
$inventory_data = [
    'Dry' => 0,
    'Wet' => 0,
];
$total_inventory = 0;

while ($row = $result_inventory->fetch_assoc()) {
    $rubber_type = $row['rubber_type'];
    $current_quantity = $row['current_inventory'] ?? 0;

    // จัดเก็บข้อมูลยางในคลังตามประเภท
    if (isset($inventory_data[$rubber_type])) {
        $inventory_data[$rubber_type] = $current_quantity;
    }

    // คำนวณจำนวนยางรวม
    $total_inventory += $current_quantity;
}

$stmt_inventory->close();

$conn->close();
?>

<div class="row">
    <div class="col-lg-4">

        <div class="row">
            <div class="col-lg-12">

                <!-- Card ที่จะแสดงข้อมูลจาก PHP -->
                <div class="card info-card customers-card">

                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">จำนวนยางในคลัง <span>| ยางร่วม</span></h5>

                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="ps-3">
                              
                                <h6>ยางรวม: <?= htmlspecialchars($total_inventory) ?> กก</h6>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- จบส่วนแสดงข้อมูล -->

            </div>
            <div></div>

        </div>


    </div>
    <div class="col-lg-4">

        <div class="row">
            <div class="col-lg-12">

                <!-- Card ที่จะแสดงข้อมูลจาก PHP -->
                <div class="card info-card customers-card">

                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">จำนวนยางในคลัง <span>| ยางแห้ง</span></h5>

                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="ps-3">
                                <h6>ยางแห้ง: <?= htmlspecialchars($inventory_data['Dry'] ?? 0) ?> กก</h6>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- จบส่วนแสดงข้อมูล -->

            </div>
            <div></div>

        </div>


    </div>
    <div class="col-lg-4">

        <div class="row">
            <div class="col-lg-12">

                <!-- Card ที่จะแสดงข้อมูลจาก PHP -->
                <div class="card info-card customers-card">

                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">จำนวนยางในคลัง <span>| ยางเปียก</span></h5>

                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="ps-3"> 
                                <h6>ยางเปียก: <?= htmlspecialchars($inventory_data['Wet'] ?? 0) ?> กก</h6>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- จบส่วนแสดงข้อมูล -->

            </div>
            <div></div>

        </div>


    </div>
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">ขายยางให้กับโรงงาน</h5>

                <!-- General Form Elements -->
                <form method="POST" action="process_rubber_sale.php">
                    <input type="hidden" name="store_id" value="<?= htmlspecialchars($store_id) ?>">

                    <div class="row mb-3">
                        <label for="factoryName" class="col-sm-2 col-form-label">ชื่อโรงงาน</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="factoryName" name="factory_name" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="rubberType" class="col-sm-2 col-form-label">ประเภทของยาง</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="rubberType" name="rubber_type" required>
                                <option value="All">ขายทั้งหมด</option>
                                <option value="Dry">ยางแห้ง</option>
                                <option value="Wet">ยางเปียก</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="quantity" class="col-sm-2 col-form-label">ปริมาณยางที่ขาย (กก)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="quantity" name="quantity" max="<?= htmlspecialchars($total_inventory) ?>" required>
                            <small class="form-text text-muted">ยางที่มีในคลัง: <?= htmlspecialchars($total_inventory) ?> กก</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="pricePerUnit" class="col-sm-2 col-form-label">ราคาต่อหน่วย (บาท/กก)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="pricePerUnit" name="price_per_unit" step="0.01" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="saleDate" class="col-sm-2 col-form-label">วันที่ขาย</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="saleDate" name="sale_date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="rubberPercentage" class="col-sm-2 col-form-label">เปอร์เซ็นต์ความเข้มข้นของยาง (%)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="rubberPercentage" name="rubber_percentage" step="0.01" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">บันทึกการขาย</button>
                        </div>
                    </div>

                </form><!-- End General Form Elements -->

            </div>
        </div>

    </div>



</div>