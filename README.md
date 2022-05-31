# Ví điện tử

## Cấu hình
- File  `database` : Cấu hình lại thông tin tài khoản database nếu khác
- File `BE.php` : Thêm Email vào biến `$Email_send` và Password vào `$PassEmail_send`.
- Cấu hình Email :
    - Tắt `Xác minh 2 bước` : [tại đây](https://myaccount.google.com/signinoptions/two-step-verification/enroll-welcome)
    - Bật `Quyền truy cập của ứng dụng kém an toàn` : [tại đây](https://myaccount.google.com/lesssecureapps)
    - Bật `Display Unlock Captcha` : [tại đây](https://accounts.google.com/b/0/DisplayUnlockCaptcha)

## Cài đặt
- Mở database và import file `database.sql`
- Bỏ Folder code vô `htdocs` nếu dùng Xampp, `www` nếu là Wamp