let nextButton = document.getElementById('next');
let prevButton = document.getElementById('prev');
let carousel = document.querySelector('.carousel');
let listHTML = document.querySelector('.carousel .list');
let seeMoreButtons = document.querySelectorAll('.seeMore');
let backButton = document.getElementById('back');

nextButton.onclick = function() {
    showSlider('next');
}
prevButton.onclick = function() {
    showSlider('prev');
}

let unAcceppClick;
let autoSlideInterval; // Biến để lưu ID của interval
const autoSlideTime = 3000; // Thời gian tự động chuyển slide (5000ms = 5 giây)

const showSlider = (type) => {
    // Dừng interval tự động chuyển slide mỗi khi hàm được gọi
    clearInterval(autoSlideInterval);

    nextButton.style.pointerEvents = 'none';
    prevButton.style.pointerEvents = 'none';

    carousel.classList.remove('next', 'prev');
    let items = document.querySelectorAll('.carousel .list .item');
    if (type === 'next') {
        listHTML.appendChild(items[0]);
        carousel.classList.add('next');
    } else {
        listHTML.prepend(items[items.length - 1]);
        carousel.classList.add('prev');
    }

    clearTimeout(unAcceppClick);
    unAcceppClick = setTimeout(() => {
        nextButton.style.pointerEvents = 'auto';
        prevButton.style.pointerEvents = 'auto';

        // Khởi động lại interval tự động sau khi hiệu ứng hoàn tất
        autoSlideInterval = setInterval(() => {
            showSlider('next');
        }, autoSlideTime);
    }, 2000); // Thời gian này (2000ms) là thời gian chờ cho hiệu ứng slide
}

backButton.onclick = function() {
    carousel.classList.remove('showDetail');
}

// Khởi động tự động chuyển slide lần đầu tiên
autoSlideInterval = setInterval(() => {
    showSlider('next');
}, autoSlideTime);
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    // Toggle Mobile Menu
    mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when a link is clicked (for smooth scrolling UX)
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (!mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });
    });

    // Optional: Header background change on scroll (for sticky header effect)
    const navbar = document.getElementById('clx_navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.remove('clx_header_main');
            navbar.classList.add('shadow-chronos-glow', 'bg-chronos-dark/95');
        } else {
            navbar.classList.add('clx_header_main');
            navbar.classList.remove('shadow-chronos-glow', 'bg-chronos-dark/95');
        }
    });
});