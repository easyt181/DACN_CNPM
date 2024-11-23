<?php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/css/QuanLyDonHangUI.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row title mb-3" style="margin: 50px 0 0 0; width:100%;">
            <div class="col-4">
                <hr>
            </div>
            <div class="col-4">
                <h2 style="text-align: center;">QUẢN LÝ ĐƠN HÀNG</h2>
            </div>
            <div class="col-4">
                <hr>
            </div>
        </div>
        <div>
            <a href="index.php?controller=donhang&action=taoQRCode"><button type="button" class="btn btn-success" aria-expanded="false" data-bs-auto-close="outside">
                    Test Tạo mã QR
                </button></a>
        </div>
        <div>
            <a href="index.php?controller=donhang&action=hienThiTrangThemDonHang" target="_blank"><button type="button" class="btn btn-primary" aria-expanded="false" data-bs-auto-close="outside">
                    Thêm đơn hàng
                </button></a>
        </div>
        <!-- <div class="row mb-3">
        <div class="col-4">
            <form method="post">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" id="searchInput" placeholder="Nhập từ khóa">
                    <input type="submit" class="btn btn-primary" name="search" value="Tìm kiếm">
                </div>
            </form>
        </div>
    </div> -->
        <!-- Danh Sách Chờ Xác Nhận -->
        <!-- Modal Chi Tiết Đơn Hàng -->
        <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng:</h5>
                        <!-- Nút đóng modal -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Thông tin chi tiết đơn hàng -->
                        <div class="order-info">
                            <h6>Bảng chi tiết đơn hàng:</h6>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Món ăn</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody id="orderDetailsTableBody">
                                    <!-- Dữ liệu sẽ được thêm động -->
                                </tbody>
                            </table>
                        </div>
                        <div class="order-summary">
                            <p><strong>Phương thức thanh toán:</strong> <span id="phuongThucThanhToanSpan"></span></p>
                            <p><strong>Ghi chú:</strong> <span id="ghiChuSpan"></span></p>
                            <p><strong>Khoảng cách giao hàng:</strong> <span id="khoangCachGiaoHangSpan"></span> km</p>
                            <p><strong>Phí ship:</strong> <span id="phiShipSpan"></span> VNĐ</p>
                            <p><strong>Tổng tiền cộng trừ:</strong> <span id="tongTienCongTruSpan"></span> VNĐ</p>
                            <p><strong>Tổng tiền:</strong> <span id="tongTienSpan"></span> VNĐ</p>
                            <p><strong>Trạng thái thanh toán:</strong> <span id="trangThaiThanhToanSpan"></span></p>
                            <p><strong>Trạng thái đơn hàng:</strong> <span id="trangThaiDonHangSpan"></span></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Nút đóng modal -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <h5>ĐANG CHỜ XÁC NHẬN</h5>
            <table class="table table-bordered" style="border:2px outset black;">
                <thead>
                    <tr style="text-align:center;">
                        <th style="width:100px;">Mã đơn hàng</th>
                        <th>Tên khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ giao hàng</th>
                        <th style="width:100px;">Ngày tạo</th>
                        <th>Tổng tiền</th>
                        <th style="width:140px;">Trạng thái thanh toán</th>
                        <th style="width:140px;">Chi tiết đơn hàng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donHangsChoXacNhan as $donHang): ?>
                        <tr>
                            <td><?= $donHang['maDonHang'] ?></td>
                            <td><?= $donHang['tenKH'] ?></td>
                            <td><?= $donHang['sdt'] ?></td>
                            <td><?= $donHang['diaChiGiaoHang'] ?></td>
                            <td><?= $donHang['ngayTao'] ?></td>
                            <td><?= number_format($donHang['tongTien'], 0, ',', '.') ?> VNĐ</td>
                            <td><?= $donHang['trangThaiThanhToan'] ?></td>
                            <td>
                            <button
                                    type="button" 
                                    style="width:140px;" 
                                    class="btn btn-info action-button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#orderDetailsModal" 
                                    onclick="loadOrderDetails('<?= $donHang['maDonHang'] ?>')">
                                    Xem chi tiết
                                </button>
                            </td>
                            <td style="width:130px;">
                                <a><button type="button" class="btn btn-success action-button" aria-expanded="false" data-bs-auto-close="outside">
                                        Xác nhận
                                    </button></a>
                                <a><button type="button" class="btn btn-danger action-button" aria-expanded="false" data-bs-auto-close="outside">
                                        Từ chối
                                    </button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <hr>
        <!-- Danh Sách Đang Xử Lý -->
        <div class="mb-3">
            <h5>ĐANG XỬ LÝ</h5>
            <table class="table table-bordered" style="border:2px outset black;">
                <thead>
                    <tr style="text-align:center;">
                        <th style="width:100px;">Mã đơn hàng</th>
                        <th>Tên khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ giao hàng</th>
                        <th style="width:100px;">Ngày tạo</th>
                        <th>Tổng tiền</th>
                        <th style="width:140px;">Trạng thái đơn hàng</th>
                        <th style="width:140px;">Chi tiết đơn hàng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donHangsDangXuLy as $donHang): ?>
                        <tr>
                            <td><?= $donHang['maDonHang'] ?></td>
                            <td><?= $donHang['tenKH'] ?></td>
                            <td><?= $donHang['sdt'] ?></td>
                            <td><?= $donHang['diaChiGiaoHang'] ?></td>
                            <td><?= $donHang['ngayTao'] ?></td>
                            <td><?= number_format($donHang['tongTien'], 0, ',', '.') ?> VNĐ</td>
                            <td><?= $donHang['trangThaiDonHang'] ?></td>
                            <td>
                                <button
                                    type="button" 
                                    style="width:140px;" 
                                    class="btn btn-info action-button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#orderDetailsModal" 
                                    onclick="loadOrderDetails('<?= $donHang['maDonHang'] ?>')">
                                    Xem chi tiết
                                </button>
                            </td>

                            <td style="width:170px;">
                                <?php if ($donHang['trangThaiDonHang'] == 'Đang chuẩn bị'): ?>
                                    <a>
                                        <button type="button" style="width: 140px; margin: 5px 10px;" class="btn btn-success action-button" aria-expanded="false" data-bs-auto-close="outside">
                                            Giao hàng
                                        </button>
                                    </a>
                                    <a>
                                        <button type="button" style="width: 140px; margin: 5px 10px;" class="btn btn-warning action-button" aria-expanded="false" data-bs-auto-close="outside">
                                            Sửa đơn hàng
                                        </button>
                                    </a>
                                    <a>
                                        <button type="button" style="width: 140px; margin: 5px 10px;" class="btn btn-danger action-button" aria-expanded="false" data-bs-auto-close="outside">
                                            Hủy đơn hàng
                                        </button>
                                    </a>
                                <?php elseif (
                                    $donHang['trangThaiDonHang'] == 'Đang chờ hoàn tiền - Đã bị hủy bởi khách hàng' ||
                                    $donHang['trangThaiDonHang'] == 'Đang chờ hoàn tiền - Đã bị hủy bởi quản lý'
                                ): ?>
                                    <a>
                                        <button type="button" style="width: 140px; margin: 5px 10px;" class="btn btn-warning action-button" aria-expanded="false" data-bs-auto-close="outside">
                                            Hoàn tiền
                                        </button>
                                    </a>
                                <?php else: ?>
                                    <a>
                                        <button type="button" style="width: 140px; margin: 5px 10px;" class="btn btn-success action-button" aria-expanded="false" data-bs-auto-close="outside">
                                            Hoàn thành
                                        </button>
                                    </a>
                                    <a>
                                        <button type="button" style="width: 140px; margin: 5px 10px;" class="btn btn-danger action-button" aria-expanded="false" data-bs-auto-close="outside">
                                            Đơn rủi ro
                                        </button>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <hr>
        <!-- Danh Sách Đã Xử Lý Xong -->
        <div class="mb-3">
            <h5>ĐÃ XỬ LÝ XONG</h5>
            <table class="table table-bordered" style="border:2px outset black;">
                <tr>
                    <th style="width:100px;">Mã đơn hàng</th>
                    <th>Tên khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ giao hàng</th>
                    <th style="width:100px;">Ngày tạo</th>
                    <th>Tổng tiền</th>
                    <th style="width:140px;">Trạng thái thanh toán</th>
                    <th style="width:140px;">Chi tiết đơn hàng</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($donHangsDaXuLyXong as $donHang): ?>
                        <tr>
                            <td><?= $donHang['maDonHang'] ?></td>
                            <td><?= $donHang['tenKH'] ?></td>
                            <td><?= $donHang['sdt'] ?></td>
                            <td><?= $donHang['diaChiGiaoHang'] ?></td>
                            <td><?= $donHang['ngayTao'] ?></td>
                            <td><?= number_format($donHang['tongTien'], 0, ',', '.') ?> VNĐ</td>
                            <td><?= $donHang['trangThaiThanhToan'] ?></td>
                            <td>
                            <button
                                    type="button" 
                                    style="width:140px;" 
                                    class="btn btn-info action-button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#orderDetailsModal" 
                                    onclick="loadOrderDetails('<?= $donHang['maDonHang'] ?>')">
                                    Xem chi tiết
                                </button>
                            </td>
                            <td style="width:130px;">
                                <a><button type="button" class="btn btn-success action-button" aria-expanded="false" data-bs-auto-close="outside">
                                        Xác nhận
                                    </button></a>
                                <a><button type="button" class="btn btn-danger action-button" aria-expanded="false" data-bs-auto-close="outside">
                                        Từ chối
                                    </button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


    </div>
    <script src="public/js/DanhSachDonHang.js"></script>

</body>

</html>