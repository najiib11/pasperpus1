<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Pasperpus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Nunito:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.8) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(255, 255, 255, 0.6) 0%, transparent 20%);
            z-index: -1;
        }

        .book-icons {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        .book-icon {
            position: absolute;
            color: #8b5a2b;
            font-size: 24px;
            animation: float 6s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .register-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .input-field {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(139, 90, 43, 0.3);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: rgba(74, 124, 89, 0.6);
            box-shadow: 0 0 0 3px rgba(74, 124, 89, 0.2);
        }

        .btn-primary {
            background: linear-gradient(to right, #4a7c59, #6ba46e);
            box-shadow: 0 4px 15px rgba(74, 124, 89, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 124, 89, 0.4);
        }

        .floating-label {
            transition: all 0.2s ease;
        }

        .floating-input:focus + .floating-label,
        .floating-input:not(:placeholder-shown) + .floating-label {
            top: -10px;
            left: 10px;
            font-size: 0.75rem;
            color: #4a7c59;
            background: white;
            padding: 0 5px;
        }

        .session-status {
            background: rgba(234, 250, 241, 0.9);
            border: 1px solid rgba(74, 124, 89, 0.3);
            backdrop-filter: blur(5px);
        }

        .title-font {
            font-family: 'Playfair Display', serif;
        }

        .magic-text {
            background: linear-gradient(to right, #8b5a2b, #4a7c59, #8b5a2b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .pulse-glow {
            animation: pulse-glow 4s ease-in-out infinite alternate;
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 20px rgba(139, 90, 43, 0.1); }
            100% { box-shadow: 0 0 30px rgba(74, 124, 89, 0.2); }
        }

        .checkbox:checked {
            background-color: #4a7c59;
            border-color: #4a7c59;
        }

        .book-animation {
            animation: book-open 2s ease-in-out infinite alternate;
            transform-origin: left center;
        }

        @keyframes book-open {
            0% { transform: perspective(400px) rotateY(0deg); }
            100% { transform: perspective(400px) rotateY(-15deg); }
        }

        .page-turn {
            animation: page-turn 3s ease-in-out infinite;
        }

        @keyframes page-turn {
            0%, 100% { transform: rotateY(0deg); }
            50% { transform: rotateY(-10deg); }
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 4px;
            transition: all 0.3s ease;
            width: 0%;
        }

        .strength-weak {
            width: 25%;
            background: #ef4444;
        }

        .strength-fair {
            width: 50%;
            background: #f59e0b;
        }

        .strength-good {
            width: 75%;
            background: #3b82f6;
        }

        .strength-strong {
            width: 100%;
            background: #10b981;
        }

        .password-feedback {
            font-size: 0.75rem;
            margin-top: 4px;
            transition: all 0.3s ease;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #8b5a2b;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #4a7c59;
        }
    </style>
</head>
<body class="py-8 px-4 text-gray-800">
    <!-- Background Book Icons -->
    <div class="book-icons" id="bookIcons"></div>

    <div class="max-w-md w-full mx-auto relative z-10">
        <!-- Session Status -->
        @if (session('status'))
        <div class="session-status mb-6 p-4 rounded-lg text-green-700">
            <i class="fas fa-book-open mr-2"></i>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        <div class="register-card rounded-2xl p-8 w-full pulse-glow">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="relative w-16 h-16">
                        <i class="fas fa-book text-5xl text-amber-700 book-animation"></i>
                        <i class="fas fa-feather-alt absolute -top-1 -right-1 text-xl text-amber-900 page-turn"></i>
                    </div>
                </div>
                <h1 class="title-font text-3xl font-bold mb-2 magic-text">Bergabung dengan Kami</h1>
                <p class="text-amber-700">Buat akun baru untuk mulai menjelajahi dunia pengetahuan</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="relative mb-6">
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required autofocus
                        placeholder=" "
                        class="floating-input input-field w-full px-4 py-3 rounded-lg focus:outline-none text-gray-800 placeholder-transparent"
                    >
                    <label for="name" class="floating-label absolute left-4 top-3 text-amber-700 pointer-events-none">
                        <i class="fas fa-user mr-2"></i>Nama Lengkap
                    </label>
                    @error('name')
                        <div class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="relative mb-6">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder=" "
                        class="floating-input input-field w-full px-4 py-3 rounded-lg focus:outline-none text-gray-800 placeholder-transparent"
                    >
                    <label for="email" class="floating-label absolute left-4 top-3 text-amber-700 pointer-events-none">
                        <i class="fas fa-envelope mr-2"></i>Alamat Email
                    </label>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="relative mb-4">
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder=" "
                            class="floating-input input-field w-full px-4 py-3 pr-10 rounded-lg focus:outline-none text-gray-800 placeholder-transparent"
                        >
                        <label for="password" class="floating-label absolute left-4 top-3 text-amber-700 pointer-events-none">
                            <i class="fas fa-lock mr-2"></i>Kata Sandi
                        </label>
                        <span class="toggle-password" data-target="password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div id="password-strength" class="password-strength"></div>
                    <div id="password-feedback" class="password-feedback text-gray-600"></div>
                    @error('password')
                        <div class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="relative mb-6">
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder=" "
                            class="floating-input input-field w-full px-4 py-3 pr-10 rounded-lg focus:outline-none text-gray-800 placeholder-transparent"
                        >
                        <label for="password_confirmation" class="floating-label absolute left-4 top-3 text-amber-700 pointer-events-none">
                            <i class="fas fa-lock mr-2"></i>Konfirmasi Kata Sandi
                        </label>
                        <span class="toggle-password" data-target="password_confirmation">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password_confirmation')
                        <div class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center mb-6">
                    <input id="terms" type="checkbox" name="terms" class="checkbox rounded border-amber-700 bg-white focus:ring-amber-500 mr-2" required>
                    <label for="terms" class="text-amber-700 text-sm">
                        Saya setuju dengan
                        <a href="#" class="text-amber-700 hover:text-amber-900 font-medium underline">Syarat & Ketentuan</a>
                    </label>
                </div>
                @error('terms')
                    <div class="text-red-500 text-sm mt-1 ml-1 mb-4">{{ $message }}</div>
                @enderror

                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full py-3 rounded-lg text-white font-semibold mb-6">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </button>

                <div class="text-center">
                    <p class="text-amber-700">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-amber-700 hover:text-amber-900 font-medium transition-colors">Masuk di sini</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="text-center mt-6 text-amber-700 text-sm opacity-70">
            © {{ date('Y') }} Pasperpus. Semua Hak Dilindungi.
        </div>
    </div>

    <script>
        // Create floating book icons for background
        function createBookIcons() {
            const bookIconsContainer = document.getElementById('bookIcons');
            const iconCount = 20;
            const bookIcons = ['fa-book', 'fa-book-open', 'fa-bookmark', 'fa-feather-alt'];

            for (let i = 0; i < iconCount; i++) {
                const icon = document.createElement('i');
                const randomIcon = bookIcons[Math.floor(Math.random() * bookIcons.length)];
                icon.classList.add('book-icon', 'fas', randomIcon);

                // Random position
                icon.style.left = `${Math.random() * 100}%`;
                icon.style.top = `${Math.random() * 100}%`;

                // Random animation delay
                icon.style.animationDelay = `${Math.random() * 6}s`;

                // Random size
                const size = Math.random() * 20 + 16;
                icon.style.fontSize = `${size}px`;

                bookIconsContainer.appendChild(icon);
            }
        }

        // Password strength indicator
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = "";

            // Length check
            if (password.length >= 8) {
                strength += 1;
            } else {
                feedback = "Kata sandi harus minimal 8 karakter. ";
            }

            // Contains lowercase
            if (/[a-z]/.test(password)) {
                strength += 1;
            } else {
                feedback += "Tambahkan huruf kecil. ";
            }

            // Contains uppercase
            if (/[A-Z]/.test(password)) {
                strength += 1;
            } else {
                feedback += "Tambahkan huruf besar. ";
            }

            // Contains numbers
            if (/[0-9]/.test(password)) {
                strength += 1;
            } else {
                feedback += "Tambahkan angka. ";
            }

            // Contains special characters
            if (/[^A-Za-z0-9]/.test(password)) {
                strength += 1;
            } else {
                feedback += "Tambahkan karakter khusus. ";
            }

            return { strength, feedback };
        }

        // Toggle password visibility
        function togglePasswordVisibility(targetId) {
            const passwordInput = document.getElementById(targetId);
            const toggleIcon = document.querySelector(`.toggle-password[data-target="${targetId}"] i`);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Initialize book icons
        createBookIcons();

        // Add interactive effects to form elements
        document.querySelectorAll('.floating-input').forEach(input => {
            if(input.value) {
                input.nextElementSibling.classList.add('floating-label-active');
            }

            input.addEventListener('focus', function() {
                this.parentElement.classList.add('border-green-500');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('border-green-500');
            });
        });

        // Password strength indicator
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const strengthIndicator = document.getElementById('password-strength');
            const feedbackElement = document.getElementById('password-feedback');

            if (passwordInput && strengthIndicator && feedbackElement) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    const { strength, feedback } = checkPasswordStrength(password);

                    // Reset classes
                    strengthIndicator.className = 'password-strength';
                    feedbackElement.textContent = '';

                    if (password.length === 0) {
                        return;
                    }

                    // Apply appropriate class based on strength
                    if (strength <= 2) {
                        strengthIndicator.classList.add('strength-weak');
                        feedbackElement.textContent = 'Kata sandi lemah. ' + feedback;
                        feedbackElement.className = 'password-feedback text-red-500';
                    } else if (strength === 3) {
                        strengthIndicator.classList.add('strength-fair');
                        feedbackElement.textContent = 'Kata sandi cukup. ' + feedback;
                        feedbackElement.className = 'password-feedback text-yellow-500';
                    } else if (strength === 4) {
                        strengthIndicator.classList.add('strength-good');
                        feedbackElement.textContent = 'Kata sandi baik. ' + feedback;
                        feedbackElement.className = 'password-feedback text-blue-500';
                    } else {
                        strengthIndicator.classList.add('strength-strong');
                        feedbackElement.textContent = 'Kata sandi kuat!';
                        feedbackElement.className = 'password-feedback text-green-500';
                    }
                });
            }

            // Add event listeners for toggle password buttons
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    togglePasswordVisibility(targetId);
                });
            });
        });

        // Add hover effect to register card
        // const registerCard = document.querySelector('.register-card');
        // if (registerCard) {
        //     registerCard.addEventListener('mousemove', (e) => {
        //         const rect = registerCard.getBoundingClientRect();
        //         const x = e.clientX - rect.left;
        //         const y = e.clientY - rect.top;

        //         const centerX = rect.width / 2;
        //         const centerY = rect.height / 2;

        //         const angleY = (x - centerX) / 25;
        //         const angleX = (centerY - y) / 25;

        //         registerCard.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg)`;
        //     });

        //     registerCard.addEventListener('mouseleave', () => {
        //         registerCard.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
        //     });
        // }
    </script>
</body>
</html>
