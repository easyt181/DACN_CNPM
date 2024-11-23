<?php
class ThucDon {
    private $db; // Kết nối tới cơ sở dữ liệu

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Tìm kiếm món ăn theo tên hoặc loại món ăn
     * @param string $keyword Từ khóa tìm kiếm
     * @return array Kết quả tìm kiếm
     */
    public function timKiemMonAn($keyword) {
        $sql = "SELECT * FROM thucdon 
                WHERE tenMonAn LIKE ? OR loaiMonAn LIKE ?";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$keyword%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách món ăn được đề cử
     * @return array Danh sách món ăn đề cử
     */
    public function layMonAnDeCu() {
        $sql = "SELECT * FROM thucdon WHERE deCuMonAn = 1 AND tinhTrang = 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin chi tiết của một món ăn
     * @param int $id ID món ăn
     * @return array|null Thông tin món ăn hoặc null nếu không tìm thấy
     */
    public function layThongTinMonAn($id) {
        $sql = "SELECT * FROM thucdon WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
