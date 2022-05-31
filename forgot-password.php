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
    <title>Quên mật khẩu</title>

    
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
    <div class="quen-mat-khau">
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
        <!-- Form quên mật khẩu -->
        <div class="container">
            <div class="row" id="row-email">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-content">
                        <div class="login-header">
                            <h3>Quên mật khẩu</h3>
                        </div>
                        <div class="login-body">
                            <form action="forgot-password.php" method="post">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email">
                                </div>
                                <button type="button" id="btn-forgot-password" class="btn btn-primary">Gửi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="row-otp">
                <div class="col-md-4 col-md-offset-4">
                    <div class="otp-content">
                        <div class="otp-header">
                            <h3>Nhập OTP</h3>
                        </div>
                        <div class="otp-body">
                            <form action="forgot-password.php" method="post">
                                <div class="form-group">
                                    <label for="otp">OTP</label>
                                    <input type="text" class="form-control" id="otp" name="otp" placeholder="Nhập OTP">
                                </div>
                                 <div class="form-group">
                                    <label for="">Mật khẩu mới</label>
                                    <input type="password" id="new-password" class="form-control" placeholder="Nhập mật khẩu mới">
                                </div>
                                <div class="form-group">
                                    <label for="">Nhập lại mật khẩu</label>
                                    <input type="password" id="re-password" class="form-control" placeholder="Nhập lại mật khẩu">
                                </div>
                                <button type="button" id="btn-verify-otp" class="btn btn-primary">Xác nhận</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>