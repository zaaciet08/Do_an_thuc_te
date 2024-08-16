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

function submitForm(button) {
    const row = button.closest('tr');
    const ma_id = row.cells[0].innerText;
    const ct_ten = row.cells[1].innerText;
    const ct_gia = row.cells[2].innerText.split('đ')[0].replace(/\./g, '');
    const ct_soluong = row.querySelector('.quantity').value;
    const ct_cachchebien = row.querySelector('.cachchebien') ? row.querySelector('.cachchebien').value : '';

    // Kiểm tra điều kiện
    if (parseFloat(ct_soluong) <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Số lượng phải lớn hơn 0!'
        });
        return;
    }

    if (ct_cachchebien === 'Chưa chọn') {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Cách chế biến chưa được chọn!'
        });
        return;
    }

    // Lấy giá trị tb_id từ thẻ HTML (thay đổi cách này nếu cần)
    const tb_id = document.querySelector('input[name="tb_id"]').value;

    $.ajax({
        url: 'submitgoimon.php',
        type: 'POST',
        data: {
            ma_id: ma_id,
            tb_id: tb_id,
            ct_ten: ct_ten,
            ct_gia: ct_gia,
            ct_soluong: ct_soluong,
            ct_cachchebien: ct_cachchebien
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    showConfirmButton: false,
                    timer: 1500,
                    text: response.message
                }).then(function() {
                    // Chuyển hướng đến trang với đúng tb_id
                    window.location.href = 'quanlygoimon.php?tb_id=' + encodeURIComponent(tb_id);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Đã xảy ra lỗi khi gửi dữ liệu.'
            });
        }
    });
}
function processPayment() {
    var formData = $('#paymentForm').serialize(); // Lấy tất cả dữ liệu trong form
  // Lấy giá trị tb_id từ thẻ HTML (thay đổi cách này nếu cần)
  const tb_id = document.querySelector('input[name="tb_id"]').value;
    $.ajax({
        type: "POST",
        url: "process_payment.php",
        data: formData,
        success: function(response) {
            Swal.fire({
                title: 'Thanh toán thành công!',
                text: 'Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'quanlygoimon.php?tb_id=' + encodeURIComponent(tb_id);
                }
            });
        },
        error: function() {
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi thanh toán. Vui lòng thử lại.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}
