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
        $query = "INSERT INTO donhang (maKH, maTaiKhoanNV, maUuDaiDH, ngayTao, phuongThucThanhToan, diaChiGiaoHang, khoangCachGiaoHang, phiShip, tongTienCongTru, tongTien, trangThaiThanhToan, trangThaiDonHang, ghiChu) 
                  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $data['maKH'], $data['maTaiKhoanNV'], $data['maUuDaiDH'], $data['ngayTao'], $data['phuongThucThanhToan'], 
            $data['diaChiGiaoHang'], $data['khoangCachGiaoHang'], $data['phiShip'], $data['tongTienCongTru'], $data['tongTien'], 
            $data['trangThaiThanhToan'], $data['trangThaiDonHang'], $data['ghiChu']
        ]);
        return true;  
    }
    public function lastInsertId($ngayTao) {
        $sql = "SELECT maDonHang FROM DonHang WHERE ngayTao = '$ngayTao'";  
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $maDonHang = $stmt->fetchColumn();
        return $maDonHang;
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

    public function kiemTraDonHang($maDonHang) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM donhang WHERE maDonHang = ?");
        $stmt->execute([$maDonHang]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }   

    public function layDSDonHang($nhomTrangThai) {
        try {
            $query = "SELECT 
                        donhang.maDonHang, 
                        donhang.ngayTao, 
                        donhang.diaChiGiaoHang,
                        donhang.tongTien, 
                        donhang.trangThaiThanhToan,
                        donhang.trangThaiDonHang,
                        hoadon.tenKH, 
                        hoadon.sdt
                      FROM donhang 
                      INNER JOIN hoadon ON donhang.maDonHang = hoadon.maDonHang
                      WHERE donhang.trangThaiDonHang IN (" . implode(", ", array_fill(0, count($nhomTrangThai), "?")) . ")";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($nhomTrangThai);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
    
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
    public function layDonHangTheoMa($maDonHang) {
        try {
            $query = "
            SELECT 
            donhang.maDonHang, 
            donhang.ngayTao, 
            donhang.diaChiGiaoHang,
            donhang.tongTien, 
            donhang.trangThaiThanhToan,
            donhang.phuongThucThanhToan,
            donhang.trangThaiDonHang,
            donhang.khoangCachGiaoHang,
            donhang.tongTienCongTru,
            donhang.ghiChu,
            donhang.phiShip,
            hoadon.tenKH, 
            hoadon.sdt
            FROM donhang 
            LEFT JOIN hoadon ON donhang.maDonHang = hoadon.maDonHang
            WHERE donhang.maDonHang = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$maDonHang]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return null;
        }
    }

}
?>
