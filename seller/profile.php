<?php


// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require('../api/db_connect.php');

// ดึงข้อมูลจากตาราง sellers และ users
$sql = "
    SELECT s.seller_name, s.address, u.phone_number 
    FROM sellers s 
    JOIN users u ON s.user_id = u.user_id 
    WHERE s.user_id = ?
";

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $seller_name = htmlspecialchars($row['seller_name'] ?? '');
    $address = htmlspecialchars($row['address'] ?? '');
    $phone_number = htmlspecialchars($row['phone_number'] ?? '');
} else {
    echo "No data found";
    exit();
}

$stmt->close();
$conn->close();
?>

<div class="col-xl-12">
    <div class="card">
        <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview" aria-selected="true" role="tab">ข้อมูลส่วนตัว</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit" aria-selected="false" role="tab" tabindex="-1">แก้ไข</button>
                </li>
            </ul>
            <div class="tab-content pt-2">

                <div class="tab-pane fade profile-overview active show" id="profile-overview" role="tabpanel">
                    <h5 class="card-title">Profile Details</h5>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ชื่อผู้ขาย</div>
                        <div class="col-lg-9 col-md-8"><?= $seller_name; ?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ที่อยู่</div>
                        <div class="col-lg-9 col-md-8"><?= $address; ?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">เบอร์โทร</div>
                        <div class="col-lg-9 col-md-8"><?= $phone_number; ?></div>
                    </div>
                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit" role="tabpanel">

                    <!-- Profile Edit Form -->
                    <form method="POST" action="update_profile.php">
                        <div class="row mb-3">
                            <label for="sellerName" class="col-md-4 col-lg-3 col-form-label">ชื่อผู้ขาย</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="sellerName" type="text" class="form-control" id="sellerName" value="<?= $seller_name; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-lg-3 col-form-label">ที่อยู่</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="address" type="text" class="form-control" id="address" value="<?= $address; ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-lg-3 col-form-label">เบอร์โทร</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="phone" type="text" class="form-control" id="phone" value="<?= $phone_number; ?>">
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form><!-- End Profile Edit Form -->

                </div>

            </div><!-- End Bordered Tabs -->

        </div>
    </div>
</div>
