<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fa fa-newspaper" style="font-size: 30px;"></i>
                <span class="ml-3 h4 mb-0">ข่าวประชาสัมพันธ์</span>
            </div>
            <a href="/admin/news/create" class="btn btn-light font-weight-bold">
                <i class="fa fa-plus"></i> เพิ่มข้อมูล
            </a>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <hr>
            <!-- Datatable -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>ชื่อหัวข้อ</th>
                            <th style="width: 150px;">เครื่องมือ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>9</td>
                            <td>ประชาสัมพันธ์สมัครสมาชิกชมรมธนาคารขยะ</td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <a href="" class="btn btn-primary btn-sm" title="ดูข้อมูล">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="" class="btn btn-warning btn-sm" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="post" class="delete_form" action="" onsubmit="return confirm('คุณตั้งใจจะลบข้อมูลหรือไม่?');">
                                        <input type="hidden" name="_token" value="yJwIbBtA3Pts2j9Fxw7zLoDmSMzsc3uswKJ0qYWW">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center" title="ลบ">
                                            <i class="fa fa-trash mr-1" aria-hidden="true"></i> ลบ
                                        </button>
                                    </form>
                                    
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->

        </div>
    </div>
</div>