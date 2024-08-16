function toggleClasses(button){
    button.classList.toggle('opened');
    document.querySelector('.sidebar').classList.toggle('open');
}


function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == document.getElementById("myModal")) {
        closeModal();
    }
}
function validateForm() {
    var depositField = document.getElementById("deposit");
    var depositValue = depositField.value;

    // Check if the value is a positive number without special characters or letters
    if (!/^\d+(\.\d+)?$/.test(depositValue) || parseFloat(depositValue) <= 0) {
        alert("Vui lòng nhập số tiền cọc hợp lệ !");
        return false;
    }

    return true;
}

function addTable() {
    // Gửi yêu cầu AJAX để thêm bàn mới
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_table.php", true); // Đảm bảo add_table.php là file xử lý trên server
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        showConfirmButton: false,
                        timer: 1500,
                        text: response.message
                    }).then(function() {
                        // Chuyển hướng đến trang với đúng tb_id
                        window.location.href = 'quanlybanan.php';
                    });
                } else {
                    alert('Có lỗi xảy ra: ' + response.message);
                }
            } else {
                alert('Có lỗi xảy ra với yêu cầu.');
            }
        }
    };

    xhr.send();
}