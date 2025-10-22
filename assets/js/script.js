let navbar = document.querySelector('.header .flex .navbar');
let userBox = document.querySelector('.header .flex .user-box'); // додали userBox

document.querySelector('#menu-btn').onclick = () => {
    if (navbar) navbar.classList.toggle('active');
    if (userBox) userBox.classList.remove('active');
}

window.onscroll = () => {
    if (userBox) userBox.classList.remove('active');
    if (navbar) navbar.classList.remove('active');
}
