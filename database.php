<?php
    // Kết nối database
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "vi_dien_tu";
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
        return;
    }
    // echo "Kết nối thành công";
?>