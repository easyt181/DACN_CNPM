let cart = [];

// Tìm kiếm món ăn
$('#btnSearch').click(function (event) {
    event.preventDefault(); 
    const keyword = $('#searchFood').val();
    $.ajax({
        url: 'controllers/TimKiemMonAn.php',
        method: 'GET',
        data: { keyword: keyword },
        success: function (response) {
            console.log('Response:', response);
            const data = JSON.parse(response);
            if (data.length > 0) {
                let tableHtml = `
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên Món Ăn</th>
                                <th>Loại Món Ăn</th>
                                <th>Giá</th>
                                <th>Tình trạng</th>
                                <th>Thêm vào giỏ</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                tableHtml += data.map(item => `
                    <tr>
                        <td>${item.tenMonAn}</td>
                        <td>${item.loaiMonAn}</td>
                        <td>${formatCurrency(item.gia)} VNĐ</td>
                        <td>${item.tinhTrang}</td>
                        <td><button class="btn btn-success btn-sm" onclick="addToCart('${item.maMonAn}', '${item.tenMonAn}', ${item.gia})">+</button></td>
                    </tr>
                `).join('');
                tableHtml += `</tbody></table>`;
                $('#searchResult').html(tableHtml);
            } else {
                $('#searchResult').html('<p>Không tìm thấy món ăn nào.</p>');
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX error: ", error);
        }
    });
});

// Hàm thêm món ăn vào giỏ hàng
function addToCart(maMonAn, tenMonAn, gia) {
    event.preventDefault();
    const existingItem = cart.find(item => item.maMonAn === maMonAn);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({ maMonAn, tenMonAn, gia, quantity: 1 });
    }
    renderCart();
}

function renderCart() {
    const cartHtml = cart.map(item => `
        <tr>
            <td>${item.tenMonAn}</td>
            <td>${formatCurrency(item.gia)} VNĐ</td>
            <td><input type="number" value="${item.quantity}" onchange="updateQuantity('${item.maMonAn}', this.value)" min="1"></td>
            <td>${formatCurrency(item.gia * item.quantity)} VNĐ</td>
            <td><button class="btn btn-danger btn-sm" onclick="removeFromCart('${item.maMonAn}')">Xóa</button></td>
        </tr>
    `).join('');
    $('#cartItems').html(cartHtml);

    updateTotal();
}
function updateQuantity(maMonAn, quantity) {
    const item = cart.find(item => item.maMonAn === maMonAn);
    if (item) {
        item.quantity = parseInt(quantity, 10);
        renderCart();
    }
}

function removeFromCart(maMonAn) {
    cart = cart.filter(item => item.maMonAn !== maMonAn);
    renderCart();
}

// Hiển thị form tải chứng minh giao dịch
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("phuongThucThanhToan").addEventListener("change", function () {
        const selectedOption = this.value;
        const transactionProofForm = document.getElementById("transactionProofForm");
        
        if (selectedOption === "Chuyển khoản trực tiếp") {
            transactionProofForm.style.display = "inline-block"; 
        } else {
            transactionProofForm.style.display = "none"; 
            document.getElementById("transactionImage").value = ""; 
        }
    });
});


// Thêm đơn hàng
document.getElementById('submitOrder').addEventListener('click', function(event) {
    const maDonHang = document.getElementById('maDonHang').value;
    if (cart.length === 0) {
        Swal.fire({
            title: 'Thông báo!',
            text: `Giỏ hàng đang trống! Vui lòng thêm món ăn vào giỏ.`,
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    const phuongThucThanhToan = document.getElementById('phuongThucThanhToan').value;
    const transactionImage = document.getElementById('transactionImage').files[0];
    if (phuongThucThanhToan === 'Chuyển khoản trực tiếp' && !transactionImage) {
        Swal.fire({
            title: 'Thông báo!',
            text: 'Vui lòng chọn ảnh chứng minh giao dịch!',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    const formData = new FormData();
    formData.append('cartItems', JSON.stringify(cart));
    formData.append('tenKH', document.getElementById('tenKH').value);
    formData.append('sdt', document.getElementById('sdt').value);
    formData.append('diaChiGiaoHang', document.getElementById('diaChiGiaoHang').value);
    formData.append('khoangCachGiaoHang', document.getElementById('khoangCachGiaoHang').value);
    formData.append('ngayTao', document.getElementById('ngayTao').value);
    formData.append('phuongThucThanhToan', document.getElementById('phuongThucThanhToan').value);
    formData.append('tongTien', document.getElementById('tongTien').value);
    formData.append('tongTienCongTru', document.getElementById('tongTienCongTru').value);
    formData.append('phiShip', document.getElementById('phiShip').value);
    formData.append('maUuDaiDH', document.getElementById('maUuDaiDH').value);
    formData.append('ghiChu', document.getElementById('ghiChu').value);
    if (transactionImage) {
        formData.append('transactionImage', transactionImage);
    }

    // Gửi FormData qua AJAX
    fetch('index.php?controller=donhang&action=themDonHangQuanLy&maDonHang='+ maDonHang, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.message === 'thieuthongtin') {
            Swal.fire({
                title: 'Thông báo!',
                text: `Vui lòng nhập đầy đủ thông tin`,
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {
            });
            return;
        }
        if (result.success) {
            if (phuongThucThanhToan === 'Thanh toán khi nhận hàng') {
                Swal.fire({
                    title: 'Thành công!',
                    text: `Đơn hàng được tạo thành công!`,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index.php?controller=donhang&action=hienThiDanhSachDonHang';
                });
            }else if (phuongThucThanhToan === 'Chuyển khoản trực tiếp') {
                    window.location.href = 'index.php?controller=donhang&action=hienThiDanhSachDonHang';
            } else {
                Swal.fire({
                    title: 'Thành công!',
                    text: `Đơn hàng được tạo thành công! Đến bước thanh toán qua QR code.`,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index.php?controller=donhang&action=thanhToanQR&maDonHang=' + result.maDonHang;
                });
            }
        } else {
            alert('Lỗi: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error details:', error); // Hiển thị chi tiết lỗi
        Swal.fire({
        title: 'Lỗi!',
        text: `Đã xảy ra lỗi khi gửi yêu cầu: ${error.message || 'Không rõ nguyên nhân.'}`,
        icon: 'error',
        confirmButtonText: 'OK'
    });
    });
    document.getElementById("themDonHang").reset();
});




//Tính khoảng cách và tiền ship
const debounce = (func, delay) => {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
};

const point1 = { lat: 21.039966, lon: 105.741885 }; 
const goongApiKey = 'YZzBVkRATVtmDRw9OC4P0p0KWiRDgyRZuyTRAj6u';

async function searchLocation() {
    const query = document.getElementById('diaChiGiaoHang').value;
    const suggestionsContainer = document.getElementById('suggestions');
    suggestionsContainer.innerHTML = '';
    suggestionsContainer.style.display = 'none';

    if (!query) return;

    const url = `https://rsapi.goong.io/Place/AutoComplete?api_key=${goongApiKey}&input=${encodeURIComponent(query)}`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`API request failed with status ${response.status}`);
        }

        const data = await response.json();
        // Hiển thị gợi ý
        if (data.predictions && data.predictions.length > 0) {
            suggestionsContainer.style.display = 'block';
            data.predictions.forEach(place => {
                const suggestion = document.createElement('div');
                suggestion.classList.add('suggestion');
                suggestion.textContent = place.description;

                suggestion.onclick = async () => {
                    document.getElementById('diaChiGiaoHang').value = place.description;
                    suggestionsContainer.innerHTML = ''; // Xóa gợi ý sau khi chọn
                    suggestionsContainer.style.display = 'none';

                    // Lấy tọa độ của địa chỉ được chọn
                    const coordinates = await getCoordinates(place.place_id);
                    if (coordinates) {
                        const point2 = { lat: coordinates.lat, lon: coordinates.lng };
                        calculateDistance(point1, point2);
                    }
                };

                suggestionsContainer.appendChild(suggestion);
            });
        } else {
            alert("Không tìm thấy kết quả phù hợp.");
        }
    } catch (error) {
        console.error('Lỗi khi tìm kiếm địa chỉ:', error);
        alert("Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại sau.");
    }
}

// Hàm giảm tần suất gọi API bằng debounce
const debouncedSearchLocation = debounce(searchLocation, 500); // 500ms trì hoãn

// Thay đổi sự kiện oninput để dùng debounce
document.getElementById('diaChiGiaoHang').oninput = debouncedSearchLocation;

// Hàm lấy tọa độ từ Goong Maps API dựa trên place_id
async function getCoordinates(placeId) {
    const url = `https://rsapi.goong.io/Place/Detail?place_id=${placeId}&api_key=${goongApiKey}`;
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`API request failed with status ${response.status}`);
        }

        const data = await response.json();
        return data.result.geometry.location; // Tọa độ {lat, lng}
    } catch (error) {
        console.error('Lỗi khi lấy tọa độ:', error);
        alert("Không thể lấy tọa độ. Vui lòng thử lại sau.");
        return null;
    }
}

// Hàm tính khoảng cách giữa hai điểm
function calculateDistance(point1, point2) {
    const R = 6371; // Bán kính trái đất tính bằng km

    const lat1 = toRadians(point1.lat);
    const lon1 = toRadians(point1.lon);
    const lat2 = toRadians(point2.lat);
    const lon2 = toRadians(point2.lon);

    const dlat = lat2 - lat1;
    const dlon = lon2 - lon1;

    const a = Math.sin(dlat / 2) * Math.sin(dlat / 2) +
              Math.cos(lat1) * Math.cos(lat2) *
              Math.sin(dlon / 2) * Math.sin(dlon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const distance = R * c; 
    document.getElementById('khoangCachGiaoHang').value = distance.toFixed(2);
    updateShippingFee();
}

function toRadians(degrees) {
    return degrees * (Math.PI / 180);
}


function formatCurrency(amount) {
    return new Intl.NumberFormat('de-DE').format(amount);
}

// Hàm cập nhật phí ship khi khoảng cách thay đổi
function updateShippingFee() {
    const distance = parseFloat(document.getElementById('khoangCachGiaoHang').value); 
    
    if (!isNaN(distance)) {
        // Làm tròn khoảng cách giao hàng nếu < 0.5 thì làm tròn về 0, nếu > 0.5 thì làm tròn lên 1
        const roundedDistance = Math.round(distance); 

        const shippingFee = roundedDistance * 5000;

        document.getElementById('phiShip').value = shippingFee;

        document.getElementById('phiShipSpan').textContent = shippingFee.toLocaleString();

        updateTotal();
    }
}


function updateTotal() {
    const totalFoodCost = cart.reduce((sum, item) => sum + item.gia * item.quantity, 0);
    $('#totalFoodCostSpan').text(formatCurrency(totalFoodCost));

    const phiShip = parseFloat($('#phiShip').val()) || 0;
    $('#phiShipSpan').text(formatCurrency(phiShip));

    const tongTienCongTru = parseFloat($('#tongTienCongTru').val()) || 0;
    $('#tongTienCongTruSpan').text(formatCurrency(tongTienCongTru));

    const tongTien = totalFoodCost + phiShip + tongTienCongTru;
    $('#totalCostSpan').text(formatCurrency(tongTien));
    $('#tongTien').val(tongTien);
}

$('#phiShip').on('input', function() {
    updateTotal();
});

$('#tongTienCongTru').on('input', function() {
    updateTotal();
});



