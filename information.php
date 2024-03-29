<?php
    require_once 'BE.php';

    if (!isLoggedIn()) {
        header('Location: index.php');
    }

    if (isFirstLogin())
    {
        header('Location: change-password.php');
    }

    $user_info = getUserInfo();

    require_once '_header.php';
?>

    <div class="thong-tin-ca-nhan">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="thong-tin-ca-nhan-content">
                        <h1>Thông tin cá nhân</h1>
                        <p>Thông tin cá nhân của bạn</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="thong-tin-ca-nhan-table">
                        <table class="table table-striped">
                            <tr>
                                <th>Tên đăng nhập</th>
                                <td><?php echo $user_info['user']; ?></td>
                            </tr>
                            <tr>
                                <th>Số dư</th>
                                <td><?php echo number_format($user_info['money']) ?> VNĐ</td>
                            </tr>
                            <tr>
                                <th>Trạng thái tài khoản</th>
                                <td><i><b><?php echo getStatus($user_info['confirm']); ?></b></i></td>
                            </tr>
                            <tr>
                                <th>Họ tên</th>
                                <td><?php echo $user_info['name']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $user_info['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Số điện thoại</th>
                                <td><?php echo $user_info['phone']; ?></td>
                            </tr>
                            <tr>
                                <th>Địa chỉ</th>
                                <td><?php echo $user_info['address']; ?></td>
                            </tr>
                            <tr>
                                <th>Ngày sinh</th>
                                <td><?php echo formatDate($user_info['birth']); ?></td>
                            </tr>
                            <tr>
                                <th>Mặt trước CMND</th>
                                <td><img src="./img/upload/<?php echo $user_info['face']; ?>" alt="" class="img-cccd"></td>
                            </tr>
                            <tr>
                                <th>Mặt sau CMND</th>
                                <td><img src="./img/upload/<?php echo $user_info['back']; ?>" alt=""  class="img-cccd"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>