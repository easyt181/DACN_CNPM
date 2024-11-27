<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$port = 3306;
$database = "db_nhom5_dacn";

$conn = new mysqli($servername, $username, $password, $database, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy mã khách hàng từ query string hoặc mặc định
$maKH = isset($_GET['maKH']) ? $_GET['maKH'] : 'KH001';

// Truy vấn danh sách đơn hàng của khách hàng, bao gồm danh sách các món ăn
$sql = "
    SELECT 
        dh.maDonHang, 
        dh.ngayTao, 
        dh.trangThaiDonHang, 
        dh.tongTien, 
        dh.diaChiGiaoHang,
        GROUP_CONCAT(td.tenMonAn ORDER BY ctdh.maChiTiet) AS danhSachMonAn,
        GROUP_CONCAT(td.hinhAnhMonAn ORDER BY ctdh.maChiTiet) AS danhSachHinhAnh
    FROM donhang dh
    JOIN chitietdonhang ctdh ON dh.maDonHang = ctdh.maDonHang
    JOIN thucdon td ON ctdh.maMonAn = td.maMonAn
    WHERE dh.maKH = ?
    GROUP BY dh.maDonHang, dh.ngayTao, dh.diaChiGiaoHang, dh.tongTien, dh.trangThaiDonHang
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $maKH);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Danh Sách Đơn Hàng</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="lichsu.css">
</head>

<body>
  <div class="container mt-4">
    <h1 class="mb-4">Danh Sách Đơn Hàng</h1>
    
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $danhSachMonAn = explode(",", $row['danhSachMonAn']);
            $danhSachHinhAnh = explode(",", $row['danhSachHinhAnh']);
            $data = ['maDH'=>$row['maDonHang'], 'trangThai' => $row['trangThaiDonHang']]
            ?>
            <div class="product-container shadow p-3 mb-4" >
              <!-- Hàng 1: Thông tin đơn hàng -->
              <div class="row align-items-center">
                <div class="col-12">
                  <h2>Đơn Hàng: <span class="madonhang_<?php echo $row['maDonHang']; ?>"><?php echo $row['maDonHang']; ?></span></h2>
                  <p>Ngày Tạo: <?php echo $row['ngayTao']; ?></p>
                  <p>Địa Chỉ Giao Hàng: <?php echo $row['diaChiGiaoHang']; ?></p>
                </div>
              </div>

              <!-- Hàng 2: Danh sách món ăn kèm hình ảnh -->
              <div class="row mt-2">
                <?php
                for ($i = 0; $i < count($danhSachMonAn); $i++) {
                    $hinhAnh = isset($danhSachHinhAnh[$i]) ? $danhSachHinhAnh[$i] : 'https://via.placeholder.com/100';
                    ?>
                    <div class="col-4 mb-3 text-center">
                      <div class="product-image">
                        <img src="<?php echo $hinhAnh; ?>" alt="Ảnh món ăn" class="img-fluid">
                      </div>
                      <p><?php echo $danhSachMonAn[$i]; ?></p>
                    </div>
                    <?php
                }
                ?>
              </div>

              <!-- Hàng 3: Tổng tiền -->
              <div class="giatien-row row mt-3">
                <div class="col-12 text-end">
                  <p class="gia">Tổng tiền: <span><?php echo number_format($row['tongTien'], 0, ',', '.'); ?>₫</span></p>
                </div>
              </div>

              <!-- Hàng 4: Trạng thái -->
              <div class="trang-thai-row text-end">
                <p>Trạng thái: <span class="gia"><?php echo $row['trangThaiDonHang']; ?></span></p>
              </div>

              <!-- Hàng 5: Các nút thao tác -->
              <div class="actions mt-3 text-end">
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reviewModal">Đánh Giá</button>
                <button class="btn btn-danger" onclick="huydonhang(<?php echo htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'); ?>)">Hủy Đơn Hàng</button>
              </div>
            </div>
            <?php
        }
    } else {
        echo "<h2>Không có đơn hàng nào cho khách hàng này.</h2>";
    }
    // Đóng kết nối
    $conn->close();
    ?>
  </div>
  <!-- Custom JS -->
  <script>
    const stars = document.querySelectorAll('.rating .fa-star');

    stars.forEach((star, index) => {
      star.addEventListener('mouseover', () => {
        stars.forEach((s, i) => {
          s.style.color = i <= index ? '#ffcc00' : '#ccc'; // Đổi màu sao khi hover
        });
      });

      star.addEventListener('mouseout', () => {
        stars.forEach((s) => {
          s.style.color = s.classList.contains('selected') ? '#ffcc00' : '#ccc'; // Khôi phục màu của sao được chọn
        });
      });

      star.addEventListener('click', () => {
        stars.forEach((s, i) => {
          s.classList.toggle('selected', i <= index); // Gắn/loại bỏ trạng thái "selected"
        });

        const ratingValue = index + 1; // Lấy giá trị đánh giá
        console.log('Đánh giá:', ratingValue);
      });
    });
  </script>
  
</body>
<script src="./lichsu.js"></script>
</html>
