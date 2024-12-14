<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSTFOOD</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="./public/css/header.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.js"></script>
    <script>
        function toggleDropdown() {
            const menu = document.getElementById('user-menu');
            menu.style.display = menu.style.display === 'none' || menu.style.display === '' ? 'block' : 'none';
        }
    </script>
</head>

<body>

    <!-- header section starts  -->

    <header class="header">

        <section class="flex">

            <a href="index.php?controller=thucdon&action=hienThiHome" class="logo"> <i class="fas fa-utensils"></i> DSTFOOD </a>

            <nav class="navbar">
                <a href="index.php?controller=thucdon&action=hienThiHome">trang chủ</a>
                <a href="#about">thông tin</a>
                <a href="#popular">Bán chạy </a>
                <a href="#menu">menu</a>
                <a href="#order">đơn hàng</a>

            </nav>

            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <a href="index.php?controller=giohang&action=hienThiGioHang" style="display: inline-block;">
                    <div id="cart-btn" class="fas fa-shopping-cart">
                    </div>
                </a>

                <?php if (isset($_SESSION['tenDangNhap'])): ?>

                    <?php if ($_SESSION['maQuyen'] === 'admin'): ?>
                        <a href="admin.php">Trang quản trị</a>
                    <?php endif; ?>
                    <a href="index.php?controller=login&action=logout" >Log out</a>
                <?php else: ?>
                    <a href="index.php?controller=login&action=login">Login</a>
                <?php endif; ?>
            </div>




        </section>

    </header>
    
</body>
</html>