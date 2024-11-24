<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sử dụng Goong Maps API</title>
    <style>
        #suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
        }
        .suggestion {
            padding: 5px;
            cursor: pointer;
        }
        .suggestion:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>Tìm kiếm địa chỉ và tính khoảng cách</h1>

    <!-- Điểm 1 cố định -->
    <p>Điểm thứ nhất (Hà Nội): Kinh độ 21.039966, Vĩ độ 105.741885</p>

    <!-- Input nhập địa chỉ -->
    <label for="address">Nhập địa chỉ:</label>
    <input type="text" id="address" placeholder="Nhập địa chỉ" oninput="searchLocation()">
    <div id="suggestions"></div>

    <!-- Khoảng cách -->
    <p id="distance"></p>

    <script>
    const debounce = (func, delay) => {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const point1 = { lat: 21.039966, lon: 105.741885 }; // Hà Nội
    const goongApiKey = 'YZzBVkRATVtmDRw9OC4P0p0KWiRDgyRZuyTRAj6u'; // Thay bằng API Key của bạn

    // Hàm tìm kiếm gợi ý địa chỉ từ Goong Maps API
    async function searchLocation() {
        const query = document.getElementById('address').value;
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
                        document.getElementById('address').value = place.description;
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
    document.getElementById('address').oninput = debouncedSearchLocation;

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

        const distance = R * c; // Khoảng cách tính bằng km

        // Hiển thị khoảng cách
        document.getElementById('distance').textContent = `Khoảng cách giữa hai điểm: ${distance.toFixed(2)} km`;
    }

    // Hàm chuyển đổi độ sang radian
    function toRadians(degrees) {
        return degrees * (Math.PI / 180);
    }
</script>

</body>
</html>
