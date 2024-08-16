<?php
include 'db/connect.php'; // Kết nối đến cơ sở dữ liệu

header('Content-Type: application/json'); // Đặt kiểu dữ liệu trả về là JSON

$response = array('status' => 'error', 'message' => 'Đã có lỗi xảy ra');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_id = $_POST['ma_id'];
    $tb_id = $_POST['tb_id'];
    $ct_ten = $_POST['ct_ten'];
    $ct_gia = $_POST['ct_gia'];
    $ct_soluong = $_POST['ct_soluong'];
    $ct_cachchebien = $_POST['ct_cachchebien'];

    // Kiểm tra nếu món ăn đã tồn tại
    $sql_check = "SELECT * FROM tbl_chitietsudung WHERE ma_id = ? AND tb_id = ? AND ct_cachchebien = ?";
    
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("iis", $ma_id, $tb_id, $ct_cachchebien);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Món ăn đã tồn tại, cập nhật số lượng
            $row_check = $result_check->fetch_assoc();
            $new_soluong = $row_check['ct_soluong'] + $ct_soluong;

            $sql_update = "UPDATE tbl_chitietsudung SET ct_soluong = ? WHERE ma_id = ? AND tb_id = ? AND ct_cachchebien = ?";
            
            if ($stmt_update = $conn->prepare($sql_update)) {
                $stmt_update->bind_param("diss", $new_soluong, $ma_id, $tb_id, $ct_cachchebien);
                
                if ($stmt_update->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Đã cập nhật số lượng món!';
                } else {
                    $response['message'] = "Lỗi: " . $stmt_update->error;
                }

                $stmt_update->close();
            } else {
                $response['message'] = "Lỗi: " . $conn->error;
            }

        } else {
            // Món ăn chưa tồn tại, thêm mới
            $sql_insert = "INSERT INTO tbl_chitietsudung (ma_id, tb_id, ct_ten, ct_gia, ct_soluong, ct_cachchebien)
                           VALUES (?, ?, ?, ?, ?, ?)";

            if ($stmt_insert = $conn->prepare($sql_insert)) {
                $stmt_insert->bind_param("iisids", $ma_id, $tb_id, $ct_ten, $ct_gia, $ct_soluong, $ct_cachchebien);
                
                if ($stmt_insert->execute()) {
                    // Sau khi gọi món thành công, cập nhật trạng thái của bàn
                    $sql_update_table = "UPDATE tbl_table SET tb_tinhtrang = 'Đang sử dụng' WHERE tb_id = ?";
                    
                    if ($stmt_update_table = $conn->prepare($sql_update_table)) {
                        $stmt_update_table->bind_param("i", $tb_id);
                        
                        if ($stmt_update_table->execute()) {
                            $response['status'] = 'success';
                            $response['message'] = 'Đã gọi món !';
                        } else {
                            $response['message'] = "Lỗi: " . $stmt_update_table->error;
                        }

                        $stmt_update_table->close();
                    } else {
                        $response['message'] = "Lỗi: " . $conn->error;
                    }

                } else {
                    $response['message'] = "Lỗi: " . $stmt_insert->error;
                }

                $stmt_insert->close();
            } else {
                $response['message'] = "Lỗi: " . $conn->error;
            }
        }

        $stmt_check->close();
    } else {
        $response['message'] = "Lỗi: " . $conn->error;
    }

    $conn->close();
}

echo json_encode($response);
?>