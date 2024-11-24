<?php

class HoaDon {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    public function themHoaDon($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO HoaDon (maDonHang, sdt, tenKH, diaChiGiaoHang, ngayTao, tongTien, 
                phuongThucThanhToan, trangThaiHoaDon, trangThaiHoanTien) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $result = $stmt->execute([
                $data['maDonHang'], $data['sdt'], $data['tenKH'], $data['diaChiGiaoHang'], $data['ngayTao'],
                $data['tongTien'], $data['phuongThucThanhToan'], $data['trangThaiHoaDon'], $data['trangThaiHoanTien']
            ]);
    
            if ($result) {
                return true;
            } else {
                throw new Exception('Error executing SQL query.');
            }
        } catch (PDOException $e) {
            // Bắt lỗi nếu có vấn đề với SQL
            echo "PDOException: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            // Bắt lỗi chung nếu có lỗi ngoài SQL
            echo "Exception: " . $e->getMessage();
            return false;
        }
    }

    
}
?>
