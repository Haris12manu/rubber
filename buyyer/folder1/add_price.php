<?php
// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // ถ้าไม่ได้ล็อกอินให้กลับไปที่หน้า login
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลราคายาง</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-container {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-container h3 {
            margin-bottom: 20px;
        }
        .form-group button {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="form-container">
                    <h3><strong>เพิ่มข้อมูลราคายาง</strong></h3>
                    <form action="add_store_price.php" method="POST">
                        <div class="form-group">
                            <label for="id">ประเภทยาง</label>
                            <select id="id" name="id" class="form-control" required>
                                <option value="1">ยางแห้ง</option>
                                <option value="2">ยางเปียก</option>
                                <!-- เพิ่มประเภทยางตามที่คุณมีในระบบ -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="adjusted_price">ราคายาง</label>
                            <input type="number" id="adjusted_price" name="adjusted_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                            <a href="index.php?page=folder1/price.php" class="btn btn-secondary">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
