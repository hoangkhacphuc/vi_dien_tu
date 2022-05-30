<?php
    require_once 'database.php';
    session_start();

    $Email_send = '';
    $PassEmail_send = '';

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
        return isset($_SESSION['user_id']);
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

?>