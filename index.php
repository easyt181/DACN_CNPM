<?php

 


date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'config/database.php';  
require_once 'controllers/QuanLyDonHangController.php';
require_once 'controllers/QuanLyDonHangController.php';
require_once 'controllers/GioHangController.php';
require_once 'controllers/LichSuController.php';
require_once 'controllers/ThucDonController.php';
require_once 'controllers/LoginController.php';
require_once 'models/LoginModel.php';


$controller = isset($_GET['controller']) ? $_GET['controller'] : 'thucdon';
$action = isset($_GET['action']) ? $_GET['action'] : 'hienThiHome';

switch ($controller) {
    case 'donhang':
        $maTaiKhoanNV = $_SESSION['maTaiKhoan'];
        if(isset($_SESSION['maTaiKhoan']) && isset($_SESSION['maQuyen']) && $_SESSION['maQuyen'] == 'admin'){
            $donHangController = new QuanLyDonHangController($pdo);
            if ($action == 'hienThiDanhSachDonHang') {
                $donHangController->layDSDonHangTheoNhom();
            }elseif($action == 'timKiemDonHang'){
                $donHangController->timKiemDonHang();   
            }elseif ($action == 'xacNhanDonHang'){
                    $donHangController->xacNhanDonHang($maTaiKhoanNV);
            }elseif ($action == 'hienThiTrangThemDonHang') {
                $donHangController->hienThiTrangThemDonHang();
            }elseif($action == 'layChiTietDonHang'){
                $donHangController->layChiTietDonHang();
            }elseif ($action == 'themDonHangQuanLy') {
                $donHangController->themDonHangQuanLy();
            }elseif($action == 'thanhToanQR'){
                if(isset($_GET['maDonHang'])){
                    $maDonHang = $_GET['maDonHang'];
                    require_once 'views/ThanhToanQRCodeUI.php';
                }
                else {
                    echo "Không tìm thấy mã đơn hàng.";
                }
            }elseif($action == 'capNhatTTDonHang'){
                $donHangController->capNhatTTDonHang();
            }elseif ($action == 'suaDonHang') {
                $donHangController->suaDonHang($_GET['maDonHang']);
            } elseif ($action == 'huyDonHang') {
                $donHangController->huyDonHang($maTaiKhoanNV);
            } 
        } else {
            echo "Bạn không có quyền truy cập vào trang này.";
        }
        break;
    case 'giohang':
        $gioHangController = new GioHangController($pdo);
        if ($action == 'hienThiGioHang'){
            $gioHangController->hienThiGioHang();
        }elseif($action == 'themDonHangKH'){
            $gioHangController->insert_DH();
        }elseif($action == 'xoaGioHang'){
            $gioHangController->remove();
        }
    case 'danhgia':
        $danhGiaController = new DanhGiaController($pdo);
        if ($action == 'guiDanhGia') {
            $danhGiaController->guiDanhGia();
        }elseif($action == 'hienThiDanhGia'){
            $danhGiaController->hienThiDanhGia();
        }elseif($action == 'huyDonHang'){
            $danhGiaController->huyDonHang();
        }
        break;
    case 'trangchu':
        if($action == 'Home'){
            require_once('views/TrangChu.php');
        }
    case 'thucdon':
        $thucDonController = new ThucDonController($pdo);
            if ($action == 'hienThiHome') {
            $thucDonController->hienThiDanhSachMonAnTrangChu();
        }
        break;
    
    case 'login':
        $loginController = new LoginController($pdo);
        if ($action === 'login') {
            $loginController->login();
        } elseif ($action === 'logout') {
            $loginController->logout();
        }
        break;
    
    default:
        header("Location: index.php?controller=thucdon&action=hienThiHome");
        break;
        
}
?>