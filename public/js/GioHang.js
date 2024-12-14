

$(document).ready(function () {
    $('.soLuong_btn-cong').on('click', function () {
        let soLuong = $(this).siblings('.soLuong_text').find('.soLuong_text-span').text();
        let count_item = $(this).data('item');
        soLuong = parseInt(soLuong) + 1
        $(this).siblings('.soLuong_text').find('.soLuong_text-span').text(soLuong);
        capNhatSoLuong(soLuong, count_item, 'tang')
    });
    $('.soLuong_btn-tru').on('click', function () {
        let soLuong = $(this).siblings('.soLuong_text').find('.soLuong_text-span').text();
        let count_item = $(this).data('item');
        soLuong = parseInt(soLuong) - 1
        if (soLuong > 0) {
            $(this).siblings('.soLuong_text').find('.soLuong_text-span').text(soLuong);
            capNhatSoLuong(soLuong, count_item, 'giam')
        }
    });
});
function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length === 2) {
        return parts.pop().split(";").shift();
    }
    return null;
}

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function capNhatSoLuong(sl, count_item, tangGiam) {
    let cartCookie = getCookie('cart');
    let tongTien = $('#tongTientxt').text();
    tongTien = tongTien.replace(" VND", "");
    tongTien = tongTien.replace(/,/g, "");
    tongTien = parseFloat(tongTien)
    if (cartCookie) {
        try {
            let decodedCart = decodeURIComponent(cartCookie);
            let cart = JSON.parse(decodedCart);
            if (tangGiam == 'tang') {
                tongTien = tongTien + parseFloat(cart[count_item].gia)
            } else {
                tongTien = tongTien - parseFloat(cart[count_item].gia)
            }
            cart[count_item].soLuong = sl.toString();
            setCookie('cart', JSON.stringify(cart), 1);
            $('#tongTientxt').text(tongTien.toLocaleString() + ' VND')
        } catch (e) {
            console.error("Lỗi khi phân tích cookie 'cart':", e);
        }
    } else {
        console.log("Không tìm thấy cookie 'cart'");
    }
}




function removeItem(maMonAn) {
    fetch('index.php?controller=giohang&action=xoaGioHang', {
        method: 'POST',
        body: JSON.stringify({ maMonAn: maMonAn }),
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.text())
        .then(data => {
            console.log(data)
            location.reload();
        })
        .catch(error => console.error('Error:', error));

}
let distance = 0
let phiShip = 0


const goongApiKey = 'YZzBVkRATVtmDRw9OC4P0p0KWiRDgyRZuyTRAj6u';

const point1 = { lat: 21.039966, lon: 105.741885 };


async function getCoordinatesFromAddress(address) {
    const url = `https://rsapi.goong.io/Geocode?address=${encodeURIComponent(address)}&api_key=${goongApiKey}`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`API request failed with status ${response.status}`);
        }

        const data = await response.json();

        if (data.results && data.results.length > 0) {
            const bestMatch = data.results[0];
            const coordinates = bestMatch.geometry.location;
            return coordinates;
        } else {
            alert("Không tìm thấy tọa độ từ địa chỉ.");
            return null;
        }
    } catch (error) {
        // console.error('Lỗi khi lấy tọa độ từ địa chỉ:', error);
        // alert("Không thể lấy tọa độ. Vui lòng thử lại sau.");    
        return null;
    }
}

function calculateDistance(point1, point2) {
    const R = 6371;

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

    return distance;
}

function toRadians(degrees) {
    return degrees * (Math.PI / 180);
}

window.onload = async function () {
    const address = document.getElementById('address-span').textContent;
    let tongTien = document.getElementById('tongTientxt').textContent;

    const coordinates = await getCoordinatesFromAddress(address);

    if (coordinates) {
        const point2 = { lat: coordinates.lat, lon: coordinates.lng };

        distance = calculateDistance(point1, point2);
        phiShip = (distance.toFixed(2) * 5000);
        tongTien = parseInt(tongTien) + parseFloat(phiShip);
        let formattedResult = phiShip.toLocaleString();
        let tongTien_ = tongTien.toLocaleString();
        // Hiển thị khoảng cách
        document.getElementById('distance').textContent = `${formattedResult} VND`;
        document.getElementById('tongTientxt').textContent = `${tongTien_} VND`;
    } else {
        tongTien = parseInt(tongTien)
        let tongTien_ = tongTien.toLocaleString();
        document.getElementById('tongTientxt').textContent = `${tongTien_} VND`;
    }
};
function getTTDH(data) {
    const tongTien = document.getElementById('tongTientxt').textContent;
    let tongTien_ = tongTien.replace(" VND", "");
    const ghiChu = document.getElementById('textarea').value;
    const selectedRadio = document.querySelector('input[name="flexRadioDefault"]:checked').id;
    let phuongThuc = '';

    if (selectedRadio === 'flexRadioDefault1') {
        phuongThuc = 'Thanh toán khi nhận hàng';
    } else if (selectedRadio === 'flexRadioDefault2') {
        phuongThuc = 'Thanh toán qua QRCode';

    }

    let tongTienNum = parseFloat(tongTien_.replace(/[.,]/g, "").replace(" VND", ""));
    tongTienNum = 2000;
    let CTDH = data.CTDH
    let TTDH_ = data.KH
    TTDH_.khoangCach = distance.toFixed(2);
    TTDH_.phiShip = phiShip;
    TTDH_.tongTien = tongTienNum;
    TTDH_.phuongThuc = phuongThuc;
    TTDH_.ghiChu = ghiChu;
    let HD_ = data.KH;
    HD_.ngayTao = new Date();
    HD_.tongTien = tongTienNum;
    HD_.phuongThucThanhToan = phuongThuc;
    $.ajax({
        url: "index.php?controller=giohang&action=themDonHangKH",
        type: "POST",
        data: JSON.stringify({ TTDH: TTDH_, CTDH: CTDH, HD: HD_ }),
        contentType: "application/json",
        success: function (response) {
            const is_true = JSON.parse(response);
            if (TTDH_.phuongThuc == 'Thanh toán khi nhận hàng') {
                if (is_true) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt đơn hàng thành công!',
                    }).then(() => {
                        window.location.href = 'index.php?controller=thucdon&action=hienThiHome';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Đã có lỗi xảy ra',
                        text: 'Vui lòng thử lại sau.'
                    }).then(() => {
                        $('.modal__thongTinKH__diaChi-input').val(diachi);
                    })
                }
            } else {
                window.location.href = 'index.php?controller=donhang&action=thanhToanQR&maDonHang=' + data.maDonHang;
            }
            document.cookie = "cart=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
        },
        error: function (err) {
            console.error("Error:", err);
        }
    });

    // Hàm hiển thị modal xác nhận

}
function showConfirmation() {
    if (!document.querySelector('.gioTrong')) {


        const diaChi = data.KH['diaChi']
        if (diaChi !== undefined) {
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
            document.getElementById("confirmAction").addEventListener("click", function handleConfirm() {
                getTTDH(data);
                confirmModal.hide();
                this.removeEventListener("click", handleConfirm);
            });

            document.getElementById("cancelAction").addEventListener("click", function handleCancel() {
                this.removeEventListener("click", handleCancel);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Đã có lỗi xảy ra',
                text: 'Vui lòng nhập địa chỉ.'
            }).then(() => {
                $('.modal__thongTinKH__diaChi-input').val(diachi);
            })
        }
    }else{
        Swal.fire({
            icon: 'error',
            title: 'Vui lòng thêm đơn hàng!'
        })
    }
}

function thayDoiTT() {
    const tenKH = $('.tenKh').text();
    const sdt = $('.sdtKh').text();
    const diachi = $('.diaChi-span').text();

    $('.modal__thongTinKH__tenKH-input').val(tenKH);
    $('.modal__thongTinKH__sdtKH-input').val(sdt);
    $('.modal__thongTinKH__diaChi-input').val(diachi);

}
// Hàm tìm kiếm và hiển thị gợi ý địa chỉ
async function searchLocation() {
    const query = document.querySelector('.modal__thongTinKH__diaChi-input').value; // Lấy giá trị từ input
    const suggestionsContainer = document.getElementById('suggestions');
    suggestionsContainer.innerHTML = ''; // Xóa các gợi ý cũ
    suggestionsContainer.style.display = 'none'; // Ẩn gợi ý nếu không có kết quả

    if (!query) return; // Nếu không có giá trị nhập vào thì không tìm kiếm

    const url = `https://rsapi.goong.io/Place/AutoComplete?api_key=${goongApiKey}&input=${encodeURIComponent(query)}`;

    try {
        const response = await fetch(url); // Gửi yêu cầu API Goong

        if (!response.ok) {
            throw new Error(`API request failed with status ${response.status}`);
        }

        const data = await response.json(); // Nhận dữ liệu từ API

        if (data.predictions && data.predictions.length > 0) {
            suggestionsContainer.style.display = 'block'; // Hiển thị các gợi ý

            data.predictions.forEach(place => {
                const suggestion = document.createElement('div');
                suggestion.classList.add('suggestion');
                suggestion.textContent = place.description; // Hiển thị tên địa chỉ trong gợi ý

                // Sự kiện khi nhấn vào gợi ý
                suggestion.onclick = () => {
                    document.querySelector('.modal__thongTinKH__diaChi-input').value = place.description; // Thay đổi giá trị của input
                    suggestionsContainer.innerHTML = ''; // Xóa gợi ý sau khi chọn
                    suggestionsContainer.style.display = 'none'; // Ẩn container gợi ý
                };

                suggestionsContainer.appendChild(suggestion); // Thêm gợi ý vào container
            });
        } else {
            alert("Không tìm thấy kết quả phù hợp.");
        }
    } catch (error) {
        console.error('Lỗi khi tìm kiếm địa chỉ:', error);
        alert("Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại sau.");
    }
}


// Hàm debounce giúp giảm tần suất gọi API
const debouncedSearchLocation = debounce(searchLocation, 500);

// Sự kiện oninput để gọi hàm searchLocation khi người dùng nhập vào ô input
document.getElementById('diaChiGiaoHang').oninput = debouncedSearchLocation;

function xacNhanThayDoiDC() {
    const diaChi = $('.modal__thongTinKH__diaChi-input').val();
    const tenKH = $('.modal__thongTinKH__tenKH-input').val();
    const sdt = $('.modal__thongTinKH__sdtKH-input').val();

    $.ajax({
        url: "index.php?controller=giohang&action=thayDoiDiaChi",
        type: "POST",
        data: JSON.stringify({ diaChi: diaChi, maKH: data.KH.maKH, tenKH: tenKH, sdt: sdt }),
        contentType: "application/json",
        success: function (response) {
            const is_thayDoi = JSON.parse(response);
            if (is_thayDoi) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thay đổi địa chỉ thành công!',
                    text: 'Chúc mừng bạn đã thay đổi địa chỉ thành công.'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Đã có lỗi xảy ra',
                    text: 'Vui lòng thử lại sau.'
                }).then(() => {
                    $('.modal__thongTinKH__diaChi-input').val(diachi);
                })
            }
        },
        error: function (err) {
            console.error("Error:", err);
        }
    });

}


