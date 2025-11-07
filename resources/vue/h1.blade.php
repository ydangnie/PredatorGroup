<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Về Chúng Tôi - PredatorWatch</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;500;600&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 50%, #0f0f0f 100%);
            color: #e0e0e0;
            overflow-x: hidden;
        }

        .predator-font {
            font-family: 'Orbitron', monospace;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glow {
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.1);
        }

        .text-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 255, 255, 0.15);
        }

        .gradient-text {
            background: linear-gradient(45deg, #ffffff, #a0a0a0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .mystery-border {
            position: relative;
            overflow: hidden;
        }

        .mystery-border::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Header -->
        <header class="fixed w-full z-50 glass-effect">
            <nav class="container mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="predator-font text-2xl font-bold gradient-text">
                        <i class="fas fa-clock mr-2"></i>PredatorWatch
                    </div>
                    <div class="hidden md:flex space-x-8">
                        <a href="#" class="hover:text-white transition-colors">Trang Chủ</a>
                        <a href="#" class="text-white font-medium">Về Chúng Tôi</a>
                        <a href="#" class="hover:text-white transition-colors">Sản Phẩm</a>
                        <a href="#" class="hover:text-white transition-colors">Liên Hệ</a>
                    </div>
                    <div class="md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center relative pt-20">
            <div class="absolute inset-0 opacity-10">
                <div class="floating absolute top-20 left-20">
                    <i class="fas fa-clock text-9xl text-gray-500"></i>
                </div>
                <div class="floating absolute bottom-20 right-20" style="animation-delay: -2s;">
                    <i class="fas fa-cog text-6xl text-gray-600"></i>
                </div>
                <div class="floating absolute top-1/2 left-10" style="animation-delay: -4s;">
                    <i class="fas fa-gem text-7xl text-gray-500"></i>
                </div>
            </div>
            
            <div class="container mx-auto px-6 text-center z-10">
                <h1 class="predator-font text-6xl md:text-8xl font-black mb-6 gradient-text text-glow fade-in">
                    PREDATOR<span class="text-white">WATCH</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-300 fade-in" style="animation-delay: 0.3s;">
                    Khám phá thế giới đồng hồ cao cấp với thiết kế bí ẩn và đẳng cấp
                </p>
                <div class="fade-in" style="animation-delay: 0.6s;">
                    <button class="glass-effect px-8 py-4 rounded-full hover:glow transition-all duration-300 mystery-border">
                        <span class="predator-font font-bold">Khám Phá Ngay</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="py-20">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="predator-font text-4xl md:text-5xl font-bold mb-6 gradient-text">
                        Câu Chuyện Của Chúng Tôi
                    </h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-white to-gray-500 mx-auto"></div>
                </div>

                <div class="grid md:grid-cols-2 gap-16 items-center">
                    <div class="fade-in">
                        <h3 class="text-3xl font-bold mb-6 text-white">Tinh Hoa Thời Gian</h3>
                        <p class="text-lg text-gray-300 mb-6 leading-relaxed">
                            PredatorWatch được thành lập với tầm nhìn tạo ra những chiếc đồng hồ không chỉ đo thời gian, 
                            mà còn là biểu tượng của sự hoàn hảo và bí ẩn. Chúng tôi kết hợp công nghệ tiên tiến với 
                            nghệ thuật chế tác truyền thống.
                        </p>
                        <p class="text-lg text-gray-300 leading-relaxed">
                            Mỗi chiếc đồng hồ PredatorWatch được thiết kế dành cho những người không chỉ muốn biết giờ, 
                            mà muốn sở hữu một tác phẩm nghệ thuật trên cổ tay.
                        </p>
                    </div>
                    <div class="relative">
                        <div class="glass-effect p-8 rounded-2xl card-hover mystery-border">
                            <div class="text-center">
                                <i class="fas fa-clock text-6xl text-white mb-6 pulse"></i>
                                <h4 class="predator-font text-2xl font-bold text-white mb-4">Độ Chính Xác Tuyệt Đối</h4>
                                <p class="text-gray-300">Công nghệ Swiss Movement với độ chính xác đến từng giây</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="py-20 bg-gradient-to-b from-transparent to-black/30">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="predator-font text-4xl font-bold mb-6 gradient-text">Giá Trị Cốt Lõi</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="glass-effect p-8 rounded-2xl card-hover text-center mystery-border" v-for="(value, index) in values" :key="index">
                        <div class="mb-6">
                            <i :class="value.icon + ' text-5xl text-white'"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4">{{ value.title }}</h3>
                        <p class="text-gray-300">{{ value.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="py-20">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="predator-font text-4xl font-bold mb-6 gradient-text">Đội Ngũ Chuyên Gia</h2>
                    <p class="text-xl text-gray-300">Những bậc thầy về nghệ thuật chế tác đồng hồ</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="glass-effect p-6 rounded-2xl card-hover text-center mystery-border" v-for="(member, index) in team" :key="index">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-600 to-gray-800 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <i :class="member.icon + ' text-2xl text-white'"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ member.name }}</h3>
                        <p class="text-gray-400 mb-4">{{ member.position }}</p>
                        <p class="text-sm text-gray-300">{{ member.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-gradient-to-r from-black/50 to-gray-900/50">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8">
                    <div class="text-center" v-for="(stat, index) in stats" :key="index">
                        <div class="predator-font text-4xl md:text-5xl font-black text-white mb-2">
                            {{ stat.number }}
                        </div>
                        <p class="text-gray-300 uppercase tracking-wider">{{ stat.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact CTA -->
        <section class="py-20">
            <div class="container mx-auto px-6 text-center">
                <div class="glass-effect p-12 rounded-3xl mystery-border max-w-4xl mx-auto">
                    <h2 class="predator-font text-3xl font-bold mb-6 gradient-text">
                        Sẵn Sàng Sở Hữu Thời Gian?
                    </h2>
                    <p class="text-xl text-gray-300 mb-8">
                        Liên hệ với chúng tôi để khám phá bộ sưu tập độc quyền
                    </p>
                    <button class="glass-effect px-8 py-4 rounded-full hover:glow transition-all duration-300 mr-4">
                        <i class="fas fa-phone mr-2"></i>
                        <span class="font-semibold">Gọi Ngay</span>
                    </button>
                    <button class="bg-white text-black px-8 py-4 rounded-full hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-envelope mr-2"></i>
                        <span class="font-semibold">Email</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-black/80 py-12">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <div class="predator-font text-2xl font-bold gradient-text mb-4">
                            <i class="fas fa-clock mr-2"></i>PredatorWatch
                        </div>
                        <p class="text-gray-400">Đồng hồ cao cấp cho những người đam mê hoàn hảo</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-4">Liên Kết</h4>
                        <div class="space-y-2">
                            <a href="#" class="block text-gray-400 hover:text-white transition-colors">Trang Chủ</a>
                            <a href="#" class="block text-gray-400 hover:text-white transition-colors">Về Chúng Tôi</a>
                            <a href="#" class="block text-gray-400 hover:text-white transition-colors">Sản Phẩm</a>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-4">Dịch Vụ</h4>
                        <div class="space-y-2">
                            <a href="#" class="block text-gray-400 hover:text-white transition-colors">Bảo Hành</a>
                            <a href="#" class="block text-gray-400 hover:text-white transition-colors">Sửa Chữa</a>
                            <a href="#" class="block text-gray-400 hover:text-white transition-colors">Tư Vấn</a>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-4">Theo Dõi</h4>
                        <div class="flex space-x-4">
                            <i class="fab fa-facebook text-2xl text-gray-400 hover:text-white cursor-pointer transition-colors"></i>
                            <i class="fab fa-instagram text-2xl text-gray-400 hover:text-white cursor-pointer transition-colors"></i>
                            <i class="fab fa-youtube text-2xl text-gray-400 hover:text-white cursor-pointer transition-colors"></i>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                    <p class="text-gray-500">&copy; 2024 PredatorWatch. Tất cả quyền được bảo lưu.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    values: [
                        {
                            icon: 'fas fa-gem',
                            title: 'Chất Lượng Cao Cấp',
                            description: 'Chỉ sử dụng vật liệu tốt nhất từ khắp nơi trên thế giới'
                        },
                        {
                            icon: 'fas fa-cogs',
                            title: 'Công Nghệ Tiên Tiến',
                            description: 'Kết hợp hoàn hảo giữa truyền thống và hiện đại'
                        },
                        {
                            icon: 'fas fa-shield-alt',
                            title: 'Bảo Hành Trọn Đời',
                            description: 'Cam kết chất lượng với dịch vụ bảo hành vượt trội'
                        }
                    ],
                    team: [
                        {
                            name: 'Master Chen',
                            position: 'Trưởng Phòng Thiết Kế',
                            icon: 'fas fa-drafting-compass',
                            description: '20 năm kinh nghiệm trong nghệ thuật chế tác đồng hồ Swiss'
                        },
                        {
                            name: 'Sarah Williams',
                            position: 'Chuyên Gia Cơ Học',
                            icon: 'fas fa-cog',
                            description: 'Tiến sĩ Cơ khí chính xác, chuyên gia về movement cao cấp'
                        },
                        {
                            name: 'David Zhang',
                            position: 'Nghệ Nhân Khắc Chữ',
                            icon: 'fas fa-hammer',
                            description: 'Bậc thầy về nghệ thuật khắc chữ và trang trí truyền thống'
                        }
                    ],
                    stats: [
                        { number: '15+', label: 'Năm Kinh Nghiệm' },
                        { number: '50K+', label: 'Khách Hàng Hài Lòng' },
                        { number: '200+', label: 'Mẫu Thiết Kế' },
                        { number: '99%', label: 'Đánh Giá 5 Sao' }
                    ]
                }
            },
            mounted() {
                // Add scroll animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -100px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('fade-in');
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.card-hover').forEach(card => {
                    observer.observe(card);
                });
            }
        }).mount('#app');
    </script>
</body>
</html>