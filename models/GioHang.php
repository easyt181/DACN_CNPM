<?php

class GioHangModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo; // Gán đối tượng PDO
    }
    public function insertDonHang($data) {
        $maDH = 'DH' . rand(100000, 999999);
        $query = "INSERT INTO donhang (maDonHang, maKH, maTaiKhoanNV, maUuDaiDH, ngayTao, phuongThucThanhToan, diaChiGiaoHang, khoangCachGiaoHang, phiShip, tongTienCongTru, tongTien, trangThaiThanhToan, trangThaiDonHang, ghiChu) 
                  VALUES (?,?,NULL,NULL,?,?,?,?,?,NULL,?,?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$maDH,
            $data['maKH'], $data['ngayTao'], $data['phuongThuc'], 
            $data['diaChiGiaoHang'], $data['khoangCach'], $data['phiShip'], $data['tongTien'], 
            'Chưa thanh toán', 'Đang chờ xác nhận', $data['ghiChu']
        ]);
        return true;
    }
    public function ThayDoiDiaChi($diaChi, $maKH, $tenKH, $sdt) {
        $query = 'UPDATE khachhang SET diaChi = ?, tenKH = ?, sdt = ? WHERE maKH = ?';

        $stmt = $this->db->prepare($query);
        try {
            $stmt->execute([$diaChi, $tenKH, $sdt, $maKH]);
            return true;
        }catch (PDOException $e) {
            echo "Lỗi kiểm tra dữ liệu: " . $e->getMessage();
            return false;
        }
    }
}


?>
