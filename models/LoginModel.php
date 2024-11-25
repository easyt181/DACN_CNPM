<?php
class TaiKhoan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByUsername($tenDangNhap) {
        $stmt = $this->pdo->prepare("SELECT * FROM taikhoan WHERE tenDangNhap = :tenDangNhap");
        $stmt->execute(['tenDangNhap' => $tenDangNhap]);
        return $stmt->fetch();
    }
}
?>
