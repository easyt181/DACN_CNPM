<?php

require_once (__DIR__ . '/../models/GioHang.php');
require_once (__DIR__ . '/../models/DonHang.php');


class GioHangController {
    private $gioHangModel;
    private $donHangModel;

    public function __construct($pdo=null) {
        if($pdo != null) {
            $this->gioHangModel = new GioHangModel($pdo);
            $this->donHangModel = new DonHang($pdo);
        }
        
    }
    
    public function remove() {
        $data = json_decode(file_get_contents('php://input'), true); 
        
            if (isset($_COOKIE['cart']) && isset($data['maMonAn'])) {
                $cart = json_decode($_COOKIE['cart'], true);
    
                foreach ($cart as $key => $item) {
                    if ($item['maMonAn'] == $data['maMonAn']) {
                        unset($cart[$key]);
                        break;  
                    }
                }
                setcookie('cart', json_encode(array_values($cart)), time() + 800000, "/"); // Lưu lại cookie với mảng đã thay đổi
    
                echo json_encode(['message' => "Sản phẩm đã được xóa khỏi giỏ hàng!"]);
            } else {
                echo json_encode(['error' => "Giỏ hàng không tồn tại hoặc trống."]);
            }
        
    }

    function insert_DH() {
        $data = json_decode(file_get_contents('php://input'), true); 
        if (isset($data['TTDH'])) {
            $is_true = $this->gioHangModel->insertDonHang($data['TTDH']);
            $maDH = $this->donHangModel->lastInsertId($data['TTDH']['ngayTao']);
            if($is_true) {
                echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được tạo thành công .', 'PTTT' => $data['TTDH']['phuongThuc'], 'maDonHang' => $maDH]);
            }else{
                echo json_encode(['success' => false, 'message' => 'Thất bại .']);
            }
        }
        
        
    }
    public function hienThiGioHang() {
        require_once('views/MuaHangUi/GioHangUi.php');
    }
}
?>
