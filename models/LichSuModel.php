<?php
require_once 'config/database.php';

class DanhGiaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Hàm để thêm đánh giá vào cơ sở dữ liệu
    public function themDanhGia($maDonHang, $maKH, $noiDungDanhGia, $soSao) {
        $sql = "INSERT INTO danhgiadonhang (maDonHang, maKH, noiDungDanhGia, soSao, ngayDanhGia) 
                VALUES (:maDonHang, :maKH, :noiDungDanhGia, :soSao, NOW())";
        $stmt = $this->pdo->prepare($sql);
        
        // Gán giá trị cho các tham số
        $stmt->bindParam(':maDonHang', $maDonHang);
        $stmt->bindParam(':maKH', $maKH);
        $stmt->bindParam(':noiDungDanhGia', $noiDungDanhGia);
        $stmt->bindParam(':soSao', $soSao);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function huyDonHang($maDonHang){
        // Sửa câu lệnh SQL, loại bỏ dấu nháy đơn quanh tên cột
        $query = "UPDATE donhang SET trangThaiDonHang = 'Đã bị hủy bởi khách hàng', ghiChu = 'khach mệt' WHERE maDonHang = :maDonHang";
        
        // Chuẩn bị câu lệnh SQL
        $stmt = $this->pdo->prepare($query);
        
        // Gán giá trị cho các tham số
        $ghiChu = 'Khách mệt';  // Gán giá trị cho ghiChu, bạn có thể thay đổi giá trị này theo nhu cầu
        $stmt->bindParam(':maDonHang', $maDonHang);
        // $stmt->bindParam(':ghiChu', 'khách mệt');
        
        // Thực thi câu lệnh
        $stmt->execute();
    }
    
}
