<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('../api/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_store = "SELECT store_id FROM purchasing_stores WHERE user_id = ?";
$stmt_store = $conn->prepare($sql_store);
$stmt_store->bind_param("i", $user_id);
$stmt_store->execute();
$result_store = $stmt_store->get_result();
$store_data = $result_store->fetch_assoc();

if (!$store_data) {
    echo "Store not found.";
    exit();
}

$store_id = $store_data['store_id'];

$sql = "SELECT sp.rubber_type_id, rt.type_name, sp.adjusted_price 
        FROM store_prices sp 
        JOIN rubber_types rt ON sp.rubber_type_id = rt.id
        WHERE sp.store_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();

$options = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options .= '<option value="' . htmlspecialchars($row['rubber_type_id']) . '" data-price="' . htmlspecialchars($row['adjusted_price']) . '">' . htmlspecialchars($row['type_name']) . '</option>';
    }
} else {
    $options = '<option value="">ไม่มีประเภทยาง</option>';
}

if (isset($_POST['seller_id'])) {
    $seller_id = filter_input(INPUT_POST, 'seller_id', FILTER_SANITIZE_NUMBER_INT);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($role === 'Seller') {
        $sql_seller = "SELECT * FROM sellers WHERE seller_id = ?";
        $stmt = $conn->prepare($sql_seller);
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        $result_seller = $stmt->get_result();
        $seller_data = $result_seller->fetch_assoc();

        if (!$seller_data) {
            echo "Seller not found.";
            exit();
        }

        $seller_name = $seller_data['seller_name'];
        $employee_id = null; // ไม่บันทึก employee_id หากเป็น Seller
    } elseif (strpos($role, 'Employee') !== false) {
        $sql_employee = "SELECT e.*, s.seller_name AS employer_name, commission_percentage, responsibility_area FROM employees e 
                         JOIN sellers s ON e.seller_id = s.seller_id 
                         WHERE e.employee_id = ?";
        $stmt = $conn->prepare($sql_employee);
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        $result_employee = $stmt->get_result();
        $employee_data = $result_employee->fetch_assoc();

        if (!$employee_data) {
            echo "Employee not found.";
            exit();
        }

        $employee_name = $employee_data['name'];
        $employer_name = $employee_data['employer_name'];
        $employee_id = $employee_data['employee_id']; // บันทึก employee_id
        $seller_id = $employee_data['seller_id']; // ใช้ employer เป็น seller
        $commission_percentage = $employee_data['commission_percentage'];
        $responsibility_area = $employee_data['responsibility_area'];
    }
} else {
    echo "No seller data found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_form'])) {
    $rubber_type_ids = $_POST['rubber_type_id'];
    $quantities = $_POST['quantity'];
    $prices_per_unit = $_POST['price_per_unit'];
    $total_prices = $_POST['total_price'];

    $sql = "INSERT INTO rubber_purchases (store_id, seller_id, employee_id, rubber_type, quantity, price_per_unit, total_price, purchase_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    for ($i = 0; $i < count($rubber_type_ids); $i++) {
        $rubber_type = filter_var($rubber_type_ids[$i], FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_var($quantities[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $price_per_unit = filter_var($prices_per_unit[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $total_price = filter_var($total_prices[$i], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // บันทึกข้อมูลลงในฐานข้อมูล
        $stmt->bind_param("iiiiddd", $store_id, $seller_id, $employee_id, $rubber_type, $quantity, $price_per_unit, $total_price);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    header("Location: success_page.php");
    exit();
}

$conn->close();
?>




<!-- ส่วน HTML ที่เหลือยังคงเดิม -->




<div class="container">
    <style type="text/css">
        .card-custom {
            width: 100%;
            border: none;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .stats {
            background: #f2f5f8 !important;
            color: #000 !important;
            border-radius: 10px;
            padding: 15px;
        }

        .articles,
        .followers,
        .rating {
            font-size: 14px;
            color: #a1aab9;
            margin-bottom: 5px;
        }

        .number1,
        .number2,
        .number3 {
            font-weight: 500;
            font-size: 16px;
            color: #333;
        }

        .image img {
            border-radius: 10px;
        }

        .student-info {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .student-info .student-details {
            display: flex;
            justify-content: space-between;
        }

        .student-info h4 {
            margin: 0;
            padding: 5px 0;
        }

        .student-info .balance {
            text-align: right;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .modal-footer .btn {
            margin-left: 10px;
        }
    </style>

    <!-- Student Info Card -->
    <div class="student-info">
        <div class="d-flex align-items-center">
            <div class="image">
                <img src="../assets/img/logo.png" width="155">
            </div>
            <div class="ml-3 w-100 student-details">
                <div>
                    <h4>รหัสผู้ขาย|
                        <?php
                        if (strpos($role, 'Employee') !== false) {
                            echo htmlspecialchars($employee_id); // แสดง employee_id ถ้าเป็น Employee
                        } else {
                            echo htmlspecialchars($seller_id); // แสดง seller_id ถ้าเป็น Seller
                        }
                        ?>
                    </h4>
                    <h4>ชื่อ| <?php echo htmlspecialchars($seller_name ?? $employee_name); ?></h4>
                </div>
                <div class="balance">
                    <span class="rating">ยอด (บาท):</span>
                    <h2 class="number3"><?php echo htmlspecialchars($balance ?? 'N/A'); ?></h2>
                </div>
            </div>
        </div>
        <?php if (strpos($role, 'Employee') !== false): ?>
            <div class="stats mt-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="articles">รหัสลูกจ้าง|</span>
                        <span class="number1"><?php echo htmlspecialchars($employee_id); ?></span>
                    </div>
                    <div>
                        <span class="followers">รหัสนายจ้าง|</span>
                        <span class="number2"><?php echo htmlspecialchars($seller_id); ?></span>
                    </div>
                    <div>
                        <span class="rating">นายจ้าง|</span>
                        <span class="number3"><?php echo htmlspecialchars($employer_name); ?></span>
                    </div>
                    <div>
                        <span class="rating">สถานะ|</span>


                    </div>
                </div>
            </div>
            <div class="stats mt-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="articles">เปอร์เซ็นต์คอมมิชชั่น|</span>
                        <span class="number1"><?php echo htmlspecialchars($commission_percentage ?? ''); ?></span>
                    </div>
                    <div>
                        <span class="followers">พื้นที่รับผิดชอบ|</span>
                        <span class="number2"><?php echo htmlspecialchars($responsibility_area ?? ''); ?></span>
                    </div>
                    <div>
                        <span class="rating">สถานะ|</span>
                        <!-- ถ้ามีสถานะ ก็แสดงค่าที่นี่ -->
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Deposit Form Card -->
    <div class="card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">รับซื้อ</h3>
            </div>
        </div>
        <div class="card-body">
            <form id="kt_form" action="store_purchase.php" method="POST">
                <input type="hidden" name="_token" value="RqbZCcrteDSWeiEQ8ZE6jL9fR4Mc5C7DwZ1DnNZ5">
                <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($store_id); ?>">
                <input type="hidden" name="seller_id" value="<?php echo htmlspecialchars($seller_id); ?>">
                <?php if (!empty($employee_id)): ?>
                    <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee_id); ?>">
                <?php endif; ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th width="60%">รายการ</th>
                            <th>จำนวน (กก)</th>
                            <th>ราคา (ต่อหน่วย)</th>
                            <th>รวม</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="one-one" id="rubber-items">
                        <tr>
                            <td>
                                <select class="form-control" name="rubber_type_id[]" id="rubber_type_id" required onchange="updatePrice(this)">
                                    <option value="">เลือกรายการ</option>
                                    <?php echo $options; ?> <!-- เพิ่มรายการประเภทยางที่ดึงมาจากฐานข้อมูล -->
                                </select>
                            </td>
                            <td>
                                <input class="form-control" type="number" name="quantity[]" placeholder="กรอกจำนวน" required oninput="updateTotal(this)">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="price_per_unit[]" placeholder="0" readonly>
                            </td>
                            <td>
                                <input class="form-control" type="text" name="total_price[]" placeholder="0" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="removeItem(this)">ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success" onclick="addItem()">เพิ่มรายการ</button>
                <div class="modal-footer">
                    <button type="submit" id="save_form" class="btn btn-primary">ยืนยัน</button>
                    <a href="index.php?page=folder1/buy_s.php" class="btn btn-danger">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>


    <script>
        function updatePrice(selectElement) {
            const row = selectElement.closest('tr');
            const priceInput = row.querySelector('input[name="price_per_unit[]"]');
            const quantityInput = row.querySelector('input[name="quantity[]"]');
            const totalPriceInput = row.querySelector('input[name="total_price[]"]');

            // ดึงราคาจาก attribute 'data-price'
            const price = parseFloat(selectElement.selectedOptions[0].getAttribute('data-price')) || 0;

            priceInput.value = price.toFixed(2); // แสดงราคาต่อหน่วย
            updateTotal(quantityInput); // อัพเดทราคารวม
        }

        function updateTotal(quantityInput) {
            const row = quantityInput.closest('tr');
            const quantity = parseFloat(quantityInput.value) || 0;
            const pricePerUnit = parseFloat(row.querySelector('input[name="price_per_unit[]"]').value) || 0;
            const totalPriceInput = row.querySelector('input[name="total_price[]"]');

            totalPriceInput.value = (quantity * pricePerUnit).toFixed(2);
        }

        function addItem() {
            let newRow = `
    <tr>
        <td>
            <select class="form-control" name="rubber_type_id[]" id="rubber_type_id" required onchange="updatePrice(this)">
                <option value="">เลือกรายการ</option>
                <?php echo $options; ?> <!-- เพิ่มรายการประเภทยางที่ดึงมาจากฐานข้อมูล -->
            </select>
        </td>
        <td>
            <input class="form-control" type="number" name="quantity[]" placeholder="กรอกจำนวน" required oninput="updateTotal(this)">
        </td>
        <td>
            <input class="form-control" type="text" name="price_per_unit[]" placeholder="0" readonly>
        </td>
        <td>
            <input class="form-control" type="text" name="total_price[]" placeholder="0" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-danger" onclick="removeItem(this)">ลบ</button>
        </td>
    </tr>`;

            document.getElementById('rubber-items').insertAdjacentHTML('beforeend', newRow);
        }

        // ฟังก์ชันลบรายการ
        function removeItem(button) {
            button.closest('tr').remove();
        }
    </script>
</div>