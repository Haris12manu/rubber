<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .section.contact {
            padding: 40px 0;
        }

        .info-box {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .info-box i {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 20px;
        }

        .info-box h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #343a40;
        }

        .info-box p {
            font-size: 1.25rem;
            margin: 0;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <section class="section contact">
        <div class="row gy-4">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="info-box card">
                            <i class="bi bi-geo-alt"></i>
                            <h3>ร้านรับซื้อ</h3>
                            <p id="count-purchasing-store">Loading...</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="info-box card">
                            <i class="bi bi-telephone"></i>
                            <h3>ผู้ขายยาง</h3>
                            <p id="count-seller">Loading...</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="info-box card">
                            <i class="bi bi-envelope"></i>
                            <h3>พนักงานรับจ้าง</h3>
                            <p id="count-employees">Loading...</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="info-box card">
                            <i class="bi bi-clock"></i>
                            <h3>ผู้ใช้งานระบบทั้งหมด</h3>
                            <p id="count-totall">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('fetch_counts.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('count-purchasing-store').innerText = data['Purchasing Store'];
                    document.getElementById('count-seller').innerText = data['Seller'];
                    document.getElementById('count-employees').innerText = data['Employees'];
                    document.getElementById('count-totall').innerText = data['Total Users']; // เพิ่มการดึงข้อมูล Total Users
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>

</body>


