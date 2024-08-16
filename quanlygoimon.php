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
    <link rel="stylesheet" href="assets/css/quanlygoimon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
</head>

<body>
    <?php 
if(isset($_GET["tb_id"])){
    $tb_id = $_GET["tb_id"];
} else {
    $tb_id = "unknown"; // Giá trị mặc định
}
?>
    <main>
        <div class="container">
            <div class="back" onclick="window.location.href='quanlybanan.php';">
                <i class="fa-solid fa-xmark"></i>
            </div>

            <h2>Quản lý bàn ăn số: <?php echo htmlspecialchars($tb_id); ?></h2>
            <div class="container_table">
                <div class="timkiem">
                    <input type="text" class="search-bar" placeholder="Tìm kiếm món ăn...">
                    <div class="search-results">
                        <table class="result-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên món</th>
                                    <th>Giá/Đơn vị</th>
                                    <th>Số lượng</th>
                                    <th>Cách chế biến</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="results-body">
                                <?php
                                $sql = "SELECT * FROM tbl_monan";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>' . $row["ma_id"] . '</td>';
                                        echo '<td>' . $row["ma_ten"] . '</td>';
                                        echo '<td>' . number_format($row['ma_gia'], 0, ',', '.') . 'đ/' . $row['ma_dvtinh'] . '</td>';
                                        echo '<td><input type="number" min="0.5" value="0" step="0.5" class="quantity"></td>';
                                        echo '<td>';
                                        if($row['ma_cachchebien'] == 0){
                                            echo 'Mặc định';
                                        } else {
                                            $cach_che_bien = explode(',', $row['ma_cachchebien']);
                                            echo '<select class="cachchebien">';
                                            echo '<option>Chưa chọn</option>';
                                            foreach ($cach_che_bien as $cach) {
                                                echo '<option value="' . trim($cach) . '">' . trim($cach) . '</option>';
                                            }
                                            echo '</select>';
                                        }
                                        echo '</td>';
                                        echo '<td><button type="button" onclick="submitForm(this)">Thêm</button></td>';
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
                <form id="hiddenForm" method="POST" action="submitgoimon.php" style="display: none;">
                    <input type="hidden" name="ma_id" id="ma_id">
                    <input type="hidden" name="tb_id" value="<?php echo htmlspecialchars($tb_id); ?>">
                    <input type="hidden" name="ct_ten" id="ct_ten">
                    <input type="hidden" name="ct_gia" id="ct_gia">
                    <input type="hidden" name="ct_soluong" id="ct_soluong">
                    <input type="hidden" name="ct_cachchebien" id="ct_cachchebien">
                </form>
                <div class="hoadon">
                    <h4>Chi tiết sử dụng</h4>
                    <div class="chitiet_hoadon">
                        <table class="hoadon_table">
                            <thead>
                                <tr>
                                    <th>Tên món</th>
                                    <th>Giá</th>
                                    <th>SL</th>
                                    <th>Cách chế biến</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                   // Kết nối đến cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối không thành công: " . mysqli_connect_error());
}
                                   // Kiểm tra và lấy tb_id từ URL
                                   if (isset($_GET["tb_id"])) {
                                       $tb_id = $conn->real_escape_string($_GET["tb_id"]); // Tránh SQL Injection
                                   } else {
                                       echo '<tr><td colspan="5">Không có thông tin bàn ăn.</td></tr>';
                                       exit;
                                   }
                                $sql_chitiet = "SELECT * FROM tbl_chitietsudung WHERE tb_id =  $tb_id";
                                $result_chitiet = $conn->query($sql_chitiet);
                                if ($result_chitiet->num_rows > 0) {
                                    while ($row_chitiet = $result_chitiet->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>' . $row_chitiet["ct_ten"] . '</td>';
                                        echo '<td>' . number_format($row_chitiet['ct_gia'], 0, ',', '.') . 'đ</td>';
                                        echo '<td>' . $row_chitiet["ct_soluong"] . '</td>';
                                        echo '<td>' . $row_chitiet["ct_cachchebien"] . '</td>';
                                        echo '<td>' .number_format($row_chitiet['ct_gia'] * $row_chitiet['ct_soluong'], 0, ',', '.') . 'đ</td>';
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
                    <div class="chitiet_thanhtoan">
                        <h4>Tạm tính</h4>
                        <?php 
// Kết nối đến cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối không thành công: " . mysqli_connect_error());
}

// Kiểm tra và lấy tb_id từ URL
if (isset($_GET["tb_id"])) {
    $tb_id = $conn->real_escape_string($_GET["tb_id"]); // Tránh SQL Injection
} else {
    echo '<tr><td colspan="5">Không có thông tin bàn ăn.</td></tr>';
    exit;
}

// Kiểm tra nếu tb_id tồn tại
if ($tb_id) {
    // Truy vấn tổng số tiền từ bảng tbl_chitietsudung
    $sql = "SELECT SUM(ct_gia * ct_soluong) AS tong_tien FROM tbl_chitietsudung WHERE tb_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tb_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Lấy tổng tiền
    $tamtinh = $row['tong_tien'] ? $row['tong_tien'] : 0;
    $tamtinh_format = number_format($tamtinh, 0, ',', '.') . 'đ';
    $tongtien = $tamtinh + (($tamtinh * 5)/100);
    $tongtien_format = number_format($tongtien, 0, ',', '.') . 'đ';
} else {
    // Nếu tb_id không tồn tại, đặt tổng tiền bằng 0
    $tamtinh = 0;
    $tamtinh_format = '0đ';
}
?>
                        <form id="paymentForm" method="POST">
                            <table class="tamtinh_table">
                                <tbody>
                                    <tr>
                                        <td colspan="4">Tạm tính:</td>
                                        <td class="tamtinh" id="tamtinh"><?= $tamtinh_format ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">Phí VAT:</td>
                                        <td id="vat">5%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">Giảm giá:</td>
                                        <td><input type="number" value="0" name="giamgia" id="giamgia" min="0" max="20"
                                                required> %</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">Tổng tiền:</td>
                                        <td class="tongtien" id="tongtien"><?= $tongtien_format ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Thanh toán bằng:</td>
                                        <td colspan="2">
                                            <div class="radio-group">
                                                <label><input type="radio" name="payment" value="Thẻ ngân hàng"
                                                        id="bank" required>Thẻ ngân hàng</label>
                                                <label><input type="radio" name="payment" value="Tiền mặt" id="cash"
                                                        required>Tiền mặt</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            <button type="button" onclick="processPayment()">Thanh toán</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Hidden inputs to pass the calculated data to the backend -->
                            <input type="hidden" name="tamtinh" value="<?= $tamtinh ?>">
                            <input type="hidden" name="tongtien" value="<?= $tongtien ?>">
                            <input type="hidden" name="tb_id" value="<?= $tb_id ?>">
                        </form>

                        <?php 
// Đóng kết nối
$conn->close();
?>


                        <!-- Thêm mã QR -->
                        <div class="qrcode" id="qrcode" style="display: none;">
                            <h3>Quét mã để thanh toán</h3>
                            <canvas id="qr-code-canvas"></canvas>
                        </div>



                        <script>
                        $(document).ready(function() {
                            $('#giamgia').on('input', function() {
                                // Lấy giá trị của 'tamtinh' từ PHP
                                var tamtinh = parseFloat('<?php echo $tamtinh; ?>');
                                var vat = (tamtinh * 5) / 100;
                                var giamgia = parseFloat($(this).val());

                                // Tính tổng tiền sau khi áp dụng giảm giá
                                var tongtien = tamtinh + vat - ((tamtinh * giamgia) / 100);

                                // Định dạng lại tổng tiền và hiển thị
                                var tongtien_format = new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(tongtien);
                                $('#tongtien').text(tongtien_format);
                                // Cập nhật mã QR
                                var qrContent = 'Quán Hải sản Bé Nhỏ' +
                                    '\nTổng tiền: ' + tongtien_format + '\nXin cảm ơn!';
                                QRCode.toCanvas(document.getElementById('qr-code-canvas'), qrContent,
                                    function(error) {
                                        if (error) console.error(error);
                                    });
                            });
                        });
                        </script>
                        <!-- Thêm thư viện qrious -->
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

                        <script>
                        document.getElementById('bank').addEventListener('change', function() {
                            if (this.checked) {
                                var totalAmount = document.getElementById('tongtien').textContent.trim();
                                var qrContent = encodeURIComponent('Quan Hai san Be Nho' +
                                    '\nTong tien thanh toan: ' +
                                    totalAmount + 'd' + '\nXin cam on !');
                                var qrCode = new QRious({
                                    element: document.getElementById('qr-code-canvas'),
                                    size: 150,
                                    value: decodeURIComponent(qrContent) // Giải mã URL encoding
                                });

                                document.getElementById('qrcode').style.display = 'block';
                            }
                        });

                        document.getElementById('cash').addEventListener('change', function() {
                            if (this.checked) {
                                document.getElementById('qrcode').style.display = 'none';
                            }
                        });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src=" js/quanlygoimon.js">
    </script>
</body>

</html>