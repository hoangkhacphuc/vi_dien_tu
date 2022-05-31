<?php
    require_once 'BE.php';

    if (!isLoggedIn()) {
        header('Location: index.php');
    }

    require_once '_header.php';
?>

    <div class="doi-mat-khau">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="doi-mat-khau-content">
                        <div class="doi-mat-khau-header">
                            <h3>Đổi mật khẩu</h3>
                        </div>
                        <div class="doi-mat-khau-body">
                            <form>
                                <?php
                                    if (!isFirstLogin()):
                                ?>
                                <div class="form-group">
                                    <label for="">Mật khẩu cũ</label>
                                    <input type="password" id="old-password" class="form-control" placeholder="Nhập mật khẩu cũ">
                                </div>
                                <?php
                                    endif;
                                ?>
                                <div class="form-group">
                                    <label for="">Mật khẩu mới</label>
                                    <input type="password" id="new-password" class="form-control" placeholder="Nhập mật khẩu mới">
                                </div>
                                <div class="form-group">
                                    <label for="">Nhập lại mật khẩu</label>
                                    <input type="password" id="re-password" class="form-control" placeholder="Nhập lại mật khẩu">
                                </div>
                                <div class="form-group">
                                    <button type="button" id="btn-change-pass" class="btn btn-primary">Đổi mật khẩu</button>
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