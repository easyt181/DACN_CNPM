<?php
include('../config/database.php'); 
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
if ($keyword == '') {
    $query = "SELECT * FROM thucdon WHERE deCuMonAn = '1'"; 

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if ($result) {
        echo json_encode($result);  
    } else {
        echo json_encode([]); 
    }
}
elseif (!empty($keyword)) {
    $query = "SELECT * FROM thucdon WHERE tenMonAn LIKE :keyword"; 

    $stmt = $pdo->prepare($query);
    $keywordParam = "%" . $keyword . "%";  
    $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if ($result) {
        echo json_encode($result);  
    } else {
        echo json_encode([]); 
    }
} else {
    echo json_encode([]);  
}
?>

