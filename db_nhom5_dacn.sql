-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 01:13 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_nhom5_dacn`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `maChiTiet` varchar(50) NOT NULL,
  `maDonHang` varchar(50) NOT NULL,
  `maMonAn` varchar(50) NOT NULL,
  `soLuong` int(11) NOT NULL,
  `donGia` decimal(19,2) NOT NULL,
  `thanhTien` decimal(19,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`maChiTiet`, `maDonHang`, `maMonAn`, `soLuong`, `donGia`, `thanhTien`) VALUES
('CT1DH615551', 'DH615551', 'MA002', 1, 50000.00, 50000.00),
('CT1DH918194', 'DH918194', 'MA003', 3, 40000.00, 120000.00),
('CT2DH615551', 'DH615551', 'MA003', 1, 40000.00, 40000.00),
('CT3DH615551', 'DH615551', 'MA004', 2, 25000.00, 50000.00);

--
-- Triggers `chitietdonhang`
--
DELIMITER $$
CREATE TRIGGER `before_insert_ChiTietDonHang` BEFORE INSERT ON `chitietdonhang` FOR EACH ROW BEGIN
    DECLARE max_maChiTiet INT;
    
    -- Lấy mã ChiTiet lớn nhất hiện tại theo maDonHang
    SELECT MAX(CAST(SUBSTRING(maChiTiet, 3) AS UNSIGNED)) INTO max_maChiTiet FROM ChiTietDonHang WHERE maDonHang = NEW.maDonHang;
    
    -- Gán mã mới cho maChiTiet (CT1DH001, CT2DH001,...)
    IF max_maChiTiet IS NULL THEN
        SET NEW.maChiTiet = CONCAT('CT1', NEW.maDonHang);
    ELSE
        SET NEW.maChiTiet = CONCAT('CT', max_maChiTiet + 1, NEW.maDonHang);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `chungminhgiaodich`
--

CREATE TABLE `chungminhgiaodich` (
  `maChungMinh` varchar(50) NOT NULL,
  `maHoaDon` varchar(50) NOT NULL,
  `soTienNhanDuoc` decimal(19,2) NOT NULL,
  `duongDanAnh` varchar(100) NOT NULL,
  `thoiGianTaiLen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chungminhgiaodich`
--

INSERT INTO `chungminhgiaodich` (`maChungMinh`, `maHoaDon`, `soTienNhanDuoc`, `duongDanAnh`, `thoiGianTaiLen`) VALUES
('CMGD67588b6779d00ofDH918194', 'HD1DH918194', 1345000.00, '/public/image/cmgd/67588b6776b89_DSC09365.JPG', '2024-12-11 01:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `danhgiadonhang`
--

CREATE TABLE `danhgiadonhang` (
  `maDanhGia` varchar(50) NOT NULL,
  `maDonHang` varchar(50) NOT NULL,
  `maKH` varchar(50) NOT NULL,
  `noiDungDanhGia` text DEFAULT NULL,
  `soSao` int(11) NOT NULL,
  `ngayDanhGia` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `maDonHang` varchar(50) NOT NULL,
  `maKH` varchar(50) NOT NULL,
  `maTaiKhoanNV` varchar(50) DEFAULT NULL,
  `maUuDaiDH` varchar(50) DEFAULT NULL,
  `ngayTao` datetime NOT NULL,
  `phuongThucThanhToan` varchar(100) NOT NULL,
  `diaChiGiaoHang` varchar(100) NOT NULL,
  `khoangCachGiaoHang` float NOT NULL,
  `phiShip` decimal(19,2) NOT NULL,
  `tongTienCongTru` decimal(19,2) DEFAULT NULL,
  `tongTien` decimal(19,2) NOT NULL,
  `trangThaiThanhToan` varchar(100) NOT NULL,
  `trangThaiDonHang` varchar(100) NOT NULL,
  `ghiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`maDonHang`, `maKH`, `maTaiKhoanNV`, `maUuDaiDH`, `ngayTao`, `phuongThucThanhToan`, `diaChiGiaoHang`, `khoangCachGiaoHang`, `phiShip`, `tongTienCongTru`, `tongTien`, `trangThaiThanhToan`, `trangThaiDonHang`, `ghiChu`) VALUES
('DH615551', 'KH012', 'TKadmin', '', '2024-12-11 01:40:00', 'Thanh toán khi nhận hàng', 'Giang Nam, Thị trấn Kiến Xương, Huyện Kiến Xương, Tỉnh Thái Bình', 103.1, 515000.00, 0.00, 655000.00, 'Chưa thanh toán', 'Đang chuẩn bị', ''),
('DH918194', 'KH013', 'TKadmin', '', '2024-12-11 01:41:00', 'Chuyển khoản trực tiếp', 'Bờ Sông, Xín Cái, Mèo Vạc, Hà Giang', 245.4, 1225000.00, 0.00, 1345000.00, 'Đã thanh toán', 'Đang chuẩn bị', '');

-- --------------------------------------------------------

--
-- Table structure for table `giaodich`
--

CREATE TABLE `giaodich` (
  `maGiaoDich` varchar(50) NOT NULL,
  `maDonHang` varchar(50) NOT NULL,
  `ngayGiaoDich` datetime NOT NULL,
  `loaiGiaoDich` varchar(100) NOT NULL,
  `soTaiKhoan` varchar(100) NOT NULL,
  `soTienVao` decimal(19,2) DEFAULT NULL,
  `soTienRa` decimal(19,2) DEFAULT NULL,
  `noiDungChuyenKhoan` varchar(100) NOT NULL,
  `ngayTaoGiaoDich` datetime NOT NULL,
  `trangThaiGiaoDich` varchar(100) NOT NULL,
  `gateWay` varchar(100) NOT NULL,
  `maThamChieuGiaoDich` varchar(255) NOT NULL,
  `noiDungGiaoDich` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `giaodich`
--
DELIMITER $$
CREATE TRIGGER `before_insert_GiaoDich` BEFORE INSERT ON `giaodich` FOR EACH ROW BEGIN
    DECLARE max_maGiaoDich INT;
    
    -- Lấy mã GiaoDich lớn nhất hiện tại theo maDonHang
    SELECT MAX(CAST(SUBSTRING(maGiaoDich, 3) AS UNSIGNED)) INTO max_maGiaoDich FROM GiaoDich WHERE maDonHang = NEW.maDonHang;
    
    -- Gán mã mới cho maGiaoDich (GD1DH001,...)
    IF max_maGiaoDich IS NULL THEN
        SET NEW.maGiaoDich = CONCAT('GD1', NEW.maDonHang);
    ELSE
        SET NEW.maGiaoDich = CONCAT('GD', max_maGiaoDich + 1, NEW.maDonHang);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `maHoaDon` varchar(50) NOT NULL,
  `maDonHang` varchar(50) NOT NULL,
  `sdt` varchar(100) NOT NULL,
  `tenKH` varchar(100) NOT NULL,
  `diaChiGiaoHang` varchar(100) NOT NULL,
  `ngayTao` datetime NOT NULL,
  `tongTien` decimal(19,2) NOT NULL,
  `phuongThucThanhToan` varchar(100) NOT NULL,
  `trangThaiHoaDon` varchar(100) NOT NULL,
  `trangThaiHoanTien` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoadon`
--

INSERT INTO `hoadon` (`maHoaDon`, `maDonHang`, `sdt`, `tenKH`, `diaChiGiaoHang`, `ngayTao`, `tongTien`, `phuongThucThanhToan`, `trangThaiHoaDon`, `trangThaiHoanTien`) VALUES
('HD1DH615551', 'DH615551', '0987231231', 'Lỗ Tử Kính', 'Giang Nam, Thị trấn Kiến Xương, Huyện Kiến Xương, Tỉnh Thái Bình', '2024-12-11 01:40:00', 655000.00, 'Thanh toán khi nhận hàng', 'Chưa thanh toán', 0),
('HD1DH918194', 'DH918194', '0987231123', 'Chu Công Cẩn', 'Bờ Sông, Xín Cái, Mèo Vạc, Hà Giang', '2024-12-11 01:41:00', 1345000.00, 'Chuyển khoản trực tiếp', 'Đã thanh toán', 0);

--
-- Triggers `hoadon`
--
DELIMITER $$
CREATE TRIGGER `before_insert_HoaDon` BEFORE INSERT ON `hoadon` FOR EACH ROW BEGIN
    DECLARE max_maHoaDon INT;
    
    -- Lấy mã HoaDon lớn nhất hiện tại theo maDonHang
    SELECT MAX(CAST(SUBSTRING(maHoaDon, 3, 4) AS UNSIGNED)) INTO max_maHoaDon FROM HoaDon WHERE maDonHang = NEW.maDonHang;
    
    -- Gán mã mới cho maHoaDon (HD1DH001,...)
    IF max_maHoaDon IS NULL THEN
        SET NEW.maHoaDon = CONCAT('HD1', NEW.maDonHang);
    ELSE
        SET NEW.maHoaDon = CONCAT('HD', max_maHoaDon + 1, NEW.maDonHang);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `maKH` varchar(50) NOT NULL,
  `maTaiKhoan` varchar(50) DEFAULT NULL,
  `tenKH` varchar(100) NOT NULL,
  `sdt` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `diaChi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`maKH`, `maTaiKhoan`, `tenKH`, `sdt`, `email`, `diaChi`) VALUES
('KH001', 'TKKH001', 'Chu Công Cẩn', '0999999999', '1234@gmail.com', 'Trường đại học Công nghệ Đông Á, Trịnh Văn Bô, Phương Canh, Nam Từ Liêm, Hà Nội'),
('KH002', NULL, 'Lỗ Tử Kính', '333444555666', NULL, 'Kinh Châu, Xã Hương Nộn, Huyện Tam Nông, Tỉnh Phú Thọ'),
('KH003', NULL, 'Hoắc Tuấn', '033444555888', NULL, 'Hải Dương'),
('KH004', NULL, 'Trương Dực Đức', '0912341234', NULL, 'Tương Dương, Thị trấn Di Lăng, Huyện Sơn Hà, Tỉnh Quảng Ngãi'),
('KH005', NULL, 'a', 'b', NULL, 'Cà Mau'),
('KH006', NULL, 'a', 'd', NULL, 'Ư Ma, Xã Pa ủ, Huyện Mường Tè, Tỉnh Lai Châu'),
('KH007', NULL, 'a', 'ư', NULL, 'Huyện Ea H\'leo, Đắk Lắk'),
('KH008', NULL, 'Chu Công Cẩn', '0978321313', NULL, 'Giang Đông, Thị trấn Sịa, Huyện Quảng Điền, Tỉnh Thừa Thiên Huế'),
('KH009', NULL, 'a', 'bc', NULL, 'Quận Cầu Giấy, Thành phố Hà Nội'),
('KH010', NULL, 'Lỗ Tử Kính', '0312123123', NULL, 'Khẩu trang Bảo Tiến, Trương Văn Lực, Cam Lộ, Hùng Vương, Hồng Bàng, Hải Phòng'),
('KH011', NULL, 'anh Tuyến', '12344441223123', NULL, 'Mạc Xá, Xã Tân Hồng, Huyện Bình Giang, Tỉnh Hải Dương'),
('KH012', NULL, 'Lỗ Tử Kính', '0987231231', NULL, 'Giang Nam, Thị trấn Kiến Xương, Huyện Kiến Xương, Tỉnh Thái Bình'),
('KH013', NULL, 'Chu Công Cẩn', '0987231123', NULL, 'Bờ Sông, Xín Cái, Mèo Vạc, Hà Giang');

--
-- Triggers `khachhang`
--
DELIMITER $$
CREATE TRIGGER `before_insert_KhachHang` BEFORE INSERT ON `khachhang` FOR EACH ROW BEGIN
    DECLARE max_maKH INT;
    
    -- Lấy mã KhachHang lớn nhất hiện tại
    SELECT MAX(CAST(SUBSTRING(maKH, 3) AS UNSIGNED)) INTO max_maKH FROM KhachHang;
    
    -- Gán mã mới cho maKH (KH001, KH002,...)
    IF max_maKH IS NULL THEN
        SET NEW.maKH = CONCAT('KH', LPAD(1, 3, '0'));
    ELSE
        SET NEW.maKH = CONCAT('KH', LPAD(max_maKH + 1, 3, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `maNhanVien` varchar(50) NOT NULL,
  `maTaiKhoan` varchar(50) DEFAULT NULL,
  `tenNhanVien` varchar(100) NOT NULL,
  `soDienThoai` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `chucVu` varchar(100) NOT NULL,
  `luong` decimal(19,2) NOT NULL,
  `trangThai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`maNhanVien`, `maTaiKhoan`, `tenNhanVien`, `soDienThoai`, `email`, `chucVu`, `luong`, `trangThai`) VALUES
('NV001', 'TKNV001', 'Bùi Công Đạt', '01234112312', 'datcongh43@gmail.com', 'Quản lý', 100000000.00, 'Đang hoạt động');

--
-- Triggers `nhanvien`
--
DELIMITER $$
CREATE TRIGGER `before_insert_NhanVien` BEFORE INSERT ON `nhanvien` FOR EACH ROW BEGIN
    DECLARE max_maNV INT;
    
    -- Lấy mã NhanVien lớn nhất hiện tại
    SELECT MAX(CAST(SUBSTRING(maNhanVien, 3) AS UNSIGNED)) INTO max_maNV FROM NhanVien;
    
    -- Gán mã mới cho maNhanVien (NV001, NV002,...)
    IF max_maNV IS NULL THEN
        SET NEW.maNhanVien = CONCAT('NV', LPAD(1, 3, '0'));
    ELSE
        SET NEW.maNhanVien = CONCAT('NV', LPAD(max_maNV + 1, 3, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `maTaiKhoan` varchar(50) NOT NULL,
  `maQuyen` varchar(100) NOT NULL,
  `tenDangNhap` varchar(100) NOT NULL,
  `matKhau` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sdt` varchar(100) NOT NULL,
  `trangThai` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `hetHanToken` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`maTaiKhoan`, `maQuyen`, `tenDangNhap`, `matKhau`, `email`, `sdt`, `trangThai`, `token`, `hetHanToken`) VALUES
('TKadmin', 'admin', 'admin', '1234', 'trandinhtuyen18@gmail.com', '0963421148', 'Kích hoạt', '', '0000-00-00 00:00:00'),
('TKKH001', 'khachhang', 'khachhang1', '1234', '1234@gmail.com', '0123456789', 'Kích hoạt', '', '0000-00-00 00:00:00'),
('TKNV001', 'nhanvien', 'nhanvien1', '1234', 'datcongh43@gmail.com', '0999123123', 'Kích hoạt', '', '0000-00-00 00:00:00');

--
-- Triggers `taikhoan`
--
DELIMITER $$
CREATE TRIGGER `trg_generate_maTaiKhoan` BEFORE INSERT ON `taikhoan` FOR EACH ROW BEGIN
    DECLARE prefix VARCHAR(5);
    DECLARE next_id INT;
    DECLARE padded_id VARCHAR(3);

    -- Xác định tiền tố mã tài khoản theo maQuyen
    IF NEW.maQuyen = 'nhanvien' THEN
        SET prefix = 'TKNV';
    ELSEIF NEW.maQuyen = 'khachhang' THEN
        SET prefix = 'TKKH';
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid maQuyen value!';
    END IF;

    -- Tìm giá trị lớn nhất hiện tại và tăng dần
    SELECT COALESCE(MAX(CAST(SUBSTRING(maTaiKhoan, 5) AS UNSIGNED)), 0) + 1
    INTO next_id
    FROM TaiKhoan
    WHERE maTaiKhoan LIKE CONCAT(prefix, '%');

    -- Định dạng số thứ tự thành 3 chữ số
    SET padded_id = LPAD(next_id, 3, '0');

    -- Gán giá trị cho maTaiKhoan
    SET NEW.maTaiKhoan = CONCAT(prefix, padded_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `thongbao`
--

CREATE TABLE `thongbao` (
  `maThongBao` varchar(50) NOT NULL,
  `maKH` varchar(50) NOT NULL,
  `noiDung` text NOT NULL,
  `NgayGui` datetime NOT NULL,
  `trangThai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `thongbao`
--
DELIMITER $$
CREATE TRIGGER `before_insert_ThongBao` BEFORE INSERT ON `thongbao` FOR EACH ROW BEGIN
    DECLARE max_maThongBao INT;
    
    -- Lấy mã ThongBao lớn nhất hiện tại theo maKH
    SELECT MAX(CAST(SUBSTRING(maThongBao, 3) AS UNSIGNED)) INTO max_maThongBao FROM ThongBao WHERE maKH = NEW.maKH;
    
    -- Gán mã mới cho maThongBao (TB1KH001,...)
    IF max_maThongBao IS NULL THEN
        SET NEW.maThongBao = CONCAT('TB1', NEW.maKH);
    ELSE
        SET NEW.maThongBao = CONCAT('TB', max_maThongBao + 1, NEW.maKH);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `thucdon`
--

CREATE TABLE `thucdon` (
  `maMonAn` varchar(50) NOT NULL,
  `tenMonAn` varchar(100) NOT NULL,
  `hinhAnhMonAn` varchar(100) NOT NULL,
  `loaiMonAn` varchar(100) NOT NULL,
  `buaSangTruaToi` varchar(100) NOT NULL,
  `moTa` text NOT NULL,
  `gia` decimal(19,2) NOT NULL,
  `tinhTrang` varchar(100) NOT NULL,
  `deCuMonAn` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thucdon`
--

INSERT INTO `thucdon` (`maMonAn`, `tenMonAn`, `hinhAnhMonAn`, `loaiMonAn`, `buaSangTruaToi`, `moTa`, `gia`, `tinhTrang`, `deCuMonAn`) VALUES
('MA001', 'Phở Bò', 'public/image/dish/pho_bo.jpg', 'Món nước', 'Sáng', 'Phở bò truyền thống Việt Nam', 45000.00, 'Đang còn món', 1),
('MA002', 'Bún Chả', 'public/image/dish/bun_cha.jpg', 'Món nước', 'Trưa', 'Bún chả Hà Nội, đậm đà vị truyền thống', 50000.00, 'Đang còn món', 0),
('MA003', 'Cơm Tấm', 'public/image/dish/com_tam.jpg', 'Món cơm', 'Trưa', 'Cơm tấm sườn bì chả, ăn kèm nước mắm chua ngọt', 40000.00, 'Đang còn món', 1),
('MA004', 'Bánh Mì Thịt', 'public/image/dish/banh_mi_thit.jpg', 'Món ăn nhanh', 'Sáng', 'Bánh mì thịt với các loại rau củ và nước sốt đặc biệt', 25000.00, 'Đang còn món', 0),
('MA005', 'Gỏi Cuốn', 'public/image/dish/goi_cuon.jpg', 'Món khai vị', 'Tối', 'Gỏi cuốn tôm thịt, ăn kèm nước chấm chua ngọt', 30000.00, 'Đang còn món', 1),
('MA006', 'Mì Quảng', 'public/image/dish/mi_quang.jpg', 'Món nước', 'Trưa', 'Mì Quảng tôm thịt, đặc sản miền Trung', 55000.00, 'Đang còn món', 0),
('MA007', 'Cháo Lòng', 'public/image/dish/chao_long.jpg', 'Món nước', 'Sáng', 'Cháo lòng, ăn kèm dồi và nước mắm gừng', 35000.00, 'Đang còn món', 0),
('MA008', 'Lẩu Thái', 'public/image/dish/lau_thai.jpg', 'Món chính', 'Tối', 'Lẩu Thái cay nồng, thích hợp cho bữa tối gia đình', 250000.00, 'Đang còn món', 1),
('MA009', 'Gà Nướng', 'public/image/dish/ga_nuong.jpg', 'Món chính', 'Tối', 'Gà nướng mật ong, thịt mềm thơm', 200000.00, 'Đang còn món', 0),
('MA010', 'Bánh Xèo', 'public/image/dish/banh_xeo.jpg', 'Món ăn vặt', 'Tối', 'Bánh xèo miền Tây, giòn rụm, ăn kèm rau sống', 45000.00, 'Đang còn món', 1);

--
-- Triggers `thucdon`
--
DELIMITER $$
CREATE TRIGGER `before_insert_ThucDon` BEFORE INSERT ON `thucdon` FOR EACH ROW BEGIN
    DECLARE max_maMonAn INT;
    
    -- Lấy mã MonAn lớn nhất hiện tại
    SELECT MAX(CAST(SUBSTRING(maMonAn, 3) AS UNSIGNED)) INTO max_maMonAn FROM ThucDon;
    
    -- Gán mã mới cho maMonAn (MA001, MA002,...)
    IF max_maMonAn IS NULL THEN
        SET NEW.maMonAn = CONCAT('MA', LPAD(1, 3, '0'));
    ELSE
        SET NEW.maMonAn = CONCAT('MA', LPAD(max_maMonAn + 1, 3, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `uudaitheodon`
--

CREATE TABLE `uudaitheodon` (
  `maUuDaiDH` varchar(50) NOT NULL,
  `tenUuDaiDH` varchar(100) NOT NULL,
  `loaiUuDaiDH` varchar(100) NOT NULL,
  `nguongGiaTriApDung` decimal(19,2) NOT NULL,
  `soTienGiamDH` decimal(19,2) DEFAULT NULL,
  `chietKhauDH` float DEFAULT NULL,
  `freeShipDH` tinyint(1) DEFAULT NULL,
  `ngayBatDauDH` datetime NOT NULL,
  `ngayKetThucDH` datetime NOT NULL,
  `trangThaiUuDaiDH` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uudaitheomon`
--

CREATE TABLE `uudaitheomon` (
  `maUuDaiMA` varchar(50) NOT NULL,
  `maMonAnApDung` varchar(50) NOT NULL,
  `loaiUuDaiMA` tinyint(1) NOT NULL,
  `tenUuDaiMA` varchar(100) NOT NULL,
  `chietKhauGiamGia` float DEFAULT NULL,
  `maMonAnQuaTang` varchar(50) DEFAULT NULL,
  `soLuongQuaTang` int(11) DEFAULT NULL,
  `ngayBatDauMA` datetime NOT NULL,
  `ngayKetThucMA` datetime NOT NULL,
  `trangThaiUuDaiMA` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`maChiTiet`),
  ADD KEY `fk_chitietdonhang_donhang` (`maDonHang`),
  ADD KEY `fk_chitietdonhang_thucdon` (`maMonAn`);

--
-- Indexes for table `chungminhgiaodich`
--
ALTER TABLE `chungminhgiaodich`
  ADD PRIMARY KEY (`maChungMinh`),
  ADD UNIQUE KEY `duongDanAnh` (`duongDanAnh`),
  ADD KEY `fk_chungminhgiaodich_hoadon` (`maHoaDon`);

--
-- Indexes for table `danhgiadonhang`
--
ALTER TABLE `danhgiadonhang`
  ADD PRIMARY KEY (`maDanhGia`),
  ADD KEY `fk_danhgiadonhang_khachhang` (`maKH`),
  ADD KEY `fk_danhgiadonhang_donhang` (`maDonHang`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`maDonHang`),
  ADD KEY `fk_donhang_khachhang` (`maKH`);

--
-- Indexes for table `giaodich`
--
ALTER TABLE `giaodich`
  ADD PRIMARY KEY (`maGiaoDich`),
  ADD KEY `fk_giaodich_donhang` (`maDonHang`);

--
-- Indexes for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`maHoaDon`),
  ADD KEY `fk_hoadon_donhang` (`maDonHang`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`maKH`),
  ADD UNIQUE KEY `sdt` (`sdt`,`email`),
  ADD KEY `fk_khachhang_taikhoan` (`maTaiKhoan`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`maNhanVien`),
  ADD UNIQUE KEY `soDienThoai` (`soDienThoai`,`email`),
  ADD KEY `fk_nhanvien_taikhoan` (`maTaiKhoan`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`maTaiKhoan`),
  ADD UNIQUE KEY `tenDangNhap` (`tenDangNhap`,`email`,`sdt`);

--
-- Indexes for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`maThongBao`),
  ADD KEY `fk_thongbao_khachhang` (`maKH`);

--
-- Indexes for table `thucdon`
--
ALTER TABLE `thucdon`
  ADD PRIMARY KEY (`maMonAn`);

--
-- Indexes for table `uudaitheodon`
--
ALTER TABLE `uudaitheodon`
  ADD PRIMARY KEY (`maUuDaiDH`);

--
-- Indexes for table `uudaitheomon`
--
ALTER TABLE `uudaitheomon`
  ADD PRIMARY KEY (`maUuDaiMA`),
  ADD KEY `fk_uudaitheomon_thucdon` (`maMonAnApDung`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `fk_chitietdonhang_donhang` FOREIGN KEY (`maDonHang`) REFERENCES `donhang` (`maDonHang`),
  ADD CONSTRAINT `fk_chitietdonhang_thucdon` FOREIGN KEY (`maMonAn`) REFERENCES `thucdon` (`maMonAn`);

--
-- Constraints for table `chungminhgiaodich`
--
ALTER TABLE `chungminhgiaodich`
  ADD CONSTRAINT `fk_chungminhgiaodich_hoadon` FOREIGN KEY (`maHoaDon`) REFERENCES `hoadon` (`maHoaDon`);

--
-- Constraints for table `danhgiadonhang`
--
ALTER TABLE `danhgiadonhang`
  ADD CONSTRAINT `fk_danhgiadonhang_donhang` FOREIGN KEY (`maDonHang`) REFERENCES `donhang` (`maDonHang`),
  ADD CONSTRAINT `fk_danhgiadonhang_khachhang` FOREIGN KEY (`maKH`) REFERENCES `khachhang` (`maKH`);

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `fk_donhang_khachhang` FOREIGN KEY (`maKH`) REFERENCES `khachhang` (`maKH`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `giaodich`
--
ALTER TABLE `giaodich`
  ADD CONSTRAINT `fk_giaodich_donhang` FOREIGN KEY (`maDonHang`) REFERENCES `donhang` (`maDonHang`);

--
-- Constraints for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `fk_hoadon_donhang` FOREIGN KEY (`maDonHang`) REFERENCES `donhang` (`maDonHang`);

--
-- Constraints for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD CONSTRAINT `fk_khachhang_taikhoan` FOREIGN KEY (`maTaiKhoan`) REFERENCES `taikhoan` (`maTaiKhoan`);

--
-- Constraints for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `fk_nhanvien_taikhoan` FOREIGN KEY (`maTaiKhoan`) REFERENCES `taikhoan` (`maTaiKhoan`);

--
-- Constraints for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD CONSTRAINT `fk_thongbao_khachhang` FOREIGN KEY (`maKH`) REFERENCES `khachhang` (`maKH`);

--
-- Constraints for table `uudaitheomon`
--
ALTER TABLE `uudaitheomon`
  ADD CONSTRAINT `fk_uudaitheomon_thucdon` FOREIGN KEY (`maMonAnApDung`) REFERENCES `thucdon` (`maMonAn`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
