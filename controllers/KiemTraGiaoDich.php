<?php

include('../config/database.php'); 

// Chỉ cho phép POST và POST có ID đơn hàng
if(!$_POST || !isset($_POST['maDonHang']))
    die('access denied');

// Lấy maDonHang từ POST
$order_id = $_POST['maDonHang'];

// Kiểm tra đơn hàng có tồn tại không với PDO
try {
    // Chuẩn bị câu lệnh SQL
    $stmt = $pdo->prepare("SELECT trangThaiThanhToan FROM donhang WHERE maDonHang = :maDonHang");
    
    // Liên kết tham số
    $stmt->bindParam(':maDonHang', $order_id, type: PDO::PARAM_STR);
    
    // Thực thi câu lệnh
    $stmt->execute();
    
    // Kiểm tra nếu có kết quả
    if ($stmt->rowCount() > 0) {
        // Lấy thông tin đơn hàng
        $order_details = $stmt->fetch(PDO::FETCH_OBJ);
        
        // Trả về kết quả trạng thái đơn hàng dạng JSON
        echo json_encode(['trangThaiThanhToan' => $order_details->trangThaiThanhToan]);  
    } else {
        // Trả về kết quả không tìm thấy đơn hàng
        echo json_encode(['trangThaiThanhToan' => 'order_not_found']);
    }
} catch (PDOException $e) {
    // Xử lý lỗi kết nối và truy vấn
    echo json_encode(['trangThaiThanhToan' => 'error', 'message' => $e->getMessage()]);
}
?>