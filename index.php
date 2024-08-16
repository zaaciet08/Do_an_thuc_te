<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileImage" content="./assets/img/Remove-bg.ai_1721935180990.png" />
    <title>BeNho Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
    <?php
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Truy vấn số lượng bàn theo từng trạng thái
$query = "
    SELECT 
        SUM(CASE WHEN tb_tinhtrang = 'Còn trống' THEN 1 ELSE 0 END) AS ban_trong,
        SUM(CASE WHEN tb_tinhtrang = 'Đang sử dụng' THEN 1 ELSE 0 END) AS dang_su_dung,
        SUM(CASE WHEN tb_tinhtrang = 'Bàn đặt' THEN 1 ELSE 0 END) AS da_dat
    FROM tbl_table
";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $ban_trong = $row['ban_trong'];
    $dang_su_dung = $row['dang_su_dung'];
    $da_dat = $row['da_dat'];
} else {
    $ban_trong = 0;
    $dang_su_dung = 0;
    $da_dat = 0;
}

$conn->close();
?>

    <main>
        <div class="container">
            <h2>Tổng quan</h2>
            <div class="container_table">
                <div class="bandangsudung">
                    <p>Đang sử dụng</p>
                    <span class="tongso"><?php echo $dang_su_dung; ?></span>
                </div>
                <div class="bantrong">
                    <p>Bàn trống</p>
                    <span class="tongso"><?php echo $ban_trong; ?></span>
                </div>
                <div class="bandat">
                    <p>Bàn đặt</p>
                    <span class="tongso"><?php echo $da_dat; ?></span>
                </div>
            </div>
        </div>
    </main>



    <script src="js/main.js"></script>
</body>

</html>