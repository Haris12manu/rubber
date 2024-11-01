

<?php
// การเชื่อมต่อฐานข้อมูล
require './api/db_connect.php'; // เชื่อมต่อฐานข้อมูลhhhh

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง store_prices และข้อมูลที่เกี่ยวข้อง
$sql = "
    SELECT ps.store_name, 
           sp.rubber_type_id, 
           sp.adjusted_price,
           ps.central_price AS central_price,
           cp.price AS central_price
    FROM store_prices sp
    JOIN purchasing_stores ps ON sp.store_id = ps.store_id
    LEFT JOIN central_price cp ON sp.rubber_type_id = cp.id
";
$result = $conn->query($sql);

// จัดกลุ่มข้อมูล
$store_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $store_name = $row['store_name'];
        $rubber_type_id = $row['rubber_type_id'];
        $adjusted_price = $row['adjusted_price'];
        $central_price = $row['central_price'];

        // Initialize store data if not exists
        if (!isset($store_data[$store_name])) {
            $store_data[$store_name] = [
                'กลาง' => '',
                'ยางแห้ง' => '',
                'ยางเปียก' => '',
            ];
        }

        // Assign prices based on rubber type
        switch ($rubber_type_id) {
            case 1: // Assuming 1 is the ID for "กลาง"
                $store_data[$store_name]['กลาง'] = htmlspecialchars($adjusted_price);
                break;
            case 2: // Assuming 2 is the ID for "ยางแห้ง"
                $store_data[$store_name]['ยางแห้ง'] = htmlspecialchars($adjusted_price);
                break;
            case 3: // Assuming 3 is the ID for "ยางเปียก"
                $store_data[$store_name]['ยางเปียก'] = htmlspecialchars($adjusted_price);
                break;
        }
    }
}

?>


