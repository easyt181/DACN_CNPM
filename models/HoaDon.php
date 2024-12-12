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
                $stmt = $this->db->prepare("SELECT maHoaDon FROM HoaDon WHERE maDonHang = ? ORDER BY ngayTao DESC LIMIT 1");
                $stmt->execute([$data['maDonHang']]);
                return $stmt->fetch(PDO::FETCH_ASSOC)['maHoaDon'];
            } else {
                throw new Exception('Error executing SQL query.');
            }
        } catch (PDOException $e) {
            echo "PDOException: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
            return false;
        }
    }
    public function capNhatTTHoaDon($trangThaiHoaDon, $trangThaiHoanTien, $maDonHang) {
        try {
            $stmt = $this->db->prepare("
                UPDATE HoaDon SET trangThaiHoaDon = ?, trangThaiHoanTien = ? WHERE maDonHang = ?
            ");
            $result = $stmt->execute([$trangThaiHoaDon, $trangThaiHoanTien, $maDonHang]);
            if ($result) {
                return true;
            } else {
                throw new Exception('Error executing SQL query.');
            }
        } catch (PDOException $e) {
            echo "PDOException: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
            return false;
        }
    }

    public function layThongTinHoaDon($maDonHang) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM HoaDon WHERE maDonHang = ? AND trangThaiHoaDon != 'Đã hủy'");
            $stmt->execute([$maDonHang]);
            $hoaDon = $stmt->fetch();
            return $hoaDon;
        } catch (PDOException $e) {
            echo "PDOException: " . $e->getMessage();
            return false;
        }
    }

}
?>
