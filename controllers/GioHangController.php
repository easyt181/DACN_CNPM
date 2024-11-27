<?php

require_once (__DIR__ . '/../models/GioHang.php');
require_once (__DIR__ . '/../models/DonHang.php');
require_once (__DIR__ . '/../models/HoaDon.php');
require_once (__DIR__ . '/../models/chiTietDonHang.php');

class GioHangController {
    private $gioHangModel;
    private $donHangModel;
    private $hoaDonModel;
    private $chiTietDonHangModel;
    private $data;
    public function __construct($pdo=null) {
        if($pdo != null) {
            $this->gioHangModel = new GioHangModel($pdo);
            $this->donHangModel = new DonHang($pdo);
            $this->chiTietDonHangModel = new ChiTietDonHang($pdo);
            $this->hoaDonModel = new HoaDon($pdo);
            $this->data = json_decode(file_get_contents('php://input'), true); 
        }
        
    }
    
    public function remove() {
        
            if (isset($_COOKIE['cart']) && isset($this->data['maMonAn'])) {
                $cart = json_decode($_COOKIE['cart'], true);
    
                foreach ($cart as $key => $item) {
                    if ($item['maMonAn'] == $this->data['maMonAn']) {
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
        if (isset($this->data['TTDH'])) {
            $is_true = $this->gioHangModel->insertDonHang($this->data['TTDH']);
            
            $maDH = $this->donHangModel->lastInsertId($this->data['TTDH']['ngayTao']);
            if($is_true) {
                echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được tạo thành công .', 'PTTT' => $this->data['TTDH']['phuongThuc'], 'maDonHang' => $maDH]);
            }else{
                echo json_encode(['success' => false, 'message' => 'Thất bại .']);
            }
            foreach($this->data['CTDH'] as $item) {
                $thanhTien = (int)$item['soLuong'] * (float)$item['donGia'];
                $CTDH = ['maMonAn'=> $item['maMonAn'], 'soLuong'=>$item['soLuong'],'donGia'=>$item['donGia'], 'maDonHang' => $maDH, 'thanhTien' => $thanhTien];
                $this->chiTietDonHangModel->themChiTietDonHang($CTDH);
            }
            $this->data['HD']['maDonHang'] = $maDH;
            $this->data['HD']['trangThaiHoaDon'] = 'Chưa thanh toán';
            $this->data['HD']['trangThaiHoanTien'] = '0';
            $this->hoaDonModel->themHoaDon($this->data['HD']);
            
            
        }
        
        
    }
    public function hienThiGioHang() {
        require_once('views/MuaHangUi/GioHangUi.php');
    }
    public function thayDoiDiaChi()  {
        if($this->data['diaChi']){
            $diaChi = $this->data['diaChi'];
            $tenKH = $this->data['tenKH'];
            $this->gioHangModel->ThayDoiDiaChi($diaChi, $tenKH);
        }
    }
}
?>
