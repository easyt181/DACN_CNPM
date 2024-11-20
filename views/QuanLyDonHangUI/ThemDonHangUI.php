<?php
// views/donhang/them.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm đơn hàng</title>
</head>
<body>
    <h1>Thêm đơn hàng mới</h1>
    <form method="post">
        <label for="maKH">Mã khách hàng:</label><br>
        <input type="text" name="maKH" required><br>
        <label for="maTaiKhoanNV">Mã nhân viên:</label><br>
        <input type="text" name="maTaiKhoanNV" required><br>
        <label for="maUuDaiDH">Mã ưu đãi:</label><br>
        <input type="text" name="maUuDaiDH"><br>
        <label for="ngayTao">Ngày tạo:</label><br>
        <input type="datetime-local" name="ngayTao" required><br>
        <label for="phuongThucThanhToan">Phương thức thanh toán:</label><br>
        <input type="text" name="phuongThucThanhToan" required><br>
        <label for="diaChiGiaoHang">Địa chỉ giao hàng:</label><br>
        <input type="text" name="diaChiGiaoHang" required><br>
        <label for="khoangCachGiaoHang">Khoảng cách giao hàng:</label><br>
        <input type="number" name="khoangCachGiaoHang"><br>
        <label for="phiShip">Phí ship:</label><br>
        <input type="number" name="phiShip"><br>
        <label for="tongTienCongTru">Tổng tiền (cộng/trừ):</label><br>
        <input type="number" name="tongTienCongTru"><br>
        <label for="tongTien">Tổng tiền:</label><br>
        <input type="number" name="tongTien" required><br>
        <label for="trangThaiThanhToan">Trạng thái thanh toán:</label><br>
        <input type="text" name="trangThaiThanhToan" required><br>
        <label for="trangThaiDonHang">Trạng thái đơn hàng:</label><br>
        <input type="text" name="trangThaiDonHang" required><br>
        <label for="ghiChu">Ghi chú:</label><br>
        <textarea name="ghiChu"></textarea><br>
        <button type="submit">Thêm đơn hàng</button>
    </form>
</body>
</html>
