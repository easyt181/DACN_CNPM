<?php

class DonHang {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function xacNhanDonHang($maDonHang, $trangThaiMoi) {
        $stmt = $this->db->prepare("UPDATE DonHang SET trangThaiDonHang = ? WHERE maDonHang = ?");
        return $stmt->execute([$trangThaiMoi, $maDonHang]);
    }

    public function themDonHang($data) {
        $stmt = $this->db->prepare("
            INSERT INTO DonHang (maKH, maTaiKhoanNV, maUuDaiDH, ngayTao, phuongThucThanhToan, diaChiGiaoHang, 
            khoangCachGiaoHang, phiShip, tongTienCongTru, tongTien, trangThaiThanhToan, trangThaiDonHang, ghiChu) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['maKH'], $data['maTaiKhoanNV'], $data['maUuDaiDH'], $data['ngayTao'], $data['phuongThucThanhToan'],
            $data['diaChiGiaoHang'], $data['khoangCachGiaoHang'], $data['phiShip'], $data['tongTienCongTru'], 
            $data['tongTien'], $data['trangThaiThanhToan'], $data['trangThaiDonHang'], $data['ghiChu']
        ]);
    }

    public function suaDonHang($maDonHang, $data) {
        $stmt = $this->db->prepare("
            UPDATE DonHang SET 
                phuongThucThanhToan = ?, diaChiGiaoHang = ?, tongTienCongTru = ?, tongTien = ?, ghiChu = ? 
            WHERE maDonHang = ?
        ");
        return $stmt->execute([
            $data['phuongThucThanhToan'], $data['diaChiGiaoHang'], $data['tongTienCongTru'], $data['tongTien'], 
            $data['ghiChu'], $maDonHang
        ]);
    }

    public function huyDonHang($maDonHang) {
        $stmt = $this->db->prepare("DELETE FROM DonHang WHERE maDonHang = ?");
        return $stmt->execute([$maDonHang]);
    }

    public function timKiemDonHang($tuKhoa) {
        $stmt = $this->db->prepare("SELECT * FROM DonHang WHERE maDonHang LIKE ? OR trangThaiDonHang LIKE ?");
        $stmt->execute(['%' . $tuKhoa . '%', '%' . $tuKhoa . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDSDonHang($trangThai) {

        $sql = "
            SELECT 
                donhang.maDonHang, 
                donhang.ngayTao, 
                donhang.diaChiGiaoHang,
                donhang.tongTien, 
                donhang.trangThaiThanhToan,
                hoadon.tenKH, 
                hoadon.sdt
            FROM donhang 
            INNER JOIN hoadon ON donhang.maDonHang = hoadon.maDonHang
            WHERE donhang.trangThaiDonHang = :trangThai
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['trangThai' => $trangThai]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
?>
