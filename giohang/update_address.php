<?php
// Cấu hình cơ sở dữ liệu
$host = 'localhost';
$port = 3308;
$dbname = 'db_nhom5_dacn';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Kết nối tới cơ sở dữ liệu
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password, $options);
} catch (PDOException $e) {
    echo 'Kết nối thất bại: ' . $e->getMessage();
    exit;
}

// Kiểm tra xem dữ liệu POST có được gửi không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ yêu cầu
    $maKH = $_POST['maKH'] ?? null;
    $tenKH = $_POST['tenKH'] ?? null;
    $sdt = $_POST['sdt'] ?? null;
    $diaChi = $_POST['diaChi'] ?? null;

    // Kiểm tra dữ liệu đầu vào
    if (!$maKH || !$tenKH || !$sdt || !$diaChi) {
        echo "Vui lòng cung cấp đầy đủ thông tin.";
        exit;
    }

    // Cập nhật thông tin khách hàng trong cơ sở dữ liệu
    $sql = "UPDATE khachhang 
            SET tenKH = :tenKH, sdt = :sdt, diaChi = :diaChi 
            WHERE maKH = :maKH";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([
            ':tenKH' => $tenKH,
            ':sdt' => $sdt,
            ':diaChi' => $diaChi,
            ':maKH' => $maKH,
        ]);

        if ($stmt->rowCount() > 0) {
            echo "Cập nhật thông tin thành công!";
        } else {
            echo "Không có thay đổi nào được thực hiện.";
        }
    } catch (PDOException $e) {
        echo "Lỗi khi cập nhật thông tin: " . $e->getMessage();
    }
} else {
    echo "Phương thức yêu cầu không hợp lệ.";
}
?>
