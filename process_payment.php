<?php
// Kết nối đến cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

// Kiểm tra kết nối
if (!$conn) {
    http_response_code(500); // Trả về mã lỗi 500 nếu không kết nối được
    die("Kết nối không thành công: " . mysqli_connect_error());
}

// Lấy dữ liệu từ AJAX request
$tamtinh = isset($_POST['tamtinh']) ? floatval($_POST['tamtinh']) : 0;
$giamgia = isset($_POST['giamgia']) ? floatval($_POST['giamgia']) : 0;
$giatrivat = 5;
$phivat = ($tamtinh * 5) / 100;
$thanhtien = $tamtinh + $phivat - ($tamtinh * $giamgia) / 100;
$phuongthuc = isset($_POST['payment']) ? $_POST['payment'] : '';
$tb_id = isset($_POST['tb_id']) ? intval($_POST['tb_id']) : 0;
function generateRandomString($length = 8) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

// Sử dụng hàm để tạo chuỗi ngẫu nhiên
$randomString = generateRandomString();
if ($tb_id && $phuongthuc) {
    // Bắt đầu transaction
    $conn->begin_transaction();

    try {
        // Thêm hóa đơn vào bảng tbl_hoadon
        $sql = "INSERT INTO tbl_hoadon (hd_code,hd_tamtinh, hd_phivat, hd_giamgia, hd_thanhtien, hd_phuongthuc, tb_id)
                VALUES (?,?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sddddsi", $randomString ,$tamtinh, $giatrivat, $giamgia, $thanhtien, $phuongthuc, $tb_id);
        
        if ($stmt->execute()) {
            // Lấy id của hóa đơn vừa được thêm
            $hd_id = $stmt->insert_id;
            
            // Truy vấn các món từ tbl_chitietsudung
            $sql_chitiet = "SELECT * FROM tbl_chitietsudung WHERE tb_id = ?";
            $stmt_chitiet = $conn->prepare($sql_chitiet);
            $stmt_chitiet->bind_param("i", $tb_id);
            $stmt_chitiet->execute();
            $result_chitiet = $stmt_chitiet->get_result();
            
            // Thêm từng chi tiết vào tbl_chitiethoadon
            while ($row_chitiet = $result_chitiet->fetch_assoc()) {
                $ma_id = $row_chitiet['ma_id'];
                $cthd_ten = $row_chitiet['ct_ten'];
                $cthd_gia = $row_chitiet['ct_gia'];
                $cthd_soluong = $row_chitiet['ct_soluong'];
                $cthd_cachchebien = $row_chitiet['ct_cachchebien'];
                
                $sql_insert_cthd = "INSERT INTO tbl_chitiethoadon (ma_id, cthd_ten, cthd_gia, cthd_soluong, cthd_cachchebien, hd_id)
                                    VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert_cthd = $conn->prepare($sql_insert_cthd);
                $stmt_insert_cthd->bind_param("isidsi", $ma_id, $cthd_ten, $cthd_gia, $cthd_soluong, $cthd_cachchebien, $hd_id);
                $stmt_insert_cthd->execute();
            }

            // Xóa tất cả món ăn trong bảng tbl_chitietsudung tại tb_id
            $sql_delete_chitiet = "DELETE FROM tbl_chitietsudung WHERE tb_id = ?";
            $stmt_delete_chitiet = $conn->prepare($sql_delete_chitiet);
            $stmt_delete_chitiet->bind_param("i", $tb_id);
            $stmt_delete_chitiet->execute();

            // Cập nhật trạng thái tb_tinhtrang trong bảng tbl_table thành 'Còn trống'
            $sql_update_table = "UPDATE tbl_table SET tb_tinhtrang = 'Còn trống' WHERE tb_id = ?";
            $stmt_update_table = $conn->prepare($sql_update_table);
            $stmt_update_table->bind_param("i", $tb_id);
            $stmt_update_table->execute();
            
            // Hoàn tất transaction
            $conn->commit();
            
            // Trả về mã thành công 200
            http_response_code(200);
            echo "success";
        } else {
            throw new Exception("Error inserting invoice.");
        }
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $conn->rollback();
        http_response_code(500);
        echo "error";
    }
    
    // Đóng statement
    $stmt->close();
} else {
    http_response_code(400); // Trả về mã lỗi 400 nếu dữ liệu không hợp lệ
    echo "invalid";
}

// Đóng kết nối
$conn->close();
?>