$(document).ready(function () {
    $('#btn-register').click(function (e) { 
        e.preventDefault();
        var formData = new FormData();
        formData.append('email', $('#email').val());
        formData.append('phone', $('#phone').val());
        formData.append('address', $('#address').val());
        formData.append('name', $('#name').val());
        formData.append('birthday', $('#birthday').val());
        formData.append('face', $('#face')[0].files[0]);
        formData.append('back', $('#back')[0].files[0]);

        $.ajax({
            url: './BE.php?action=register',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data == '')
                {
                    swal('Thất bại', 'Đăng ký thất bại', 'error');
                    return;
                }
                data = JSON.parse(data);
                if (data.status == 400)
                {
                    swal('Thất bại', data.message, 'error');
                    return;
                }
                swal('Thành công', 'Đăng ký thành công', 'success');
                setTimeout(function () {
                    window.location.href = './index.php';
                }
                , 2000);
            }
        });
    });
});