
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

    public function timKiemDonHang() {
        if (isset($_POST['search'])) {
            $keyword = $_POST['keyword'];
            $nhomChoXacNhan = ['Đang chờ xác nhận'];    
            $nhomDangXuLy = ['Đang chuẩn bị', 'Đang giao hàng', 'Đang chờ hoàn tiền - Đã bị hủy bởi khách hàng', 'Đang chờ hoàn tiền - Đã bị hủy bởi quản lý',];
            $nhomDaXuLyXong = ['Đã hoàn thành','Đã bị hủy bởi khách hàng','Đã bị hủy bởi quản lý', 'Đã hoàn tiền', 'Đơn hàng rủi ro'];
            $donHangsChoXacNhan = $this->donHangModel->timKiemDonHang($nhomChoXacNhan, $keyword);
            $donHangsDangXuLy = $this->donHangModel->timKiemDonHang($nhomDangXuLy, $keyword);
            $donHangsDaXuLyXong = $this->donHangModel->timKiemDonHang($nhomDaXuLyXong, $keyword);
            include 'views/QuanLyDonHangUI/DanhSachDonHangUI.php';  // Giả sử bạn đang trả về view này
        }
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
    
    
    
    // Xác nhận đơn hàng
    public function xacNhanDonHang($maTaiKhoanNV) {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents("php://input"), true);
        $maDonHang = $data['maDonHang'];
        try{
            $this->donHangModel->xacNhanDonHang( $maTaiKhoanNV, $maDonHang);
            echo json_encode(['success' => true, 'message' => 'Xác nhận đơn hàng thành công.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }

        // Cần gửi thêm thông báo cho khách hàng
    }


    public function capNhatTTDonHang(){
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents("php://input"), true);
        $maDonHang = $data['maDonHang'];
        $trangThaiDonHang = $data['trangThaiDonHang'];
        $dataDonHang = $this->donHangModel->layDonHangTheoMa($maDonHang); 
        try{  
            if($trangThaiDonHang == 'Đang chuẩn bị'){
                $trangThaiMoi = 'Đang giao hàng';
                $trangThaiThanhToan = $dataDonHang['trangThaiThanhToan'];
                $this->donHangModel->capNhatTTDonHang($trangThaiThanhToan,$trangThaiMoi, $maDonHang);
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
            }elseif($trangThaiDonHang == 'Đang giao hàng'){
                $trangThaiMoi = 'Đã hoàn thành';
                if($dataDonHang['trangThaiThanhToan'] == 'Chưa thanh toán'){
                    $trangThaiThanhToan = 'Đã thanh toán';
                    $trangThaiHoaDon = 'Đã thanh toán';
                    $this->donHangModel->capNhatTTDonHang($trangThaiThanhToan, $trangThaiMoi, $maDonHang);
                    $this->hoaDonModel->capNhatTTHoaDon($trangThaiHoaDon, '0', $maDonHang);
                    echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
                }elseif($dataDonHang['trangThaiThanhToan'] == 'Đã thanh toán'){
                    $trangThaiThanhToan = $dataDonHang['trangThaiThanhToan'];
                    $this->donHangModel->capNhatTTDonHang($trangThaiThanhToan, $trangThaiMoi, $maDonHang);
                    echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
                }
            
        }
        }catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        // Cần gửi thêm thông báo cho khách hàng
    }
    // Hủy đơn hàng
    public function huyDonHang($maTaiKhoanNV) {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents("php://input"), true);
        $maDonHang = $data['maDonHang'];
        $lyDo = $data['lyDo']; 
        $ghiChu = 'Đã bị hủy bởi quản lý '.$maTaiKhoanNV.' với lý do: '.$lyDo;
        $dataDonHang = $this->donHangModel->layDonHangTheoMa($maDonHang);
        if($dataDonHang['trangThaiThanhToan'] == 'Đã thanh toán'){ 
            $trangThaiDonHang = 'Đang chờ hoàn tiền - Đã bị hủy bởi quản lý';
            $trangThaiHoaDon = 'Đã hủy - Hoàn tiền';
            $trangThaiHoanTien = '1';  
            $this->hoaDonModel->capNhatTTHoaDon($trangThaiHoaDon, $trangThaiHoanTien, $maDonHang); 
        }else{
            $trangThaiDonHang = 'Đã bị hủy bởi quản lý';
            $trangThaiHoaDon = 'Đã hủy';
            $trangThaiHoanTien = '0';
            $this->hoaDonModel->capNhatTTHoaDon($trangThaiHoaDon, $trangThaiHoanTien, $maDonHang); 
        }
        try{
            $this->donHangModel->huyDonHang($maTaiKhoanNV, $trangThaiDonHang, $ghiChu, $maDonHang);
            echo json_encode(['success' => true, 'message' => 'Từ chối đơn hàng thành công.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        // Cần gửi thêm thông báo cho khách hàng    
    }



    // Sửa đơn hàng
    public function suaDonHang($maDonHang) {

    }

    
}
?>


