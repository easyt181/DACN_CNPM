<?php
require_once 'models/LichSuModel.php';

class DanhGiaController {
    private $lichSuModel;

    public function __construct($pdo) {
        $this->lichSuModel = new DanhGiaModel($pdo);
    }

    // Hàm để xử lý việc gửi đánh giá
    public function guiDanhGia() {
        // Kiểm tra xem người dùng đã gửi dữ liệu đánh giá chưa
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maDonHang = $_POST['maDonHang'];
            $maKH = $_POST['maKH'];
            $noiDungDanhGia = $_POST['noiDungDanhGia'];
            $soSao = $_POST['soSao'];

            // Gọi phương thức từ model để thêm đánh giá
            if ($this->lichSuModel->themDanhGia($maDonHang, $maKH, $noiDungDanhGia, $soSao)) {
                // Nếu thêm thành công, chuyển hướng hoặc thông báo
                echo 'Đánh giá đã được gửi thành công!';
            } else {
                echo 'Đã có lỗi xảy ra. Vui lòng thử lại.';
            }
        }
    }
    public function huyDonHang(){
        $data = json_decode(file_get_contents('php://input'), true); 
        if (isset($data['maDH'])) {
            $this->lichSuModel->huyDonHang($data['maDH']);
        }
        
        
    }
    public function hienThiDanhGia() {
        require_once('views/MuaHangUi/lichsu.php');
    }
}
