//search-form
let searchForm = document.querySelector('.search-form-container');

document.querySelector('#search-btn').onclick = () => {
    searchForm.classList.toggle('active');
    // cart.classList.remove('active');    
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}
//gio hang-demo
let cart = document.querySelector('.shopping-cart-container');

document.querySelector('#cart-btn').onclick = () => {
    // cart.classList.toggle('active');
    searchForm.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

// let loginForm = document.querySelector('.login-form-container');

// document.querySelector('#login-btn').onclick = () =>{
//     loginForm.classList.toggle('active');
//     searchForm.classList.remove('active');
//     cart.classList.remove('active');    
//     navbar.classList.remove('active');
// }

let navbar = document.querySelector('.header .navbar');

document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
    searchForm.classList.remove('active');
    // cart.classList.remove('active');    
    loginForm.classList.remove('active');
}

window.onscroll = () => {
    navbar.classList.remove('active');
}

document.querySelector('.home').onmousemove = (e) => {

    let x = (window.innerWidth - e.pageX * 2) / 90;
    let y = (window.innerHeight - e.pageY * 2) / 90;

    document.querySelector('.home .home-parallax-img').style.transform = `translateX(${y}px) translateY(${x}px)`;
}

document.querySelector('.home').onmouseleave = () => {

    document.querySelector('.home .home-parallax-img').style.transform = `translateX(0px) translateY(0px)`;
}

$(document).ready(() => {
    $('.btn_themMAGioHang').on('click', function () {
        const boxGioHang = $(this).closest('.box');
        let maMon = boxGioHang.data('mamon')
        addToCart(maMon)
    });
});
function getCookie(name) {
    let cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.startsWith(name + '=')) {
            return decodeURIComponent(cookie.substring(name.length + 1));
        }
    }
    return '[]';
}

// Hàm đặt cookie
function setCookie(name, value, days) {
    let date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${encodeURIComponent(value)};expires=${date.toUTCString()};path=/`;
}


function addToCart(product) {

    let found = false;
    const newProduct = { maMonAn: product };
    let cart = JSON.parse(getCookie('cart'));

    for (let i = 0; i < cart.length; i++) {
        if (cart[i].maMonAn == newProduct.maMonAn) {
            cart[i].soLuong += 1;
            found = true;
            break;
        }
    }

    if (!found) {
        newProduct['soLuong'] = 1;
        cart.push(newProduct);
    }
    setCookie('cart', JSON.stringify(cart), 1);
    Swal.fire({
        icon: 'success',
        title: 'Thêm món thành công!',
        timer: 1000,
        showConfirmButton: false
    }).then(() => {

    });
}
