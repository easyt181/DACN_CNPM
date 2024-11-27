function huydonhang(data) {
    if (data.trangThai == 'Đang chờ xác nhận' || data.trangThai == 'Đang chuẩn bị'){
        fetch('index.php?controller=danhgia&action=huyDonHang', {
            method: 'POST',
            body: JSON.stringify({ maDH: data.maDH }),  
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.text())
        .then(data => {  
            // location.reload(); 
        })
        .catch(error => console.error('Error:', error));
}
else{
    alert('Không thể hủy đơn hàng!');
}
}