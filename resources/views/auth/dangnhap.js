tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: {
                    900: '#111827',
                    800: '#1f2937',
                    700: '#374151',
                    600: '#4b5563',
                    500: '#6b7280',
                    400: '#9ca3af',
                    300: '#d1d5db',
                    200: '#e5e7eb',
                    100: '#f3f4f6',
                    50: '#f9fafb'
                }
            }
        }
    }
}
const loginTab = document.getElementById('login-tab');
const registerTab = document.getElementById('register-tab');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');
const switchToRegister = document.getElementById('switch-to-register');
const switchToLogin = document.getElementById('switch-to-login');

function showLoginForm() {
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
    loginTab.classList.add('bg-primary-900', 'text-primary-300');
    loginTab.classList.remove('text-primary-400');
    registerTab.classList.remove('bg-primary-900', 'text-primary-300');
    registerTab.classList.add('text-primary-400');
}

function showRegisterForm() {
    registerForm.classList.remove('hidden');
    loginForm.classList.add('hidden');
    registerTab.classList.add('bg-primary-900', 'text-primary-300');
    registerTab.classList.remove('text-primary-400');
    loginTab.classList.remove('bg-primary-900', 'text-primary-300');
    loginTab.classList.add('text-primary-400');
}

loginTab.addEventListener('click', showLoginForm);
registerTab.addEventListener('click', showRegisterForm);
switchToRegister.addEventListener('click', showRegisterForm);
switchToLogin.addEventListener('click', showLoginForm);