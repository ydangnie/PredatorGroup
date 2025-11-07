<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Về Chúng Tôi - PredatorWatch</title>
    <!-- Include Tailwind CSS via CDN for modern styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Vue.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.min.js"></script>
    <style>
        /* Custom Fonts (Optional but enhances modern feel) */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700;900&display=swap');

        /* Base dark theme styling */
        :root {
            --color-dark-bg: #0a0a0a;
            --color-dark-secondary: #1a1a1a;
            --color-text-light: #e5e7eb;
            --color-accent: #ff4500; /* Deep Orange/Red accent for mystery/power */
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--color-dark-bg);
            color: var(--color-text-light);
            transition: background-color 0.5s, color 0.5s;
        }

        /* Custom Scrollbar for aesthetic */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--color-dark-secondary);
        }
        ::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-accent);
        }

        /* Custom component styling using Vue data/methods where appropriate, but static CSS here */
        .section-title {
            letter-spacing: 0.2em;
            text-shadow: 0 0 10px rgba(255, 69, 0, 0.5);
        }

        .card-hover {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(255, 69, 0, 0.3), 0 0 30px rgba(255, 69, 0, 0.2);
        }

        /* Background effect for hero section */
        .hero-bg {
            background-image: 
                linear-gradient(to bottom, rgba(10, 10, 10, 0.9), rgba(10, 10, 10, 0.7)),
                radial-gradient(circle at center, rgba(51, 65, 85, 0.1), rgba(10, 10, 10, 1)),
                url('https://images.unsplash.com/photo-1524804396411-30c93118c3a5?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>

<div id="app">
    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-[--color-dark-secondary] bg-opacity-95 shadow-lg backdrop-blur-sm border-b border-gray-800">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="#" class="text-2xl font-black tracking-widest uppercase text-[--color-accent]">
                    PredatorWatch
                </a>
                <!-- Desktop Menu (Hidden on Mobile for simplicity) -->
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="#mission" class="text-gray-300 hover:text-[--color-accent] px-3 py-2 rounded-md text-sm font-medium transition duration-300">Sứ Mệnh</a>
                    <a href="#story" class="text-gray-300 hover:text-[--color-accent] px-3 py-2 rounded-md text-sm font-medium transition duration-300">Câu Chuyện</a>
                    <a href="#values" class="text-gray-300 hover:text-[--color-accent] px-3 py-2 rounded-md text-sm font-medium transition duration-300">Giá Trị</a>
                    <a href="#contact" class="bg-[--color-accent] text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg hover:bg-orange-600 transition duration-300">Liên Hệ</a>
                </div>
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button @click="toggleMenu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[--color-accent]">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Panel (Conditional Rendering via Vue) -->
        <div v-if="isMobileMenuOpen" class="md:hidden border-t border-gray-800">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#mission" @click="isMobileMenuOpen = false" class="block text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-base font-medium transition duration-300">Sứ Mệnh</a>
                <a href="#story" @click="isMobileMenuOpen = false" class="block text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-base font-medium transition duration-300">Câu Chuyện</a>
                <a href="#values" @click="isMobileMenuOpen = false" class="block text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-base font-medium transition duration-300">Giá Trị</a>
                <a href="#contact" @click="isMobileMenuOpen = false" class="block text-white bg-[--color-accent] hover:bg-orange-600 px-3 py-2 rounded-md text-base font-medium mt-2 text-center">Liên Hệ</a>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section: Modern, Mysterious, High Impact -->
        <section class="hero-bg h-[80vh] flex items-center justify-center text-center relative overflow-hidden border-b-4 border-[--color-accent] shadow-2xl">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="relative z-10 max-w-4xl px-6">
                <h1 class="text-6xl sm:text-8xl font-extrabold mb-4 uppercase tracking-tighter leading-tight">
                    PredatorWatch
                </h1>
                <p class="text-xl sm:text-3xl font-light text-gray-300 mb-8 italic">
                    Đồng hồ được tạo ra để chinh phục thời gian.
                </p>
                <a href="#story" class="inline-block px-10 py-4 text-lg font-bold uppercase rounded-full 
                                       bg-transparent border-2 border-[--color-accent] text-[--color-accent] 
                                       shadow-xl hover:bg-[--color-accent] hover:text-white 
                                       transition duration-500 ease-in-out transform hover:scale-105">
                    Khám Phá Di Sản
                </a>
            </div>
        </section>

        <!-- Mission Section -->
        <section id="mission" class="py-20 sm:py-32 bg-[--color-dark-secondary]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <h2 class="text-4xl sm:text-5xl font-bold text-center mb-16 section-title text-white">
                    SỨ MỆNH CỦA CHÚNG TÔI
                </h2>
                
                <div class="grid md:grid-cols-3 gap-10">
                    <!-- Mission Card 1 -->
                    <div class="p-8 bg-black rounded-xl border-t-4 border-[--color-accent] shadow-2xl card-hover text-center">
                        <div class="text-5xl mb-4 text-[--color-accent]">
                            &#9889; <!-- Symbol for Precision -->
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-white">Độ Chính Xác Tuyệt Đối</h3>
                        <p class="text-gray-400">Mỗi chi tiết đều được chế tác với sai số gần như bằng không, đảm bảo thời gian bạn đo đếm luôn trung thực.</p>
                    </div>
                    <!-- Mission Card 2 -->
                    <div class="p-8 bg-black rounded-xl border-t-4 border-[--color-accent] shadow-2xl card-hover text-center">
                        <div class="text-5xl mb-4 text-[--color-accent]">
                            &#9998; <!-- Symbol for Craftsmanship -->
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-white">Nghệ Thuật Thủ Công</h3>
                        <p class="text-gray-400">Sự kết hợp giữa kỹ thuật chế tác truyền thống và vật liệu tiên tiến tạo nên kiệt tác đeo tay.</p>
                    </div>
                    <!-- Mission Card 3 -->
                    <div class="p-8 bg-black rounded-xl border-t-4 border-[--color-accent] shadow-2xl card-hover text-center">
                        <div class="text-5xl mb-4 text-[--color-accent]">
                            &#128274; <!-- Symbol for Endurance -->
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-white">Sức Bền Vượt Thời Gian</h3>
                        <p class="text-gray-400">Thiết kế mạnh mẽ, vật liệu chống chịu khắc nghiệt, PredatorWatch được tạo ra để tồn tại.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Story Section -->
        <section id="story" class="py-20 sm:py-32 bg-[--color-dark-bg]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Image/Visual side -->
                    <div class="relative order-2 lg:order-1">
                        <img src="https://images.unsplash.com/photo-1602447257669-e251b9038395?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                             alt="Chế tác đồng hồ PredatorWatch" 
                             class="w-full h-auto object-cover rounded-xl shadow-2xl border-4 border-gray-800 transform hover:scale-[1.01] transition duration-500">
                        <!-- Overlay detail -->
                        <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-gradient-to-t from-black to-transparent"></div>
                    </div>
                    
                    <!-- Text content side -->
                    <div class="order-1 lg:order-2">
                        <p class="text-lg font-semibold text-[--color-accent] mb-3 tracking-widest uppercase">
                            Di Sản & Tầm Nhìn
                        </p>
                        <h2 class="text-5xl sm:text-6xl font-extrabold mb-8 leading-tight text-white">
                            Câu Chuyện Đằng Sau Mỗi Chiếc Đồng Hồ
                        </h2>
                        <p class="text-gray-300 mb-6 text-lg leading-relaxed">
                            PredatorWatch không chỉ là một thương hiệu; đó là một triết lý. Ra đời từ niềm đam mê mãnh liệt với sự chính xác cơ khí và thẩm mỹ tối giản nhưng mạnh mẽ, chúng tôi tin rằng thời gian là tài sản quý giá nhất.
                        </p>
                        <p class="text-gray-400 mb-8 text-lg leading-relaxed border-l-4 border-gray-700 pl-4 italic">
                            "Chúng tôi không chế tạo đồng hồ, chúng tôi tạo ra công cụ để đo lường những khoảnh khắc quyết định." - Nhà sáng lập.
                        </p>
                        <a href="#" class="text-[--color-accent] font-bold hover:text-white transition duration-300 flex items-center group">
                            Tìm hiểu sâu hơn về quá trình thiết kế
                            <span class="ml-2 transition-transform group-hover:translate-x-1">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section (Interactive) -->
        <section id="values" class="py-20 sm:py-32 bg-[--color-dark-secondary]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <h2 class="text-4xl sm:text-5xl font-bold text-center mb-16 section-title text-white">
                    CÁC GIÁ TRỊ CỐT LÕI
                </h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="value in coreValues" :key="value.id" 
                         class="p-6 rounded-lg border border-gray-800 bg-black hover:bg-gray-900 transition duration-300 cursor-pointer shadow-xl"
                         :class="{'border-[--color-accent]': activeValue === value.id}"
                         @click="activeValue = value.id">
                        
                        <div class="text-3xl mb-3" :class="activeValue === value.id ? 'text-[--color-accent]' : 'text-gray-500'">
                            {{ value.icon }}
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-white">{{ value.title }}</h4>
                        <p class="text-sm text-gray-400 h-12">{{ value.description }}</p>
                    </div>
                </div>

                <!-- Detail Pop-up/Display based on selection -->
                <div v-if="selectedDetail" class="mt-12 p-8 rounded-xl bg-gray-900 border-l-8 border-[--color-accent] shadow-inner transition-opacity duration-500">
                    <h3 class="text-3xl font-bold mb-3 text-[--color-accent]">{{ selectedDetail.title }}</h3>
                    <p class="text-gray-300 text-lg">{{ selectedDetail.detail }}</p>
                </div>
                <div v-else class="mt-12 p-6 text-center bg-gray-800 rounded-lg border border-gray-700">
                    <p class="text-gray-400 italic">Nhấp vào một giá trị ở trên để xem chi tiết.</p>
                </div>

            </div>
        </section>

        <!-- Call to Action/Contact -->
        <section id="contact" class="py-20 bg-[--color-dark-bg] border-t border-gray-800">
            <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
                <h2 class="text-4xl sm:text-5xl font-extrabold mb-4 text-white">
                    Sẵn Sàng Cho Bước Tiếp Theo?
                </h2>
                <p class="text-xl text-gray-300 mb-10">
                    Đội ngũ chuyên gia của chúng tôi sẵn sàng tư vấn về bộ sưu tập độc quyền tiếp theo của bạn.
                </p>
                <button @click="showContactModal = true" class="px-10 py-4 text-xl font-bold uppercase rounded-full 
                                       bg-[--color-accent] text-white 
                                       shadow-2xl hover:bg-orange-600 
                                       transition duration-500 ease-in-out transform hover:scale-105">
                    Yêu Cầu Tư Vấn Riêng
                </button>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-[--color-dark-secondary] border-t border-gray-800 py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="text-2xl font-black tracking-widest uppercase text-[--color-accent] mb-3">
                PredatorWatch
            </div>
            <p class="text-sm text-gray-500">&copy; 2024 PredatorWatch. Tất cả quyền được bảo lưu. | Thiết kế bởi Chuyên Gia Web Hiện Đại.</p>
            <div class="mt-4 space-x-4">
                <a href="#" class="text-gray-400 hover:text-[--color-accent] transition">Chính Sách</a>
                <span class="text-gray-600">|</span>
                <a href="#" class="text-gray-400 hover:text-[--color-accent] transition">Điều Khoản</a>
            </div>
        </div>
    </footer>

    <!-- Contact Modal (Vue Conditional Rendering) -->
    <div v-if="showContactModal" class="fixed inset-0 bg-black bg-opacity-70 z-[100] flex items-center justify-center p-4" @click.self="showContactModal = false">
        <div class="bg-[--color-dark-secondary] p-8 rounded-xl w-full max-w-lg shadow-3xl border border-gray-700 transform transition-all duration-300 scale-100 border-t-8 border-[--color-accent]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-bold text-white">Đặt Lịch Tư Vấn</h3>
                <button @click="showContactModal = false" class="text-gray-400 hover:text-white text-2xl">&times;</button>
            </div>
            
            <form @submit.prevent="submitContact">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Tên Của Bạn</label>
                    <input type="text" id="name" v-model="contactForm.name" required
                           class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:ring-[--color-accent] focus:border-[--color-accent] text-white">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" id="email" v-model="contactForm.email" required
                           class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:ring-[--color-accent] focus:border-[--color-accent] text-white">
                </div>
                <div class="mb-6">
                    <label for="interest" class="block text-sm font-medium text-gray-300 mb-1">Mô Tả Nhu Cầu</label>
                    <textarea id="interest" v-model="contactForm.interest" rows="3" required
                              class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:ring-[--color-accent] focus:border-[--color-accent] text-white"></textarea>
                </div>
                
                <button type="submit" class="w-full py-3 bg-[--color-accent] text-white font-bold rounded-lg hover:bg-orange-600 transition duration-300 shadow-lg">
                    Gửi Yêu Cầu
                </button>
                <p v-if="submissionMessage" class="mt-3 text-center text-sm font-medium" :class="submissionSuccess ? 'text-green-400' : 'text-red-400'">{{ submissionMessage }}</p>
            </form>
        </div>
    </div>

</div>

<script>
    new Vue({
        el: '#app',
        data: {
            isMobileMenuOpen: false,
            activeValue: 1, // Default active value
            showContactModal: false,
            submissionMessage: '',
            submissionSuccess: false,
            contactForm: {
                name: '',
                email: '',
                interest: ''
            },
            coreValues: [
                { id: 1, icon: '&#9906;', title: 'Bí Ẩn & Độc Quyền', description: 'Mỗi mẫu là một câu chuyện độc nhất, không dành cho đám đông.' },
                { id: 2, icon: '&#128065;', title: 'Kiểm Soát Tuyệt Đối', description: 'Thiết kế lấy cảm hứng từ sự thống trị và khả năng làm chủ môi trường.' },
                { id: 3, icon: '&#128204;', title: 'Vật Liệu Cao Cấp', description: 'Chỉ sử dụng thép không gỉ cấp hàng không vũ trụ và kính sapphire chống trầy xước.' },
                { id: 4, icon: '&#128161;', title: 'Đổi Mới Không Ngừng', description: 'Luôn tiên phong trong công nghệ bộ máy và thẩm mỹ tối giản hóa.' }
            ]
        },
        computed: {
            selectedDetail() {
                if (!this.activeValue) return null;
                return this.coreValues.find(v => v.id === this.activeValue);
            }
        },
        methods: {
            toggleMenu() {
                this.isMobileMenuOpen = !this.isMobileMenuOpen;
            },
            submitContact() {
                // Simulate API submission
                console.log('Form Submitted:', this.contactForm);
                
                this.submissionSuccess = true;
                this.submissionMessage = 'Cảm ơn bạn! Yêu cầu của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ sớm nhất.';
                
                // Clear form after a short delay
                setTimeout(() => {
                    this.contactForm = { name: '', email: '', interest: '' };
                    this.submissionMessage = '';
                    this.showContactModal = false; // Close modal after success
                }, 3000);
            }
        }
    });
</script>

</body>
</html>
