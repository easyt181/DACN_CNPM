<?php

class HoaDon {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    public function themHoaDon($data) {
        $stmt = $this->db->prepare("
            INSERT INTO HoaDon (maDonHang, sdt, tenKH, diaChiGiaoHang, ngayTao, tongTien, 
            phuongThucThanhToan, trangThaiHoaDon, trangThaiHoanTien) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['maDonHang'], $data['sdt'], $data['tenKH'], $data['diaChiGiaoHang'], $data['ngayTao'],
            $data['tongTien'], $data['phuongThucThanhToan'], $data['trangThaiHoaDon'], $data['trangThaiHoanTien']
        ]);
    }
}
?>
