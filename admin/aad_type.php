<div class="container">
  <form class="form fv-plugins-bootstrap fv-plugins-framework" id="kt_form" action="insert_rubber_type.php" method="POST" enctype="multipart/form-data" novalidate="novalidate">
    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
      <!--begin::Form-->
      <div class="card-header">
        <div class="card-title">
          <h3 class="card-label">
            เพิ่มประเภทขยะ <i class="mr-2"></i>
          </h3>
        </div>
        <div class="card-toolbar">
          <div class="btn-group">
            <button type="submit" id="save_form" class="btn btn-primary font-weight-bolder">
              <i class="ki ki-check icon-sm"></i>
              บันทึก
            </button>
            <button type="button" id="cancel_form" class="btn btn-secondary font-weight-bolder" onclick="window.location.href='index.php?page=type.php'">
              <i class="ki ki-close icon-sm"></i>
              ยกเลิก
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <input type="hidden" name="_token" value="ZvVz5Aw9uAncv9IS7j7jV7utG4K8LPifUhQgO5Ke">
        <div class="row">
          <div class="col-xl-3"></div>
          <div class="col-xl-6">
            <div class="my-5">
              <div class="form-group row fv-plugins-icon-container">
                <label class="col-3">ชื่อประเภท</label>
                <div class="col-9">
                  <input class="form-control" name="type_name" type="text" placeholder="กรอกชื่อประเภทขยะ" value="" required="">
                  <div class="fv-plugins-message-container"></div>
                </div>
              </div>
              <div class="form-group row fv-plugins-icon-container">
                <label class="col-3">คำอธิบาย</label>
                <div class="col-9">
                  <input class="form-control" name="description" type="text" placeholder="กรอกคำอธิบาย" value="" required="">
                  <div class="fv-plugins-message-container"></div>
                </div>
              </div>
              <div class="separator separator-dashed my-10"></div>
            </div>
          </div>
          <div class="col-xl-3"></div>
        </div>
      </div>
    </div>
  </form>
</div>
