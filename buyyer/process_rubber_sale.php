<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require('../api/db_connect.php');

    $store_id = $_POST['store_id'];
    $factory_name = $_POST['factory_name'];
    $quantity_to_sell = $_POST['quantity'];
    $price_per_unit = $_POST['price_per_unit'];
    $sale_date = $_POST['sale_date'];
    $rubber_percentage = $_POST['rubber_percentage']; // เปอร์เซ็นต์ความเข้มข้นของยาง
    $rubber_type = $_POST['rubber_type']; // ประเภทของยางที่ขาย

    // คำนวณราคารวม
    $total_price = $quantity_to_sell * ($rubber_percentage / 100) * $price_per_unit;

    // เริ่มกระบวนการ transaction
    $conn->begin_transaction();

    try {
        // กำหนดค่า rubber_type หากเลือก "ขายทั้งหมด"
        if ($rubber_type === 'All') {
            $rubber_type = 'Mixed (Dry, Wet)'; // แสดงว่าขายทั้งยางแห้งและยางเปียก
        }

        // บันทึกการขายลงใน rubber_sales
        $stmt = $conn->prepare("INSERT INTO rubber_sales (store_id, factory_name, quantity, price_per_unit, total_price, sale_date, rubber_percentage, rubber_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isidisss", $store_id, $factory_name, $quantity_to_sell, $price_per_unit, $total_price, $sale_date, $rubber_percentage, $rubber_type);
        $stmt->execute();
        $stmt->close();

        // อัปเดต sale_status เฉพาะยางที่เลือก
        if ($rubber_type === 'Mixed (Dry, Wet)') {
            // อัปเดต sale_status สำหรับรายการที่เกี่ยวข้องทั้งหมด (ทั้งยางแห้งและยางเปียก)
            $stmt_update = $conn->prepare("UPDATE rubber_purchases SET sale_status = '1' WHERE store_id = ?");
            $stmt_update->bind_param("i", $store_id);
        } else {
            // อัปเดต sale_status เฉพาะรายการที่ตรงกับประเภทของยาง (Dry หรือ Wet)
            $stmt_update = $conn->prepare("UPDATE rubber_purchases SET sale_status = '1' WHERE store_id = ? AND rubber_type = ?");
            $stmt_update->bind_param("is", $store_id, $rubber_type);
        }
        $stmt_update->execute();
        $stmt_update->close();

        // ดึงยอดซื้อรวม (total_purchases) จาก rubber_purchases โดยไม่สนใจ sale_status
        $stmt_purchases = $conn->prepare("
            SELECT SUM(total_price) AS total_purchases 
            FROM rubber_purchases 
            WHERE store_id = ?
        ");
        $stmt_purchases->bind_param("i", $store_id);
        $stmt_purchases->execute();
        $result_purchases = $stmt_purchases->get_result();
        $total_purchases = $result_purchases->fetch_assoc()['total_purchases'];
        $stmt_purchases->close();

        // ดึงยอดขายรวม (total_sales) จาก rubber_sales
        $stmt_sales = $conn->prepare("
            SELECT SUM(total_price) AS total_sales 
            FROM rubber_sales 
            WHERE store_id = ?
        ");
        $stmt_sales->bind_param("i", $store_id);
        $stmt_sales->execute();
        $result_sales = $stmt_sales->get_result();
        $total_sales = $result_sales->fetch_assoc()['total_sales'];
        $stmt_sales->close();

        // คำนวณกำไรขาดทุน
        $profit_loss = $total_sales - $total_purchases;

        // บันทึกข้อมูลลงในตาราง profit_loss
        $stmt_profit_loss = $conn->prepare("
            INSERT INTO profit_loss (store_id, total_purchases, total_sales, profit_loss, record_date)
            VALUES (?, ?, ?, ?, CURDATE())
        ");
        $stmt_profit_loss->bind_param("iddd", $store_id, $total_purchases, $total_sales, $profit_loss);
        $stmt_profit_loss->execute();
        $stmt_profit_loss->close();

        // ยืนยันการทำธุรกรรม
        $conn->commit();

        // รีเฟรชหน้าเว็บกลับไปยังหน้าขายยางพร้อมแสดงข้อมูลที่อัปเดตแล้ว
        header("Location: index.php?page=folder1/sell_factory.php");
        exit();
    } catch (Exception $e) {
        // หากเกิดข้อผิดพลาด ให้ยกเลิกการทำธุรกรรม
        $conn->rollback();
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    $conn->close();
}
?>
