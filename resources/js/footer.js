function handleNewsletter(event) {
    event.preventDefault();
    const btn = event.target.querySelector('.pw-newsletter-btn');
    const btnText = document.getElementById('btnText');
    const input = event.target.querySelector('.pw-newsletter-input');

    // Loading state
    btnText.innerHTML = '<div class="pw-loading-dots"><span></span><span></span><span></span></div>';
    btn.disabled = true;

    // Simulate API call
    setTimeout(() => {
        btnText.textContent = '✓ Đã đăng ký';
        btn.style.background = 'linear-gradient(135deg, #1a1a1a 0%, #0a0a0a 100%)';
        input.value = '';

        // Reset after 3 seconds
        setTimeout(() => {
            btnText.textContent = 'Gửi';
            btn.style.background = 'linear-gradient(135deg, #333333 0%, #111111 100%)';
            btn.disabled = false;
        }, 3000);
    }, 1500);
}

// Add smooth scroll behavior
document.querySelectorAll('.pw-footer-links a').forEach(link => {
    link.addEventListener('click', function(e) {
        if (this.getAttribute('href').startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
});

// Add parallax effect to decorations
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const decorations = document.querySelectorAll('.pw-footer-decoration');

    decorations.forEach((decoration, index) => {
        const speed = index === 0 ? 0.5 : 0.3;
        decoration.style.transform = `translateY(${scrolled * speed}px)`;
    });
});
// 1. Hàm hiển thị thông báo
function showToast(message, type = 'success') {
    const toast = document.getElementById("custom-toast");
    const text = document.getElementById("toast-text");
    const icon = document.getElementById("toast-icon");

    if (!toast) return;

    text.innerText = message;
    if (type === 'removed') {
        icon.className = "fa-solid fa-trash-can";
        icon.style.color = "#ef4444";
    } else {
        icon.className = "fa-solid fa-check-circle";
        icon.style.color = "#22c55e";
    }

    toast.classList.add("show");
    setTimeout(() => { toast.classList.remove("show"); }, 3000);
}

// 2. Hàm xử lý Click Tim (Dùng chung)
function toggleWishlist(event, productId) {
    event.preventDefault();
    event.stopPropagation();

    // Tìm thẻ icon bên trong nút bấm
    const btn = event.currentTarget;
    const icon = btn.querySelector('i');

    fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401 || response.status === 419) {
                if (confirm('Bạn cần đăng nhập để sử dụng tính năng này. Đăng nhập ngay?')) {
                    window.location.href = '/dangnhap';
                }
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;

            // Cập nhật số lượng trên Header (nếu có)
            const badge = document.getElementById('wishlist-count-badge');
            let currentCount = badge && badge.innerText ? parseInt(badge.innerText) : 0;

            if (data.status === 'added') {
                // Đổi icon tim đỏ
                if (icon) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    icon.style.color = '#ef4444';
                }
                // Tăng số lượng
                if (badge) {
                    badge.innerText = currentCount + 1;
                    badge.style.display = 'inline-block';
                }
                showToast(data.message, 'added');

            } else if (data.status === 'removed') {
                // Đổi icon tim rỗng
                if (icon) {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    icon.style.color = '#ccc';
                }
                // Giảm số lượng
                if (badge) {
                    let newCount = currentCount - 1;
                    badge.innerText = newCount > 0 ? newCount : 0;
                    if (newCount <= 0) badge.style.display = 'none';
                }
                showToast(data.message, 'removed');
            }
        })
        .catch(error => console.error('Lỗi:', error));
}
// Hàm hiển thị thông báo (Global function)
function showToast(message, type = 'success') {
    const toast = document.getElementById("custom-toast");
    const text = document.getElementById("toast-text");
    const icon = document.getElementById("toast-icon");

    if (!toast) return;

    text.innerText = message;

    // Đổi màu icon dựa trên loại thông báo
    if (type === 'removed') {
        icon.className = "fa-solid fa-trash-can";
        icon.style.color = "#ef4444"; // Đỏ
    } else {
        icon.className = "fa-solid fa-check-circle";
        icon.style.color = "#22c55e"; // Xanh lá
    }

    toast.classList.add("show");

    // Tự ẩn sau 3 giây
    setTimeout(function() {
        toast.classList.remove("show");
    }, 3000);
}