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

// Fetch the data from the database using the retrieved store_id
$sql = "SELECT `store_name`, `address`, `phone` FROM `purchasing_stores` WHERE `store_id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Handle null values before passing to htmlspecialchars
    $store_name = htmlspecialchars($row['store_name'] ?? '');
    $address = htmlspecialchars($row['address'] ?? '');
    $phone = htmlspecialchars($row['phone'] ?? '');
} else {
    echo "0 results";
    exit();
}

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
                        <div class="col-lg-3 col-md-4 label">ชื่อร้าน</div>
                        <div class="col-lg-9 col-md-8"><?= htmlspecialchars($store_name); ?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ที่อยู่ร้าน</div>
                        <div class="col-lg-9 col-md-8"><?= htmlspecialchars($address); ?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">เบอร์โทร</div>
                        <div class="col-lg-9 col-md-8"><?= htmlspecialchars($phone); ?></div>
                    </div>
                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit" role="tabpanel">

                    <!-- Profile Edit Form -->
                    <form method="POST" action="update_profile.php">
                        <div class="row mb-3">
                            <label for="storeName" class="col-md-4 col-lg-3 col-form-label">ชื่อร้าน</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="storeName" type="text" class="form-control" id="storeName" value="<?= htmlspecialchars($store_name); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-lg-3 col-form-label">ที่อยู่ร้าน</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="address" type="text" class="form-control" id="address" value="<?= htmlspecialchars($address); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-lg-3 col-form-label">เบอร์โทร</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="phone" type="text" class="form-control" id="phone" value="<?= htmlspecialchars($phone); ?>">
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
