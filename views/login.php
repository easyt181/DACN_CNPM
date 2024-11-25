<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
     <!-- font awesome icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css stylesheet -->
    <link rel="stylesheet" href="./public/css/logincss.css">
    <script>
function openErrorModal() {
  document.getElementById("errorModal").style.display = "block";
}

// Đóng modal
function closeErrorModal() {
  document.getElementById("errorModal").style.display = "none";
}

// Đóng modal khi người dùng nhấn ra ngoài modal
window.onclick = function(event) {
  var modal = document.getElementById("errorModal");
  if (event.target === modal) {
    closeErrorModal();
  }
}

// Nếu có lỗi, mở modal khi trang tải xong
<?php if (isset($error)): ?>
  openErrorModal();
<?php endif; ?>
</script>
</head>
<body>
<!-- Modal -->
<div id="errorModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeErrorModal()">&times;</span>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
  </div>
</div>


    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="#">
                <h1>CREATE ACCOUNT</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    
                </div>
                <span>OR</span>
                <div class="infield">
                    <input type="text" placeholder="Name" />
                    <label></label>
                </div>
                <div class="infield">
                    <input type="email" placeholder="Email" name="email"/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" />
                    <label></label>
                </div>
                <button>Đăng ký</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
        <form method="post" action="index.php?controller=login&action=login" autocomplete="off">
                <h1>LOG IN</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    
                </div>
                <span>OR</span>
                <div class="infield">
                    <input type="text" placeholder="Ten tai khoan" name="tenDangNhap" id="tenDangNhap" autocomplete="off" required>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="matKhau" id="matKhau" autocomplete="off" required>
                    <label></label>
                </div>
                <a href="#" class="forgot">Bạn quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>Đăng nhập ngay để không bỏ lỡ những món ngon dành riêng cho bạn!</p>
                    <button>Sign in</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Đăng ký ngay - Những món ăn ngon chỉ cách bạn một cú click!</p>
                    <button>Sign up</button>
                </div>
            </div>
            <button id="overlayBtn"></button>
        </div>
    </div>
    
    <!-- js code -->
    <script>
        const container = document.getElementById('container');
        const overlayCon = document.getElementById('overlayCon');
        const overlayBtn = document.getElementById('overlayBtn');

        overlayBtn.addEventListener('click', ()=>{
            container.classList.toggle('right-panel-active');

            overlayBtn.classList.remove('btnScaled');
            window.requestAnimationFrame(()=> {
                overlayBtn.classList.add('btnScaled');
            })
        })
    </script>

</body>
</html>
