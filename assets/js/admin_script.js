document.addEventListener("DOMContentLoaded", function () {

   const navbar = document.querySelector('.header .navbar');
   const userBox = document.querySelector('.header .account-box');
   const menuBtn = document.querySelector('#menu-btn');
   const userBtn = document.querySelector('#user-btn');

   if(menuBtn){
      menuBtn.addEventListener('click', () => {
         navbar.classList.toggle('active');
         if(userBox) userBox.classList.remove('active');
      });
   }

   if(userBtn){
      userBtn.addEventListener('click', () => {
         userBox.classList.toggle('active');
         if(navbar) navbar.classList.remove('active');
      });
   }

   window.addEventListener('scroll', () => {
      if(navbar) navbar.classList.remove('active');
      if(userBox) userBox.classList.remove('active');
   });

   window.addEventListener('resize', () => {
      if(window.innerWidth > 768){
         if(navbar) navbar.classList.remove('active');
      }
   });

});