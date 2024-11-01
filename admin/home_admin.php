<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Information</title>
</head>

<body>

    <section class="section contact">
        <div class="row gy-4">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="info-box card" style="background-color: #FFD700;"> <!-- เพิ่มบรรทัดนี้ -->
                            <i class="bi bi-person" style="font-size: 100px; color: rgba(0, 0, 0, 0.1); position: absolute; top: 10%; left: 10%;"></i>
                            <h3 style="font-family: 'Georgia', serif; font-size: 24px; position: relative; z-index: 1;">ผู้ดูแลระบบ</h3>
                            <p id="count-admin" style="font-family: 'Verdana', sans-serif; font-size: 18px; font-weight: bold; color: #000080; font-size: 36px; position: relative; z-index: 1;">Loading...</p>
                        </div>
                    </div>


                    <div class="col-lg-4">
                        <div class="info-box card" style="background-color: #ADD8E6; font-family: 'Arial', sans-serif; font-size: 16px; position: relative; overflow: hidden;">
                            <i class="bi bi-geo-alt" style="font-size: 100px; color: rgba(0, 0, 0, 0.1); position: absolute; top: 10%; left: 10%;"></i>
                            <h3 style="font-family: 'Georgia', serif; font-size: 24px; position: relative; z-index: 1;">ร้านรับซื้อ</h3>
                            <p id="count-purchasing-store" style="font-family: 'Verdana', sans-serif; font-size: 18px; font-weight: bold; color: #000080; font-size: 36px; position: relative; z-index: 1;">Loading...</p>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="info-box card" style="background-color: #FFA07A;">
                            <i class="bi bi-telephone" style="font-size: 100px; color: rgba(0, 0, 0, 0.1); position: absolute; top: 10%; left: 10%;"></i></i>
                            <h3 style="font-family: 'Georgia', serif; font-size: 24px; position: relative; z-index: 1;">ผู้ขายยาง</h3>
                            <p id="count-seller" style="font-family: 'Verdana', sans-serif; font-size: 18px; font-weight: bold; color: #000080; font-size: 36px; position: relative; z-index: 1;">Loading...</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="info-box card" style="background-color: #66FF00;">
                            <div style="border-radius: 50%; width: 120px; height: 120px; background-color: rgba(0, 0, 0, 0.1); display: flex; justify-content: center; align-items: center; position: absolute; top: 10%; left: 10%;">
                                <i class="bi bi-check" style="font-size: 100px; color: rgba(0, 0, 0, 0.1); position: absolute; top: 10%; left: 10%;"></i>
                            </div>
                            <h3>บัญชีที่อนุมัติแล้ว</h3>
                            <p id="count-approved" style="font-family: 'Verdana', sans-serif; font-size: 18px; font-weight: bold; color: #000080; font-size: 36px; position: relative; z-index: 1;">Loading...</p> <!-- เปลี่ยน ID เพื่อความแตกต่าง -->
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="info-box card" style="background-color: #FF3333;">
                            <div style="border-radius: 50%; width: 120px; height: 120px; background-color: rgba(0, 0, 0, 0.1); display: flex; justify-content: center; align-items: center; position: absolute; top: 10%; left: 10%;">
                                <i class="bi bi-check" style="font-size: 100px; color: rgba(0, 0, 0, 0.1); position: absolute; top: 10%; left: 10%;"></i>
                            </div>
                            <h3 style="color:aliceblue">บัญชีที่ยังไม่อนุมัติ</h3>
                            <p id="count-pending" style="font-family: 'Verdana', sans-serif; font-size: 18px; font-weight: bold; color: #000080; font-size: 36px; position: relative; z-index: 1;">Loading...</p><!-- เปลี่ยน ID เพื่อความแตกต่าง -->
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="info-box card" style="background-color: #87CEFA;">
                            <i class="bi bi-clock" style="font-size: 100px; color: rgba(0, 0, 0, 0.1); position: absolute; top: 10%; left: 10%;"></i></i>
                            <h3>ผู้ใช้งานระบบทั้งหมด</h3>
                            <p id="count-totall" style="font-family: 'Verdana', sans-serif; font-size: 18px; font-weight: bold; color: #000080; font-size: 36px; position: relative; z-index: 1;">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <?php
                    include('cart_admin.php')
                    ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('fetch_counts.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('count-purchasing-store').innerText = data['Purchasing Store'];
                    document.getElementById('count-seller').innerText = data['Seller'];
                    document.getElementById('count-totall').innerText = data['Total Users'];
                    document.getElementById('count-admin').innerText = data['Admin']; // เพิ่มการดึงข้อมูล Admin
                })
                .catch(error => console.error('Error fetching data:', error));
        });


        // ดึงข้อมูลจาก PHP ที่ส่งเป็น JSON
        fetch('fetch.php') // เปลี่ยนเป็น path ของไฟล์ PHP ที่ใช้
            .then(response => response.json())
            .then(data => {
                // อัปเดตจำนวนใน HTML
                document.getElementById('count-approved').textContent = data.approved;
                document.getElementById('count-pending').textContent = data.pending;
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    </script>

</body>