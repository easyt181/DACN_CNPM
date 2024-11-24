function loadOrderDetails(maDonHang) {
    $.ajax({
        url: 'index.php?controller=donhang&action=layChiTietDonHang',
        method: 'GET',
        data: { maDonHang: maDonHang },
        contentType: 'application/json',  
        dataType: 'json',
        success: function(response) {
            console.log('Phản hồi từ server:', response); 
            try {
                if (response.success) {
                    const orderDetails = response.orderDetails;
                    const orderInfo = response.orderInfo;
                    const orderDetailsTableBody = $('#orderDetailsTableBody');
                    orderDetailsTableBody.empty();

                    orderDetails.forEach((item, index) => {
                        orderDetailsTableBody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.tenMonAn}</td>
                                <td>${formatCurrency(item.donGia)} VNĐ</td>
                                <td style="padding-left:35px;">${item.soLuong}</td>
                                <td>${formatCurrency(item.thanhTien)} VNĐ</td>
                            </tr>
                        `);
                    });
                    
                    $('#phuongThucThanhToanSpan').text(orderInfo.phuongThucThanhToan);
                    $('#ghiChuSpan').text(orderInfo.ghiChu || 'Không có');
                    $('#khoangCachGiaoHangSpan').text(orderInfo.khoangCachGiaoHang || 0);
                    $('#phiShipSpan').text(formatCurrency(orderInfo.phiShip));
                    $('#tongTienCongTruSpan').text(formatCurrency(orderInfo.tongTienCongTru));
                    $('#tongTienSpan').text(formatCurrency(orderInfo.tongTien));
                    $('#trangThaiThanhToanSpan').text(orderInfo.trangThaiThanhToan);
                    $('#trangThaiDonHangSpan').text(orderInfo.trangThaiDonHang);
                } else {
                    alert(response.message || 'Không thể lấy thông tin đơn hàng.');
                }
            } catch (error) {
                console.error('Lỗi phân tích JSON:', error);
                alert('Phản hồi từ server không hợp lệ.');
            }
        },
        error: function(xhr) {
            console.error('Lỗi khi tải chi tiết đơn hàng:', xhr.responseText);
            alert('Không thể lấy thông tin đơn hàng.');
        }
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('de-DE').format(amount);
}
