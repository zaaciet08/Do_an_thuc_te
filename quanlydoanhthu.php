<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileImage" content="./assets/img/Remove-bg.ai_1721935180990.png" />
    <title>BeNho Admin</title>
    <link rel="stylesheet" href="assets/css/quanlydoanhthu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Thư viện Chart.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
</head>

<body>
    <?php include 'view/menu.php' ?>
    <?php
    // Kết nối cơ sở dữ liệu
    $conn = mysqli_connect("localhost", "root", "", "doanthucte");

    // Kiểm tra kết nối
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    // Kiểm tra xem người dùng đã chọn khoảng thời gian chưa
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Tạo câu truy vấn SQL dựa trên khoảng thời gian
    $query = "
        SELECT 
            SUM(hd_tamtinh) AS total_tamtinh, 
            SUM(hd_thanhtien) AS total_thanhtien,
            SUM(hd_giamgia) AS total_giamgia, 
            COUNT(*) AS total_phuongthuc, 
            SUM(CASE WHEN hd_phuongthuc = 'Thẻ ngân hàng' THEN 1 ELSE 0 END) AS total_the,
            SUM(CASE WHEN hd_phuongthuc = 'Tiền mặt' THEN 1 ELSE 0 END) AS total_tienmat
        FROM tbl_hoadon
        WHERE 1=1";

    // Nếu người dùng chọn khoảng thời gian, thêm điều kiện vào câu truy vấn
    if (!empty($start_date) && !empty($end_date)) {
        $query .= " AND hd_thoigian BETWEEN '$start_date' AND '$end_date'";
    }

    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $total_tamtinh = $row['total_tamtinh'];
        $total_thanhtien = $row['total_thanhtien'];
        $total_phuongthuc = $row['total_phuongthuc'];
        $giamgia = $row['total_giamgia'];
        $total_giamgia = (($total_tamtinh * $giamgia)/100);
        $total_phivat = ($total_tamtinh * 5)/100;
        $total = $total_tamtinh + $total_thanhtien + $total_giamgia+$total_phivat;

        // Tính phần trăm cho biểu đồ
        $percent_tamtinh = ($total_tamtinh / $total) * 100;
        $percent_thanhtien = ($total_thanhtien / $total) * 100;
        $percent_giamgia = ($total_giamgia / $total)*100;
        $percent_phivat = ($total_phivat / $total)*100;
        // Số lần thanh toán bằng thẻ ngân hàng và tiền mặt
        $total_the = $row['total_the'];
        $total_tienmat = $row['total_tienmat'];
    } else {
        $percent_tamtinh = 0;
        $percent_thanhtien = 0;
        $total = 0;
        $total_phuongthuc = 0;
        $total_the = 0;
        $total_tienmat = 0;
    }

    $conn->close();
    ?>

    <main>
        <div class="container">
            <h2>Quản lý doanh thu</h2>
            <form method="GET" action="">
                <label for="start_date">Ngày bắt đầu:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">Ngày kết thúc:</label>
                <input type="date" id="end_date" name="end_date" required>

                <button type="submit">Xem thống kê</button>
            </form>
            <div class="container_table">
                <div class="doanhthu">
                    <p>Doanh thu</p>
                    <span class="tongso"><?php echo number_format($total_thanhtien, 0, ',', '.'); ?> đ</span>
                </div>
                <canvas id="doanhThuChart" width="300" height="300"></canvas> <!-- Biểu đồ tròn -->
                <div class="phivat">
                    <p>Phương thức</p>
                    <span class="tongso"><?php echo $total_phuongthuc; ?></span>
                </div>
                <canvas id="phuongThucChart" width="300" height="300"></canvas> <!-- Biểu đồ cột -->
            </div>
        </div>
    </main>

    <script>
    var ctx1 = document.getElementById('doanhThuChart').getContext('2d');
    var doanhThuChart = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['Tạm tính', 'Giảm giá', 'Phí VAT',
                'Thành tiền'
            ],
            datasets: [{
                label: 'Phần trăm doanh thu',
                data: [<?php echo $percent_tamtinh; ?>, <?php echo $percent_giamgia; ?>,
                    <?php echo $percent_phivat; ?>,
                    <?php echo $percent_thanhtien; ?>
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(153, 102, 255, 0.2)', // Màu cho Phí VAT
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)', // Viền cho Phí VAT
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                        }
                    }
                }
            }
        }
    });

    var ctx2 = document.getElementById('phuongThucChart').getContext('2d');
    var phuongThucChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Thẻ ngân hàng', 'Tiền mặt'],
            datasets: [{
                label: 'Số lần thực hiện',
                data: [<?php echo $total_the; ?>, <?php echo $total_tienmat; ?>],
                backgroundColor: [
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
    </script>

    <script src="js/quanlydoanhthu.js"></script>
</body>

</html>