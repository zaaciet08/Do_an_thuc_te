<?php
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "doanthucte");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_POST['ma_id'])) {
    $ma_id = intval($_POST['ma_id']);

    $sql = "SELECT * FROM tbl_monan WHERE ma_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ma_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(["status" => "error", "message" => "Không tìm thấy dữ liệu."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID không hợp lệ."]);
}

$conn->close();
?>