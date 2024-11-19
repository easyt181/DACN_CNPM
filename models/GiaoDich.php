<?php

class GiaoDich {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function themGiaoDich($data) {
        $stmt = $this->db->prepare("
            INSERT INTO GiaoDich (maTaiKhoanNV, ngayGiaoDich, loaiGiaoDich, soTaiKhoan, soTienVao, 
            soTienRa, noiDungGiaoDich, ngayTaoGiaoDich, trangThaiGiaoDich) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['maTaiKhoanNV'], $data['ngayGiaoDich'], $data['loaiGiaoDich'], $data['soTaiKhoan'], 
            $data['soTienVao'], $data['soTienRa'], $data['noiDungGiaoDich'], $data['ngayTaoGiaoDich'], 
            $data['trangThaiGiaoDich']
        ]);
    }
}
?>
