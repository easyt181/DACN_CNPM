
<?php
require_once(__DIR__ . '/../models/DonHang.php');
require_once(__DIR__ . '/../models/KhachHang.php');
require_once(__DIR__ . '/../models/ChiTietDonHang.php');
require_once(__DIR__ . '/../models/HoaDon.php');
require_once(__DIR__ . '/../models/ChungMinhGiaoDich.php');

class QuanLyDonHangController
{
    private $donHangModel;
    private $khachHangModel;
    private $hoaDonModel;
    private $chiTietDonHangModel;

    private $chungMinhGiaoDichModel;

    public function __construct($pdo)
    {
        $this->donHangModel = new DonHang($pdo);
        $this->chiTietDonHangModel = new ChiTietDonHang($pdo);
        $this->khachHangModel = new KhachHang($pdo);
        $this->hoaDonModel = new HoaDon($pdo);
        $this->chungMinhGiaoDichModel = new ChungMinhGiaoDich($pdo);
    }

    public function layDSDonHangTheoNhom()
    {
        $nhomChoXacNhan = ['Đang chờ xác nhận'];
        $nhomDangXuLy = ['Đang chuẩn bị', 'Đang giao hàng', 'Đang chờ hoàn tiền - Đã bị hủy bởi khách hàng', 'Đang chờ hoàn tiền - Đã bị hủy bởi quản lý',];
        $nhomDaXuLyXong = ['Đã hoàn thành', 'Đã bị hủy bởi khách hàng', 'Đã bị hủy bởi quản lý', 'Đã hoàn tiền', 'Đơn hàng rủi ro'];
        $donHangsChoXacNhan = $this->donHangModel->layDSDonHang($nhomChoXacNhan);
        $donHangsDangXuLy = $this->donHangModel->layDSDonHang($nhomDangXuLy);
        $donHangsDaXuLyXong = $this->donHangModel->layDSDonHang($nhomDaXuLyXong);
        require_once 'views/QuanLyDonHangUI/DanhSachDonHangUI.php';
    }

    public function timKiemDonHang()
    {
        if (isset($_POST['search'])) {
            $keyword = $_POST['keyword'];
            $nhomChoXacNhan = ['Đang chờ xác nhận'];
            $nhomDangXuLy = ['Đang chuẩn bị', 'Đang giao hàng', 'Đang chờ hoàn tiền - Đã bị hủy bởi khách hàng', 'Đang chờ hoàn tiền - Đã bị hủy bởi quản lý',];
            $nhomDaXuLyXong = ['Đã hoàn thành', 'Đã bị hủy bởi khách hàng', 'Đã bị hủy bởi quản lý', 'Đã hoàn tiền', 'Đơn hàng rủi ro'];
            $donHangsChoXacNhan = $this->donHangModel->timKiemDonHang($nhomChoXacNhan, $keyword);
            $donHangsDangXuLy = $this->donHangModel->timKiemDonHang($nhomDangXuLy, $keyword);
            $donHangsDaXuLyXong = $this->donHangModel->timKiemDonHang($nhomDaXuLyXong, $keyword);
            include 'views/QuanLyDonHangUI/DanhSachDonHangUI.php';
        }
    }

    // Hàm hiển thị trang Sửa đơn hàng
    public function hienThiTrangSuaDonHang() {
        $maDonHang = $_GET['maDonHang'] ?? null;
        if (!$maDonHang) {
            echo "Mã đơn hàng không hợp lệ!";
            return;
        }
        $donHang = $this->donHangModel->layDonHangTheoMa($maDonHang);
        if (!$donHang) {
            echo "Đơn hàng không tồn tại!";
            return;
        }
        $chiTietDonHang = $this->chiTietDonHangModel->layChiTietDonHangTheoMaDonHang($maDonHang);
        
        require_once 'views/QuanLyDonHangUI/SuaDonHangUI.php';
    }

    public function layHoaDonTheoDonHang($maDonHang){
        try {
            $hoaDon = $this->hoaDonModel->layThongTinHoaDon($maDonHang);
            if ($hoaDon && $hoaDon['trangThaiHoaDon'] !== 'Đã hủy') {
                echo json_encode(['success' => true, 'hoaDon' => $hoaDon]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Hóa đơn không tồn tại hoặc đã bị hủy.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    // Sửa đơn hàng
    public function suaDonHang($maDonHang) {

        

    }

    // Hàm lấy chi tiết đơn hàng
    public function layChiTietDonHang()
    {
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


    // Hàm xử lý tên ảnh & upload ảnh
    public function uploadFileCMGD($file)
    {
        $uploadDir = __DIR__ . '/../public/image/cmgd/';
        $originalFileName = basename($file['name']);
        $uniqueFileName = uniqid() . "_" . $originalFileName;
        $filePath = $uploadDir . $uniqueFileName;
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return '/public/image/cmgd/' . $uniqueFileName;
        }
        return null; 
    }

    // Thêm đơn hàng
    public function themDonHangQuanLy($maTaiKhoanNV)
    {   
        header('Content-Type: application/json; charset=utf-8');
        $cartItems = isset($_POST['cartItems']) ? json_decode($_POST['cartItems'], true) : [];
        $tenKH = $_POST['tenKH'];
        $sdt = $_POST['sdt'];
        $diaChiGiaoHang = $_POST['diaChiGiaoHang'];
        $ngayTao = $_POST['ngayTao'];
        $phuongThucThanhToan = $_POST['phuongThucThanhToan'];
        $khoangCachGiaoHang = $_POST['khoangCachGiaoHang'];
        $phiShip = $_POST['phiShip'];
        $tongTien = $_POST['tongTien'];
        $tongTienCongTru = $_POST['tongTienCongTru'] ?? null;
        $maUuDaiDH = $_POST['maUuDaiDH'] ?? null;
        $ghiChu = $_POST['ghiChu'] ?? null;
        $maDonHang = isset($_GET['maDonHang']) ? $_GET['maDonHang'] : null;
        if (!$maDonHang) {
            echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ.']);
            return;
        }
        $existingOrder = $this->donHangModel->kiemTraDonHang($maDonHang);
        if ($existingOrder) {
            $maDonHang = 'DH' . mt_rand(100000, 999999);
        }

        $duongDanAnh = null;
        if (isset($_FILES['transactionImage'])) {
            if ($_FILES['transactionImage']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'Lỗi upload file: ' . $_FILES['transactionImage']['error']]);
                return;
            }
            $duongDanAnh = $this->uploadFileCMGD($_FILES['transactionImage']);
        }

        if ($tenKH == '' || $sdt == '' || $diaChiGiaoHang == '' || $ngayTao == '' || $phuongThucThanhToan == '' || $khoangCachGiaoHang == '' || $phiShip == '' || $tongTien == '') {
            echo json_encode(['success' => false, 'message' => 'thieuthongtin']);
            return;
        }
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
            // Khởi tạo trạng thái thanh toán đã
            if($phuongThucThanhToan  === 'Chuyển khoản trực tiếp' && $duongDanAnh){
                $trangThaiThanhToan = 'Đã thanh toán';
                $trangThaiHoaDon = 'Đã thanh toán';
            }else{
                $trangThaiThanhToan = 'Chưa thanh toán';
                $trangThaiHoaDon = 'Chưa thanh toán';
            }   
            $dataDonHang = [
                'maDonHang' => $maDonHang,  
                'maKH' => $maKH,
                'maTaiKhoanNV' => $maTaiKhoanNV,
                'maUuDaiDH' => $maUuDaiDH,
                'ngayTao' => $ngayTao,
                'phuongThucThanhToan' => $phuongThucThanhToan,
                'diaChiGiaoHang' => $diaChiGiaoHang,
                'khoangCachGiaoHang' => $khoangCachGiaoHang,
                'phiShip' => $phiShip,
                'tongTienCongTru' => $tongTienCongTru,
                'tongTien' => $tongTien,
                'trangThaiThanhToan' => $trangThaiThanhToan,
                'trangThaiDonHang' => 'Đang chuẩn bị',
                'ghiChu' => $ghiChu,
            ];

            // Thêm đơn hàng vào database
            $maDonHang = $this->donHangModel->themDonHang($dataDonHang);
            if (!$maDonHang) {
                echo json_encode(['success' => false, 'message' => 'Không thể thêm đơn hàng.']);
                return;
            }

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
            $maHoaDon = $this->hoaDonModel->themHoaDon($dataHoaDon);
            if($phuongThucThanhToan === 'Chuyển khoản trực tiếp' && $duongDanAnh) {
                $data = [
                    'maChungMinh' => 'CMGD' . uniqid() .'of'. $maDonHang,
                    'maHoaDon' => $maHoaDon,
                    'soTienNhanDuoc' => $tongTien,
                    'duongDanAnh' => $duongDanAnh,
                    'thoiGianTaiLen' => $ngayTao,
                ];
                $cmgd = $this->chungMinhGiaoDichModel->themChungMinhGiaoDich($data);
                $this->donHangModel->capNhatTTDonHang('Đã thanh toán', 'Đang chuẩn bị', $maDonHang);
                if($cmgd === false){
                    echo json_encode(['success' => false, 'message' => 'Không thể thêm chứng minh giao dịch.']);
                    return;
                }
            }
            echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được tạo thành công .', 'maDonHang' => $maDonHang]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }



    // Xác nhận đơn hàng
    public function xacNhanDonHang($maTaiKhoanNV)
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents("php://input"), true);
        $maDonHang = $data['maDonHang'];
        try {
            $this->donHangModel->xacNhanDonHang($maTaiKhoanNV, $maDonHang);
            echo json_encode(['success' => true, 'message' => 'Xác nhận đơn hàng thành công.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }

        // Cần gửi thêm thông báo cho khách hàng
    }


    public function capNhatTTDonHang()
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents("php://input"), true);
        $maDonHang = $data['maDonHang'];
        $trangThaiDonHang = $data['trangThaiDonHang'];
        $dataDonHang = $this->donHangModel->layDonHangTheoMa($maDonHang);
        try {
            if ($trangThaiDonHang == 'Đang chuẩn bị') {
                $trangThaiMoi = 'Đang giao hàng';
                $trangThaiThanhToan = $dataDonHang['trangThaiThanhToan'];
                $this->donHangModel->capNhatTTDonHang($trangThaiThanhToan, $trangThaiMoi, $maDonHang);
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
            } elseif ($trangThaiDonHang == 'Đang giao hàng') {
                $trangThaiMoi = 'Đã hoàn thành';
                if ($dataDonHang['trangThaiThanhToan'] == 'Chưa thanh toán') {
                    $trangThaiThanhToan = 'Đã thanh toán';
                    $trangThaiHoaDon = 'Đã thanh toán';
                    $this->donHangModel->capNhatTTDonHang($trangThaiThanhToan, $trangThaiMoi, $maDonHang);
                    $this->hoaDonModel->capNhatTTHoaDon($trangThaiHoaDon, '0', $maDonHang);
                    echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
                } elseif ($dataDonHang['trangThaiThanhToan'] == 'Đã thanh toán') {
                    $trangThaiThanhToan = $dataDonHang['trangThaiThanhToan'];
                    $this->donHangModel->capNhatTTDonHang($trangThaiThanhToan, $trangThaiMoi, $maDonHang);
                    echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công.']);
                }
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        // Cần gửi thêm thông báo cho khách hàng
    }
    // Hủy đơn hàng
    public function huyDonHang($maTaiKhoanNV)
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents("php://input"), true);
        $maDonHang = $data['maDonHang'];
        $lyDo = $data['lyDo'];
        $ghiChu = 'Đã bị hủy bởi quản lý ' . $maTaiKhoanNV . ' với lý do: ' . $lyDo;
        $dataDonHang = $this->donHangModel->layDonHangTheoMa($maDonHang);
        if ($dataDonHang['trangThaiThanhToan'] == 'Đã thanh toán') {
            $trangThaiDonHang = 'Đang chờ hoàn tiền - Đã bị hủy bởi quản lý';
            $trangThaiHoaDon = 'Đã hủy - Hoàn tiền';
            $trangThaiHoanTien = '1';
            $this->hoaDonModel->capNhatTTHoaDon($trangThaiHoaDon, $trangThaiHoanTien, $maDonHang);
        } else {
            $trangThaiDonHang = 'Đã bị hủy bởi quản lý';
            $trangThaiHoaDon = 'Đã hủy';
            $trangThaiHoanTien = '0';
            $this->hoaDonModel->capNhatTTHoaDon($trangThaiHoaDon, $trangThaiHoanTien, $maDonHang);
        }
        try {
            $this->donHangModel->huyDonHang($maTaiKhoanNV, $trangThaiDonHang, $ghiChu, $maDonHang);
            echo json_encode(['success' => true, 'message' => 'Từ chối đơn hàng thành công.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        // Cần gửi thêm thông báo cho khách hàng    
    }
}
?>


