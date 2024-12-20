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
    <link rel="stylesheet" href="./public/css/ThayDoiMK.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.js"></script>
</head>
<body>
    <div class="container">
        <form action="index.php?controller=login&action=thayDoiMK" method="POST">
            
        <h3>Đặt lại mật khẩu</h3>
            <input type="hidden" name="token" value="<?php echo $token?>">
            <input type="hidden" name="thoiHan" value="<?php echo $thoiHan?>">
            <div class="input_password">
                <label for="password">Mật khẩu mới:</label>
                <input type="password" name="matKhau" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
            </div>
            <button class="button_submit btn btn-primary mb-3" type="submit">Đặt lại mật khẩu</button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="./public/js/ThayDoiMK.js"></script>
</html>