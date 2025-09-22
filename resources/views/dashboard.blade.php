<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-blue-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <h1 class="text-2xl font-bold flex justify-center items-center gap-2">
                        Selamat Datang di
                        <span class="text-blue-600 flex items-center gap-1">
                            PASPERPUS
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m0-12l8 4.5M12 6L4 10.5M20 18V10.5M4 18V10.5m16 7.5l-8-4.5m0 0L4 18"/>
                            </svg>
                        </span>
                    </h1>

                    <p class="mt-2 text-lg text-gray-600">
                        Perpustakaan SMKS Pasundan 1 Cianjur
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
