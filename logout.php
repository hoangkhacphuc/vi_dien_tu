<?php
    // Đăng xuất và chuyển trang index.php
    session_start();
    session_destroy();
    header("Location: index.php");
?>