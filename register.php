<?php
    require_once 'BE.php';

    if (isLoggedIn()) {
        header('Location: index.php');
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>

    
    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- SweetAlert CDN -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">

    <!-- Custom JS -->
    <script src="./main.js"></script>

</head>
<body>
    <div class="dang-ky">
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="header-content">
                            <h1>Ví điện tử</h1>
                            <p>Chào mừng bạn đến với Ví điện tử</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-content">
                        <div class="login-header">
                            <h3>Đăng ký</h3>
                        </div>
                        <div class="login-body">
                            <form>
                                <div class="form-group">
                                    <label for="name">Họ tên</label>
                                    <input type="text" class="form-control" id="name" placeholder="Tên">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại">
                                </div>
                                <div class="form-group">
                                    <label for="birthday">Ngày sinh</label>
                                    <input type="date" class="form-control" id="birthday" name="birthday" placeholder="Ngày sinh">
                                </div>
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Địa chỉ">
                                </div>
                                <div class="form-group">
                                    <label for="face">Mặt trước chứng minh nhân dân</label>
                                    <input type="file" class="form-control" id="face" name="face" placeholder="Mặt trước chứng minh nhân dân">
                                </div>
                                <div class="form-group">
                                    <label for="back">Mặt sau chứng minh nhân dân</label>
                                    <input type="file" class="form-control" id="back" name="back" placeholder="Mặt sau chứng minh nhân dân">
                                </div>
                                <button type="button" class="btn btn-primary" id="btn-register">Đăng ký</button>
                                <div class="form-group flex-space-bw">
                                    <a href="index.php">Đăng nhập</a>
                                    <a href="forgot-password.php">Quên mật khẩu?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>