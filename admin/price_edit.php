<div class="container mt-5">
        <h2>กำหนดราคากลาง</h2>
        <form action="save_central_price.php" method="POST">
            <div class="form-group">
                <label for="rubber_type">ประเภทของยาง</label>
                <select id="rubber_type" name="rubber_type" class="form-control" required>
                    <option value="">เลือกประเภทของยาง</option>
                    <option value="Dry">ยางแห้ง</option>
                    <option value="Wet">ยางเปียก</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">ราคากลาง (บาท)</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
        </form>
    </div>