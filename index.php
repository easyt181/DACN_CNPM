<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'config/database.php'; 
require_once 'controllers/QuanLyDonHangController.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'donhang';
$action = isset($_GET['action']) ? $_GET['action'] : 'hienThiDanhSachDonHang';

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
}
?>


