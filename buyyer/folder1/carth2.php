<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Rubber Quantity Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>จำนวนยางในแต่ละเดือน ปี <span id="year"></span></h2>
    <canvas id="myLineChart" width="400" height="200"></canvas>
    <script>
        fetch('data.php') // เรียกใช้ไฟล์ PHP ที่ดึงข้อมูล
            .then(response => response.json())
            .then(data => {
                document.getElementById('year').textContent = data.year; // แสดงปีในพ.ศ.

                const ctx = document.getElementById('myLineChart').getContext('2d');
                const myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels, // ใช้ label ที่ดึงมาจาก PHP
                        datasets: [{
                            label: 'ยางแห้ง', // เปลี่ยนชื่อให้แสดงชัดเจน
                            data: data.data_dry, // ใช้ข้อมูล Dry
                            fill: false,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            tension: 0.1
                        }, {
                            label: 'ยางเปียก', // เปลี่ยนชื่อให้แสดงชัดเจน
                            data: data.data_wet, // ใช้ข้อมูล Wet
                            fill: false,
                            borderColor: 'rgba(255, 99, 132, 1)', // เปลี่ยนสีกราฟ Wet
                            tension: 0.1
                        }, {
                            label: 'รวมยาง', // เปลี่ยนชื่อให้แสดงชัดเจน
                            data: data.data_total, // ใช้ข้อมูลรวม
                            fill: false,
                            borderColor: 'rgba(255, 206, 86, 1)', // สีของกราฟรวม
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'เดือน'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'จำนวนรวม'
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
</body>
</html>
