<?php
require_once 'models/ThucDonModel.php';

class ThucDonController {
    private $thucDonModel;

    public function __construct($pdo) {
        $this->thucDonModel = new ThucDonModel($pdo);
    }

    // Hiển thị danh sách món ăn
    public function hienThiDanhSachMonAnTrangChu() {
        $tuKhoa = isset($_GET['search']) ? $_GET['search'] : '';  // Nhận giá trị tìm kiếm từ form

        if ($tuKhoa) {
            // Tìm kiếm món ăn theo tên
            $danhSachMonAn = $this->thucDonModel->timKiemMonAn($tuKhoa);
        } else {
            // Nếu không có từ khóa, lấy tất cả món ăn
            $danhSachMonAn = $this->thucDonModel->layDanhSachMonAn();
        }

        // Truyền dữ liệu vào view (home.php)
        require_once './views/home.php';
    }

    public function layDanhSachMonAn() {
        return $this->thucDonModel->layDanhSachMonAn();
    }


}
?>
