
<?php
$cart = [
    [
        'maMonAn' => 'MA001',
        'tenMonAn' => 'Phở Bò',
        'hinhAnhMonAn' => 'public/image/pho_bo.jpg',
        'loaiMonAn' => 'Món nước',
        'buaSangTruaToi' => 'Sáng',
        'moTa' => 'Phở bò truyền thống Việt Nam',
        'gia' => '45000.00',
        'tinhTrang' => 'Đang còn món',
        'deCuMonAn' => '1',
        'soLuong' => '2'
    ],
    [
        'maMonAn' => 'MA002',
        'tenMonAn' => 'Bún Chả',
        'hinhAnhMonAn' => 'images/bun_cha.jpg',
        'loaiMonAn' => 'Món nước',
        'buaSangTruaToi' => 'Trưa',
        'moTa' => 'Bún chả Hà Nội, đậm đà vị truyền thống',
        'gia' => '50000.00',
        'tinhTrang' => 'Đang còn món',
        'deCuMonAn' => '1',
        'soLuong' => '3'
    ]
];

// Chuyển mảng thành JSON và lưu vào cookie
setcookie('cart', json_encode($cart), time() + 3600, "/"); // 1 giờ


?>
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
    <link rel="stylesheet" href="./public/css/style.css">
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

            <a href="#" class="logo"> <i class="fas fa-utensils"></i> DSTFOOD </a>

            <nav class="navbar">
                <a href="#home">trang chủ</a>
                <a href="#about">thông tin</a>
                <a href="#popular">Bán chạy </a>
                <a href="#menu">menu</a>
                <a href="#order">đơn hàng</a>

            </nav>

            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="search-btn" class="fas fa-search"></div>
                <a href="index.php?controller=giohang&action=hienThiGioHang" style="display: inline-block;"><div id="cart-btn" class="fas fa-shopping-cart" >
                </div></a>

                <?php if (isset($_SESSION['tenDangNhap'])): ?>

                <?php if ($_SESSION['maQuyen'] === 'admin'): ?>
                <a href="admin.php">Trang quản trị</a>
                <?php endif; ?>
                <a href="index.php?controller=login&action=logout">Log out</a>
                <?php else: ?>
                <a href="index.php?controller=login&action=login">Login</a>
                <?php endif; ?>
            </div>




        </section>

    </header>

    <!-- header section ends  -->

    <!-- search-form  -->

    <div class="search-form-container">
        <section>
            <form action="index.php?controller=thucdon&action=hienThiHome" method="get">
                <!-- Giữ lại giá trị tìm kiếm khi trang được tải lại -->
                <input type="search" name="search" placeholder="Tìm kiếm món ăn..." id="search-box"
                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <label for="search-box" class="fas fa-search"></label>
            </form>
        </section>
    </div>



    <!-- shopping-cart section  -->

    <div class="shopping-cart-container">

        <section class="products-container">

            <h3 class="title">your products</h3>

            <div class="box-container">

                <div class="box">
                    <i class="fas fa-times"></i>
                    <img src="image/menu-1.png" alt="">
                    <div class="content">
                        <h3>delicious food</h3>
                        <span> quantity : </span>
                        <input type="number" name="" value="1" id="">
                        <br>
                        <span> price : </span>
                        <span class="price"> $40.00 </span>
                    </div>
                </div>

                <div class="box">
                    <i class="fas fa-times"></i>
                    <img src="image/menu-2.png" alt="">
                    <div class="content">
                        <h3>delicious food</h3>
                        <span> quantity : </span>
                        <input type="number" name="" value="1" id="">
                        <br>
                        <span> price : </span>
                        <span class="price"> $40.00 </span>
                    </div>
                </div>

                <div class="box">
                    <i class="fas fa-times"></i>
                    <img src="image/menu-3.png" alt="">
                    <div class="content">
                        <h3>delicious food</h3>
                        <span> quantity : </span>
                        <input type="number" name="" value="1" id="">
                        <br>
                        <span> price : </span>
                        <span class="price"> $40.00 </span>
                    </div>
                </div>

                <div class="box">
                    <i class="fas fa-times"></i>
                    <img src="image/menu-4.png" alt="">
                    <div class="content">
                        <h3>delicious food</h3>
                        <span> quantity : </span>
                        <input type="number" name="" value="1" id="">
                        <br>
                        <span> price : </span>
                        <span class="price"> $40.00 </span>
                    </div>
                </div>

                <div class="box">
                    <i class="fas fa-times"></i>
                    <img src="image/menu-5.png" alt="">
                    <div class="content">
                        <h3>delicious food</h3>
                        <span> quantity : </span>
                        <input type="number" name="" value="1" id="">
                        <br>
                        <span> price : </span>
                        <span class="price"> $40.00 </span>
                    </div>
                </div>

            </div>

        </section>

        <section class="cart-total">

            <h3 class="title"> cart total </h3>

            <div class="box">

                <h3 class="subtotal"> subtotal : <span>$200</span> </h3>
                <h3 class="total"> total : <span>$200</span> </h3>

                <a href="#" class="btn">proceed to checkout</a>

            </div>

        </section>

    </div>

    <!-- login-form  -->



    <!-- home section starts  -->

    <section class="home" id="home">

        <div class="content">
            <span>welcome DSTFood</span>
            <h3>Nâng tầm trải nghiệm ẩm thực😋</h3>
            <p>DSTFOOD tự tin đem đến cho bạn những trải nghiệm đặc biệt và tuyệt vời nhất của chúng tôi</p>
            <a href="#" class="btn">đặt hàng ngay</a>
        </div>

        <div class="image">
            <img src="./public/image/home-img.png" alt="" class="home-img">
            <img src="./public/image/home-parallax-img.png" alt="" class="home-parallax-img">
        </div>

    </section>

    <!-- home section ends  -->

    <!-- category section starts  -->

    <section class="category">

        <a href="#" class="box">
            <img src="./public/image/cat-1.png" alt="">
            <h3>combo</h3>
        </a>

        <a href="#" class="box">
            <img src="./public/image/cat-2.png" alt="">
            <h3>pizza</h3>
        </a>

        <a href="#" class="box">
            <img src="./public/image/cat-3.png" alt="">
            <h3>burger</h3>
        </a>

        <a href="#" class="box">
            <img src="./public/image/cat-4.png" alt="">
            <h3>chicken</h3>
        </a>

        <a href="#" class="box">
            <img src="./public/image/cat-5.png" alt="">
            <h3>dinner</h3>
        </a>

        <a href="#" class="box">
            <img src="./public/image/cat-6.png" alt="">
            <h3>Drinks</h3>
        </a>

    </section>

    <!-- category section ends -->


    <!-- about section starts  -->

    <div class="about" id="about">

        <section class="flex">

            <div class="image">
                <img src="./public/image/about-img.png" alt="">
            </div>

            <div class="content">
                <span>Who are we?</span>
                <h3 class="title">Tại sao nên chọn chúng tôi!</h3>
                <p>Chúng tôi là DST Food, chuyên cung cấp những món ăn tươi ngon, được chế biến từ nguyên liệu chất
                    lượng. Với đội ngũ đầu bếp tài năng, dịch vụ tận tình chúng tôi cam kết mang đến cho bạn những trải
                    nghiệm ẩm thực tuyệt vời !!!</p>

                <div class="icons-container">
                    <div class="icons">
                        <img src="./public/image/serv-1.png" alt="">
                        <h3>Giao hàng nhanh chóng</h3>
                    </div>
                    <div class="icons">
                        <img src="./public/image/serv-2.png" alt="">
                        <h3>Thực phẩm tươi mới</h3>
                    </div>
                    <div class="icons">
                        <img src="./public/image/serv-3.png" alt="">
                        <h3>Chất lượng hàng đầu</h3>
                    </div>
                    <div class="icons">
                        <img src="./public/image/serv-4.png" alt="">
                        <h3>Hỗ trợ 24/7</h3>
                    </div>
                </div>
            </div>

        </section>

    </div>

    <!-- about section ends -->

    <!-- popular section starts  -->

    <section class="popular" id="popular">

        <div class="heading">
            <span>Best seller</span>

        </div>
        <?php if (!empty($danhSachMonAn)): ?>
        <script>
        window.onload = function() {
            if (window.location.search.includes('search')) {
                const popularSection = document.querySelector('#popular');
                window.scrollTo(0, popularSection.offsetTop);
            }
        };
        </script>
        <div class="box-container">


            <?php foreach ($danhSachMonAn as $monAn): ?>
            <div class="box">
                <a href="#" class="fas fa-heart"></a>
                <div class="image">
                    <img src="<?php echo $monAn['hinhAnhMonAn']; ?>" alt="<?php echo $monAn['tenMonAn']; ?>">
                </div>
                <div class="content">
                    <h3><?php echo $monAn['tenMonAn']; ?></h3>
                    <div class="price"><?php echo number_format($monAn['gia'], 0, ',', '.'); ?> VNĐ</div>
                    <a href="#" class="btn">đặt hàng</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>Không có món ăn nào trong thực đơn.</p>
            <?php endif; ?>



            <!-- <div class="box">
            <a href="#" class="fas fa-heart"></a>
            <div class="image">
                <img src="../public/image/food-2.png" alt="">
            </div>
            <div class="content">
                <h3>delicious food</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    
                </div>
                <div class="price">$40.00 <span>$50.00</span></div>
                <a href="#" class="btn">đặt hàng</a>
            </div>
        </div>

        <div class="box">
            <a href="#" class="fas fa-heart"></a>
            <div class="image">
                <img src="image/food-3.png" alt="">
            </div>
            <div class="content">
                <h3>delicious food</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    
                </div>
                <div class="price">$40.00 <span>$50.00</span></div>
                <a href="#" class="btn">đặt hàng</a>
            </div>
        </div>

        <div class="box">
            <a href="#" class="fas fa-heart"></a>
            <div class="image">
                <img src="image/food-4.png" alt="">
            </div>
            <div class="content">
                <h3>delicious food</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    
                </div>
                <div class="price">$40.00 </div>
                <a href="#" class="btn">đặt hàng</a>
            </div>
        </div>

        <div class="box">
            <a href="#" class="fas fa-heart"></a>
            <div class="image">
                <img src="image/food-5.png" alt="">
            </div>
            <div class="content">
                <h3>delicious food</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    
                </div>
                <div class="price">$40.00</div>
                <a href="#" class="btn">đặt hàng</a>
            </div>
        </div>

        <div class="box">
            <a href="#" class="fas fa-heart"></a>
            <div class="image">
                <img src="image/food-6.png" alt="">
            </div>
            <div class="content">
                <h3>delicious food</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    
                </div>
                <div class="price">$40.00 </div>
                <a href="#" class="btn">đặt hàng</a>
            </div>
        </div>

        <div class="box">
            <a href="#" class="fas fa-heart"></a>
            <div class="image">
                <img src="image/food-7.png" alt="">
            </div>
            <div class="content">
                <h3>delicious food</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    
                </div>
                <div class="price">$40.00 </div>
                <a href="#" class="btn">đặt hàng</a>
            </div>
        </div>

        <div class="box">
            <a href="#" class="fas fa-heart"></a>
            <div class="image">
                <img src="image/food-8.png" alt="">
            </div>
            <div class="content">
                <h3>delicious food</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    
                </div>
                <div class="price">$40.00 </div>
                <a href="#" class="btn">đặt hàng</a>
            </div>
        </div> -->

        </div>

    </section>

    <!-- popular section ends -->

    <!-- banner section starts  -->

    <!-- <section class="banner">

    <div class="row-banner">
        <div class="content">
            <span>double cheese</span>
            <h3>burger</h3>
            <p>with cococola and fries</p>
            <a href="#" class="btn">order now</a>
        </div>
    </div>

    <div class="grid-banner">
        <div class="grid">
            <img src="image/banner-1.png" alt="">
            <div class="content">
                <span>special offer</span>
                <h3>upto 50% off</h3>
                <a href="#" class="btn">order now</a>
            </div>
        </div>
        <div class="grid">
            <img src="image/banner-2.png" alt="">
            <div class="content center">
                <span>special offer</span>
                <h3>upto 25% extra</h3>
                <a href="#" class="btn">order now</a>
            </div>
        </div>
        <div class="grid">
            <img src="image/banner-3.png" alt="">
            <div class="content">
                <span>limited offer</span>
                <h3>100% cashback</h3>
                <a href="#" class="btn">order now</a>
            </div>
        </div>
    </div>

</section> -->

    <!-- banner section ends -->

    <!-- menu section starts  -->



    <!-- menu section ends -->

    <!-- order section starts  -->

    <section class="order" id="order">

        <div class="heading">
            <span>order now</span>
            <h3>giao hàng tận nơi!</h3>
        </div>

        <div class="icons-container">

            <div class="icons">
                <img src="./public/image/icon-1.png" alt="">
                <h3>7:00am to 11:00pm</h3>
            </div>

            <div class="icons">
                <img src="./public/image/icon-2.png" alt="">
                <h3>0989988999</h3>
            </div>

            <div class="icons">
                <img src="./public/image/icon-3.png" alt="">
                <h3>Đại học công nghệ đông á</h3>
            </div>

        </div>



    </section>

    <!-- order section ends -->

    <!-- blogs section starts  -->



    <!-- blogs section ends -->

    <!-- footer section starts  -->

    <footer class="footer">

        <section class="newsletter">
            <h3>Đăng ký nhận tin mới</h3>
            <form action="">
                <input type="email" name="" placeholder="nhập email ..." id="">
                <input type="submit" value="subscribe">
            </form>
        </section>

        <section class="box-container">

            <div class="box">
                <h3><i class="fas fa-utensils"></i>DSTFOOD</h3>
                <p><b>Đặt hàng đơn giản, ship hàng nhanh chóng!!!</b></p>
                <p><b>Thời gian mở cửa:</b> 7:00am đến 11:00pm</p>
                <p><b>Địa chỉ:</b> Đại học công nghệ Đông Á</p>
                <p><b>Số điện thoại liên hệ:</b> 0989998999</p>
                <p><b>Email:</b>@gmail.com</p>

            </div>

            <div class="box">
                <h3>our menu</h3>
                <a href="#"><i class="fas fa-arrow-right"></i> pizza</a>
                <a href="#"><i class="fas fa-arrow-right"></i> burger</a>
                <a href="#"><i class="fas fa-arrow-right"></i> chicken</a>
                <a href="#"><i class="fas fa-arrow-right"></i> pasta</a>
                <a href="#"><i class="fas fa-arrow-right"></i> and more...</a>
            </div>

            <div class="box">
                <h3>quick links</h3>
                <a href="#home"> <i class="fas fa-arrow-right"></i> home</a>
                <a href="#about"> <i class="fas fa-arrow-right"></i> about</a>
                <a href="#popular"> <i class="fas fa-arrow-right"></i> popular</a>
                <a href="#menu"> <i class="fas fa-arrow-right"></i> menu</a>
                <a href="#order"> <i class="fas fa-arrow-right"></i> order</a>

            </div>

            <div class="box">
                <h3>Chính sách</h3>
                <a href="#"> <i class="fas fa-arrow-right"></i> terms of use</a>
                <a href="#"> <i class="fas fa-arrow-right"></i> privary policy</a>
            </div>



        </section>

        <section class="bottom">

            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>

            </div>

            <div class="credit"> created <span>DST Group</span> | all rights reserved! </div>

        </section>

    </footer>

    <!-- footer section ends -->

















    <!-- custom js file link  -->
    <script src="./public/js/script.js"></script>
    <script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdown-menu');
        menu.classList.toggle('show'); // Hiện/Ẩn menu
    }

    // Đóng menu khi nhấn ngoài
    window.onclick = function(event) {
        if (!event.target.matches('.fas')) {
            const dropdown = document.getElementById('dropdown-menu');
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    }
    </script>

</body>

</html>