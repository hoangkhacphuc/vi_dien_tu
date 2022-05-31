<?php
    require_once 'BE.php';

    if (!isLoggedIn()) {
        header('Location: index.php');
    }

    if (!isAdmin())
    {
        header('Location: index.php');
    }

    require_once '_headerAdmin.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $user_info = getUserInfo($id);
    }   else {
?>
<script>
    window.location.href = 'activation.php';
</script>
<?php } ?>
    <div class="thong-tin-ca-nhan">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="thong-tin-ca-nhan-content">
                        <h1>Thông tin chi tiết</h1>
                        <p>Thông tin cá nhân của tài khoản</p>
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
                            <tr>
                                <th>Thao tác</th>
                                <td id="btn-change-confirm">
                                    <?php if ($user_info['confirm'] == 0) : ?>
                                        <button class="btn btn-success" data-id="<?= $user_info['id'] ?>" data-type="1">Kích hoạt</button>
                                        <button class="btn btn-danger" data-id="<?= $user_info['id'] ?>" data-type="2">Vô hiệu hóa</button>
                                        <button class="btn btn-warning" data-id="<?= $user_info['id'] ?>" data-type="4">Chờ cập nhật</button>
                                    <?php else : if ($user_info['confirm'] == 4) : ?>
                                        <button class="btn btn-success" data-id="<?= $user_info['id'] ?>" data-type="1">Xác minh</button>
                                        <button class="btn btn-warning" data-id="<?= $user_info['id'] ?>" data-type="4">Chờ cập nhật</button>
                                    <?php endif;
                                    endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>