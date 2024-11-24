
<?php
require_once(__DIR__ . '/../models/DonHang.php');
require_once(__DIR__ . '/../models/KhachHang.php');
require_once(__DIR__ . '/../models/ChiTietDonHang.php');
require_once(__DIR__ . '/../models/HoaDon.php');

class QuanLyDonHangController {
    private $donHangModel;
    private $khachHangModel;
    private $hoaDonModel;
    private $chiTietDonHangModel;

    public function __construct($pdo) {
        $this->donHangModel = new DonHang($pdo);
        $this->chiTietDonHangModel = new ChiTietDonHang($pdo);
        $this->khachHangModel = new KhachHang($pdo);
        $this->hoaDonModel = new HoaDon($pdo);  
    }

    public function layDSDonHangTheoNhom() {
        $nhomChoXacNhan = ['Đang chờ xác nhận'];    
        $nhomDangXuLy = ['Đang chuẩn bị', 'Đang giao hàng', 'Đang chờ hoàn tiền - Đã bị hủy bởi khách hàng', 'Đang chờ hoàn tiền - Đã bị hủy bởi quản lý',];
        $nhomDaXuLyXong = ['Đã hoàn thành','Đã bị hủy bởi khách hàng','Đã bị hủy bởi quản lý', 'Đã hoàn tiền', 'Đơn hàng rủi ro'];
        $donHangsChoXacNhan = $this->donHangModel->layDSDonHang($nhomChoXacNhan);
        $donHangsDangXuLy = $this->donHangModel->layDSDonHang($nhomDangXuLy);
        $donHangsDaXuLyXong = $this->donHangModel->layDSDonHang($nhomDaXuLyXong);
        require_once 'views/QuanLyDonHangUI/DanhSachDonHangUI.php';
    }

    // Hiển thị form thêm đơn hàng
    public function hienThiTrangThemDonHang() {
        require_once 'views/QuanLyDonHangUI/ThemDonHangUI.php';
    }

    public function layChiTietDonHang() {
        try {
            header('Content-Type: application/json');
            $maDonHang = isset($_GET['maDonHang']) ? $_GET['maDonHang'] : '';
    
            if (empty($maDonHang)) {
                echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ.']);
                return;
            }
            $orderInfo = $this->donHangModel->layDonHangTheoMa($maDonHang);
            $orderDetails = $this->chiTietDonHangModel->layChiTietDonHangTheoMaDonHang($maDonHang); 
            echo json_encode([
                'success' => true,
                'orderInfo' => $orderInfo,
                'orderDetails' => $orderDetails
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()]);
        }
    }
    // Thêm đơn hàng
    public function themDonHangQuanLy() {
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!$inputData || !isset($inputData['cartItems']) || !is_array($inputData['cartItems'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
            return;
        }
    
        $cartItems = $inputData['cartItems'];
        $sdt = $inputData['sdt'];
        $tenKH = $inputData['tenKH'];
        $diaChiGiaoHang = $inputData['diaChiGiaoHang'];
        $ngayTao = $inputData['ngayTao'];
        $phuongThucThanhToan = $inputData['phuongThucThanhToan'];
        $phiShip = $inputData['phiShip'];
        $tongTien = $inputData['tongTien'];
        $tongTienCongTru = $inputData['tongTienCongTru'] ?? null; 
        $maUuDaiDH = $inputData['maUuDaiDH'] ?? null; 
        $ghiChu = $inputData['ghiChu'] ?? null; 
        
        try {
            // Kiểm tra khách hàng đã tồn tại
            $khachHang = $this->khachHangModel->kiemTraKH($sdt);
            if (!$khachHang) {
                $dataKH = [
                    'maTaiKhoan' => null,
                    'tenKH' => $tenKH,
                    'sdt' => $sdt,
                    'email' => null,
                    'diaChi' => $diaChiGiaoHang,
                ];
                $this->khachHangModel->themKH($dataKH);
                $khachHang = $this->khachHangModel->layThongTinKhachHang($sdt);
            }
    
            $maKH = $khachHang['maKH'];
    
            // Xác định trạng thái thanh toán và hóa đơn
            $trangThaiThanhToan = 'Chưa thanh toán';
            $trangThaiHoaDon = $trangThaiThanhToan;
    
            // Chuẩn bị dữ liệu đơn hàng
            $dataDonHang = [
                'maKH' => $maKH,
                'maTaiKhoanNV' => null, // Tạm thời null nếu chưa có quản lý đăng nhập
                'maUuDaiDH' => $maUuDaiDH,
                'ngayTao' => $ngayTao,
                'phuongThucThanhToan' => $phuongThucThanhToan,
                'diaChiGiaoHang' => $diaChiGiaoHang,
                'khoangCachGiaoHang' => $inputData['khoangCachGiaoHang'],
                'phiShip' => $phiShip,
                'tongTienCongTru' => $tongTienCongTru,
                'tongTien' => $tongTien,
                'trangThaiThanhToan' => $trangThaiThanhToan,
                'trangThaiDonHang' => 'Đang chuẩn bị',
                'ghiChu' => $ghiChu,
            ];
    
            // Thêm đơn hàng vào database
            $thucThi = $this->donHangModel->themDonHang($dataDonHang);
            if (!$thucThi) {
                echo json_encode(['success' => false, 'message' => 'Không thể thêm đơn hàng.']);
                return;
            }
            // Lấy mã đơn hàng vừa thêm
            $maDonHang = $this->donHangModel->lastInsertId($ngayTao);
    
            // Thêm từng món ăn vào chi tiết đơn hàng
            foreach ($cartItems as $item) {
                $dataChiTietDonHang = [
                    'maDonHang' => $maDonHang,
                    'maMonAn' => $item['maMonAn'],
                    'soLuong' => (int) $item['quantity'],
                    'donGia' => (float) $item['gia'],
                    'thanhTien' => (int) $item['quantity'] * (float) $item['gia'],
                ];
                $this->chiTietDonHangModel->themChiTietDonHang($dataChiTietDonHang);
            }
            
            $dataHoaDon = [
                'maDonHang' => $maDonHang,
                'sdt' => $sdt,
                'tenKH' => $tenKH,
                'diaChiGiaoHang' => $diaChiGiaoHang,
                'ngayTao' => $ngayTao,
                'tongTien' => $tongTien,
                'phuongThucThanhToan' => $phuongThucThanhToan,
                'trangThaiHoaDon' => $trangThaiHoaDon,
                'trangThaiHoanTien' => '0',
            ];
            $this->hoaDonModel->themHoaDon($dataHoaDon);
            echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được tạo thành công .','PTTT' => $phuongThucThanhToan, 'maDonHang' => $maDonHang]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }
    
    
    
    

    // Sửa đơn hàng
    public function suaDonHang($maDonHang) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'phuongThucThanhToan' => $_POST['phuongThucThanhToan'],
                'diaChiGiaoHang' => $_POST['diaChiGiaoHang'],
                'tongTienCongTru' => $_POST['tongTienCongTru'],
                'tongTien' => $_POST['tongTien'],
                'ghiChu' => $_POST['ghiChu']
            ];
            $this->donHangModel->suaDonHang($maDonHang, $data);
            header('Location: index.php?controller=donhang&action=index');
        } else {
            $donHang = $this->donHangModel->timKiemDonHang($maDonHang)[0];
            require_once 'views/donhang/sua.php';
        }
    }

    // Hủy đơn hàng
    public function huyDonHang($maDonHang) {
        $this->donHangModel->huyDonHang($maDonHang);
        header('Location: index.php?controller=donhang&action=index');
    }
}
?>


