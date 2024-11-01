<?php
// เชื่อมต่อฐานข้อมูล
require('../api/db_connect.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// เริ่มเซสชันหากยังไม่ได้เริ่ม
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// ดึง store_id ที่เกี่ยวข้องกับ user_id จากฐานข้อมูล
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT store_id FROM purchasing_stores WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

// ตรวจสอบว่า store_id มีค่าไหม
if (empty($store_id)) {
    die("ไม่พบข้อมูลร้านค้า");
}

// ตรวจสอบว่ามีการส่งข้อมูลประเภทของยางหรือไม่
$rubber_type = 'Both'; // ค่าเริ่มต้นแสดงยางทั้งหมด
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rubber_type'])) {
    $rubber_type = $_POST['rubber_type']; // รับค่าประเภทของยางจากฟอร์ม
}

// กรองข้อมูลตามประเภทของยางที่เลือก
$type_condition = "";
if ($rubber_type == 'Dry') {
    $type_condition = "AND rubber_type = 'Dry'";
} elseif ($rubber_type == 'Wet') {
    $type_condition = "AND rubber_type = 'Wet'";
}

// ดึงข้อมูลการ์ดที่ 1: จำนวน quantity ที่ sale_status = 0 และ store_id ตรงกัน
$query1 = "SELECT SUM(quantity) as total_quantity FROM rubber_purchases WHERE sale_status = '0' AND store_id = ? $type_condition";
$stmt1 = $conn->prepare($query1);
$stmt1->bind_param("i", $store_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$row1 = $result1->fetch_assoc();
$total_quantity = $row1['total_quantity'] ?? 0; // ใช้ 0 ถ้าไม่พบข้อมูล
$stmt1->close();

// ดึงข้อมูลการ์ดที่ 2: total_price จากตาราง rubber_purchases ที่ sale_status = 0 และ store_id ตรงกัน
$query2 = "SELECT SUM(total_price) as total_price FROM rubber_purchases WHERE sale_status = '0' AND store_id = ? $type_condition";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("i", $store_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$row2 = $result2->fetch_assoc();
$total_price = $row2['total_price'] ?? 0; // ใช้ 0 ถ้าไม่พบข้อมูล
$stmt2->close();

// ดึงข้อมูลการ์ดที่ 4: price จากตาราง central_price
$query4 = "SELECT price FROM central_price ORDER BY id DESC LIMIT 1"; // สมมติว่าต้องการดึงราคาล่าสุด
$result4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($result4);
$central_price = $row4['price'];

// การคำนวณต้นทุนที่รับซื้อ
$percentage = 0; // กำหนดค่าเริ่มต้นสำหรับเปอร์เซ็น
$total_cost = 0; // กำหนดค่าเริ่มต้นสำหรับต้นทุนที่รับซื้อ
$profit_loss = 0; // กำหนดค่าเริ่มต้นสำหรับกำไรหรือขาดทุน
$profit_loss_message = ""; // ข้อความสำหรับกำไรหรือขาดทุน
$profit_loss_color = ""; // กำหนดสีสำหรับกำไรหรือขาดทุน

// ตรวจสอบว่ามีการส่งข้อมูลเปอร์เซ็นจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['input_data'])) {
    $percentage = floatval($_POST['input_data']); // รับค่าจากฟอร์ม

    // แยกการคำนวณตามประเภทของยาง
    $total_cost = $total_quantity * ($percentage / 100) * $central_price;

    // คำนวณกำไรหรือขาดทุน
    $profit_loss = $total_cost - $total_price;

    // กำหนดข้อความกำไรหรือขาดทุน
    if ($profit_loss > 0) {
        $profit_loss_message = "กำไร: " . number_format($profit_loss, 2);
        $profit_loss_color = "text-success"; // สีเขียว
    } else {
        $profit_loss_message = "ขาดทุน: " . number_format(abs($profit_loss), 2); // ใช้ abs() เพื่อแสดงค่าบวก
        $profit_loss_color = "text-danger"; // สีแดง
    }
}

mysqli_close($conn);
?>

<!-- ส่วนฟอร์มปุ่มเลือกประเภทของยาง -->
<div class="container">
    <h2 class="my-4 text-center">การตัดสินใจก่อนการขาย</h2>

    <div class="row">
        <div class="col-md-6 mb-4">
            <form action="" method="POST">
                <h4>เลือกประเภทยาง:</h4>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rubber_type" id="dry" value="Dry" <?php if ($rubber_type == 'Dry') echo 'checked'; ?> required>
                    <label class="form-check-label" for="dry">ยางแห้ง (Dry)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rubber_type" id="wet" value="Wet" <?php if ($rubber_type == 'Wet') echo 'checked'; ?> required>
                    <label class="form-check-label" for="wet">ยางเปียก (Wet)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rubber_type" id="both" value="Both" <?php if ($rubber_type == 'Both') echo 'checked'; ?> required>
                    <label class="form-check-label" for="both">ยางร่วม (Both)</label>
                </div>
                <button type="submit" class="btn btn-primary mt-2">กดเลือก</button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- การ์ดที่ 1: Quantity ของ rubber_purchases ที่ sale_status = 0 -->
        <div class="col-md-4 mb-4">
            <div class="card card-tale">
                <div class="card-body">
                    <h5 class="card-title">น้ำหนักยางที่รับ</h5>
                    <p class="fs-30 mb-2"><?php echo $total_quantity; ?></p>
                </div>
            </div>
        </div>

        <!-- การ์ดที่ 2: Total Price ของ rubber_purchases ที่ sale_status = 0 -->
        <div class="col-md-4 mb-4">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <h5 class="card-title">ต้นทุนที่รับซื้อ</h5>
                    <p class="fs-30 mb-2"><?php echo $total_price; ?></p>
                </div>
            </div>
        </div>

        <!-- การ์ดที่ 3: ป้อนข้อมูล -->
        <!-- การ์ดที่ 3: ป้อนข้อมูล -->
        <div class="col-md-4 mb-4">
            <div class="card card-light-blue">
                <div class="card-body">
                    <h5 class="card-title">เปอร์เซ็น</h5>
                    <form action="" method="POST">
                        <input type="hidden" name="rubber_type" value="<?php echo $rubber_type; ?>">
                        <input type="number" name="input_data" class="form-control mb-2" placeholder="Enter percentage" value="<?php echo isset($_POST['input_data']) ? htmlspecialchars($_POST['input_data']) : ''; ?>" required>
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>


        <div class="row">
            <!-- การ์ดที่ 4: Price จากตาราง central_price -->
            <div class="col-md-6 mb-4">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <h5 class="card-title">ราคายางโรงงาน</h5>
                        <p class="fs-30 mb-2"><?php echo $central_price; ?></p>
                    </div>
                </div>
            </div>

            <!-- การ์ดที่ 5: แสดงผลการคำนวณ -->
            <div class="col-md-6 mb-4">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <h5 class="card-title">ผลการคำนวณ</h5>
                        <p class="fs-30 mb-2">ต้นทุนที่รับซื้อ: <?php echo $total_price; ?></p>
                        <p class="fs-30 mb-2">ต้นทุนที่ควรจะเป็น: <?php echo number_format($total_cost, 2); ?></p>
                        <p class="fs-30 mb-2 <?php echo $profit_loss_color; ?>"><?php echo $profit_loss_message; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>