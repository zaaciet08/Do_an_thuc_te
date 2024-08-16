function toggleClasses(button){
    button.classList.toggle('opened');
    document.querySelector('.sidebar').classList.toggle('open');
}
document.querySelector('.search-bar').addEventListener('input', function() {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll('#results-body tr');

    rows.forEach(function(row) {
        var cells = row.querySelectorAll('td');
        var match = false;

        cells.forEach(function(cell) {
            if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
            }
        });

        if (match) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
function deleteItem(ma_id) {
    // Hiển thị hộp thoại xác nhận bằng SweetAlert2
    Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa món ăn này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Nếu nhấn "Xóa", gửi yêu cầu xóa đến server
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_monan.php", true); // Đảm bảo delete_monan.php là file xử lý trên server
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                // Chuyển hướng đến trang với đúng tb_id
                                window.location.href = 'quanlymonan.php';
                            });

                            // Xóa hàng khỏi bảng
                            var row = document.querySelector(`tr[data-id="${ma_id}"]`);
                            if (row) {
                                row.remove();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Có lỗi xảy ra',
                                text: response.message
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Có lỗi xảy ra',
                            text: 'Có lỗi xảy ra với yêu cầu.'
                        });
                    }
                }
            };

            xhr.send("ma_id=" + encodeURIComponent(ma_id));
        }
    });
}
function updateItem() {
    var ma_id = $('#edit_ma_id').val();
    var ma_ten = $('#edit_ma_ten').val();
    var ma_gia = $('#edit_ma_gia').val();
    var ma_cachchebien = $('#edit_ma_cachchebien').val();

    $.ajax({
        url: 'update_monan.php',
        type: 'POST',
        data: {
            ma_id: ma_id,
            ma_ten: ma_ten,
            ma_gia: ma_gia,
            ma_cachchebien: ma_cachchebien
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    // Chuyển hướng đến trang với đúng tb_id
                    window.location.href = 'quanlymonan.php';
                });

            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert("Có lỗi xảy ra trong quá trình cập nhật.");
        }
    });
}

function openModal(ma_id) {
    $.ajax({
        url: 'get_mon_an.php',
        type: 'POST',
        data: {
            ma_id: ma_id
        },
        dataType: 'json',
        success: function(response) {
            if (response.status !== "error") {
                // Điền dữ liệu vào form
                $('#edit_ma_id').val(response.ma_id);
                $('#edit_ma_ten').val(response.ma_ten);
                $('#edit_ma_gia').val(response.ma_gia);
                $('#edit_ma_cachchebien').val(response.ma_cachchebien);


                // Hiển thị modal
                $("#myModal").show();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert("Có lỗi xảy ra trong quá trình lấy dữ liệu.");
        }
    });
}

function closeModal() {
    $("#myModal").hide();
}

// Đóng modal khi nhấp vào bên ngoài modal
window.onclick = function(event) {
    if (event.target == document.getElementById("myModal")) {
        closeModal();
    }
}
function openModalThem() {
    $("#myModalThem").show();
}

function closeModalThem() {
    $("#myModalThem").hide();
}
// Đóng modal khi nhấp vào bên ngoài modal
window.onclick = function(event) {
    if (event.target == document.getElementById("myModalThem")) {
        closeModalThem();
    }
}
function AddItem() {
    var ma_ten = $('#edit_ma_tenThem').val();
    var ma_gia = $('#edit_ma_giaThem').val();
    var ma_dvtinh = $('#edit_ma_dvtinhThem').val();
    var ma_cachchebien = $('#edit_ma_cachchebienThem').val();

    $.ajax({
        url: 'add_monan.php',
        type: 'POST',
        data: {
            ma_ten: ma_ten,
            ma_gia: ma_gia,
            ma_dvtinh: ma_dvtinh,
            ma_cachchebien: ma_cachchebien
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    // Chuyển hướng đến trang với đúng tb_id
                    window.location.href = 'quanlymonan.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: response.message,
                    showConfirmButton: true
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Có lỗi xảy ra trong quá trình thêm món ăn.',
                showConfirmButton: true
            });
        }
    });
}

