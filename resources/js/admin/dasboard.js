document.addEventListener('DOMContentLoaded', function() {
    // Chỉ chạy code khi tìm thấy biểu đồ
    const canvas = document.getElementById('revenueChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(192, 192, 192, 0.3)');
        gradient.addColorStop(1, 'rgba(192, 192, 192, 0.01)');

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                datasets: [{
                    label: 'Doanh Thu (Triệu VNĐ)',
                    data: [125, 189, 156, 234, 198, 267, 245],
                    borderColor: '#c0c0c0',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#c0c0c0',
                    pointBorderColor: '#1a1a1a',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#c0c0c0',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(28, 28, 28, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#c0c0c0',
                        borderColor: '#3a3a3a',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString('vi-VN') + ' triệu ₫';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#2a2a2a', drawBorder: false },
                        ticks: {
                            color: '#9a9a9a',
                            callback: function(value) { return value + 'M'; }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#9a9a9a' }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        // Filter buttons logic...
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Update chart logic here (như cũ)...
                // (Giữ nguyên logic update chart của bạn)
            });
        });
    }

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