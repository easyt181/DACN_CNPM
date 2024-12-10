<?php

class ChiTietDonHang {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function layChiTietDonHangTheoMaDonHang($maDonHang) {
        $stmt = $this->db->prepare("SELECT td.tenMonAn, ct.maMonAn, ct.donGia, ct.soLuong, ct.thanhTien FROM chitietdonhang ct LEFT JOIN thucdon td ON ct.maMonAn = td.maMonAn WHERE ct.maDonHang = ?");
        $stmt->execute([$maDonHang]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function tinhThanhTien($maChiTiet) {
        $stmt = $this->db->prepare("SELECT soLuong, donGia FROM ChiTietDonHang WHERE maChiTiet = ?");
        $stmt->execute([$maChiTiet]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['soLuong'] * $result['donGia'];
    }

    public function themChiTietDonHang($data) {
        $stmt = $this->db->prepare("
            INSERT INTO ChiTietDonHang (maDonHang, maMonAn, soLuong, donGia, thanhTien) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['maDonHang'], $data['maMonAn'], $data['soLuong'], $data['donGia'], $data['thanhTien']
        ]);
    }
}
?>
