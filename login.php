<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

include 'db/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // Password di-hash dengan MD5

    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password' AND role = 'admin'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Kepegawaian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .login-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .form-input:focus {
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }
        .login-button {
            transition: all 0.3s ease;
        }
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8 flex gap-8">
            <!-- Left side - Illustration and Welcome Text -->
            <div class="flex-1 hidden lg:block">
                <div class="login-animation">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/login-3305943-2757111.png" 
                         alt="Login Illustration" 
                         class="w-full max-w-md mx-auto">
                </div>
                <div class="text-center mt-8">
                    <h2 class="text-3xl font-extrabold text-indigo-900">
                        Selamat Datang di Sistem Kepegawaian
                    </h2>
                    <p class="mt-3 text-lg text-gray-600">
                        Kelola data pegawai dengan lebih mudah dan efisien
                    </p>
                </div>
            </div>

            <!-- Right side - Login Form -->
            <div class="flex-1">
                <div class="bg-white py-8 px-8 shadow-2xl rounded-2xl">
                    <div class="sm:mx-auto sm:w-full sm:max-w-md">
                        <div class="text-center">
                            <i class="fas fa-users-gear text-5xl text-indigo-600 mb-4"></i>
                            <h2 class="text-2xl font-bold text-gray-900">Login Admin</h2>
                            <p class="mt-2 text-sm text-gray-600">
                                Masukkan kredensial Anda untuk mengakses sistem
                            </p>
                        </div>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-4 animate-shake">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        <?php echo $error; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form class="mt-8 space-y-6" method="POST" action="" id="loginForm">
                        <div class="space-y-5">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>Username
                                </label>
                                <div class="mt-1">
                                    <input id="username" name="username" type="text" required 
                                        class="form-input appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-lg transition-all duration-300"
                                        placeholder="Masukkan username">
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock text-gray-400 mr-2"></i>Password
                                </label>
                                <div class="mt-1 relative">
                                    <input id="password" name="password" type="password" required
                                        class="form-input appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-lg transition-all duration-300"
                                        placeholder="Masukkan password">
                                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" 
                                class="login-button group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-sign-in-alt text-indigo-300 group-hover:text-indigo-200"></i>
                                </span>
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center text-sm text-gray-600">
                    <p>&copy; <?php echo date('Y'); ?> Sistem Kepegawaian. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Password Visibility
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle icon
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Form Animation
            const form = document.getElementById('loginForm');
            const inputs = form.querySelectorAll('input');

            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Add loading state to button when form is submitted
            form.addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
            });
        });
    </script>
</body>
</html>
