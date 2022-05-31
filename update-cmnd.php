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

    <div class="update-cmnd">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="update-cmnd-content">
                        <h1>Cập nhật chứng minh nhân dân</h1>
                        <p>Cập nhật mặt trước và mặt sau chứng minh nhân dân mới</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="update-cmnd-table">
                        <table class="table table-striped">
                            <tr>
                                <th>Mặt trước</th>
                                <td><img src="./img/upload/<?= $user_info['face'] ?>" alt="" class="img-cccd"></td>
                            </tr>
                            <tr>
                                <th>Mặt sau CMND</th>
                                <td><img src="./img/upload/<?php echo $user_info['back']; ?>" alt=""  class="img-cccd"></td>
                            </tr>
                            <tr>
                                <th>Upload mặt trước CMND</th>
                                <td><input type="file" name="face" id="face" required></td>
                            </tr>
                            <tr>
                                <th>Upload mặt sau CMND</th>
                                <td><input type="file" name="back" id="back" required></td>
                            </tr>

                        </table>
                        <button type="button" class="btn btn-primary mg-btn" id="btn-update-cmnd">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>