<?php
require_once 'config/database.php';  // Kết nối cơ sở dữ liệu
require_once 'controllers/DonHangController.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'donhang';
$action = isset($_GET['action']) ? $_GET['action'] : 'hienThiDanhSachDonHang';

switch ($controller) {
    case 'donhang':
        $donHangController = new DonHangController($pdo);
        if ($action == 'hienThiDanhSachDonHang') {
            $donHangController->layDSDonHangChoXacNhan();
        } elseif ($action == 'themDonHang') {
            $donHangController->themDonHang();
        } elseif ($action == 'suaDonHang') {
            $donHangController->suaDonHang($_GET['maDonHang']);
        } elseif ($action == 'huyDonHang') {
            $donHangController->huyDonHang($_GET['maDonHang']);
        }
        break;
    // Các controller khác
}
?>

