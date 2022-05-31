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

    <div class="lich-su">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="lich-su-content">
                        <h1>Lịch sử giao dịch</h1>
                        <p>Xem danh sách các giao dịch gần đây</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="lich-su-table">
                        <table class="table table-striped">
                            <tr>
                                <th>Thời gian</th>
                                <th>Phương thức</th>
                                <th>Số tiền</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>
                            <?php
                                $transfers = getTransfers();
                                $method = array(1,3,5);
                                foreach ($transfers as $transfer) :
                            ?>
                            <tr>
                                <td><?php echo $transfer['created'] ?></td>
                                <td><?php echo $transfer['name'] ?></td>
                                <td><?php echo (in_array($transfer['method_id'], $method) ? '-' : '') . number_format($transfer['total_money']) ?> VNĐ</td>
                                <td><?php echo getStatusTransfer($transfer['confirm']);  ?></td>
                                <td>
                                    <a href="detail-transfer.php?id=<?php echo $transfer['id'] ?>">Xem chi tiết</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>