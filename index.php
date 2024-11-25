<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'config/database.php';  
require_once 'controllers/QuanLyDonHangController.php';
require_once 'controllers/GioHangController.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'trangchu';
$action = isset($_GET['action']) ? $_GET['action'] : 'Home';

switch ($controller) {
    case 'donhang':
        $donHangController = new QuanLyDonHangController($pdo);
        if ($action == 'hienThiDanhSachDonHang') {
            $donHangController->layDSDonHangTheoNhom();
        }
        elseif ($action == 'hienThiTrangThemDonHang') {
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
        }elseif ($action == 'suaDonHang') {
            $donHangController->suaDonHang($_GET['maDonHang']);
        } elseif ($action == 'huyDonHang') {
            $donHangController->huyDonHang($_GET['maDonHang']);
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
    case 'trangchu':
        if($action == 'Home'){
            require_once('views/TrangChu.php');
        }
        
}
?>


