import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            // Thêm 'resources/js/layout/chatbot.js' vào mảng input
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/layout/chatbot.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
});