<?php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<div class="container" style="margin-top:150px;">
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
    <div class="row mb-3">
        <div class="col-4">
            <form method="post">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" id="searchInput" placeholder="Nhập từ khóa">
                    <input type="submit" class="btn btn-primary" name="search" value="Tìm kiếm">
                </div>
            </form>
        </div>
    </div>
    <div class="mb-3">
        <h5>DANH SÁCH CHỜ XÁC NHẬN</h5>
        <table class="table table-bordered" style="border:2px outset black;">
            <thead>
                <tr style="text-align:center;">
                    <th>Mã đơn hàng</th>
                    <th>Tên khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Ngày tạo</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái thanh toán</th>
                    <th>Chi tiết đơn hàng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donHangs as $donHang): ?>
                    <tr>
                        <td><?= $donHang['maDonHang'] ?></td>
                        <td><?= $donHang['tenKH'] ?></td>
                        <td><?= $donHang['sdt'] ?></td>
                        <td><?= $donHang['diaChiGiaoHang'] ?></td>
                        <td><?= $donHang['ngayTao'] ?></td>
                        <td><?= number_format($donHang['tongTien'], 0, ',', '.') ?> VNĐ</td>
                        <td><?= $donHang['trangThaiThanhToan'] ?></td>
                        <td>
                            <a><button type="button" class="btn btn-info" aria-expanded="false" data-bs-auto-close="outside">
                            Xem chi tiết
                                </button></a>
                        </td>
                        <td style="width:130px;">
                            <a><button type="button" class="btn btn-success" aria-expanded="false" data-bs-auto-close="outside">
                                    Xác nhận
                                </button></a>
                            <a><button type="button" class="btn btn-danger" aria-expanded="false" data-bs-auto-close="outside">
                                Từ chối
                            </button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
