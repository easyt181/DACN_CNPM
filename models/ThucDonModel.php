<?php
require_once 'config/database.php';
class ThucDonModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy danh sách món ăn
    public function layDanhSachMonAn() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM thucDon");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách món ăn
        } catch (PDOException $e) {
            echo "Lỗi truy vấn: " . $e->getMessage();
            return [];
        }
    }

    // Tìm kiếm món ăn theo tên
    public function timKiemMonAn($tuKhoa) {
        $sql = "SELECT hinhAnhMonAn, tenMonAn, gia FROM thucdon WHERE tinhTrang = 'Còn hàng' AND tenMonAn LIKE :tuKhoa";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tuKhoa' => '%' . $tuKhoa . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về kết quả tìm kiếm
    }
}
?>
