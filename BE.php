<?php
    require_once 'database.php';
    session_start();
    // Lấy múi giờ Việt Nam
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $Email_send = 'kenplaygirl@gmail.com';
    $PassEmail_send = 'Kenplaygirl2402hoangkhacphuc';

    // Kiểm tra hành động API
    $action = "";
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    }

    if ($action == "register") {
        if (isLoggedIn())
        {
            apiResponse(400, "Bạn đã đăng nhập");
            return;
        }
        // Kiểm tra input
        if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['phone']) || !isset($_POST['birthday']) || !isset($_POST['address']) || !isset($_FILES['face']) || !isset($_FILES['back'])) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['birthday']) || empty($_POST['address']) || empty($_FILES['face']) || empty($_FILES['back'])) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        // Gán giá trị
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $birthday = $_POST['birthday'];
        $address = $_POST['address'];
        $face = $_FILES['face'];
        $back = $_FILES['back'];

        if (!isEmail($email)) {
            apiResponse(400, "Email không hợp lệ");
            return;
        }
        if (!isPhone($phone)) {
            apiResponse(400, "Số điện thoại không hợp lệ");
            return;
        }
        if (!isValidDate($birthday)) {
            apiResponse(400, "Ngày sinh không hợp lệ");
            return;
        }
        // Kiểm tra file
        if ($face['error'] != 0 || $back['error'] != 0) {
            apiResponse(400, "File không hợp lệ");
            return;
        }
        if ($face['size'] > 1000000 || $back['size'] > 1000000) {
            apiResponse(400, "File quá lớn");
            return;
        }

        // Chỉ nhận file ảnh
        $extension = pathinfo($face['name'], PATHINFO_EXTENSION);
        if ($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
            apiResponse(400, "File ảnh không hợp lệ");
            return;
        }
        $extension = pathinfo($back['name'], PATHINFO_EXTENSION);
        if ($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
            apiResponse(400, "File ảnh không hợp lệ");
            return;
        }
        // kiểm tra email, số điện thoại đã tồn tại hay chưa
        $sql = "SELECT * FROM account WHERE email = '$email' OR phone = '$phone'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            apiResponse(400, "Email hoặc số điện thoại đã tồn tại");
            return;
        }


        // lưu ảnh vào folder img/upload
        $face_name = time() . "_1_" . $face['name'];
        $back_name = time() . "_2_" . $back['name'];
        move_uploaded_file($face['tmp_name'], "img/upload/" . $face_name);
        move_uploaded_file($back['tmp_name'], "img/upload/" . $back_name);

        // Tạo tài khoản, mật khẩu random
        $password = randomString(6);
        $username = randomNumber(10);
        // Kiểm tra username đã tồn tại hay chưa tới khi hợp lệ
        $sql = "SELECT * FROM account WHERE user = '$username'";
        $result = mysqli_query($conn, $sql);

        while (mysqli_num_rows($result) > 0) {
            $username = randomNumber(10);
            $sql = "SELECT * FROM account WHERE user = '$username'";
            $result = mysqli_query($conn, $sql);
        }

        $pass_hash = md5($password);
        
        // Lưu thông tin vào database
        $sql = "INSERT INTO account (user, pass, name, email, phone, birth, address, face, back) VALUES ('$username', '$pass_hash', '$name', '$email', '$phone', '$birthday', '$address', '$face_name', '$back_name')";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            apiResponse(400, "Lỗi khi thêm tài khoản");
            return;
        }
        // Gửi mail thông báo
        $result = sendMail($name, $email, 'Đăng ký tài khoản - Ví điện tử', 'Đăng ký tài khoản thành công.<br>Tài khoản: '. $username .'<br>Mật khẩu: ' . $password);
        if (!$result) {
            apiResponse(400, "Tài khoản đã được tạo, nhưng không thể gửi mail thông báo.
                                    Tài khoản: $username 
                                    Mật khẩu: $password");
            return;
        }


        apiResponse(200, "Đăng ký thành công");
        return;
    }

    if ($action == "login") {
        if (isLoggedIn())
        {
            apiResponse(400, "Bạn đã đăng nhập");
            return;
        }
        // check isset user, pass
        if (!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password'])) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        // Gán giá trị
        $username = $_POST['username'];
        $password = $_POST['password'];
        // Kiểm tra tài khoản, mật khẩu
        $sql = "SELECT * FROM account WHERE user = '$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            apiResponse(400, "Tài khoản không tồn tại");
            return;
        }

        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        $pass = $row['pass'];
        $verify = $row['confirm'];

        // Kiểm tra số lần sai mật khẩu
        $sql = "SELECT * FROM locked WHERE account_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        $amount_wrong = 0;
        $row = mysqli_num_rows($result);
        if ($row > 0) 
        {
            $row = mysqli_fetch_assoc($result);
            $amount_wrong = $row['amount'];
        }

        if ($amount_wrong >= 6)
        {
            apiResponse(400, "Tài khoản đã bị khóa do nhập sai mật khẩu nhiều lần, vui lòng liên hệ quản trị viên để được hỗ trợ");
            return;
        }

        if (md5($password) != $pass) {
            if ($amount_wrong == 0)
            {
                $sql = "INSERT INTO locked (account_id, amount) VALUES ('$user_id', '1')";
                $result = mysqli_query($conn, $sql);
            }
            else
            {
                if ($amount_wrong == 3 && strtotime($row['created']) > time() - 60)
                {
                    apiResponse(400, "Tài khoản hiện đang bị tạm khóa, vui lòng thử lại sau 1 phút");
                    return;
                }
                // Update số lần sai mật khẩu và thời gian
                $sql = "UPDATE locked SET amount = amount + 1, created = NOW() WHERE account_id = '$user_id'";
                $result = mysqli_query($conn, $sql);
            }
            $amount_wrong++;
            if ($amount_wrong >= 6)
            {
                apiResponse(400, "Tài khoản đã bị khóa do nhập sai mật khẩu nhiều lần, vui lòng liên hệ quản trị viên để được hỗ trợ");
                return;
            }
            if ($amount_wrong == 3)
            {
                apiResponse(400, "Tài khoản hiện đang bị tạm khóa, vui lòng thử lại sau 1 phút");
                return;
            }
            apiResponse(400, "Sai mật khẩu");
            return;
        }

        if ($verify == 2)
        {
            apiResponse(400, "Tài khoản này đã bị vô hiệu hóa, vui lòng liên hệ tổng đài 18001008");
            return;
        }

        $sql = "DELETE FROM locked WHERE account_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        // Lưu thông tin vào session
        $_SESSION['User_ID'] = $user_id;
        apiResponse(200, "Đăng nhập thành công");
    }

    if ($action == "change-pass") {
        if (!isLoggedIn())
        {
            apiResponse(400, "Bạn chưa đăng nhập");
            return;
        }
        // check isset pass, new-pass, confirm-pass
        if (!isset($_POST['new-password']) || !isset($_POST['confirm-password']) || empty($_POST['new-password']) || empty($_POST['confirm-password'])) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        if (!isFirstLogin() && (!isset($_POST['password']) || empty($_POST['password']))) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        // Gán giá trị
        $password = $_POST['password'];
        $new_password = $_POST['new-password'];
        $confirm_password = $_POST['confirm-password'];
        if ($new_password != $confirm_password) {
            apiResponse(400, "Mật khẩu không khớp");
            return;
        }

        $user_id = $_SESSION['User_ID'];

        if (!isFirstLogin())
        {
            $sql = "SELECT * FROM account WHERE id = '$user_id' AND pass = '" . md5($password) . "'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 0) {
                apiResponse(400, "Mật khẩu cũ không chính xác");
                return;
            }
        }
        $sql = "UPDATE account SET pass = '" . md5($new_password) . "', change_pass = '1' WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        apiResponse(200, "Đổi mật khẩu thành công");
    }

    if ($action == "forgot-password") {
        if (isLoggedIn())
        {
            apiResponse(400, "Bạn đã đăng nhập");
            return;
        }
        // check isset email
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        // Gán giá trị
        $email = $_POST['email'];
        $sql = "SELECT * FROM account WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            apiResponse(400, "Email không tồn tại");
            return;
        }
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        $name = $row['name'];
        $pass = randomString(6);
        $otp = randomNumber(6);

        $sql = "UPDATE account SET otp = '$otp', otp_created = NOW() WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        // Send mail
        $send = sendMail($name, $email, "Quên mật khẩu", "OTP : $otp<br>Lưu ý: Mã OTP chỉ có hiệu lực trong vòng 1 phút");
        if ($send) {
            apiResponse(200, "Đã gửi mã OTP đến email");
        } else {
            apiResponse(400, "Gửi mã OTP thất bại");
        }
    }

    if ($action == "verify-otp") {
        if (isLoggedIn())
        {
            apiResponse(400, "Bạn đã đăng nhập");
            return;
        }
        // check isset otp, email, new-password, confirm-password
        if (!isset($_POST['otp']) || !isset($_POST['email']) || !isset($_POST['new-password']) || !isset($_POST['confirm-password']) || empty($_POST['otp']) || empty($_POST['email']) || empty($_POST['new-password']) || empty($_POST['confirm-password'])) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        // Gán giá trị
        $otp = $_POST['otp'];
        $email = $_POST['email'];
        $new_password = $_POST['new-password'];
        $confirm_password = $_POST['confirm-password'];
        if ($new_password != $confirm_password) {
            apiResponse(400, "Mật khẩu không khớp");
            return;
        }

        $sql = "SELECT * FROM account WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            apiResponse(400, "Email không tồn tại");
            return;
        }

        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        $otp_user = $row['otp'];
        $otp_created = $row['otp_created'];
        $otp_created = strtotime($otp_created);
        $now = time();
        if ($otp != $otp_user) {
            apiResponse(400, "Mã OTP không chính xác");
            return;
        }
        if ($now - $otp_created > 60) {
            apiResponse(400, "Mã OTP đã hết hạn");
            return;
        }
        // Update password
        $sql = "UPDATE account SET pass = '" . md5($new_password) . "' WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        apiResponse(200, "Đổi mật khẩu thành công");
    }

    if ($action == "recharge") {
        if (!isLoggedIn())
        {
            apiResponse(400, "Bạn chưa đăng nhập");
            return;
        }
        if (!isVerified())
        {
            apiResponse(400, "Tính năng này chỉ dành cho các tài khoản đã được xác minh");
            return;
        }
        // check isset card-number, expire-date, cvv, money
        if (!isset($_POST['card-number']) || !isset($_POST['expire-date']) || !isset($_POST['cvv']) || !isset($_POST['money']) || empty($_POST['card-number']) || empty($_POST['expire-date']) || empty($_POST['cvv']) || empty($_POST['money'])) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        // Gán giá trị
        $card_number = $_POST['card-number'];
        $expire_date = $_POST['expire-date'];
        $cvv = $_POST['cvv'];
        $money = $_POST['money'];
        $user_id = $_SESSION['User_ID'];
        // Kiểm tra thông tin hợp lệ
        if (!checkCardNumber($card_number)) {
            apiResponse(400, "Số thẻ không hợp lệ");
            return;
        }
        if (!isValidDate($expire_date)) {
            apiResponse(400, "Ngày hết hạn không hợp lệ");
            return;
        }
        if (!checkCVV($cvv)) {
            apiResponse(400, "Mã CVV không hợp lệ");
            return;
        }
        if (!checkRechargeMoney($money)) {
            apiResponse(400, "Số tiền không hợp lệ hoặc nhỏ hơn 20.000 VNĐ");
            return;
        }

        $card_input = array(
            array(
                'card_number' => '111111',
                'expire_date' => '2022-10-10',
                'cvv' => '411',
                'money' => '-1',
            ),
            array(
                'card_number' => '222222',
                'expire_date' => '2022-11-11',
                'cvv' => '443',
                'money' => '1000000',
            ),
            array(
                'card_number' => '333333',
                'expire_date' => '2022-12-12',
                'cvv' => '577',
                'money' => '0',
            ),
        );

        $card_choose = -1;

        foreach ($card_input as $card) {
            if ($card['card_number'] == $card_number) {
                $card_choose = $card;
                break;
            }
        }

        if ($card_choose == -1) {
            apiResponse(400, "Thẻ này không dược hỗ trợ");
            return;
        }

        if ($card_choose['expire_date'] != $expire_date) {
            apiResponse(400, "Ngày hết hạn không chính xác");
            return;
        }

        if ($card_choose['cvv'] != $cvv) {
            apiResponse(400, "Mã CVV không chính xác");
            return;
        }

        if ($card_choose['money'] == 0) {
            apiResponse(400, "Thẻ hết tiền");
            return;
        }

        if ($card_choose['money'] < $money && $card_choose['money'] != -1) {
            apiResponse(400, "Mỗi lần nạp tối đa là " . number_format($card_choose['money']) . " VNĐ");
            return;
        }

        // Nạp tiền
        $sql = "INSERT INTO transaction (account_id, card_number, exp, cvv, total_money, method_id, confirm) VALUES ('$user_id', '$card_number', '$expire_date', '$cvv', '$money', '2', '1')";

        $result = mysqli_query($conn, $sql);
        if (!$result) {
            apiResponse(400, "Có lỗi xảy ra");
            return;
        }
        $transaction_id = mysqli_insert_id($conn);
        // Cập nhật số dư
        $sql = "UPDATE account SET money = money + '$money' WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            apiResponse(400, "Có lỗi xảy ra");
            return;
        }
        apiResponse(200, "Nạp tiền thành công");
    }

    if ($action == "change-confirm")
    {
        if (!isLoggedIn())
        {
            apiResponse(400, "Bạn chưa đăng nhập");
            return;
        }
        if (!isAdmin())
        {
            apiResponse(400, "Tính năng này chỉ dành cho các tài khoản admin");
            return;
        }
        // check isset id, confirm
        if (!isset($_POST['id']) || !isset($_POST['confirm']) || empty($_POST['id']) || empty($_POST['confirm'])) {
            apiResponse(400, "Thiếu thông tin");
            return;
        }
        // Gán giá trị
        $id = $_POST['id'];
        $confirm = $_POST['confirm'];
        // Kiểm tra thông tin hợp lệ
        if (!is_numeric($id)) {
            apiResponse(400, "ID không hợp lệ");
            return;
        }
        if (!is_numeric($confirm) || $confirm < 0 || $confirm > 4) {
            apiResponse(400, "Trạng thái không hợp lệ");
            return;
        }
        // Cập nhật trạng thái
        $result = confirmAccount($id, $confirm);
        if (!$result) {
            apiResponse(400, "Có lỗi xảy ra");
            return;
        }
        apiResponse(200, "Cập nhật thành công");
    }









    // Hàm xuất kết quả API
    function apiResponse($status, $message, $data = null) {
        $response = array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        );
        echo json_encode($response);
    }

    // Hàm kiểm tra email
    function isEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Hàm kiểm tra đăng nhập
    function isLoggedIn() {
        return isset($_SESSION['User_ID']);
    }

    // Hàm kiểm tra số điện thoại
    function isPhone($phone) {
        return preg_match("/^[0-9]{10,13}$/", $phone);
    }

    // Hàm kiểm tra ngày hợp lệ
    function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    // Hàm random number
    function randomNumber($length) {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

    // Hàm random string
    function randomString($length) {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= chr(mt_rand(65, 90));
        }
        return $result;
    }

    // Hàm gửi mail
    function sendMail($name, $email, $subject, $message) {
        global $Email_send;
        global $PassEmail_send;

        include('./lib/class.smtp.php');
        include "./lib/class.phpmailer.php"; 
        $mail = new PHPMailer();
        $mail->IsSMTP();             
        $mail->CharSet  = "utf-8";
        $mail->SMTPDebug  = 0; 
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host       = "smtp.gmail.com";
        $mail->Port       = 465;
        $mail->Username   = $Email_send;
        $mail->Password   = $PassEmail_send;
        $mail->SetFrom($Email_send, 'Ví điện tử');
        $mail->AddReplyTo($Email_send, 'Ví điện tử');
        $mail->Subject    = $subject;
        $mail->MsgHTML($message);
        $mail->AddAddress($email, $name);
        if(!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }
    }

    // Hàm kiểm tra lần đầu đăng nhập
    function isFirstLogin() {
        global $conn;
        if (!isLoggedIn())
        {
            return false;
        }
        $user_id = $_SESSION['User_ID'];
        $sql = "SELECT * FROM account WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['change_pass'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    // Hàm lấy thông tin cá nhân
    function getUserInfo($id = null) {
        global $conn;
        if (!isLoggedIn())
        {
            return false;
        }
        $user_id = 0;
        if ($id == null) {
            $user_id = $_SESSION['User_ID'];
        } else {
            $user_id = $id;
        }
        $sql = "SELECT * FROM account WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    // Hàm chuyển format ngày tháng năm
    function formatDate($date) {
        $date = date_create($date);
        return date_format($date, 'd/m/Y');
    }

    // Hàm lấy trạng thái tài khoản
    function getStatus($status) {
        if ($status == 0) {
            return "Chưa xác minh";
        } else if ($status == 1) {
            return "Đã xác minh";
        } else if ($status == 3) {
            return "Chờ cập nhật";
        }
    }

    // Hàm kiểm tra card number
    function checkCardNumber($card_number) {
        if (strlen($card_number) != 6 || !is_numeric($card_number)) {
            return false;
        }
        return true;
    }

    // Hàm kiểm tra CVV
    function checkCVV($cvv) {
        if (strlen($cvv) != 3 || !is_numeric($cvv)) {
            return false;
        }
        return true;
    }

    // Hàm kiểm tra số tiền nạp
    function checkRechargeMoney($amount) {
        if (!is_numeric($amount) || $amount < 20000) {
            return false;
        }
        return true;
    }

    // Hàm kiểm tra người dùng đã được xác mình chưa
    function isVerified() {
        global $conn;
        if (!isLoggedIn())
        {
            return false;
        }
        $user_id = $_SESSION['User_ID'];

        $sql = "SELECT * FROM account WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['confirm'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    // Hàm lấy lịch sử giao dịch
    function getTransfers()
    {
        global $conn;
        if (!isLoggedIn())
        {
            return array();
        }
        if (!isVerified()) {
            return array();
        }

        $user_id = $_SESSION['User_ID'];

        $sql = "SELECT t.created, m.name, t.total_money, t.confirm, t.id, t.method_id FROM transaction as t, method as m WHERE t.account_id = '$user_id' AND t.method_id = m.id ORDER BY t.created DESC;";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $transfers = array();
        while ($row) {
            $transfers[] = $row;
            $row = mysqli_fetch_assoc($result);
        }
        return $transfers;
    }

    // Lấy trạng thái giao dịch
    function getStatusTransfer($status) {
        if ($status == 0) {
            return "Chờ xác nhận";
        } else if ($status == 1) {
            return "Hoàn thành";
        } else if ($status == 2) {
            return "Hủy";
        }
    }

    // Lấy thông tin chi tiết của giao dịch
    function getTransferById($id) {
        global $conn;
        if (!isLoggedIn())
        {
            return array();
        }
        if (!isVerified()) {
            return array();
        }
        
        $user_id = $_SESSION['User_ID'];

        $sql = "SELECT * FROM transaction as t, method as m WHERE t.account_id = '$user_id' AND t.method_id = m.id AND t.id = '$id'";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            return array();
        }
        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    // Kiểm tra xem có phải là admin không
    function isAdmin() {
        global $conn;
        if (!isLoggedIn())
        {
            return false;
        }
        $user_id = $_SESSION['User_ID'];
        $sql = "SELECT * FROM account WHERE id = '$user_id' AND role_id = '1'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            return true;
        } else {
            return false;
        }
    }

    // Lấy danh sách tài khoản mới tạo hoặc bổ sung CMND
    function getListAccountWaitingActivation() {
        global $conn;
        if (!isLoggedIn())
        {
            return array();
        }
        if (!isAdmin()) {
            return array();
        }
        $sql = "SELECT * FROM account WHERE confirm = '0' OR confirm = '4' ORDER BY created_updated DESC";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $accounts = array();
        while ($row) {
            $accounts[] = $row;
            $row = mysqli_fetch_assoc($result);
        }
        return $accounts;
    }

    // Lấy trạng thái tài khoản
    function getStatusAccount($status) {
        if ($status == 0) {
            return "Chưa xác minh";
        } else if ($status == 1) {
            return "Đã xác minh";
        } else if ($status == 2) {
            return "Đã hủy";
        } else if ($status == 3) {
            return "Yêu cầu cấp CMND";
        } else if ($status == 4) {
            return "Chờ xác minh CMND";
        }
    }

    // Thay confirm tài khoản có id
    function confirmAccount($id, $confirm) {
        global $conn;
        if (!isLoggedIn())
        {
            return false;
        }
        if (!isAdmin()) {
            return false;
        }
        $sql = "UPDATE account SET confirm = '$confirm' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Danh sách tài khoản đã kích hoạt
    function getListAccountActivated() {
        global $conn;
        if (!isLoggedIn())
        {
            return array();
        }
        if (!isAdmin()) {
            return array();
        }
        $sql = "SELECT * FROM account WHERE confirm = '1' ORDER BY created_updated DESC";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $accounts = array();
        while ($row) {
            $accounts[] = $row;
            $row = mysqli_fetch_assoc($result);
        }
        return $accounts;
    }

    // danh sách tài khoản bị vô hiệu hóa
    function getListAccountDeactivated() {
        global $conn;
        if (!isLoggedIn())
        {
            return array();
        }
        if (!isAdmin()) {
            return array();
        }
        $sql = "SELECT * FROM account WHERE confirm = '2' ORDER BY created_updated DESC";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $accounts = array();
        while ($row) {
            $accounts[] = $row;
            $row = mysqli_fetch_assoc($result);
        }
        return $accounts;
    }

?>