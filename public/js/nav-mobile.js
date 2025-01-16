let navMenuBtn = document.getElementById('menu-btn');
let mobileMenu = document.querySelector('.nav-mobile .nav-mobile-links');

navMenuBtn.addEventListener('click', () =>{
    if(navMenuBtn.classList.contains('fa-bars')){
        navMenuBtn.classList.remove('fa-bars');
        navMenuBtn.classList.add('fa-xmark');
        mobileMenu.classList.add('active');
    }else if(navMenuBtn.classList.contains('fa-xmark')){
        navMenuBtn.classList.remove('fa-xmark');
        navMenuBtn.classList.add('fa-bars');
        mobileMenu.classList.remove('active');
    }
})