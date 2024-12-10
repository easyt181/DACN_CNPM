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
    public function kiemTraTK($data) {
        $query = "SELECT COUNT(*) AS soLuong FROM taikhoan WHERE tenDangNhap = ? OR sdt = ? OR email = ?";
        $stmt = $this->pdo->prepare($query);

        try {
            $stmt->execute([
                $data['tenDangNhap'], 
                $data['sdt'], 
                $data['email']]
            );
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['soLuong'] > 0) {
                return true; 
            } else {
                return false; 
            }
        } catch (PDOException $e) {
            echo "Lỗi kiểm tra dữ liệu: " . $e->getMessage();
            return false;
        }
    }

    public function taoTaiKhoanKH($data) {
        $query = "INSERT INTO taikhoan(maQuyen, tenDangNhap, matKhau, email, sdt, trangThai) 
                  VALUES(?,?,?,?,?,?)";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->execute([
            $data['maQuyen'],
            $data['tenDangNhap'],
            $data['matKhau'],
            $data['email'],
            $data['sdt'],
            $data['trangThai']
        ]);
        return true;
    }
    public function layMaTK($tenDangNhap) {
        $query = "SELECT maTaiKhoan FROM taikhoan WHERE tenDangNhap = ?";
        $stmt = $this->pdo->prepare($query);
        try {
            $stmt->execute([$tenDangNhap] );
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi kiểm tra dữ liệu: " . $e->getMessage();
            return false;
        }
    }
    public function kiemTraEmail($email)  {
        $query = "SELECT COUNT(*) AS soLuong FROM taikhoan WHERE email = ?";
        $stmt = $this->pdo->prepare($query);
        try{
            $stmt->execute([
                $email
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result['soLuong']>0){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e) {
            echo "Lỗi kiểm tra dữ liệu: " . $e->getMessage();
            return false;
        }
    }
    public function LayLaiMK($email, $reset_token, $token_expiry)  {
        $query = 'UPDATE taikhoan SET token = ?, hetHanToken = ? WHERE email = ?';
        $stmt = $this->pdo->prepare($query);
        try{
            $stmt->execute([
                $reset_token,
                $token_expiry,
                $email
            ]);
            return true;
        }catch (PDOException $e) {
            echo "err sql: " . $e->getMessage();
            return false; 
        }
    }
    public function kiemTraThoiHan($token)  {
        $query = 'SELECT hetHanToken FROM taikhoan WHERE token = ?';
        $stmt = $this->pdo->prepare($query);
        try{
            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo "err sql: " . $e->getMessage();
            return false; 
        }
    }
    public function thayDoiMK($token, $matKhau)  {
        $query = 'UPDATE taikhoan SET matKhau = ? WHERE token = ?';
        $stmt = $this->pdo->prepare($query);
        try{
            $stmt->execute([
                $matKhau ,
                $token
            ]);
            return true;
        }catch (PDOException $e) {
            echo "err sql: " . $e->getMessage();
            return false; 
        }
    }
    
}
?>
