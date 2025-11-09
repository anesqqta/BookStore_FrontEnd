let navbar = document.querySelector('.header .flex .navbar');
let userBox = document.querySelector('.header .flex .user-box'); 

document.querySelector('#menu-btn').onclick = () => {
    if (navbar) navbar.classList.toggle('active');
    if (userBox) userBox.classList.remove('active');
}

window.onscroll = () => {
    if (userBox) userBox.classList.remove('active');
    if (navbar) navbar.classList.remove('active');
}

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.querySelector('.filter-toggle');
    const dropdown = document.querySelector('.filter-dropdown');

    if (toggleBtn && dropdown) {
        toggleBtn.addEventListener('click', () => {
            if(dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'block';
            }
        });
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target) && !toggleBtn.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
   console.log("Profile page loaded");
});
