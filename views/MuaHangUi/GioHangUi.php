<?php
session_start(); 
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
// $gioHangController = new GioHangController();

if (isset($_SESSION['KH'])) {
    $KH = $_SESSION['KH'];
} 
$soLuongMon = count($cart);
$maKh = $KH['maKH'];
$ngayTao = date('Y-m-d H:i:s');
$diaChi = $KH['diaChi'];
$TTDH = [
    'maKH' => $maKh,
    'diaChi' => $diaChi,
    'ngayTao' => $ngayTao,
];
$tongTien = 0;
foreach ($cart as $item){
    $tongTien += (int)$item['gia'] * (int)$item['soLuong'];
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./public/css/GioHang.css">
</head>
<body>
    <div class="container">
        <div class="TTKH">
            <div class="row title">
                <h2>Đơn hàng</h2>
                <div class="title__prev">
                    <i class="bi bi-arrow-left-short"></i>
                    <p><a href="../Test/index.php" class="btn ">Quay lại</a></p>
                </div>
            </div>
            <div class="row container_TTKH">
            <div class="col">
                <div class="location">
                    <i class="bi bi-geo-alt-fill icon_location"></i>
                     <p>Địa chỉ giao hàng</p>
                </div>
                <div class=" info_TTKH">
                    <div class="tenKh"><?php echo $KH['tenKH']; ?></div>
                    <div class="line"></div>
                    <div class="sdtKh"><?php echo $KH['sdt'];?></div>
                </div>
                <div class="diaChi"><span>Địa chỉ:</span> <span id="address-span"><?php echo $KH['diaChi']?></span></div>

            </div>
            <div class="col btn_TTKH"><a href="#">Thay đổi</a></div>
        </div>
        </div>
        <div class="row container_TTmonAn">
            <div class="col ">
               <?php
               if(!empty($cart)) {
                foreach ($cart as $item) {
                    echo ' <div class="monAn"> 
                    <div class="row">
                        <div class="col TTMon">
                            <img class="img_monAn" src="https://static.kinhtedothi.vn/w960/images/upload/2022/09/16/phobohanoi.jpg" class="rounded float-start" alt="'.htmlentities($item['tenMonAn']).'">
                            <div class="TTmonAn">
                                <div class="tenMon">'.htmlentities($item['tenMonAn']).'</div>
                                <div class="gia">'.htmlentities(number_format((int)$item['gia'])).' VND</div>
                                <div class="soLuong">x'.htmlentities($item['soLuong']).'</div>
                            </div>
                        </div>
                        <div class="col huy">
                            <button 
                                type="button" 
                                class="btn btn-danger btn_huy" 
                                onclick="removeItem(\''.htmlspecialchars($item['maMonAn'], ENT_QUOTES, 'UTF-8').'\')">
                                Hủy
                            </button>
                        </div>
                    </div>
                </div>';
            
                }
               }
               else{
                    echo '<div class="">Giỏ hàng trống</div>';
               }
                
               ?>
            </div>
            <div class="col-4 thanhToan">
                <div class="soLuongMon">
                    <span>Tổng cộng(món):</span> 
                    <span><?php echo $soLuongMon;?></span>
                </div>
                <div class="phiGiaoHang">
                    <span>Phí ship:</span> 
                    <span id="distance">1</span>
                </div>
                <div class="input-group uuDai">
                    <input type="text" class="form-control" placeholder="Ưu đãi" aria-label="Ưu đãi" aria-describedby="basic-addon2">
                    <span class="input-group-text btn_uuDai" id="basic-addon2 " data-bs-toggle="modal" data-bs-target="#exampleModal">Thay đổi</span>
                </div>
                <div class="tongTien">
                    <span>Tổng tiền:</span> 
                    <span id="tongTientxt"><?php echo $tongTien?></span>
                </div>
                <div class="ghiChu">
                    <span>Ghi chú</span>
                    <textarea name="" id="textarea" rows="3"></textarea>
                </div>
                <div class="phuongThuc">
                    <span>Phương thức thanh toán</span>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                           Thanh toán khi nhận hàng
                        </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                        <label class="form-check-label" for="flexRadioDefault2">
                            Thanh toán trực tiếp
                        </label>
                    </div>
                </div>
                <div class="btn_thanhToan">
                <button type="button" class="btn btn-danger" 
                    onclick="showConfirmation(<?php echo htmlspecialchars(json_encode($TTDH), ENT_QUOTES, 'UTF-8'); ?>)">
                    Thanh toán
                </button>

                </div>
            </div>
        </div>
       
    </div>

    <!-- Modal -->
        <div class="modal fade " id="exampleModal" tabindex="21" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ưu đãi đơn hàng</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
            
            </div>
        </div>
        </div>
    <!-- confirm -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Xác nhận</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn xác nhận thanh toán đơn hàng?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelAction">Hủy</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="./public/js/GioHang.js"></script>
</html> 

<!-- '. htmlspecialchars($item['hinhAnhMonAn'], ENT_QUOTES, 'UTF-8').' -->