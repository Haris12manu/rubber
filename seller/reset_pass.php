<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>เปลี่ยนรหัสผ่าน</h4> <a href="https://codingdriver.com/"></a>
            </div>
            <div class="card-body">
                <form method="POST" id="kt_form" action="/admin/changepassword" class="fv-plugins-bootstrap fv-plugins-framework" novalidate="novalidate">
                    <input type="hidden" name="_token" value="7YQm6NTq3DuH1Fsx8bclmqYZr8WnmdFJJf5SHFn0">
                    <div class="form-group row fv-plugins-icon-container">
                        <label for="password" class="col-md-4 col-form-label text-md-right">รหัสผ่านเดิม</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control " name="current_password" autocomplete="current_password">

                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>

                    <div class="form-group row fv-plugins-icon-container">
                        <label for="password" class="col-md-4 col-form-label text-md-right">รหัสผ่านใหม่</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control " name="password" autocomplete="password">
                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>

                    <div class="form-group row fv-plugins-icon-container">
                        <label for="password" class="col-md-4 col-form-label text-md-right">ยืนยันรหัสผ่าน</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control " name="password_confirmation" autocomplete="password_confirmation">
                            <div class="fv-plugins-message-container"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                บันทึก
                            </button>
                        </div>
                    </div>
                    <div></div><input type="hidden">
                </form>
            </div>
        </div>
    </div>
</div>