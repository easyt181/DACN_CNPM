<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đánh Giá Sản Phẩm</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="styles1.css">
</head>
<body>
  <div class="container mt-4">
    <!-- Nút mở dialog -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">Đánh Giá Sản Phẩm</button>

    <!-- Dialog -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!-- Tiêu đề -->
          <div class="modal-header">
            <h5 class="modal-title text-danger fw-bold" id="reviewModalLabel">Đánh Giá Sản Phẩm</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Hướng dẫn -->
            <!-- Thông tin sản phẩm -->
            <div class="d-flex align-items-center mb-4">
              <img src="https://via.placeholder.com/100" alt="Sản phẩm" class="rounded">
              <div class="ms-3">
                <h5 class="fw-bold">Bộ sạc nhanh (Củ P.D 20.W + Dây sạc nhanh Type-C)</h5>
                <p class="text-muted">Không nóng máy, an toàn ổn định dành cho điện thoại</p>
                <p class="text-danger">Phân loại hàng: LẺ CÁP 20W</p>
              </div>
            </div>
            <!-- Đánh giá sao -->
            <div class="mb-4">
              <h6 class="fw-bold">Chất lượng sản phẩm</h6>
              <div class="d-flex align-items-center">
                <div class="text-warning">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                </div>
              </div>
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

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
