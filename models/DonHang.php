<?php

class DonHang {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
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
            // Câu truy vấn SQL với điều kiện tìm kiếm từ khóa
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
    
            // Nếu từ khóa tìm kiếm không rỗng, thêm điều kiện vào câu truy vấn
            if (!empty($tuKhoa)) {
                $query .= " AND (hoadon.tenKH LIKE ? OR hoadon.sdt LIKE ? OR donhang.maDonHang LIKE ?)";
            }
    
            // Chuẩn bị câu truy vấn
            $stmt = $this->db->prepare($query);
    
            // Mảng các tham số đầu vào (trạng thái đơn hàng)
            $params = $nhomTrangThai;
    
            // Nếu có từ khóa tìm kiếm, thêm vào mảng tham số
            if (!empty($tuKhoa)) {
                $searchTerm = "%" . $tuKhoa . "%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
            }
    
            // Thực thi câu truy vấn với tham số
            $stmt->execute($params);
    
            // Lấy kết quả trả về
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
