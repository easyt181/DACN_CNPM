<?php
require_once 'C:\xampp\htdocs\DACN_CNPM\views\header.php';
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
$gioHangController = new GioHangController($pdo);
$arrMonAn = $gioHangController->thongTinGioHang();
$index = 0;
$cart = $arrMonAn['cart'];
$CTDH = $arrMonAn['CTDH'];
$KH = $_SESSION['KH'];
$lastElement = end($CTDH);
$tongTien = !empty($CTDH) ? $lastElement['tongTien'] : 0;
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


    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="TTKH">
            <div class="row title">
                <h2>Giỏ hàng</h2>
                <a class="title__prev" href="index.php?controller=thucDon&action=hienThiHome" class="btn ">
                    <!-- <i class="bi bi-arrow-left-short"></i> -->
                    <p>Quay lại</p>
                </a>
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
                        <div class="sdtKh"><?php echo $KH['sdt']; ?></div>
                    </div>
                    <div class="diaChi"><span>Địa chỉ:</span> <span id="address-span" class="diaChi-span"><?php echo $KH['diaChi'] ?></span></div>

                </div>
                <div class="col-2 btn_TTKH"><a href="#" type="button" class="title__thayDoi" data-bs-toggle="modal" data-bs-target="#thayDoiThongTinKH" onclick="thayDoiTT()">Thay đổi</a></div>
            </div>
        </div>
        <div class="row container_TTmonAn">
            <div class="col ">
                <?php
                if (!empty($cart)) {
                    $count_item = 0;
                    foreach ($cart as $item) {
                        echo ' <div class="monAn"> 
                    <div class="row">
                        <div class="col TTMon TTMon_' . $count_item . '">
                            <img class="img_monAn" src="' . htmlentities($item['hinhAnhMonAn']) . '" class="rounded float-start" alt="' . htmlentities($item['tenMonAn']) . '">
                            <div class="TTmonAn">
                                <div class="tenMon">' . htmlentities($item['tenMonAn']) . '</div>
                                <div class="gia">' . htmlentities(number_format((int)$item['gia'])) . ' VND</div>
                                <div class="soLuong">
                                <div class="soLuong_btn soLuong_btn-tru" data-item="' . $count_item . '"><i class="bi bi-dash-circle-fill"></i></div>
                                <div class="soLuong_text">x<span class="soLuong_text-span">' . htmlentities($CTDH[$index]['soLuong']) . '</span></div>
                                <div class="soLuong_btn soLuong_btn-cong" data-item="' . $count_item . '"><i class="bi bi-plus-circle-fill"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col huy">
                            <button 
                                type="button" 
                                class="btn btn-danger btn_huy" 
                                onclick="removeItem(\'' . htmlspecialchars($item['maMonAn'], ENT_QUOTES, 'UTF-8') . '\')">
                                Hủy
                            </button>
                        </div>
                    </div>
                </div>';
                        $index += 1;
                        $count_item += 1;
                    }
                } else {
                    echo '<div class="gioTrong">Giỏ hàng trống</div>';
                }

                ?>
            </div>
            <div class="col-4 thanhToan">
                <div class="soLuongMon">
                    <span>Tổng cộng(món):</span>
                    <span><?php echo count($cart); ?></span>
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
                    <span id="tongTientxt"><?php echo $tongTien ?></span>
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
                            Thanh toán qua QRCode
                        </label>
                    </div>
                </div>
                <div class="btn_thanhToan">
                    <button type="button" class="btn btn-danger" onclick="showConfirmation()">
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
    <!-- Thay doi thong tin kh -->
    <div class="modal fade" id="thayDoiThongTinKH" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Thông tin khách hàng</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal__thongTinKH">
                    <div class="input-group mb-3">
                        <div>
                            <p>Tên khách hàng</p>
                            <input type="text" class="form-control modal__thongTinKH__tenKH-input" placeholder="Tên khách hàng" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div>
                            <p>Số điện thoại</p>
                            <input type="text" class="form-control modal__thongTinKH__sdtKH-input" placeholder="Số điện thoại" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div type="text" class="input-group mb-3 modal__thongTinKH__diaChi">
                        <div>
                            <p>Địa chỉ</p>
                            <input type="text" class="form-control modal__thongTinKH__diaChi-input" placeholder="Địa chỉ" aria-label="Username" aria-describedby="basic-addon1" oninput="searchLocation()">
                        </div>
                        <div id="suggestions">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="xacNhanThayDoiDC()">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    <?php $data = ['CTDH' => $CTDH, 'KH' => $KH]; ?>
    var data = <?php echo json_encode($data); ?>;
</script>
<script src="./public/js/GioHang.js"></script>

</html>

<!-- '. htmlspecialchars($item['hinhAnhMonAn'], ENT_QUOTES, 'UTF-8').' -->