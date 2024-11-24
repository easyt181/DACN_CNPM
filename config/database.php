<?php
$host = 'localhost';
$dbname = 'db_nhom5_dacn';
$username = 'root';
$password = '';
$port = 3308; // Thêm tham số cổng
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Cập nhật chuỗi kết nối PDO để sử dụng cổng 3308
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password, $options);
} catch (PDOException $e) {
    echo 'Kết nối thất bại: ' . $e->getMessage();
    exit;
}
