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

    <div class="chuyen-tien">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="chuyen-tien-content">
                        <h1>Chuyển tiền</h1>
                        <p>Chuyển tiền tới tài khoản cùng hệ thống</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="chuyen-tien-table">
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
                                <th>Số điện thoại người nhận</th>
                                <td><input type="number" id='phone-number' class="form-control" placeholder="Nhập số điện thoại" min='0'></td>
                            </tr>
                            <tr>
                                <th>Tên người nhận</th>
                                <td><input type="text" id='name' class="form-control" placeholder="Tên người nhận" disabled></td>
                            </tr>
                            <tr>
                                <th>Số tiền</th>
                                <td><input type="number" id="money" class="form-control" placeholder="Nhập số tiền" min='20000'></td>
                            </tr>
                            <tr>
                                <th>Người trả phí</th>
                                <td>
                                    <select class="form-control" id="payer">
                                        <option value="0">Người gửi</option>
                                        <option value="1">Người nhận</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Tổng tiền</th>
                                <td><input type="number" id="total" class="form-control" value="0" disabled></td>
                            </tr>
                            <tr>
                                <th>Ghi chú</th>
                                <td><textarea class="form-control" id="note" rows="3" placeholder="Nhập ghi chú"></textarea></td>
                            </tr>
                            <tr>
                                <th>Mã OTP</th>
                                <td>
                                    <input type="number" id="otp" class="form-control" placeholder="Nhập mã OTP">
                                    <button class="btn btn-primary" id="send-otp">Gửi mã OTP</button>
                                </td>
                            </tr>
                        </table>
                        <button class="btn btn-primary" id="btn-transfers">Chuyển tiền</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>