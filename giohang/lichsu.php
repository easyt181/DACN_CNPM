<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giao Diện Sản Phẩm</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Font Awesome for Stars -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <div class="container">
    <div class="product-container shadow">
      <!-- Hàng 1: Ảnh sản phẩm và thông tin -->
      <div class="row align-items-center">
        <div class="col-3 text-center">
          <div class="product-image">
            <img src="https://via.placeholder.com/150" alt="Bộ sạc nhanh">
          </div>
        </div>
        <div class="col-9">
          <div class="product-info">
            <h2>Bộ sạc nhanh (Củ P.D 20.W + Dây sạc nhanh Type-C)</h2>
          </div>
        </div>
      </div>
      <!-- Hàng 2: Tổng tiền -->
      <div class="giatien-row row mt-3">
        <div class="col-12 text-end">
          <p class="gia">Giá tiền: <span>30.900₫</span></p>
        </div>
      </div>
      <!-- Hàng 3: Trạng thái -->
      <div class="trang-thai-row text-end">
        <p>Trạng thái: <span class="gia">Đang Chờ Xác Nhận</span></p>
      </div>
      <!-- Hàng 4: Các nút thao tác -->
      <div class="actions mt-3">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reviewModal">Đánh Giá</button>
        <button class="btn btn-danger">Hủy Đơn Hàng</button>
      </div>
      
  <!-- Dialog đánh giá -->
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
              <h5 class="fw-bold">Bộ sạc nhanh (Củ P.D 20.W + Dây sạc nhanh Type-C)</h5>
            </div>
          </div>
          <!-- Đánh giá sao -->
          <div class="mb-4">
            <h6 class="fw-bold">Chất lượng sản phẩm</h6>
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

  <!-- Custom JS -->
  <script>
    const stars = document.querySelectorAll('.rating .fa-star');

    stars.forEach((star, index) => {
      // Xử lý khi di chuột qua sao
      star.addEventListener('mouseover', () => {
        stars.forEach((s, i) => {
          s.style.color = i <= index ? '#ffcc00' : '#ccc'; // Đổi màu sao khi hover
        });
      });

      // Xử lý khi rời chuột khỏi sao
      star.addEventListener('mouseout', () => {
        stars.forEach((s) => {
          s.style.color = s.classList.contains('selected') ? '#ffcc00' : '#ccc'; // Khôi phục màu của sao được chọn
        });
      });

      // Xử lý khi nhấp chọn sao
      star.addEventListener('click', () => {
        stars.forEach((s, i) => {
          s.classList.toggle('selected', i <= index); // Gắn/loại bỏ trạng thái "selected"
        });

        const ratingValue = index + 1; // Lấy giá trị đánh giá
        console.log('Đánh giá:', ratingValue);
      });
    });

  </script>
    </div>
  </div>

</body>

</html>