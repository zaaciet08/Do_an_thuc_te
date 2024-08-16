<?php
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_ten = $_POST['ma_ten'];
    $ma_gia = $_POST['ma_gia'];
    $ma_dvtinh = $_POST['ma_dvtinh'];
    $ma_cachchebien = $_POST['ma_cachchebien'];

    // Kiểm tra xem tất cả các trường có giá trị hay không
    if (empty($ma_ten) || empty($ma_gia) || empty($ma_dvtinh)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin.']);
        exit();
    }

    // Lấy ma_id lớn nhất hiện tại trong bảng
    $result = $conn->query("SELECT MAX(ma_id) AS max_id FROM tbl_monan");
    $row = $result->fetch_assoc();
    $next_id = $row['max_id'] + 1;

    // Thêm món ăn mới vào cơ sở dữ liệu
    $sql = "INSERT INTO tbl_monan (ma_id, ma_ten, ma_gia, ma_dvtinh, ma_cachchebien) VALUES ('$next_id', '$ma_ten', '$ma_gia', '$ma_dvtinh', '$ma_cachchebien')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Thêm món ăn thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi thêm món ăn: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
}
?>