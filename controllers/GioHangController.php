<?php

require_once (__DIR__ . '/../models/GioHang.php');
$data = json_decode(file_get_contents('php://input'), true); 
if(isset($data['maMonAn'])){
    $gioHangController = new GioHangController();
    $gioHangController->remove($data['maMonAn']);
}
// if (isset($data['TTDH'])) {
//         $gioHangController = new GioHangController();
//         $gioHangController->insert_DH($data['TTDH']);
//         echo json_encode($data['TTDH']);
// }


class GioHangController {
    private $gioHangModel;

    public function __construct($pdo=null) {
        if($pdo != null) {
            $this->gioHangModel = new GioHangModel($pdo);
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
            if($is_true) {
                echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được tạo thành công .']);
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
