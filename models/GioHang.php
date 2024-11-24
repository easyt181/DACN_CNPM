<?php

class GioHangModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo; // Gán đối tượng PDO
    }
    public function insertDonHang($data) {
        // $maDH = 'DH' . rand(100000, 999999);
        $query = "INSERT INTO donhang (maKH, maTaiKhoanNV, maUuDaiDH, ngayTao, phuongThucThanhToan, diaChiGiaoHang, khoangCachGiaoHang, phiShip, tongTienCongTru, tongTien, trangThaiThanhToan, trangThaiDonHang, ghiChu) 
                  VALUES (?,NULL,NULL,?,?,?,?,?,NULL,?,?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $data['maKH'], $data['ngayTao'], $data['phuongThuc'], 
            $data['diaChi'], $data['khoangCach'], $data['phiShip'], $data['tongTien'], 
            'Chưa thanh toán', 'Đang chờ xác nhận', $data['ghiChu']
        ]);
        return true;
    }
    public function LayMaDHNgayTao() {
        
    }
}


?>
