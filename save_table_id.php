<?php
include_once 'db/connect.php';
// Kiểm tra nếu request method là POST và dữ liệu đã được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = $_POST['people'];
    $deposit = $_POST['deposit'];
    $tableId = $_POST['tableId'];

    // Chuẩn bị và thực thi câu lệnh SQL để chèn dữ liệu vào bảng tbl_bandat
    $sql = "INSERT INTO tbl_bandat (bd_ngay, bd_thoigian, bd_soluong, bd_tiencoc, tb_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $date, $time, $people, $deposit, $tableId);

    if ($stmt->execute()) {
        // Sau khi thêm dữ liệu vào bảng tbl_bandat, cập nhật trường tb_trangthai của bảng tbl_table
        $update_sql = "UPDATE tbl_table SET tb_tinhtrang = 'Bàn đặt' WHERE tb_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $tableId);
        $update_stmt->execute();

        echo json_encode([
            'status' => 'success',
            'message' => 'Đặt bàn thành công!'
            
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Lỗi: ' . $stmt->error
        ]);
    }

    // Đóng kết nối
    $stmt->close();
    $update_stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Yêu cầu không hợp lệ.'
    ]);
}
?>
