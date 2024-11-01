<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Line Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <style>
        h2 {
            text-align: center;
        }

        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h2>จำนวนผู้ขายรายวัน/รายเดือน/รายปี</h2>
    <div class="button-container">
        <button onclick="updateChart('daily')">รายวัน</button>
        <button onclick="updateChart('monthly')">รายเดือน</button>
        <button onclick="updateChart('yearly')">รายปี</button>
    </div>
    <canvas id="sellerLineChart" width="400" height="200"></canvas>

    <script>
        const ctx = document.getElementById('sellerLineChart').getContext('2d');
        let sellerLineChart;

        // ฟังก์ชันเพื่อดึงข้อมูลจากฐานข้อมูล
        function fetchData(period) {
            fetch(`getcarth.php?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    const labels = [];
                    const counts = [];

                    // แยกข้อมูลตามประเภทที่ดึงมา
                    if (period === 'daily') {
                        data = data.slice(-7); // แสดง 7 วันล่าสุด

                        data.forEach(item => {
                            const dateParts = item.date.split('-');
                            const yearBuddha = parseInt(dateParts[0]) + 543;
                            const formattedDate = `${parseInt(dateParts[2])} ${getMonthThai(parseInt(dateParts[1]))}`;
                            labels.push(formattedDate);
                            counts.push(item.seller_count);
                        });
                    } else if (period === 'monthly') {
                        const months = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
                        data.forEach(item => {
                            labels.push(months[item.month - 1]);
                            counts.push(item.seller_count);
                        });
                    } else if (period === 'yearly') {
                        data.forEach(item => {
                            labels.push(item.year);
                            counts.push(item.seller_count);
                        });
                    }

                    // สร้างแผนภูมิแบบเส้น
                    if (sellerLineChart) {
                        sellerLineChart.destroy();
                    }

                    sellerLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'จำนวนผู้ขาย',
                                data: counts,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                datalabels: {
                                    color: 'black',
                                    align: 'top',
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                    formatter: function(value) {
                                        return value + ' คน';
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: period === 'daily' ? 'วันที่' : (period === 'monthly' ? 'เดือน' : 'ปี'),
                                        font: {
                                            size: 16
                                        }
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'จำนวนผู้ขาย',
                                        font: {
                                            size: 16
                                        }
                                    },
                                    beginAtZero: true
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function updateChart(period) {
            fetchData(period);
        }

        function getMonthThai(month) {
            const months = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            return months[month - 1];
        }

        window.onload = () => fetchData('monthly');
    </script>
</body>

</html>
