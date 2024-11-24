<?php
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
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password, $options);
} catch (PDOException $e) {
    echo 'Kết nối thất bại: ' . $e->getMessage();
    exit;
}

// Lấy thông tin khách hàng có mã KH001
$maKH = 'KH001';
$sql = "SELECT * FROM khachhang WHERE maKH = :maKH";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':maKH', $maKH, PDO::PARAM_STR);
$stmt->execute();
$customer = $stmt->fetch();

if (!$customer) {
    echo "Không tìm thấy thông tin khách hàng.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <style>
        #suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            position: absolute;
            z-index: 1000;
            background-color: #fff;
            width: calc(100% - 30px); /* Đảm bảo chiều rộng khớp với ô nhập */
        }
        .suggestion {
            padding: 8px;
            cursor: pointer;
        }
        .suggestion:hover {
            background-color: #f0f0f0;
        }
    </style>

</head>
<body>
<!-- Nút mở modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addressModal">
    Thay đổi địa chỉ
</button>

<!-- Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Thông tin giao hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Hiển thị thông tin hiện tại -->
                <form id="updateAddressForm">
                    <input type="hidden" id="maKH" name="maKH" value="<?php echo htmlspecialchars($customer['maKH']); ?>"> <!-- Mã khách hàng -->
                    
                    <div class="mb-3">
                        <label for="tenKH" class="form-label">Tên khách hàng</label>
                        <input type="text" class="form-control" id="tenKH" name="tenKH" value="<?php echo htmlspecialchars($customer['tenKH']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="sdt" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="sdt" name="sdt" value="<?php echo htmlspecialchars($customer['sdt']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="diaChi" class="form-label">Địa chỉ hiện tại</label>
                        <input type="text" class="form-control" id="diaChi" name="diaChi" placeholder="Nhập địa chỉ" oninput="searchLocation()">
                        <div id="suggestions" style="border: 1px solid #ccc; max-height: 150px; overflow-y: auto; display: none; position: absolute; z-index: 1000; background: #fff;"></div>
                    </div>


                    <!-- Nút lưu thay đổi -->
                    <button type="button" class="btn btn-primary" id="saveAddress">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script xử lý lưu thông tin thay đổi -->
<script>
    // Khi nhấn "Lưu thay đổi", gửi thông tin cập nhật
    document.getElementById('saveAddress').addEventListener('click', function() {
    var maKH = document.getElementById('maKH').value;
    var tenKH = document.getElementById('tenKH').value;
    var sdt = document.getElementById('sdt').value;
    var diaChi = document.getElementById('diaChi').value;

    // Gửi AJAX (sử dụng fetch API) để cập nhật thông tin
    fetch('update_address.php', {
        method: 'POST',
        body: new URLSearchParams({
            'maKH': maKH,
            'tenKH': tenKH,
            'sdt': sdt,
            'diaChi': diaChi
        }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Hiển thị thông báo kết quả từ PHP
        if (data === "Cập nhật thông tin thành công!") {
            // Cập nhật thông tin hiển thị ngay trong modal
            document.getElementById('tenKH').value = tenKH;
            document.getElementById('sdt').value = sdt;
            document.getElementById('diaChi').value = diaChi;
        }
    })
    .catch(error => {
        alert("Lỗi: " + error);
    });
});

</script>

<script>
const debounce = (func, delay) => {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
};

const goongApiKey = 'YZzBVkRATVtmDRw9OC4P0p0KWiRDgyRZuyTRAj6u'; // Thay bằng API Key của bạn

// Hàm tìm kiếm gợi ý địa chỉ từ Goong Maps API
async function searchLocation() {
    const query = document.getElementById('diaChi').value;
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
                suggestion.style.padding = '5px';
                suggestion.style.cursor = 'pointer';
                suggestion.textContent = place.description;

                suggestion.onclick = () => {
                    document.getElementById('diaChi').value = place.description;
                    suggestionsContainer.innerHTML = ''; // Xóa gợi ý sau khi chọn
                    suggestionsContainer.style.display = 'none';
                };

                suggestionsContainer.appendChild(suggestion);
            });
        }
    } catch (error) {
        console.error('Lỗi khi tìm kiếm địa chỉ:', error);
    }
}

// Hàm giảm tần suất gọi API
const debouncedSearchLocation = debounce(searchLocation, 500); // 500ms trì hoãn

// Gán sự kiện "oninput" cho ô nhập địa chỉ
document.getElementById('diaChi').addEventListener('input', debouncedSearchLocation);
</script>


</body>
</html>
