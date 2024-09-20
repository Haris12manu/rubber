<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require('../api/db_connect.php');

    $store_id = $_POST['store_id'];
    $factory_name = $_POST['factory_name'];
    $quantity_to_sell = $_POST['quantity'];
    $price_per_unit = $_POST['price_per_unit'];
    $sale_date = $_POST['sale_date'];
    $rubber_percentage = $_POST['rubber_percentage'];
    $total_price = $quantity_to_sell * $price_per_unit;

    // บันทึกการขายลงใน rubber_sales
    $stmt = $conn->prepare("INSERT INTO rubber_sales (store_id, factory_name, quantity, price_per_unit, total_price, sale_date, rubber_percentage) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isidisi", $store_id, $factory_name, $quantity_to_sell, $price_per_unit, $total_price, $sale_date, $rubber_percentage);

    if ($stmt->execute()) {
        // ดำเนินการลดจำนวนยางในคลัง
        $remaining_quantity = $quantity_to_sell;

        // ดึงรายการซื้อยางที่ยังมีคงเหลือในคลัง
        $stmt_purchase = $conn->prepare("SELECT purchase_id, quantity_remaining FROM rubber_purchases WHERE store_id = ? AND quantity_remaining > 0 ORDER BY purchase_date ASC");
        $stmt_purchase->bind_param("i", $store_id);
        $stmt_purchase->execute();
        $result = $stmt_purchase->get_result();

        while ($row = $result->fetch_assoc()) {
            if ($remaining_quantity <= 0) {
                break; // หากขายหมดแล้ว ให้หยุดกระบวนการ
            }

            $purchase_id = $row['purchase_id'];
            $quantity_remaining = $row['quantity_remaining'];

            if ($quantity_remaining > $remaining_quantity) {
                // กรณีที่ยางในคลังเพียงพอสำหรับการขาย
                $new_quantity_remaining = $quantity_remaining - $remaining_quantity;
                $remaining_quantity = 0;
            } else {
                // กรณีที่ยางในคลังไม่เพียงพอ
                $new_quantity_remaining = 0;
                $remaining_quantity -= $quantity_remaining;
            }

            // อัปเดตปริมาณยางในคลัง
            $stmt_update = $conn->prepare("UPDATE rubber_purchases SET quantity_remaining = ?, is_sold = IF(quantity_remaining = 0, TRUE, is_sold) WHERE purchase_id = ?");
            $stmt_update->bind_param("di", $new_quantity_remaining, $purchase_id);
            $stmt_update->execute();
            $stmt_update->close();
        }

        $stmt_purchase->close();

        echo "บันทึกการขายสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
