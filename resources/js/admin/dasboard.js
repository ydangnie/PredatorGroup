document.addEventListener('DOMContentLoaded', function() {
    // Xóa logic khởi tạo biểu đồ cứng để chỉ sử dụng logic động trong dasboard.blade.php

    // Navigation active state
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Mobile menu toggle
    const menuBtn = document.createElement('div');
    menuBtn.className = 'icon-btn';
    menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
    menuBtn.style.position = 'fixed';
    menuBtn.style.top = '1rem';
    menuBtn.style.left = '1rem';
    menuBtn.style.zIndex = '1000';
    menuBtn.style.display = 'none';

    if (window.innerWidth <= 768) {
        document.body.appendChild(menuBtn);
        menuBtn.style.display = 'flex';
    }

    menuBtn.addEventListener('click', () => {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) sidebar.classList.toggle('active');
    });

    window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth <= 768) {
            menuBtn.style.display = 'flex';
        } else {
            menuBtn.style.display = 'none';
            if (sidebar) sidebar.classList.remove('active');
        }
    });

    // Animate stats
    document.querySelectorAll('.stat-value').forEach(stat => {
        const finalValue = stat.textContent;
        stat.textContent = '0';
        setTimeout(() => { stat.textContent = finalValue; }, 300);
    });

    // Đưa hàm toggleSubMenu ra window để HTML gọi được (vì dùng onclick="")
    window.toggleSubMenu = function(navItem) {
        const subMenu = navItem.nextElementSibling;
        const arrow = navItem.querySelector('.nav-arrow');
        if (subMenu && subMenu.classList.contains('sub-nav-container')) {
            if (subMenu.style.display === "none" || subMenu.style.display === "") {
                subMenu.style.display = "block";
                navItem.classList.add('active');
                if (arrow) arrow.style.transform = "rotate(180deg)";
            } else {
                subMenu.style.display = "none";
                navItem.classList.remove('active');
                if (arrow) arrow.style.transform = "rotate(0deg)";
            }
        }
    };

    window.toggleBanner = function() {
        var bannerContent = document.getElementById('banner-content');
        if (bannerContent) {
            bannerContent.style.display = (bannerContent.style.display === 'none') ? 'block' : 'none';
        }
    };
});