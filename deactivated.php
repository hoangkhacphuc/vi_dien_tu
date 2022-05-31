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
                    <h1>Vô hiệu hóa</h1>
                    <p>Danh sách tài khoản bị vô hiệu hóa</p>
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
                                $list = getListAccountDeactivated();
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
                                    <button class="btn btn-success" data-id="<?= $value['id'] ?>" data-type="1">Kích hoạt</button>
                                    <button class="btn btn-warning" data-id="<?= $value['id'] ?>" data-type="4">Chờ cập nhật</button>
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