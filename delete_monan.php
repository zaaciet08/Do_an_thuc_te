<?php
// Kết nối đến cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

// Kiểm tra kết nối
if (!$conn) {
    http_response_code(500); // Trả về mã lỗi 500 nếu không kết nối được
    die(json_encode(array('status' => 'error', 'message' => 'Kết nối không thành công: ' . mysqli_connect_error())));
}

// Lấy dữ liệu từ AJAX request
$ma_id = isset($_POST['ma_id']) ? intval($_POST['ma_id']) : 0;

if ($ma_id) {
    // Thực hiện câu lệnh xóa món ăn
    $sql = "DELETE FROM tbl_monan WHERE ma_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $ma_id);
        
        if ($stmt->execute()) {
            // Trả về kết quả thành công
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'message' => 'Món ăn đã được xóa!'));
        } else {
            // Trả về lỗi
            http_response_code(500);
            echo json_encode(array('status' => 'error', 'message' => 'Lỗi: ' . $stmt->error));
        }

        $stmt->close();
    } else {
        // Trả về lỗi nếu không thể chuẩn bị câu lệnh
        http_response_code(500);
        echo json_encode(array('status' => 'error', 'message' => 'Lỗi: ' . $conn->error));
    }
} else {
    http_response_code(400); // Trả về mã lỗi 400 nếu dữ liệu không hợp lệ
    echo json_encode(array('status' => 'error', 'message' => 'Dữ liệu không hợp lệ.'));
}

// Đóng kết nối
$conn->close();
?>