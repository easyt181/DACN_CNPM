<?php

class ChungMinhGiaoDich {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function themChungMinhGiaoDich($data){
        $sql = "INSERT INTO chungminhgiaodich (maChungMinh, maHoaDon, soTienNhanDuoc, duongDanAnh, thoiGianTaiLen)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['maChungMinh'], $data['maHoaDon'],
            $data['soTienNhanDuoc'], $data['duongDanAnh'], $data['thoiGianTaiLen']
        ]);
        return true;
    }
    
}
?>
