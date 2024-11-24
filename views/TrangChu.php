<?php
    session_start();
    include './config/database.php';

    try {
        // Lấy dữ liệu từ bảng thucdon
        $sql = "SELECT * FROM thucdon";
        $result = $pdo->query($sql);

        // Lấy thông tin khách hàng với maKH = 'KH001'
        $query = "SELECT * FROM khachhang WHERE maKH = 'KH001'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $KH = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($KH) {
            // Lưu thông tin khách hàng vào session
            $_SESSION['KH'] = $KH;
        }

        // Quản lý giỏ hàng thông qua cookie
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
        setcookie('cart', json_encode($cart), time() + 800000, "/");

    } catch (PDOException $e) {
        // Xử lý lỗi truy vấn hoặc kết nối
        echo "Lỗi: " . $e->getMessage();
        exit;
    }
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách món ăn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Danh sách món ăn</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Hình Ảnh</th>
                    <th>Tên Món Ăn</th>
                    <th>Giá</th>
                    <th>Thêm</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->rowCount() > 0): ?>
                <?php foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row): ?>
                    <tr>
                        <td>
                            <img src="<?= htmlspecialchars($row['hinhAnhMonAn']) ?>" alt="Hình ảnh món ăn" style="width: 100px;">
                        </td>
                        <td><?= htmlspecialchars($row['tenMonAn']) ?></td>
                        <td><?= number_format($row['gia'], 0, ',', '.') ?> VNĐ</td>
                        <td>
                            <button class="btn btn-success" onclick="addToCart(<?= htmlspecialchars(json_encode($row)) ?>)">
                                +
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Không có món ăn nào trong thực đơn.</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
        <a href="index.php?controller=giohang&action=hienThiGioHang" class="btn btn-primary">Xem Giỏ Hàng</a>
    </div>

    <script>
        // Hàm lấy giỏ hàng từ cookie
        function getCookie(name) {
            let cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                let cookie = cookies[i].trim();
                if (cookie.startsWith(name + '=')) {
                    return decodeURIComponent(cookie.substring(name.length + 1));
                }
            }
            return '[]';
        }

        // Hàm đặt cookie
        function setCookie(name, value, days) {
            let date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            document.cookie = `${name}=${encodeURIComponent(value)};expires=${date.toUTCString()};path=/`;
        }

        // Lấy giỏ hàng từ cookie hoặc khởi tạo mới
        let cart = JSON.parse(getCookie('cart'));

        // Thêm sản phẩm vào giỏ
        function addToCart(product) {
            let found = false;
         
            for (let i = 0; i < cart.length; i++) {
                if (cart[i].maMonAn === product.maMonAn) {console.log(cart[i])
                    cart[i].soLuong += 1; // Tăng số lượng nếu trùng
                    found = true;
                    break;
                }
            }

            if (!found) {
                product.soLuong = 1; // Nếu chưa có, thêm mới với số lượng = 1
                cart.push(product);
            }

            setCookie('cart', JSON.stringify(cart), 1); // Lưu giỏ hàng vào cookie
            alert(`${product.tenMonAn} đã được thêm vào giỏ hàng!`);
        }
    </script>
</body>
</html>