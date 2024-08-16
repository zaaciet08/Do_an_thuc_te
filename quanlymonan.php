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
    <link rel="stylesheet" href="assets/css/quanlymonan.css">
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
            <h2>Quản lý món ăn</h2>
            <a href="#" class="btn_them" onclick="openModalThem()">Thêm món ăn</a>
            <!--Modal thêm món ăn-->
            <div id="myModalThem" class="modalThem">
                <form id="reservationFormThem" action="quanlymonan.php" method="post" onsubmit="return validateForm()">
                    <div class="modal-contentThem">
                        <span class="closeThem" onclick="closeModalThem()">&times;</span>
                        <h2>Thêm món ăn</h2>
                        <div class="form-groupThem">
                            <label for="edit_ma_tenThem">Tên món:</label>
                            <input type="text" id="edit_ma_tenThem" name="ma_tenThem">
                        </div>
                        <div class="form-groupThem">
                            <label for="edit_ma_giaThem">Giá (VND):</label>
                            <input type="text" id="edit_ma_giaThem" name="ma_giaThem">
                        </div>
                        <div class="form-groupThem">
                            <label for="edit_ma_dvtinhThem">Đơn vị tính:</label>
                            <input type="text" id="edit_ma_dvtinhThem" name="ma_dvtinh">
                        </div>
                        <div class="form-groupThem">
                            <label for="edit_ma_cachchebienThem">Cách chế biến:</label>
                            <input type="text" id="edit_ma_cachchebienThem" name="ma_cachchebienThem">
                        </div>
                        <button type="button" onclick="AddItem()" style="cursor: pointer;">Thêm món ăn</button>
                    </div>
                </form>
            </div>
            <div class="container_table">
                <div class="timkiem">
                    <input type="text" class="search-bar" placeholder="Tìm kiếm món ăn...">
                    <div class="search-results">
                        <table class="result-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên món</th>
                                    <th>Giá/Đơn vị</th>
                                    <th>Cách chế biến</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="results-body">
                                <?php
                                $sql = "SELECT * FROM tbl_monan";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>' . $row["ma_id"] . '</td>';
                                        echo '<td>' . $row["ma_ten"] . '</td>';
                                        echo '<td>' . number_format($row['ma_gia'], 0, ',', '.') . 'đ/' . $row['ma_dvtinh'] . '</td>';
                                        echo '<td>';
                                        if($row['ma_cachchebien'] == 0){
                                            echo 'Mặc định';
                                        } else {
                                            $cach_che_bien = explode(',', $row['ma_cachchebien']);
                                            echo '<select class="cachchebien">';
                                            foreach ($cach_che_bien as $cach) {
                                                echo '<option value="' . trim($cach) . '">' . trim($cach) . '</option>';
                                            }
                                            echo '</select>';
                                        }
                                        echo '</td>';
                                        echo '<td><a href="#" class="btn_chinhsua" onclick="openModal(' . $row["ma_id"] . ')">Chỉnh sửa</a> &ensp;&ensp;<button type="button" class="btn_xoa" onclick="deleteItem(' . $row["ma_id"] . ')">Xóa</button></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6">Không có kết quả nào.</td></tr>';
                                }
                                $conn->close();
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <!--Modal chỉnh sửa-->
                    <div id="myModal" class="modal">
                        <form id="reservationForm" action="quanlymonan.php" method="post"
                            onsubmit="return validateForm()">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal()">&times;</span>
                                <h2>Chỉnh sửa món ăn</h2>

                                <input type="hidden" id="edit_ma_id">
                                <div class="form-group">
                                    <label for="edit_ma_ten">Tên món:</label>
                                    <input type="text" id="edit_ma_ten" name="ma_ten">
                                </div>
                                <div class="form-group">
                                    <label for="edit_ma_gia">Giá/kg:</label>
                                    <input type="text" id="edit_ma_gia" name="ma_gia">
                                </div>
                                <div class="form-group">
                                    <label for="edit_ma_cachchebien">Cách chế biến:</label>
                                    <input type="text" id="edit_ma_cachchebien" name="ma_cachchebien">
                                </div>
                                <button type="button" onclick="updateItem()" style="cursor: pointer;">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                    <!--Modal thêm món ăn-->
                </div>
            </div>
        </div>
    </main>


    <script src="js/quanlymonan.js"></script>
</body>


</html>