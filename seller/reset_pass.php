<?php

$_SESSION['_token'] = bin2hex(random_bytes(16)); // สร้าง CSRF token
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>เปลี่ยนรหัสผ่าน</h4>
            </div>
            <div class="card-body">
                <form method="POST" id="kt_form" action="edit_pass.php" class="fv-plugins-bootstrap fv-plugins-framework" novalidate="novalidate">
                    <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                    
                    <div class="form-group row fv-plugins-icon-container">
                        <label for="current_password" class="col-md-4 col-form-label text-md-right">รหัสผ่านเดิม</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="current_password" id="current_password" autocomplete="current_password" required>
                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>

                    <div class="form-group row fv-plugins-icon-container">
                        <label for="new_password" class="col-md-4 col-form-label text-md-right">รหัสผ่านใหม่</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password" id="new_password" autocomplete="new-password" required>
                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>

                    <div class="form-group row fv-plugins-icon-container">
                        <label for="confirm_password" class="col-md-4 col-form-label text-md-right">ยืนยันรหัสผ่าน</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password_confirmation" id="confirm_password" autocomplete="new-password" required>
                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
