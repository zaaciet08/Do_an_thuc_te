<?php
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_POST['ma_id']) && isset($_POST['ma_ten']) && isset($_POST['ma_gia']) && isset($_POST['ma_cachchebien'])) {
    $ma_id = intval($_POST['ma_id']);
    $ma_ten = $_POST['ma_ten'];
    $ma_gia = $_POST['ma_gia'];
    $ma_cachchebien = $_POST['ma_cachchebien'];

    $sql = "UPDATE tbl_monan SET ma_ten = ?, ma_gia = ?, ma_cachchebien = ? WHERE ma_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $ma_ten, $ma_gia, $ma_cachchebien, $ma_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Cập nhật thành công."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Cập nhật thất bại."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ."]);
}

$conn->close();
?>