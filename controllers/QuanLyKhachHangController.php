<?php
require_once 'models/KhachHang.php';

class QuanLyKhachHangController {
    private $khachHangModel;

    public function __construct($pdo) {
        $this->khachHangModel = new KhachHang($pdo);
    }

    // Kiểm tra khách hàng
    public function kiemTraKH($sdt) {
        $result = $this->khachHangModel->kiemTraKH($sdt);
        if ($result) {
            return "Khách hàng đã tồn tại.";
        }
        return "Khách hàng chưa có trong hệ thống.";
    }

    // Thêm khách hàng
    public function themKH($data) {
        if ($this->khachHangModel->kiemTraKH($data['sdt'])) {
            return "Khách hàng đã tồn tại.";
        }
        if ($this->khachHangModel->themKH($data)) {
            return "Thêm khách hàng thành công.";
        }
        return "Thêm khách hàng thất bại.";
    }

    // Cập nhật thông tin khách hàng
    public function suaKH($maKH, $data) {
        if ($this->khachHangModel->suaKH($maKH, $data)) {
            return "Cập nhật thông tin khách hàng thành công.";
        }
        return "Cập nhật thông tin khách hàng thất bại.";
    }

    // Xóa khách hàng
    public function xoaKH($maKH) {
        if ($this->khachHangModel->xoaKH($maKH)) {
            return "Xóa khách hàng thành công.";
        }
        return "Xóa khách hàng thất bại.";
    }

    // Lấy thông tin khách hàng
    public function layThongTinKhachHang($maKH) {
        return $this->khachHangModel->layThongTinKhachHang($maKH);
    }

    // Lấy danh sách khách hàng
    public function layDanhSachKH() {
        return $this->khachHangModel->layDanhSachKH();
    }

    // Tìm kiếm khách hàng theo từ khóa
    public function timKiemKH($keyword) {
        return $this->khachHangModel->timKiemKH($keyword);
    }
}
?>
