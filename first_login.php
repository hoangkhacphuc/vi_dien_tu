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
    <title>Đăng nhập lần đầu</title>

    
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
    <div class="dang-nhap">
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
                            <h3>Đăng nhập</h3>
                        </div>
                        <div class="login-body">
                            <form action="index.php" method="POST">
                                <div class="form-group">
                                    <label for="username">Tài khoản</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Tài khoản">
                                </div>
                                <div class="form-group">
                                    <label for="password">Mật khẩu</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                                </div>
                                <div class="form-group flex-space-bw">
                                    <a href="register.php">Đăng ký</a>
                                    <a href="change-password.php">Đổi mật khẩu</a>
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