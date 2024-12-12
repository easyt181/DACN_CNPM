<?php

class DonHang {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function themDonHang($data) {
        $query = "INSERT INTO donhang (maDonHang, maKH, maTaiKhoanNV, maUuDaiDH, ngayTao, phuongThucThanhToan, diaChiGiaoHang, khoangCachGiaoHang, phiShip, tongTienCongTru, tongTien, trangThaiThanhToan, trangThaiDonHang, ghiChu) 
                  VALUES (?, ?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $data['maDonHang'], $data['maKH'], $data['maTaiKhoanNV'], $data['maUuDaiDH'], $data['ngayTao'], $data['phuongThucThanhToan'], 
            $data['diaChiGiaoHang'], $data['khoangCachGiaoHang'], $data['phiShip'], $data['tongTienCongTru'], $data['tongTien'], 
            $data['trangThaiThanhToan'], $data['trangThaiDonHang'], $data['ghiChu']
        ]);
        $maDonHang = $this->lastInsertId($data['ngayTao']);
        return $maDonHang;  
    }
    
    public function kiemTraDonHang($maDonHang){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM DonHang WHERE maDonHang = ?");
        $stmt->execute([$maDonHang]);
        $result = $stmt->fetchColumn();
        if($result > 0){
            return true;
        }else{
            return false;
        }
    }

    public function lastInsertId($ngayTao) {
        $sql = "SELECT maDonHang FROM DonHang WHERE ngayTao = '$ngayTao'";  
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $maDonHang = $stmt->fetchColumn();
        return $maDonHang;
    }

    public function capNhatTTDonHang($trangThaiThanhToan, $trangThaiMoi, $maDonHang): bool{
        $stmt = $this->db->prepare("UPDATE DonHang SET trangThaiThanhToan = ?, trangThaiDonHang = ? WHERE maDonHang = ?");
        $stmt->execute([$trangThaiThanhToan,$trangThaiMoi, $maDonHang]);
        return true;    
    }

    public function xacNhanDonHang($maTaiKhoanNV, $maDonHang) {
        $stmt = $this->db->prepare("UPDATE DonHang SET trangThaiDonHang = 'Đang chuẩn bị', maTaiKhoanNV = ? WHERE maDonHang = ?");
        $stmt->execute([$maTaiKhoanNV, $maDonHang]);
        return true;
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

    public function huyDonHang($maTaiKhoanNV, $trangThaiDonHang, $ghiChu, $maDonHang) {
        $stmt = $this->db->prepare("UPDATE DonHang SET maTaiKhoanNV = ?, trangThaiDonHang = ?, ghiChu = ?  WHERE maDonHang = ?");
        $stmt->execute([$maTaiKhoanNV, $trangThaiDonHang, $ghiChu, $maDonHang]);
        return true;
    }


    public function timKiemDonHang($nhomTrangThai, $tuKhoa) {
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

            if (!empty($tuKhoa)) {
                $query .= " AND (hoadon.tenKH LIKE ? OR hoadon.sdt LIKE ? OR donhang.maDonHang LIKE ?)";
            }

            $stmt = $this->db->prepare($query);
            $params = $nhomTrangThai;

            if (!empty($tuKhoa)) {
                $searchTerm = "%" . $tuKhoa . "%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
            }

            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
    
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
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
                      WHERE donhang.trangThaiDonHang IN (" . implode(", ", array_fill(0, count($nhomTrangThai), "?")) . ")
                      ORDER BY donhang.ngayTao DESC";  // Thêm sắp xếp theo ngayTao giảm dần
            
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
            donhang.maUuDaiDH,
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
