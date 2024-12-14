<?php

require_once(__DIR__ . '/../models/GioHang.php');
require_once(__DIR__ . '/../models/DonHang.php');
require_once(__DIR__ . '/../models/HoaDon.php');
require_once(__DIR__ . '/../models/chiTietDonHang.php');
require_once(__DIR__ . '/../models/khachHang.php');

class GioHangController
{

    private $gioHangModel;
    private $donHangModel;
    private $hoaDonModel;
    private $chiTietDonHangModel;
    private $khachHangModel;
    private $thucDonModel;
    private $data;
    public function __construct($pdo = null)
    {
        if ($pdo != null) {
            $this->gioHangModel = new GioHangModel($pdo);
            $this->donHangModel = new DonHang($pdo);
            $this->chiTietDonHangModel = new ChiTietDonHang($pdo);
            $this->hoaDonModel = new HoaDon($pdo);
            $this->khachHangModel = new KhachHang($pdo);
            $this->thucDonModel = new ThucDonModel($pdo);
            $this->data = json_decode(file_get_contents('php://input'), true);
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }
    }

    public function layThongTinKH()
    {
        if (isset($_SESSION['maTaiKhoan'])) {
            $rl = $this->khachHangModel->layThongTinKHMaTK($_SESSION['maTaiKhoan']);
            $_SESSION['KH'] = $rl;
        }
    }
    public function thongTinGioHang()
    {
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

        if (!empty($cart) && isset($cart[0]['maMonAn'])) {

            $danhSachMonAn = [];

            foreach ($cart as $maMon) {
                $monAn = $this->thucDonModel->danhSachMonAnGioHang($maMon['maMonAn']);
                if (!empty($monAn)) {
                    array_push($danhSachMonAn, $monAn[0]);
                }
            }

            $CTDH = [];
            $tongTien = 0;
            $index = 0;

            foreach ($cart as $item) {
                if (isset($danhSachMonAn[$index])) {
                    array_push($CTDH, ['maMonAn' => $item['maMonAn'], 'soLuong' => $item['soLuong'], 'donGia' => $danhSachMonAn[$index]['gia']]);
                    $tongTien += (int)$danhSachMonAn[$index]['gia'] * (int)$item['soLuong'];
                    $danhSachMonAn[$index]['soLuong'] = $item['soLuong'];
                }
                $index += 1;
            }

            array_push($CTDH, ['tongTien' => $tongTien]);

            $arrMonAn = ['CTDH' => $CTDH, 'cart' => $danhSachMonAn];

            $danhSachMonAn[0]['tongTien'] = $tongTien;

            setcookie('cart', json_encode($danhSachMonAn), time() + (86400 * 30), "/");

            return $arrMonAn;
        } else {
            return ['CTDH' => [], 'cart' => []];
        }
    }


    public function remove()
    {

        if (isset($_COOKIE['cart']) && isset($this->data['maMonAn'])) {
            $cart = json_decode($_COOKIE['cart'], true);

            foreach ($cart as $key => $item) {
                if ($item['maMonAn'] == $this->data['maMonAn']) {
                    unset($cart[$key]);
                    break;
                }
            }
            setcookie('cart', json_encode(array_values($cart)), time() + 800000, "/"); // Lưu lại cookie với mảng đã thay đổi

            echo json_encode(['message' => "Sản phẩm đã được xóa khỏi giỏ hàng!"]);
        } else {
            echo json_encode(['error' => "Giỏ hàng không tồn tại hoặc trống."]);
        }
    }

    function insert_DH()
    {
        if (isset($this->data['TTDH'])) {
            $this->data['TTDH']['ngayTao'] = date("Y-m-d H:i:s");
            $diaChiGiaoHang = $this->data['HD']['diaChi'];
            $this->data['TTDH']['diaChiGiaoHang'] = $diaChiGiaoHang;
            $is_true = $this->gioHangModel->insertDonHang($this->data['TTDH']);
            $maDH = $this->donHangModel->lastInsertId($this->data['TTDH']['ngayTao']);
            if ($is_true) {
                foreach ($this->data['CTDH'] as $item) {
                    if (!empty($item['maMonAn'])) {
                        $thanhTien = (int)$item['soLuong'] * (float)$item['donGia'];
                        $CTDH = ['maMonAn' => $item['maMonAn'], 'soLuong' => $item['soLuong'], 'donGia' => $item['donGia'], 'maDonHang' => $maDH, 'thanhTien' => $thanhTien];
                        $this->chiTietDonHangModel->themChiTietDonHang($CTDH);
                    }
                }
                $this->data['HD']['maDonHang'] = $maDH;
                $this->data['HD']['trangThaiHoaDon'] = 'Chưa thanh toán';
                $this->data['HD']['trangThaiHoanTien'] = '0';
                $this->data['HD']['diaChiGiaoHang'] = $diaChiGiaoHang;
                $this->hoaDonModel->themHoaDon($this->data['HD']);
                echo true;
            }
        }
    }
    public function hienThiGioHang($pdo)
    {
        $db = $pdo;
        require_once('views/MuaHangUi/GioHangUi.php');
    }
    public function thayDoiDiaChi()
    {
        if ($this->data['diaChi']) {
            $diaChi = $this->data['diaChi'];
            $maKH = $this->data['maKH'];
            $tenKH = $this->data['tenKH'];
            $sdt = $this->data['sdt'];
            $is_thayDoiDiaChi = $this->gioHangModel->ThayDoiDiaChi($diaChi, $maKH, $tenKH, $sdt);
            echo $is_thayDoiDiaChi;
        }
    }
}
