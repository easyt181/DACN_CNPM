<?php
class ThucDonModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy danh sách món ăn
    public function layDanhSachMonAn() {
        $sql = "SELECT hinhAnhMonAn, tenMonAn, gia FROM thucdon WHERE tinhTrang = 'Còn hàng'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về dạng mảng
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
