import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',
                'resources/css/layout/main.css', 'resources/js/layout/main.js',
                'resources/css/admin/main.css', 'resources/js/admin/main.js',
                // Thêm 'resources/js/layout/chatbot.js' vào mảng input
                'resources/js/layout/chatbot.js','resources/css/layout/chatbot.css',
                'resources/css/layout/sanpham.css',
                'resources/css/admin/admindasboard.css', 'resources/js/admin/dasboard.js',
                'resources/css/admin/banner.css','resources/css/layout/chitietsanpham.css',
                'resources/css/layout/lienhe.css', 'resources/js/layout/lienhe.js',
                'resources/css/checkout/giohang.css'
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
});
