<?php
include_once 'db/connect.php'
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileImage" content="./assets/img/Remove-bg.ai_1721935180990.png" />
    <title>BeNho Admin</title>
    <link rel="stylesheet" href="assets/css/quanlyhoadon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
</head>

<body>
    <?php include 'view/menu.php' ?>
    <main>
        <div class="container">
            <h2>Quản lý hóa đơn</h2>
            <div class="container_table">
                <div class="timkiem">
                    <input type="text" class="search-bar" placeholder="Tìm kiếm hóa đơn...">
                    <div class="search-results">
                        <table class="result-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Tiền tạm tính</th>
                                    <th>Phí VAT</th>
                                    <th>Giảm giá</th>
                                    <th>Tổng tiền</th>
                                    <th>Phương thức</th>
                                    <th>Bàn</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody id="results-body">
                                <?php
                                $sql = "SELECT * FROM tbl_hoadon";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>#' . $row["hd_code"] . '</td>';
                                        echo '<td>' . number_format($row['hd_tamtinh'], 0, ',', '.') . 'đ'.'</td>';
                                        echo '<td>' . $row["hd_phivat"] . '</td>';
                                        echo '<td>' . $row["hd_giamgia"] . '</td>';
                                        echo '<td>' . number_format($row['hd_thanhtien'], 0, ',', '.') . 'đ'.'</td>';
                                        echo '<td>' . $row["hd_phuongthuc"] . '</td>';
                                        echo '<td>' . $row["tb_id"] . '</td>';
                                        echo '<td>' . $row["hd_thoigian"] . '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6">Không có kết quả nào.</td></tr>';
                                }
                                $conn->close();
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="js/quanlyhoadon.js"></script>
</body>

</html>