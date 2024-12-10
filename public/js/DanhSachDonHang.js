document.getElementById('addOrderButton').addEventListener('click', function () {
  const maDonHang = 'DH' + Math.floor(Math.random() * (999999 - 100000 + 1) + 100000);
  const url = `index.php?controller=donhang&action=hienThiTrangThemDonHang&maDonHang=${maDonHang}`;
  window.open(url, '_blank');
});


function suaDonHangButton(maDonHang) { 
  const url = `index.php?controller=donhang&action=hienThiTrangSuaDonHang&maDonHang=${maDonHang}`;
  window.open(url, '_blank');
}


// Hiển thị chi tiết đơn hàng
function loadOrderDetails(maDonHang) {
  $.ajax({
    url: "index.php?controller=donhang&action=layChiTietDonHang",
    method: "GET",
    data: { maDonHang: maDonHang },
    contentType: "application/json",
    dataType: "json",
    success: function (response) {
      try {
        if (response.success) {
          const orderDetails = response.orderDetails;
          const orderInfo = response.orderInfo;
          const orderDetailsTableBody = $("#orderDetailsTableBody");
          orderDetailsTableBody.empty();

          orderDetails.forEach((item, index) => {
            orderDetailsTableBody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.tenMonAn}</td>
                                <td>${formatCurrency(item.donGia)} VNĐ</td>
                                <td style="padding-left:35px;">${
                                  item.soLuong
                                }</td>
                                <td>${formatCurrency(item.thanhTien)} VNĐ</td>
                            </tr>
                        `);
          });

          $("#phuongThucThanhToanSpan").text(orderInfo.phuongThucThanhToan);
          $("#ghiChuSpan").text(orderInfo.ghiChu || "Không có");
          $("#khoangCachGiaoHangSpan").text(orderInfo.khoangCachGiaoHang || 0);
          $("#phiShipSpan").text(formatCurrency(orderInfo.phiShip));
          $("#tongTienCongTruSpan").text(
            formatCurrency(orderInfo.tongTienCongTru)
          );
          $("#tongTienSpan").text(formatCurrency(orderInfo.tongTien));
          $("#trangThaiThanhToanSpan").text(orderInfo.trangThaiThanhToan);
          $("#trangThaiDonHangSpan").text(orderInfo.trangThaiDonHang);
        } else {
          alert(response.message || "Không thể lấy thông tin đơn hàng.");
        }
      } catch (error) {
        console.error("Lỗi phân tích JSON:", error);
        alert("Phản hồi từ server không hợp lệ.");
      }
    },
    error: function (xhr) {
      console.error("Lỗi khi tải chi tiết đơn hàng:", xhr.responseText);
      alert("Không thể lấy thông tin đơn hàng.");
    },
  });
}

function formatCurrency(amount) {
  return new Intl.NumberFormat("de-DE").format(amount);
}

// Xác nhận đơn hàng
function xacNhanDonHang(maDonHang) {
  Swal.fire({
    title: "Xác nhận đơn hàng này?",
    text: "Hãy đọc kỹ thông tin đơn hàng trước khi xác nhận đơn hàng!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#009933",
    cancelButtonColor: "#d33",
    confirmButtonText: "Xác nhận",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?controller=donhang&action=xacNhanDonHang",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({ maDonHang: maDonHang }),
        success: function (response) {
          try {
            if (response.success === true) {
              Swal.fire({
                title: "Thành công!",
                text: `Xác nhận đơn hàng '${maDonHang}' thành công!`,
                icon: "success", // Các loại icon: 'success', 'error', 'warning', 'info', 'question'
                confirmButtonText: "OK",
              }).then(() => {
                location.reload();
              });
            } else {
              alert(response.message || "Không thể xác nhận đơn hàng.");
            }
          } catch (error) {
            console.error("Lỗi phân tích JSON:", error);
            alert("Phản hồi từ server không hợp lệ.");
          }
        },
        error: function (xhr) {
          console.error("Lỗi khi xác nhận đơn hàng:", xhr.responseText);
          alert("Không thể xác nhận đơn hàng.");
        },
      });
    } else {
      return;
    }
  });
}

// Từ chối và hủy đơn hàng
function hienFormTuChoi(maDonHang) {
  document.getElementById("maDonHangTuChoi").value = maDonHang;
  const modal = new bootstrap.Modal(document.getElementById("lyDoTuChoiModal"));
  modal.show();

  const lyDoTuChoi = document.getElementById("lyDoTuChoi");
  const lyDoKhacGroup = document.getElementById("lyDoKhacGroup");
  lyDoTuChoi.addEventListener("change", function () {
    if (lyDoTuChoi.value === "Khác") {
      lyDoKhacGroup.style.display = "block";
    } else {
      lyDoKhacGroup.style.display = "none";
    }
  });
}

function xacNhanTuChoi() {
  const maDonHang = document.getElementById("maDonHangTuChoi").value;
  const lyDoTuChoi = document.getElementById("lyDoTuChoi").value;
  const lyDoKhac = document.getElementById("lyDoKhac").value;
  if (!lyDoTuChoi) {
    Swal.fire({
      title: "Thông báo!",
      text: `Vui lòng chọn lý do từ chối.`,
      icon: "warning", // Các loại icon: 'success', 'error', 'warning', 'info', 'question'
      confirmButtonText: "OK",
    }).then(() => {});
    return;
  }
  const lyDo = lyDoTuChoi === "Khác" ? lyDoKhac : lyDoTuChoi;
  Swal.fire({
    title: "Bạn có chắc chắn?",
    text: "Hành động này sẽ thực hiện thay đổi!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#009933",
    cancelButtonColor: "#d33",
    confirmButtonText: "Xác nhận",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "index.php?controller=donhang&action=huyDonHang",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({ maDonHang: maDonHang, lyDo: lyDo }),
        success: function (response) {
          try {
            if (response.success === true) {
              Swal.fire({
                title: "Thành công!",
                text: `Từ chối đơn hàng '${maDonHang}' thành công!`,
                icon: "success", // Các loại icon: 'success', 'error', 'warning', 'info', 'question'
                confirmButtonText: "OK",
              }).then(() => {
                location.reload();
              });
            } else {
              alert(response.message || "Không thể từ chối đơn hàng.");
            }
          } catch (error) {
            console.error("Lỗi phân tích JSON:", error);
            alert("Phản hồi từ server không hợp lệ.");
          }
        },
        error: function (xhr) {
          console.error("Lỗi khi từ chối đơn hàng:", xhr.responseText);
          alert("Không thể từ chối đơn hàng.");
        },
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      return;
    }
  });
}


// Cập nhật trạng thái cho đơn hàng bình thường
function capNhatTTDonHang(maDonHang, trangThaiDonHang) {
  if (trangThaiDonHang == 'Đang chuẩn bị') {
    Swal.fire({
      title: `Đơn hàng ${maDonHang} đã được chuẩn bị xong? Giao hàng ngay?`,
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#009933",
      cancelButtonColor: "#d33",
      confirmButtonText: "Xác nhận",
      cancelButtonText: "Hủy",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "index.php?controller=donhang&action=capNhatTTDonHang",
          method: "POST",
          contentType: "application/json",
          data: JSON.stringify({
            maDonHang: maDonHang,
            trangThaiDonHang: trangThaiDonHang,
          }),
          success: function (response) {
            try {
              if (response.success === true) {
                Swal.fire({
                  title: "Thành công!",
                  text: `Đơn hàng ${maDonHang} đã được giao cho Shipper!`,
                  icon: "success", // Các loại icon: 'success', 'error', 'warning', 'info', 'question'
                  confirmButtonText: "OK",
                }).then(() => {
                  location.reload();
                });
              } else {
                alert(
                  response.message || "Không thể cập nhật trạng thái đơn hàng."
                );
              }
            } catch (error) {
              console.error("Lỗi phân tích JSON:", error);
              alert("Phản hồi từ server không hợp lệ.");
            }
          },
          error: function (xhr) {
            console.error(
              "Lỗi khi cập nhật trạng thái đơn hàng:",
              xhr.responseText
            );
            alert("Không thể cập nhật trạng thái đơn hàng.");
          },
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        return;
      }
    });
  }else {
    Swal.fire({
      title: `Xác nhận hoàn thành đơn hàng ${maDonHang}?`,
      text: "Hãy kiểm tra kỹ trước khi thực hiện thao tác này!",  
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#009933",
      cancelButtonColor: "#d33",
      confirmButtonText: "Xác nhận",
      cancelButtonText: "Hủy",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "index.php?controller=donhang&action=capNhatTTDonHang",
          method: "POST",
          contentType: "application/json",
          data: JSON.stringify({
            maDonHang: maDonHang,
            trangThaiDonHang: trangThaiDonHang,
          }),
          success: function (response) {
            try {
              if (response.success === true) {
                Swal.fire({
                  title: "Thành công!",
                  text: `Đơn hàng ${maDonHang} đã hoàn thành!`,
                  icon: "success", // Các loại icon: 'success', 'error', 'warning', 'info', 'question'
                  confirmButtonText: "OK",
                }).then(() => {
                  location.reload();
                });
              } else {
                alert(
                  response.message || "Không thể cập nhật trạng thái đơn hàng."
                );
              }
            } catch (error) {
              console.error("Lỗi phân tích JSON:", error);
              alert("Phản hồi từ server không hợp lệ.");
            }
          },
          error: function (xhr) {
            console.error(
              "Lỗi khi cập nhật trạng thái đơn hàng:",
              xhr.responseText
            );
            alert("Không thể cập nhật trạng thái đơn hàng.");
          },
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        return;
      }
    });
  }
}
