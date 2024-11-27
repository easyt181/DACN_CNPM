<?php
class LoginController {
    private $taiKhoanModel;
    private $khachHangModel;

    public function __construct($pdo) {
        $this->taiKhoanModel = new TaiKhoan($pdo);
        $this->khachHangModel = new KhachHang($pdo);
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
                $_SESSION['maTaiKhoan'] = $taiKhoan['maTaiKhoan'];

                if ($taiKhoan['maQuyen'] === 'admin') {
                    header("Location: index.php?controller=donhang&action=hienThiDanhSachDonHang");
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
    public function DangKyKH() {
        if (isset($_GET['controller']) && $_GET['controller'] === 'login' && $_GET['action'] === 'dangKyKH') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $tenDangNhap = $_POST['tenDangNhap'] ?? null;
                    $matKhau = $_POST['matKhau'] ?? null;
                    $email = $_POST['email'] ?? null;
                    $sdt = $_POST['sdt'] ?? null;
                    $data = [
                        'maQuyen' => 'khachhang',
                        'tenDangNhap' => $tenDangNhap,
                        'matKhau' => $matKhau,
                        'email' => $email,
                        'sdt' => $sdt,
                        'trangThai' => 'Đang hoạt động'
                    ];
                $is_tenDangNhap = $this->taiKhoanModel->kiemTraTK($data); 
                if(!$is_tenDangNhap){
                    $is_data = $this->taiKhoanModel->taoTaiKhoanKH($data);
                    if($is_data) {
                        $maTK = $this->taiKhoanModel->layMaTK($data['tenDangNhap']);
                        echo $maTK['maTaiKhoan'];
                        $dataKH = [
                            'maTaiKhoan' => $maTK['maTaiKhoan'],
                            'tenKH' => $data['tenDangNhap'],
                            'sdt' => $data['sdt'],
                            'email' => $data['email'],
                            'diaChi' => null

                        ];
                        $this->khachHangModel->themKH($dataKH);
                        return true;
                    }
                    return false;
                }else{
                    echo "Tài khoản đã tồn tại.";
                    return false;
                }
            } else {
                echo "Yêu cầu không hợp lệ! Chỉ hỗ trợ phương thức POST.";
            }
        }
        
    }
    
    
}
?>
