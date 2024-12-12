<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta id="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa đơn hàng - <?= $donHang['maDonHang'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/css/ThemDonHangUI.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <!-- Modal hóa đơn -->
<div class="modal fade" id="hoaDonModal" tabindex="-1" aria-labelledby="hoaDonModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="hoaDonModalLabel">Thông Tin Hóa Đơn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Mã Hóa Đơn:</strong> <span id="maHoaDon"></span></p>
        <p><strong>Số Điện Thoại:</strong> <span id="sdt"></span></p>
        <p><strong>Tên Khách Hàng:</strong> <span id="tenKH"></span></p>
        <p><strong>Địa Chỉ Giao Hàng:</strong> <span id="diaChiGiaoHang"></span></p>    
        <p><strong>Ngày Tạo:</strong> <span id="ngayTao"></span></p>
        <p><strong>Tổng Tiền:</strong> <span id="tongTien"></span></p>
        <p><strong>Phương Thức Thanh Toán:</strong> <span id="phuongThucThanhToan"></span></p>
        <p><strong>Trạng Thái Hóa Đơn:</strong> <span id="trangThaiHoaDon"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

    <input type="hidden" id="maDonHang" value="<?= $donHang['maDonHang'] ?>">  
    <div class="container mt-5">
        <h2 class="text-center mb-4">SỬA ĐƠN HÀNG: <?= $donHang['maDonHang'] ?></h2>
        <form id="themDonHang" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="customer-info">
                        <label for="tenKH" class="form-label">Tên khách hàng:</label>
                        <input type="text" class="form-control" id="tenKH" value="<?= $donHang['tenKH'] ?>" required>
                    </div>
                    <div class="customer-info">
                        <label for="sdt" class="form-label">Số điện thoại:</label>
                        <input type="text" class="form-control" id="sdt" value="<?= $donHang['sdt'] ?>" required>
                    </div>
                    <div class="customer-info">
                        <label for="diaChiGiaoHang" class="form-label">Địa chỉ giao hàng:</label>
                        <input type="text" class="form-control" id="diaChiGiaoHang" value="<?= $donHang['diaChiGiaoHang'] ?>"  required readonly>
                    </div>
                    <div id="suggestions">
                    </div>
                    <div class="customer-info">
                        <label for="khoangCachGiaoHang" class="form-label">Khoảng cách giao hàng (km):</label>
                        <input type="number" step="0.1" class="form-control" id="khoangCachGiaoHang" value="<?= $donHang['khoangCachGiaoHang'] ?>" readonly>
                    </div>
                    <div class="customer-info">
                        <label for="phiShip" class="form-label">
                            Phí ship: <span id="phiShipSpan">0</span> VNĐ
                        </label>
                        <input type="number" class="form-control" id="phiShip" value="<?= $donHang['phiShip'] ?>" readonly>
                    </div>
                </div>
                <!-- Thông tin ngày tạo, phương thức thanh toán, khoảng cách giao hàng, phí ship (bên phải) -->
                <div class="col-md-6">
                    <div class="payment-info">
                        <label for="ngayTao" class="form-label">Ngày tạo:</label>
                        <input type="datetime-local" class="form-control" id="ngayTao" value="<?= $donHang['ngayTao'] ?>" required readonly>
                    </div>
                    <div class="payment-info">
                        <label for="phuongThucThanhToan" class="form-label">Phương thức thanh toán:</label>
                        <select class="form-select" id="phuongThucThanhToan">
                            <option selected><?= $donHang['phuongThucThanhToan'] ?></option>
                            <option>...</option>
                            <option>Thanh toán khi nhận hàng</option>
                            <option>Thanh toán qua QRCode</option>
                            <option>Chuyển khoản trực tiếp</option>
                        </select>
                    </div>
                    <div class="payment-info" id="transactionProofForm" style="display: none; margin-top: 15px;">
                        <label for="transactionImage">Tải ảnh giao dịch:</label>
                        <input type="file" id="transactionImage" accept="image/*">
                    </div>
                    <div class="payment-info">
                        <label for="trangThaiThanhToan" class="form-label">Trạng thái thanh toán:</label>
                        <input type="text" class="form-control" id="trangThaiThanhToan" value="<?= $donHang['trangThaiThanhToan'] ?>" required readonly>
                    </div>
                    <?php if($donHang['trangThaiThanhToan'] == "Đã thanh toán"){?>
                    <div class="payment-info">
                        <button type="button" style="width: 140px; float:right;" class="btn btn-warning action-button" aria-expanded="false" data-bs-auto-close="outside">Hoàn tiền</button>
                        <button type="button" onclick="hienThiHoaDon('<?= $donHang['maDonHang'] ?>')" style="width: 140px; float:left;" class="btn btn-info action-button" aria-expanded="false" data-bs-auto-close="outside">Xem hóa đơn</button>
                    </div>
                    <?php }else{ ?>
                    <div class="payment-info">
                        <button type="button" onclick="hienThiHoaDon('<?= $donHang['maDonHang'] ?>')" style="width: 140px;" class="btn btn-info action-button" aria-expanded="false" data-bs-auto-close="outside">Xem hóa đơn</button>
                    </div>
                     <?php } ?>
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
                    <input type="hidden" id="initialCartData" value='<?= json_encode($chiTietDonHang) ?>'>
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
                    <input type="number" class="form-control" id="tongTienCongTru" value="<?= $donHang['tongTienCongTru'] ?>">
                </div>
                <div class="col-md-4">
                    <label for="maUuDaiDH" class="form-label">Mã ưu đãi:</label>
                    <input type="text" class="form-control" id="maUuDaiDH" value="<?= $donHang['maUuDaiDH'] ?>">
                </div>
                <div class="col-md-4">
                    <label for="tongTien" class="form-label">
                        Tổng tiền: <span id="totalCostSpan">0</span> VNĐ
                    </label>
                    <input type="number" class="form-control" id="tongTien" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="ghiChu" class="form-label">Ghi chú cho đơn hàng:</label>
                <textarea class="form-control" id="ghiChu" rows="4" value="<?= $donHang['ghiChu'] ?>"></textarea>
            </div>
            <button type="button" id="updateOrder">Sửa đơn hàng</button>
        </form>
    </div>
    <script src="public/js/SuaDonHang.js"></script>
</body>

</html>