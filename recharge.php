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

    if (!isVerified()) :
?>
    <script>
        swal('Thông báo', 'Tính năng này chỉ dành cho các tài khoản đã được xác minh', 'warning');
        setTimeout(function() {
            window.location.href = 'information.php';
        }, 3000);
    </script>
<?php endif; ?>

    <div class="nap-tien">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="nap-tien-content">
                        <h1>Nạp tiền</h1>
                        <p>Nạp tiền vào tài khoản</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="nap-tien-table">
                        <table class="table table-striped">
                            <tr>
                                <th>Số dư</th>
                                <td><?php echo number_format($user_info['money']) ?> VNĐ</td>
                            </tr>
                            <tr>
                                <th>Mã thẻ</th>
                                <td><input type="number" id='card-number' class="form-control" placeholder="Nhập mã thẻ" min='0' max='999999'></td>
                            </tr>
                            <tr>
                                <th>Ngày hết hạn</th>
                                <td><input type="date" id="expire-date" class="form-control" placeholder="Nhập ngày hết hạn"></td>
                            </tr>
                            <tr>
                                <th>CVV</th>
                                <td><input type="number" id="cvv" class="form-control" placeholder="Nhập CVV" min='000' max='999'></td>
                            </tr>
                            <tr>
                                <th>Số tiền</th>
                                <td><input type="number" id="money" class="form-control" placeholder="Nhập số tiền" min='20000'></td>
                            </tr>
                        </table>
                        <button class="btn btn-primary" id="btn-recharge">Nạp tiền</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>