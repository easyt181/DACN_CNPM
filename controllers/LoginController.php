<?php
class LoginController {
    private $taiKhoanModel;

    public function __construct($pdo) {
        $this->taiKhoanModel = new TaiKhoan($pdo);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenDangNhap = $_POST['tenDangNhap'];
            $matKhau = $_POST['matKhau'];

            // Tìm tài khoản trong cơ sở dữ liệu
            $taiKhoan = $this->taiKhoanModel->findByUsername($tenDangNhap);
            if ($taiKhoan && $taiKhoan['matKhau'] === $matKhau) {
                // Lưu thông tin đăng nhập vào session
                session_start();
                $_SESSION['tenDangNhap'] = $taiKhoan['tenDangNhap'];
                $_SESSION['maQuyen'] = $taiKhoan['maQuyen'];

                // Điều hướng theo quyền
                if ($taiKhoan['maQuyen'] === 'admin') {
                    header("Location: ./views/admin.php");
                } else {
                    header("Location: index.php?controller=thucdon&action=hienThiHome");
                }
                exit();
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
                require 'views/login.php';
            }
        } else {
            require 'views/login.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: index.php?controller=login&action=login");
        exit();
    }
}
?>
