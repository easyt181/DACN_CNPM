<?php
class KhachHang {
    private $db; 

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Kiểm tra khách hàng đã có trong hệ thống chưa
    public function kiemTraKH($sdt) {
        $sql = "SELECT * FROM khachhang WHERE sdt = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sdt]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm khách hàng mới
    public function themKH($data) {
        $sql = "INSERT INTO khachhang (maTaiKhoan, tenKH, sdt, email, diaChi)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['maTaiKhoan'], $data['tenKH'],
            $data['sdt'], $data['email'], $data['diaChi']
        ]);
        return true;
    }

    // Sửa thông tin khách hàng
    public function suaKH($maKH, $data) {
        $sql = "UPDATE khachhang 
                SET maTaiKhoan = ?, tenKH = ?, sdt = ?, email = ?, diaChi = ?
                WHERE maKH = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['maTaiKhoan'], $data['tenKH'], $data['sdt'],
            $data['email'], $data['diaChi'], $maKH
        ]);
    }

    // Xóa khách hàng
    public function xoaKH($maKH) {
        $sql = "DELETE FROM khachhang WHERE maKH = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$maKH]);
    }

    // Lấy thông tin khách hàng
    public function layThongTinKhachHang($sdt) {
        $sql = "SELECT * FROM khachhang WHERE sdt = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sdt]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách khách hàng
    public function layDanhSachKH() {
        $sql = "SELECT * FROM khachhang";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm khách hàng theo từ khóa
    public function timKiemKH($keyword) {
        $sql = "SELECT * FROM khachhang 
                WHERE tenKH LIKE ? OR sdt LIKE ? OR email LIKE ?";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$keyword%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
