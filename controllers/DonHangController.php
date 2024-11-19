<?php
require_once 'models/DonHang.php';
require_once 'models/ChiTietDonHang.php';

class DonHangController {
    private $donHangModel;
    private $chiTietDonHangModel;

    public function __construct($pdo) {
        $this->donHangModel = new DonHang($pdo);
        $this->chiTietDonHangModel = new ChiTietDonHang($pdo);
    }

    // Hiển thị danh sách đơn hàng

    public function layDSDonHangChoXacNhan() {
        $trangThai = 'Đang chờ xác nhận';
        $donHangs = $this->donHangModel->layDSDonHang($trangThai);
        require_once 'views/QuanLyDonHang/DanhSachDonHang.php';
    }



    // Thêm đơn hàng
    public function themDonHang() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'maKH' => $_POST['maKH'],
                'maTaiKhoanNV' => $_POST['maTaiKhoanNV'],
                'maUuDaiDH' => $_POST['maUuDaiDH'],
                'ngayTao' => $_POST['ngayTao'],
                'phuongThucThanhToan' => $_POST['phuongThucThanhToan'],
                'diaChiGiaoHang' => $_POST['diaChiGiaoHang'],
                'khoangCachGiaoHang' => $_POST['khoangCachGiaoHang'],
                'phiShip' => $_POST['phiShip'],
                'tongTienCongTru' => $_POST['tongTienCongTru'],
                'tongTien' => $_POST['tongTien'],
                'trangThaiThanhToan' => $_POST['trangThaiThanhToan'],
                'trangThaiDonHang' => $_POST['trangThaiDonHang'],
                'ghiChu' => $_POST['ghiChu'],
            ];
            $this->donHangModel->themDonHang($data);
            header('Location: index.php?controller=donhang&action=index');
        } else {
            require_once 'views/QuanLyDonHang/ThemDonHang.php';
        }
    }

    // Sửa đơn hàng
    public function suaDonHang($maDonHang) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'phuongThucThanhToan' => $_POST['phuongThucThanhToan'],
                'diaChiGiaoHang' => $_POST['diaChiGiaoHang'],
                'tongTienCongTru' => $_POST['tongTienCongTru'],
                'tongTien' => $_POST['tongTien'],
                'ghiChu' => $_POST['ghiChu']
            ];
            $this->donHangModel->suaDonHang($maDonHang, $data);
            header('Location: index.php?controller=donhang&action=index');
        } else {
            $donHang = $this->donHangModel->timKiemDonHang($maDonHang)[0];
            require_once 'views/donhang/sua.php';
        }
    }

    // Hủy đơn hàng
    public function huyDonHang($maDonHang) {
        $this->donHangModel->huyDonHang($maDonHang);
        header('Location: index.php?controller=donhang&action=index');
    }
}
?>