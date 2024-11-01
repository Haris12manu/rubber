<?php
// เชื่อมต่อฐานข้อมูล
require '../api/db_connect.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง announcements
$sql = "SELECT `id`, `title`, `content`, `image_path`, `file_path`, `created_at`, `updated_at` FROM `announcements` WHERE 1";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fa fa-newspaper" style="font-size: 30px;"></i>
                <span class="ml-3 h4 mb-0">ข่าวประชาสัมพันธ์</span>
            </div>
            <a href="index.php?page=create_news.php" class="btn btn-light font-weight-bold">
                <i class="fa fa-plus"></i> เพิ่มข้อมูล
            </a>
        </div>
        <div class="card-body">
            <hr>
            <!-- Datatable -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>ชื่อหัวข้อ</th>
                            <th>รายละเอียด</th>
                            <th style="width: 150px;">เครื่องมือ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td>
                                        <div class="content-preview" style="max-height: 50px; overflow: hidden;">
                                            <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="mr-1">
                                                <a href="index.php?page=edit_news.php&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" title="แก้ไข">
                                                    <i class="fa fa-edit" aria-hidden="true"></i> แก้ไข
                                                </a>
                                            </div>
                                            <form method="post" class="delete_form" action="index.php?page=delete_news.php" onsubmit="return confirm('คุณตั้งใจจะลบข้อมูลหรือไม่?');">
                                                <input type="hidden" name="_token" value="yJwIbBtA3Pts2j9Fxw7zLoDmSMzsc3uswKJ0qYWW">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" title="ลบ">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> ลบ
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">ไม่มีข้อมูล</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $conn->close(); ?>
