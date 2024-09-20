<?php

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // ถ้าไม่ได้ล็อกอินให้กลับไปที่หน้า login
    exit();
}

require('../api/db_connect.php');
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง store_prices และข้อมูลที่เกี่ยวข้อง เฉพาะของผู้ใช้ที่ล็อกอินอยู่
$user_id = $_SESSION['user_id'];
$sql = "
    SELECT sp.id, 
           ps.store_name, 
           rt.type_name AS rubber_type_name, 
           sp.adjusted_price
    FROM store_prices sp
    JOIN purchasing_stores ps ON sp.store_id = ps.store_id
    JOIN rubber_types rt ON sp.rubber_type_id = rt.id
    WHERE ps.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<div class="container my-5">
    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0" id="dynamic-date-title"></h4>
            <div>
                <button type="button" id="save_form" class="btn btn-warning font-weight-bolder">
                    <i class="fas fa-check icon-sm"></i>
                    อัพเดตราคาสัปดาห์นี้
                </button>
                <button type="button" id="add_price" class="btn btn-success font-weight-bolder ml-2">
                    <i class="fas fa-plus icon-sm"></i>
                    เพิ่มราคายาง
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <div class="mb-4">
                <div class="row align-items-center">
                </div>
            </div>
            <!-- Datatable -->
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th title="ชื่อรายการขยะ">ประเภทยางก้อนถ้วย</th>
                        <th title="ราคาที่รับซื้อ / กก">ราคาที่รับซื้อ</th>
                    </tr>
                </thead>
                <tbody id="priceTableBody">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['rubber_type_name']); ?></td>
                                <td contenteditable="true" data-id="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['adjusted_price']); ?> บาท</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">ไม่มีข้อมูล</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
    function formatDate(date) {
        const monthNames = [
            "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน",
            "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม",
            "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
        ];

        const day = date.getDate();
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear() + 543; // Convert to Buddhist calendar year

        return `ข้อมูลการกำหนดราคายางก้อนถ้วย ณ วันที่ ${day} ${month} ${year}`;
    }

    document.addEventListener("DOMContentLoaded", () => {
        const dateTitleElement = document.getElementById("dynamic-date-title");
        const today = new Date();
        dateTitleElement.textContent = formatDate(today);
    });

    document.getElementById('add_price').addEventListener('click', function() {
        window.location.href = 'index.php?page=add_price.php'; // เปลี่ยน URL เป็นที่อยู่ของหน้าที่ต้องการ
    });

    document.getElementById('save_form').addEventListener('click', function() {
        const rows = document.querySelectorAll('#priceTableBody tr');
        const data = [];

        rows.forEach(row => {
            const id = row.querySelector('td[contenteditable]').getAttribute('data-id');
            const adjusted_price = row.querySelector('td[contenteditable]').textContent.replace(' บาท', '').trim();
            data.push({
                id,
                adjusted_price
            });
        });

        fetch('update_prices.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'อัพเดตราคาสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        location.reload(); // รีโหลดหน้าเมื่อผู้ใช้กดปุ่ม "ตกลง"
                    });
                } else {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการอัพเดตราคา',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                }
            })

            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการอัพเดตราคา');
            });
    });
</script>