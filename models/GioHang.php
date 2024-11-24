<?php

class GioHangModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo; // Gán đối tượng PDO
    }

    // function insertDonHang($donhang) {
    //     echo json_encode($donhang);
    //     // Câu lệnh SQL
    //     $query = "INSERT INTO donhang (maDonHang, maKH, maTaiKhoanNV, maUuDaiDH, ngayTao, phuongThucThanhToan, diaChiGiaoHang,
    //          khoangCachGiaoHang, phiShip, tongTienCongTru, tongTien, trangThaiThanhToan, trangThaiDonHang, ghiChu)
    //          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    //     $stmt = $this->db->prepare($query); // Sử dụng `$this->db`
    //     $maDH = 'DH' . rand(100000, 999999); // Random mã đơn hàng
    
    //     // Liên kết các giá trị
    //     $stmt->bindValue(1, $maDH);
    //     $stmt->bindValue(2, $donhang['maKH']);
    //     $stmt->bindValue(3, NULL, PDO::PARAM_NULL); // Giá trị NULL
    //     $stmt->bindValue(4, NULL, PDO::PARAM_NULL); // Giá trị NULL
    //     $stmt->bindValue(5, $donhang['ngayTao']);
    //     $stmt->bindValue(6, $donhang['phuongThuc']);
    //     $stmt->bindValue(7, $donhang['diaChi']);
    //     $stmt->bindValue(8, $donhang['khoangCach']);
    //     $stmt->bindValue(9, $donhang['phiShip']);
    //     $stmt->bindValue(10, NULL, PDO::PARAM_NULL); // Giá trị NULL
    //     $stmt->bindValue(11, $donhang['tongTien']);
    //     $stmt->bindValue(12, 'Chưa thanh toán'); // Mặc định
    //     $stmt->bindValue(13, 'Đang chờ xác nhận'); // Mặc định
    //     $stmt->bindValue(14, $donhang['ghiChu'] ?? NULL, PDO::PARAM_NULL); // Ghi chú hoặc NULL
    
    //     if ($stmt->execute()) {
    //         echo "Thêm đơn hàng thành công!";
    //     } else {
    //         echo "Lỗi: " . $stmt->errorInfo()[2];
    //     }
    // }
    public function insertDonHang($data) {
        $maDH = 'DH' . rand(100000, 999999);
        $query = "INSERT INTO donhang (maDonHang, maKH, maTaiKhoanNV, maUuDaiDH, ngayTao, phuongThucThanhToan, diaChiGiaoHang, khoangCachGiaoHang, phiShip, tongTienCongTru, tongTien, trangThaiThanhToan, trangThaiDonHang, ghiChu) 
                  VALUES (?,?,NULL,NULL,?,?,?,?,?,NULL,?,?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $maDH, $data['maKH'], $data['ngayTao'], $data['phuongThuc'], 
            $data['diaChi'], $data['khoangCach'], $data['phiShip'], $data['tongTien'], 
            'Chưa thanh toán', 'Đang chờ xác nhận', $data['ghiChu']
        ]);
        return true;
    }
}


?>
