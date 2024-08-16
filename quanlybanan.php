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
    <link rel="stylesheet" href="assets/css/quanlybanan.css">
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
    <main>
        <div class="container">
            <div class="back" onclick="addTable()">
                Thêm bàn mới
            </div>

            <h2>Quản lý bàn ăn</h2>
            <div class="container_table">
                <?php 

    $sql = "SELECT * FROM tbl_table";
    $result = $conn->query($sql);

    // Hiển thị dữ liệu
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="banan">';
            echo '<h3>Bàn: ' . $row["tb_id"] . '</h3>';
            echo '<span class="tinhtrang">' . $row["tb_tinhtrang"] . '</span><br>';
            // Kiểm tra tình trạng và hiển thị hoặc ẩn button
            if ($row["tb_tinhtrang"] == "Còn trống") {
                echo '<a href="#" onclick="openModal(' . $row["tb_id"] . ')">Đặt bàn</a>';
                echo '<a href="quanlygoimon.php?tb_id='.$row["tb_id"].'" class="bn13" type="button" ><i class="fa-solid fa-plus"></i></a>';
            }else if ($row["tb_tinhtrang"] == "Đang sử dụng") {
                echo '<a href="quanlygoimon.php?tb_id='.$row["tb_id"].'">Xem chi tiết</a>';
            }else if ($row["tb_tinhtrang"] == "Bàn đặt"){
                echo '<a href="#">Xem chi tiết bàn đặt</a>';
            }
            
            echo '</div>';
        }
    } else {
        echo "Không có kết quả nào.";
    }
    // Đóng kết nối
    $conn->close();
    ?>
                <div id="myModal" class="modal">
                    <form id="reservationForm" action="quanlybanan.php" method="post" onsubmit="return validateForm()">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal()">&times;</span>
                            <h2>Bàn: <span id="tableId"></span></h2>
                            <label for="date">Ngày:</label>
                            <input type="date" id="date" name="date"><br>
                            <label for="time">Thời gian:</label>
                            <input type="time" id="time" name="time"><br>
                            <label for="people">Số lượng:</label>
                            <input type="number" min="1" id="people" name="people"><br>
                            <label for="deposit">Tiền cọc:</label>
                            <input type="text" id="deposit" name="deposit"><br>
                            <button type="button" class="btn_datban" onclick="submitForm()" style="cursor: pointer;">Xác
                                nhận</button>
                        </div>
                    </form>
                </div>


                <script>
                function openModal(tableId) {
                    $("#myModal").show();
                    $("#tableId").text(tableId);
                }

                function closeModal() {
                    $("#myModal").hide();
                }

                function submitForm() {
                    // Lấy dữ liệu từ form
                    var tableId = $("#tableId").text();
                    console.log("Table ID: " + tableId); // Kiểm tra giá trị của tableId

                    var formData = {
                        date: $("#date").val(),
                        time: $("#time").val(),
                        people: $("#people").val(),
                        deposit: $("#deposit").val(),
                        tableId: tableId
                    };

                    // Gửi dữ liệu lên server bằng AJAX
                    $.ajax({
                        type: "POST",
                        url: "save_table_id.php",
                        data: formData,
                        success: function(response) {
                            console.log(response); // Kiểm tra phản hồi từ server
                            Swal.fire({
                                icon: 'success',
                                title: 'Đặt bàn thành công!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.href = 'quanlybanan.php';
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("Lỗi AJAX: " + status + " - " + error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Đã xảy ra lỗi!',
                                text: 'Vui lòng thử lại.',
                                showConfirmButton: true
                            });
                        }
                    });
                }
                </script>
            </div>
        </div>
    </main>

    <script src="js/quanlybanan.js"></script>
</body>

</html>