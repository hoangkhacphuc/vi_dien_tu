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

    $('#btn-login').click(function (e) { 
        e.preventDefault();
        // Lấy dữ liệu từ form
        let username = $('#username').val();
        let password = $('#password').val();

        // POST dữ liệu đến server
        $.post('./BE.php?action=login', {
            username: username,
            password: password
        }, function (data) {
            if (data == '')
            {
                swal('Thất bại', 'Đăng nhập thất bại', 'error');
                return;
            }
            
            data = JSON.parse(data);
            console.log(data);
            if (data.status == 400)
            {
                swal('Thất bại', data.message, 'error');
                return;
            }
            swal('Thành công', 'Đăng nhập thành công', 'success');
            setTimeout(function () {
                window.location.href = './index.php';
            }
            , 2000);
        });
    });

    $('#btn-change-pass').click(function (e) { 
        e.preventDefault();
        // Get value 
        let oldPass = $('#old-password').val() || '';
        let newPass = $('#new-password').val();
        let confirmPass = $('#re-password').val();

        // POST dữ liệu đến server
        $.post('./BE.php?action=change-pass', {
            password: oldPass,
            'new-password': newPass,
            'confirm-password': confirmPass
        }, function (data) {
            if (data == '')
            {
                swal('Thất bại', 'Đổi mật khẩu thất bại', 'error');
                return;
            }
            data = JSON.parse(data);
            if (data.status == 400)
            {
                swal('Thất bại', data.message, 'error');
                return;
            }
            swal('Thành công', 'Đổi mật khẩu thành công', 'success');
            setTimeout(function () {
                window.location.href = './index.php';
            }
            , 2000);
        });
    });
});