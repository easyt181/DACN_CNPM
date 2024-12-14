<?php
require_once 'C:\xampp\htdocs\DACN_CNPM\views\header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./public/css/LayLaiMK.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row box">
            <div class="col-2 imt_logo"><img src="https://imaginethatcreative.net/blog/wp-content/uploads/2023/06/2250206.png" alt=""></div>
            <div class="col-8">
                <div class="box_text">
                <h2>Quên mật khẩu</h2>
                <p>Vui lòng nhập tên đăng nhập hoặc email đã đăng ký của bạn. Chúng tôi sẽ gửi cho bạn liên kết để đặt lại mật khẩu.</p>
                <form method="POST" action="index.php?controller=login&action=guiEmail">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn_submit">Đặt lại mật khẩu</button>
                </form>
            </div>
            </div>
        </div>
        
    </div>
</body>
</html>