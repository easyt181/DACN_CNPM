<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giao Diện Sản Phẩm</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!--<link rel="stylesheet" href="styles.css">-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .product-container {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 16px;
    max-width: 1000px;
    margin: auto;
  }
  .product-image img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
  }
  .product-info h2 {
    font-size: 16px;
    font-weight: bold;
    color: #333;
  }
  .product-info .description {
    font-size: 14px;
    color: #555;
    margin-top: 8px;
  }
  .product-info .variant,
  .product-info .quantity {
    font-size: 14px;
    color: #777;
    margin-top: 4px;
  }
  .price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
  }
  .price-row .original-price {
    font-weight: bold;
    color: #e40046;
  }
  .price-row .p span {
    font-size: 16px;
    font-weight: bold;
    color: #e40046;
  }
  .actions {
    text-align: center;
    margin-top: 16px;
  }
  .actions button {
    margin: 4px;
  }
  </style>
</head>
<body>
  <div class="container">
    <div class="product-container shadow">
      <!-- Row 1: Product Image and Info -->
      <div class="row align-items-center">
        <div class="col-3 text-center">
          <div class="product-image">
            <img src="https://via.placeholder.com/150" alt="Bộ sạc nhanh">
          </div>
        </div>
        <div class="col-9">
          <div class="product-info">
            <h2>Bộ sạc nhanh ( Củ P.D 20.W + Dây sạc nhanh Type-C )</h2>
            <p class="description">Không nóng máy, an toàn ổn định dành cho điện thoại</p>
            <p class="variant">Phân loại hàng: LẺ CÁP 20W</p>
            <p class="quantity">Số lượng: x1</p>
          </div>
        </div>
      </div>
      <!-- Row 2: Price and Status -->
      <div class="price-row row mt-3">
        <div class="col-6">
          <p class="price">
            Trạng thái: <span class="original-price">Đang Chờ Xác Nhận</span>
          </p>
        </div>
        <div class="col-6 text-end">
          <p class="total-price">Giá tiền: <span>30.900₫</span></p>
        </div>
      </div>
      <!-- Row 3: Actions -->
      <div class="actions mt-3">
        <button class="btn btn-warning">Đánh Giá</button>
        <button class="btn btn-danger">Hủy Đơn Hàng</button>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->

</body>
</html>