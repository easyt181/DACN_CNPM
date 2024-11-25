<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta id="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/css/ThemDonHangUI.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="container mt-5">
        <!-- Tiêu đề -->
        <h2 class="text-center mb-4">THÊM ĐƠN HÀNG MỚI</h2>
        <form method="POST">
            <!-- Thông tin khách hàng (bên trái) -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="customer-info">
                        <label for="tenKH" class="form-label">Tên khách hàng:</label>
                        <input type="text" class="form-control" id="tenKH" required>
                    </div>
                    <div class="customer-info">
                        <label for="sdt" class="form-label">Số điện thoại:</label>
                        <input type="text" class="form-control" id="sdt" required>
                    </div>
                    <div class="customer-info">
                        <label for="diaChiGiaoHang" class="form-label">Địa chỉ giao hàng:</label>
                        <input type="text" class="form-control" id="diaChiGiaoHang" oninput="searchLocation()" required>
                        
                    </div>
                    <div id="suggestions">
                    </div>
                    <div class="customer-info">
                        <label for="khoangCachGiaoHang" class="form-label">Khoảng cách giao hàng (km):</label>
                        <input type="number" step="0.1" class="form-control" id="khoangCachGiaoHang" oninput="updateShippingFee()">
                    </div>
                    <div class="customer-info">
                        <label for="phiShip" class="form-label">
                            Phí ship: <span id="phiShipSpan">0</span> VNĐ
                        </label>
                        <input type="number" class="form-control" id="phiShip" readonly>
                    </div>
                </div>

                <!-- Thông tin ngày tạo, phương thức thanh toán, khoảng cách giao hàng, phí ship (bên phải) -->
                <div class="col-md-6">
                    <div class="payment-info">
                        <label for="ngayTao" class="form-label">Ngày tạo:</label>
                        <input type="datetime-local" class="form-control" id="ngayTao" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                    <div class="payment-info">
                        <label for="phuongThucThanhToan" class="form-label">Phương thức thanh toán:</label>
                        <select class="form-select" id="phuongThucThanhToan">
                            <option selected>Thanh toán khi nhận hàng</option>
                            <option>Thanh toán qua QRCode</option>
                            <option>Chuyển khoản trực tiếp</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Tìm kiếm món ăn -->
            <div class="mb-3">
                <label for="searchFood" class="form-label">Tìm kiếm món ăn:</label>
                <input type="text" class="form-control" id="searchFood" placeholder="Nhập tên món ăn...">
                <div id="timKiemMonAn"> <button type="button" class="btn btn-secondary mt-2" id="btnSearch">Tìm kiếm</button></div>

            </div>

            <div id="searchResult" class="mb-3">
                <!-- Kết quả tìm kiếm món ăn hiển thị ở đây -->
            </div>

            <!-- Giỏ hàng -->
            <div class="mb-3">
                <h5>Giỏ hàng:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên món</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="cartItems">
                        <!-- Các món trong giỏ hàng sẽ được thêm vào đây -->
                    </tbody>
                </table>
                <p><strong>Tổng tiền món ăn:</strong> <span id="totalFoodCostSpan">0</span> VNĐ</p>
            </div>

            <!-- Các thông tin khác -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="tongTienCongTru" class="form-label">
                        Tổng tiền phát sinh: <span id="tongTienCongTruSpan">0</span> VNĐ
                    </label>
                    <input type="number" class="form-control" id="tongTienCongTru">
                </div>
                <div class="col-md-4">
                    <label for="maUuDaiDH" class="form-label">Mã ưu đãi:</label>
                    <input type="text" class="form-control" id="maUuDaiDH">
                </div>
                <div class="col-md-4">
                    <label for="tongTien" class="form-label">
                        Tổng tiền: <span id="totalCostSpan">0</span> VNĐ
                    </label>
                    <input type="number" class="form-control" id="tongTien" required>
                </div>
            </div>

            <!-- Ghi chú cho đơn hàng -->
            <div class="mb-3">
                <label for="ghiChu" class="form-label">Ghi chú cho đơn hàng:</label>
                <textarea class="form-control" id="ghiChu" rows="4"></textarea>
            </div>
            <!-- Nút thêm đơn hàng -->
            <button type="button" id="submitOrder">Thêm đơn hàng</button>
        </form>
    </div>
    <script src="public/js/ThemDonHang.js"></script>
</body>

</html>