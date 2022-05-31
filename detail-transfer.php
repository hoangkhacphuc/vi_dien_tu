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

    <div class="chi-tiet">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="chi-tiet-content">
                        <h1>Chi tiết giao dịch</h1>
                        <p>Xem chi tiết giao dịch</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="chi-tiet-table">
                        <?php 
                        $id = isset($_GET['id']) ? $_GET['id'] : -1;
                        $transfer = getTransferById($id);

                        if (count($transfer) == 0) {
                            echo '<p>Không tìm thấy giao dịch</p>';
                        }
                        else { ?>
                            <table class="table table-striped">
                                <tr>
                                    <th>Thời gian</th>
                                    <td><?php echo $transfer['created']; ?></td>
                                </tr>
                                <tr>
                                    <th>Phương thức</th>
                                    <td><?php echo $transfer['name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Người gửi</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Người nhận</th>
                                    <td>Tôi</td>
                                </tr>
                                <tr>
                                    <th>Mã thẻ</th>
                                    <td><?php echo $transfer['card_number']; ?></td>
                                </tr>
                                <tr>
                                    <th>Ngày hết hạn</th>
                                    <td><?php echo formatDate($transfer['exp']); ?></td>
                                </tr>
                                <tr>
                                    <th>Số tiền</th>
                                    <td><?php echo number_format($transfer['total_money']); ?> VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td><?php echo getStatusTransfer($transfer['confirm']);  ?></td>
                                </tr>
                                <tr>
                                    <th>Chi tiết</th>
                                    <td><?php echo $transfer['content'] ?></td>
                                </tr>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>