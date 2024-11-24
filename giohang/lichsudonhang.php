<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$port = 3308;
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
            ?>
            <div class="product-container shadow p-3 mb-4">
              <!-- Hàng 1: Ảnh sản phẩm và thông tin -->
              <div class="row align-items-center">
                <div class="col-12">
                  <h2>Đơn Hàng: <?php echo $row['maDonHang']; ?></h2>
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
                <button class="btn btn-danger">Hủy Đơn Hàng</button>
              </div>
              <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Tiêu đề -->
        <div class="modal-header">
          <h5 class="modal-title text-danger fw-bold" id="reviewModalLabel">Đánh Giá Sản Phẩm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Thông tin sản phẩm -->
          <div class="d-flex align-items-center mb-4">
            <img src="https://via.placeholder.com/100" alt="Sản phẩm" class="rounded">
            <div class="ms-3">
              <h2 class="fw-bold">Đơn Hàng: <?php echo $row['maDonHang']; ?></h2>
            </div>
          </div>
          <!-- Đánh giá sao -->
          <div class="mb-4">
            <h6 class="fw-bold">Đánh Giá đơn hàng</h6>
            <div class="rating">
              <i class="fas fa-star" data-value="1"></i>
              <i class="fas fa-star" data-value="2"></i>
              <i class="fas fa-star" data-value="3"></i>
              <i class="fas fa-star" data-value="4"></i>
              <i class="fas fa-star" data-value="5"></i>
            </div>
            <p class="text-muted mt-2">Chọn số sao để đánh giá.</p>
          </div>
          <!-- Bình luận -->
          <div class="mb-4">
            <label for="review-text" class="form-label fw-bold">Viết Đánh Giá:</label>
            <textarea id="review-text" class="form-control" rows="3" placeholder="Để lại đánh giá..."></textarea>
          </div>
        </div>
        <!-- Nút thao tác -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở Lại</button>
          <button type="button" class="btn btn-danger">Hoàn Thành</button>
        </div>
      </div>
    </div>
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
  
</body>
</html>

