<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .card-shadow {
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }
            .floating {
                animation: floating 6s ease-in-out infinite;
            }
            @keyframes floating {
                0% { transform: translate(0, 0px); }
                50% { transform: translate(0, -15px); }
                100% { transform: translate(0, 0px); }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Background dengan pattern dan animasi -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden gradient-bg">
                <!-- Pattern overlay -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                </div>

                <!-- Konten utama side panel -->
                <div class="relative z-10 flex flex-col justify-between h-full px-12 py-12 text-white">
                    <!-- Logo -->
                    <div>
                        <a href="/" class="flex items-center space-x-3 text-xl font-bold">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <x-application-logo class="w-6 h-6 text-indigo-600" />
                            </div>
                            <span>{{ config('app.name', 'Laravel') }}</span>
                        </a>
                    </div>

                    <!-- Konten tengah -->
                    <div class="max-w-md">
                        <div class="floating mb-8">
                            <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h2 class="text-4xl font-bold mb-4 leading-tight">
                            Platform Terdepan untuk Solusi Digital Anda
                        </h2>
                        <p class="text-blue-100 text-lg opacity-90">
                            Bergabunglah dengan komunitas kami dan temukan cara baru untuk meningkatkan produktivitas dengan tools terbaik.
                        </p>
                    </div>

                    <!-- Footer side panel -->
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 rounded-full bg-white"></div>
                            <div class="w-3 h-3 rounded-full bg-white opacity-40"></div>
                            <div class="w-3 h-3 rounded-full bg-white opacity-40"></div>
                        </div>
                        <div class="text-blue-100 text-sm">
                            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-8 bg-gray-50">
                <div class="w-full max-w-md">
                    <!-- Mobile Header -->
                    <div class="lg:hidden text-center mb-10">
                        <a href="/" class="inline-flex items-center space-x-3 text-gray-800">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                                <x-application-logo class="w-6 h-6 text-white" />
                            </div>
                            <span class="text-xl font-bold">{{ config('app.name', 'Laravel') }}</span>
                        </a>
                    </div>

                    <!-- Card Container -->
                    <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                        <!-- Card Header dengan gradien -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-6">
                            <h1 class="text-2xl font-bold text-white">
                                @if(request()->routeIs('login'))
                                    Selamat Datang Kembali
                                @elseif(request()->routeIs('register'))
                                    Bergabung Bersama Kami
                                @elseif(request()->routeIs('password.request'))
                                    Lupa Kata Sandi?
                                @elseif(request()->routeIs('password.reset'))
                                    Atur Ulang Kata Sandi
                                @else
                                    {{ $title ?? 'Selamat Datang' }}
                                @endif
                            </h1>
                            <p class="text-blue-100 mt-2">
                                @if(request()->routeIs('login'))
                                    Masuk untuk melanjutkan ke akun Anda
                                @elseif(request()->routeIs('register'))
                                    Daftar untuk membuat akun baru
                                @elseif(request()->routeIs('password.request'))
                                    Kami akan mengirimkan link reset ke email Anda
                                @elseif(request()->routeIs('password.reset'))
                                    Buat kata sandi baru untuk akun Anda
                                @else
                                    {{ $subtitle ?? '' }}
                                @endif
                            </p>
                        </div>

                        <!-- Card Content -->
                        <div class="px-8 py-8">
                            {{ $slot }}
                        </div>

                        <!-- Card Footer -->
                        <div class="border-t border-gray-100 px-8 py-6 bg-gray-50">
                            @if(request()->routeIs('login'))
                                <div class="text-center text-sm text-gray-600">
                                    Belum memiliki akun?
                                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors duration-200 ml-1">
                                        Daftar sekarang
                                    </a>
                                </div>
                                <div class="text-center mt-4">
                                    <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
                                        Lupa kata sandi?
                                    </a>
                                </div>
                            @elseif(request()->routeIs('register'))
                                <div class="text-center text-sm text-gray-600">
                                    Sudah memiliki akun?
                                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors duration-200 ml-1">
                                        Masuk di sini
                                    </a>
                                </div>
                            @elseif(request()->routeIs('password.request'))
                                <div class="text-center text-sm text-gray-600">
                                    Ingat kata sandi Anda?
                                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors duration-200 ml-1">
                                        Kembali ke halaman masuk
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Links for Mobile -->
                    <div class="mt-8 text-center text-sm text-gray-500 lg:hidden">
                        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
