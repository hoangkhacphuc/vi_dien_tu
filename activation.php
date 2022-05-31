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

?>

<div class="cho-kich-hoat">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="cho-kich-hoat-content">
                    <h1>Chọn kích hoạt</h1>
                    <p>Chọn tài khoản cần kích hoạt</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="cho-kich-hoat-content">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tài khoản</th>
                                <th>Họ và tên</th>
                                <th>Ngày sinh</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $list = getListAccountWaitingActivation();
                                foreach ($list as $value) :
                            ?>
                            <tr>
                                <td><?= $value['user'] ?></td>
                                <td><?= $value['name'] ?></td>
                                <td><?= formatDate($value['birth']) ?></td>
                                <td><?= $value['email'] ?></td>
                                <td><?= $value['phone'] ?></td>
                                <td><?= $value['address'] ?></td>
                                <td><?= getStatusAccount($value['confirm']) ?></td>
                                <td id="btn-change-confirm">
                                    <a href="detailAccount.php?id=<?= $value['id'] ?>" class="btn btn-primary">Chi tiết</a>
                                    <?php if ($value['confirm'] == 0) : ?>
                                        <button class="btn btn-success" data-id="<?= $value['id'] ?>" data-type="1">Kích hoạt</button>
                                        <button class="btn btn-danger" data-id="<?= $value['id'] ?>" data-type="2">Vô hiệu hóa</button>
                                        <button class="btn btn-warning" data-id="<?= $value['id'] ?>" data-type="4">Chờ cập nhật</button>
                                    <?php else : if ($value['confirm'] == 4) : ?>
                                        <button class="btn btn-success" data-id="<?= $value['id'] ?>" data-type="1">Xác minh</button>
                                        <button class="btn btn-warning" data-id="<?= $value['id'] ?>" data-type="4">Chờ cập nhật</button>
                                    <?php endif;
                                    endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>  
    </div>

</div>


</body>
</html>