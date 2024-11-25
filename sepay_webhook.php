<?php

// Include file db_connect.php, file chứa toàn bộ kết nối CSDL
require('config/database.php');

// Lấy dữ liệu từ webhook
$data = json_decode(file_get_contents('php://input'));
if (!is_object($data)) {
    echo json_encode(['success' => FALSE, 'message' => 'No data']);
    die('No data found!');
}

// Khởi tạo các biến từ dữ liệu webhook
$gateway = $data->gateway;
$transaction_date = $data->transactionDate;
$account_number = $data->accountNumber;
$sub_account = $data->subAccount;
$transfer_type = $data->transferType;
$transfer_amount = $data->transferAmount;
$accumulated = $data->accumulated;
$code = $data->code;
$transaction_content = $data->content;
$reference_number = $data->referenceCode;
$body = $data->description;

$amount_in = 0;
$amount_out = 0;

// Kiểm tra giao dịch tiền vào hay tiền ra
if ($transfer_type == "in") {
    $amount_in = $transfer_amount;
} else if ($transfer_type == "out") {
    $amount_out = $transfer_amount;
}

$regex = '/DH\d+/';  // Tìm kiếm chuỗi bắt đầu bằng 'DH' và theo sau là một hoặc nhiều chữ số
preg_match($regex, $transaction_content, $matches);

// Nếu không tìm thấy mã đơn hàng
if (empty($matches[0])) {
    echo json_encode(['success' => false, 'message' => 'Order not found in transaction content.']);
    die();
}

// Gán mã đơn hàng tìm được (mã đầy đủ 'DH018' chẳng hạn)
$pay_order_id = $matches[0];

// Chạy câu lệnh INSERT vào bảng GiaoDich
try {
    $sql = "INSERT INTO GiaoDich (maDonHang, ngayGiaoDich, loaiGiaoDich, soTaiKhoan, soTienVao, soTienRa, noiDungGiaoDich, ngayTaoGiaoDich, trangThaiGiaoDich, gateway, reference_number, body) 
            VALUES (:pay_order_id, :transaction_date, :transfer_type, :account_number, :amount_in, :amount_out, :transaction_content, NOW(), 'Đang chờ hoàn tất', :gateway, :reference_number, :body)";
    
    // Chuẩn bị câu lệnh
    $stmt = $pdo->prepare($sql);
    
    // Gắn giá trị vào các tham số
    $stmt->bindParam(':pay_order_id', $pay_order_id);
    $stmt->bindParam(':transaction_date', $transaction_date);
    $stmt->bindParam(':transfer_type', $transfer_type);
    $stmt->bindParam(':account_number', $account_number);
    $stmt->bindParam(':amount_in', $amount_in);
    $stmt->bindParam(':amount_out', $amount_out);
    $stmt->bindParam(':transaction_content', $transaction_content);
    $stmt->bindParam(':gateway', $gateway);
    $stmt->bindParam(':reference_number', $reference_number);
    $stmt->bindParam(':body', $body);
    
    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo json_encode(['success' => TRUE]);
    } else {
        echo json_encode(['success' => FALSE, 'message' => 'Can not insert record to mysql']);
        die();
    }
} catch (Exception $e) {
    echo json_encode(['success' => FALSE, 'message' => 'Error: ' . $e->getMessage()]);
    die();
}

// Tìm đơn hàng với mã đơn hàng và số tiền tương ứng với giao dịch thanh toán trên
try {
    $stmt = $pdo->prepare("SELECT * FROM donhang WHERE maDonHang = :pay_order_id AND tongTien = :amount_in AND trangThaiThanhToan = 'Chưa thanh toán'");
    $stmt->bindParam(':pay_order_id', $pay_order_id, PDO::PARAM_STR); // Sử dụng PARAM_STR cho kiểu VARCHAR
    $stmt->bindParam(':amount_in', $amount_in);
    $stmt->execute();
      
    if ($stmt->rowCount() > 0) {
        // Cập nhật trạng thái liên quan đơn hàng
        $updateStmt = $pdo->prepare("UPDATE donhang SET trangThaiThanhToan = 'Đã thanh toán' WHERE maDonHang = :pay_order_id");
        $updateStmt->bindParam(':pay_order_id', $pay_order_id);


        if ($updateStmt->execute()) {
            $updateGiaoDich = $pdo->prepare("UPDATE GiaoDich SET trangThaiGiaoDich = 'Hoàn tất' WHERE maDonHang = :pay_order_id");
            $updateGiaoDich->bindParam(':pay_order_id', $pay_order_id);
            $updateGiaoDich->execute();
            
            $updateHoaDon = $pdo->prepare("UPDATE HoaDon SET trangThaiHoaDon = 'Đã thanh toán' WHERE maDonHang = :pay_order_id");
            $updateHoaDon->bindParam(':pay_order_id', $pay_order_id);   
            $updateHoaDon->execute();   
            echo json_encode(['success' => TRUE]);
        } else {
            echo json_encode(['success' => FALSE, 'message' => 'Failed to update order status']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found or mismatched amount']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => FALSE, 'message' => 'Error: ' . $e->getMessage()]);
    die();
}

?>
