<?php
// Kết nối đến cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

// Kiểm tra kết nối
if (!$conn) {
    http_response_code(500); // Trả về mã lỗi 500 nếu không kết nối được
    die(json_encode(array('status' => 'error', 'message' => 'Kết nối không thành công: ' . mysqli_connect_error())));
}

// Xử lý thêm bàn mới vào bảng tbl_table
$sql = "INSERT INTO tbl_table (tb_tinhtrang) VALUES ('Còn trống')";

if ($conn->query($sql) === TRUE) {
    // Trả về kết quả thành công
    http_response_code(200);
    echo json_encode(array('status' => 'success', 'message' => 'Bàn mới đã được thêm!'));
} else {
    // Trả về lỗi
    http_response_code(500);
    echo json_encode(array('status' => 'error', 'message' => 'Lỗi: ' . $conn->error));
}

// Đóng kết nối
$conn->close();
?>