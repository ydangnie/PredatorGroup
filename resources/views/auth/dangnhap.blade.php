<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Đăng nhập / Đăng ký</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="dangnhap.css">
</head>
<body class="text-gray-100">

  <div class="form-container bg-primary-800 rounded-2xl shadow-2xl overflow-hidden w-full max-w-md">
    <!-- Tabs -->
    <div class="flex border-b border-primary-700">
      <button id="login-tab" class="flex-1 py-4 px-6 text-center font-semibold text-primary-300 bg-primary-900 rounded-tl-2xl transition-colors">Đăng nhập</button>
      <button id="register-tab" class="flex-1 py-4 px-6 text-center font-semibold text-primary-400 hover:text-primary-200 transition-colors">Đăng ký</button>
    </div>

    <!-- Login Form -->
    <div id="login-form" class="p-8">
      <h2 class="text-2xl font-bold mb-6 text-center">Đăng Nhập</h2>

      <!-- Google Login -->
      <button class="w-full flex items-center justify-center gap-3 py-3 mb-5 bg-primary-700 hover:bg-primary-600 rounded-xl transition-colors border border-primary-600">
        <i class="fab fa-google text-red-400 text-xl"></i>
        <span>Đăng nhập bằng Google</span>
      </button>

      <div class="relative flex items-center my-6">
        <div class="flex-grow border-t border-primary-600"></div>
        <span class="mx-4 text-primary-400 text-sm">hoặc</span>
        <div class="flex-grow border-t border-primary-600"></div>
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Email</label>
        <input type="email" class="input-field w-full px-4 py-3 rounded-lg bg-primary-700 border border-primary-600 focus:border-primary-500 text-white placeholder-primary-400" placeholder="you@example.com" />
      </div>

      <!-- Password -->
      <div class="mb-6">
        <div class="flex justify-between mb-2">
          <label class="block text-sm font-medium">Mật khẩu</label>
          <a href="#" class="text-sm text-primary-400 hover:text-primary-200">Quên mật khẩu?</a>
        </div>
        <input type="password" class="input-field w-full px-4 py-3 rounded-lg bg-primary-700 border border-primary-600 focus:border-primary-500 text-white placeholder-primary-400" placeholder="••••••••" />
      </div>

      <!-- Submit -->
      <button class="w-full py-3 bg-primary-600 hover:bg-primary-500 rounded-xl font-semibold transition-colors">
        Đăng nhập
      </button>

      <div class="mt-6 text-center text-sm text-primary-400">
        Chưa có tài khoản? 
        <button id="switch-to-register" class="switch-btn font-medium">Đăng ký ngay</button>
      </div>
    </div>

    <!-- Register Form (hidden by default) -->
    <div id="register-form" class="p-8 hidden">
      <h2 class="text-2xl font-bold mb-6 text-center">Tạo tài khoản mới</h2>

      <!-- Google Login -->
      <button class="w-full flex items-center justify-center gap-3 py-3 mb-5 bg-primary-700 hover:bg-primary-600 rounded-xl transition-colors border border-primary-600">
        <i class="fab fa-google text-red-400 text-xl"></i>
        <span>Đăng ký bằng Google</span>
      </button>

      <div class="relative flex items-center my-6">
        <div class="flex-grow border-t border-primary-600"></div>
        <span class="mx-4 text-primary-400 text-sm">hoặc</span>
        <div class="flex-grow border-t border-primary-600"></div>
      </div>

      <!-- Full Name -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Họ và tên</label>
        <input type="text" class="input-field w-full px-4 py-3 rounded-lg bg-primary-700 border border-primary-600 focus:border-primary-500 text-white placeholder-primary-400" placeholder="Nguyễn Văn A" />
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Email</label>
        <input type="email" class="input-field w-full px-4 py-3 rounded-lg bg-primary-700 border border-primary-600 focus:border-primary-500 text-white placeholder-primary-400" placeholder="you@example.com" />
      </div>

      <!-- Password -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Mật khẩu</label>
        <input type="password" class="input-field w-full px-4 py-3 rounded-lg bg-primary-700 border border-primary-600 focus:border-primary-500 text-white placeholder-primary-400" placeholder="••••••••" />
      </div>

      <!-- Confirm Password -->
      <div class="mb-6">
        <label class="block text-sm font-medium mb-2">Xác nhận mật khẩu</label>
        <input type="password" class="input-field w-full px-4 py-3 rounded-lg bg-primary-700 border border-primary-600 focus:border-primary-500 text-white placeholder-primary-400" placeholder="••••••••" />
      </div>

      <!-- Submit -->
      <button class="w-full py-3 bg-primary-600 hover:bg-primary-500 rounded-xl font-semibold transition-colors">
        Đăng ký
      </button>

      <div class="mt-6 text-center text-sm text-primary-400">
        Đã có tài khoản? 
        <button id="switch-to-login" class="switch-btn font-medium">Đăng nhập ngay</button>
      </div>
    </div>
  </div>

</body>
</html>