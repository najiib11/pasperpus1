<aside id="default-sidebar"
    class="top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <ul class="space-y-2 font-medium">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-2 rounded-lg
                      {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700 text-blue-700' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-blue-600 dark:text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998A8.5 8.5 0 1 0 17.973 12.066a.999.999 0 0 0-1-1.066Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            {{-- Hanya untuk pustakawan --}}
            @if(Auth::user()->hasRole('pustakawan'))

                    {{-- Kategori Buku --}}
                    <li>
                        <a href="{{ route('kategori.index') }}"
                            class="flex items-center p-2 rounded-lg
                                                                                                                                                                                                                                      {{ request()->routeIs('kategori.*') ? 'bg-gray-200 dark:bg-gray-700 text-blue-700' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4 3a1 1 0 0 0-1 1v12a1 1 0 0 0 1.447.894L10 14.118l5.553 2.776A1 1 0 0 0 17 16V4a1 1 0 0 0-1-1H4Z" />
                            </svg>
                            <span class="ms-3">Kategori Buku</span>
                        </a>
                    </li>

                    {{-- Daftar Buku --}}
                    <li>
                        <a href="{{ route('buku.index') }}"
                            class="flex items-center p-2 rounded-lg
                                                                                                                                                                                                                                      {{ request()->routeIs('buku.*') ? 'bg-gray-200 dark:bg-gray-700 text-blue-700' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 18 18">
                                <path
                                    d="M3 2a1 1 0 0 0-1 1v13a1 1 0 0 0 1.447.894L9 14.118l5.553 2.776A1 1 0 0 0 16 16V3a1 1 0 0 0-1-1H3Z" />
                            </svg>
                            <span class="ms-3">Daftar Buku</span>
                        </a>
                    </li>

                    {{-- Peminjaman Buku --}}
                    <li>
                        <a href="{{ route('peminjaman.index') }}" class="flex items-center p-2 rounded-lg
                                    {{ request()->routeIs('peminjaman.index') && request('tab') !== 'dikembalikan'
                ? 'bg-gray-200 dark:bg-gray-700 text-blue-700'
                : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h9l5-5V4a2 2 0 0 0-2-2H4Zm9 13.5V11h4.5L13 15.5Z" />
                            </svg>
                            <span class="ms-3">Peminjaman Buku</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('peminjaman.index', ['tab' => 'dikembalikan']) }}" class="flex items-center p-2 rounded-lg
                                                                                                                                                                                       {{ request()->routeIs('peminjaman.*') && request('tab') === 'dikembalikan'
                ? 'bg-gray-200 dark:bg-gray-700 text-blue-700'
                : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h9l5-5V4a2 2 0 0 0-2-2H4Zm9 13.5V11h4.5L13 15.5Z" />
                            </svg>
                            <span class="ms-3">Pengembalian Buku</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('peminjaman.keloladenda') }}" class="flex items-center p-2 rounded-lg
                            {{ request()->routeIs('peminjaman.keloladenda')
                ? 'bg-gray-200 dark:bg-gray-700 text-blue-700'
                : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h9l5-5V4a2 2 0 0 0-2-2H4Zm9 13.5V11h4.5L13 15.5Z" />
                            </svg>
                            <span class="ms-3">Kelola Denda</span>
                        </a>
                    </li>


                    {{-- Data Siswa --}}
                    <li>
                        <a href="{{ route('siswa.index') }}"
                            class="flex items-center p-2 rounded-lg
                                                                                                                        {{ request()->routeIs('siswa.index') ? 'bg-gray-200 dark:bg-gray-700 text-blue-700' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 20 18">
                                <path d="M6 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6ZM6 9a6 6 0 0 0-6 6v2h12v-2a6 6 0 0 0-6-6Z" />
                            </svg>
                            <span class="ms-3">Data Siswa</span>
                        </a>
                    </li>

                    {{-- Data Guru --}}
                    <li>
                        <a href="{{ route('guru.index') }}"
                            class="flex items-center pe-2 py-2 rounded-lg
                                                                                                                                                                                                                                      {{ request()->routeIs('guru.*') ? 'bg-gray-200 dark:bg-gray-700 text-blue-700' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                                fill="currentColor" viewBox="0 0 20 18">
                                <path d="M14 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6ZM14 9a6 6 0 0 0-6 6v2h12v-2a6 6 0 0 0-6-6Z" />
                            </svg>
                            <span class="ms-3">Data Guru</span>
                        </a>
                    </li>
            @endif

            {{-- Untuk user biasa (bukan pustakawan) --}}
            @role('anggota')
            <li>
                <a href="{{ route('buku.index') }}" class="flex items-center p-2 rounded-lg
        {{ request()->routeIs('buku.index') ? 'bg-gray-200 text-blue-700' : 'text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M4 3a1 1 0 011-1h11a1 1 0 011 1v14a1 1 0 01-1.447.894L10 15.118l-5.553 2.776A1 1 0 013 17V3z" />
                    </svg>
                    <span class="ml-3">Daftar Buku</span>
                </a>
            </li>
            <li>
                <a href="{{ route('peminjaman.tampil') }}"
                    class="flex items-center p-2 rounded-lg
                                            {{ request()->routeIs('peminjaman.tampil') ? 'bg-gray-200 dark:bg-gray-700 text-blue-700' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                        fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M3 2a1 1 0 0 0-1 1v13a1 1 0 0 0 1.447.894L9 14.118l5.553 2.776A1 1 0 0 0 16 16V3a1 1 0 0 0-1-1H3Z" />
                    </svg>
                    <span class="ms-3">Riwayat Peminjaman</span>
                </a>
            </li>
            @endrole

            @role('guru')
            <li>
                <a href="{{ route('buku.index') }}" class="flex items-center p-2 rounded-lg
        {{ request()->routeIs('buku.index') ? 'bg-gray-200 text-blue-700' : 'text-gray-900 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M4 3a1 1 0 011-1h11a1 1 0 011 1v14a1 1 0 01-1.447.894L10 15.118l-5.553 2.776A1 1 0 013 17V3z" />
                    </svg>
                    <span class="ml-3">Daftar Buku</span>
                </a>
            </li>
            <li>
                <a href="{{ route('peminjaman.tampil') }}"
                    class="flex items-center p-2 rounded-lg
                                            {{ request()->routeIs('peminjaman.tampil') ? 'bg-gray-200 dark:bg-gray-700 text-blue-700' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400"
                        fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M3 2a1 1 0 0 0-1 1v13a1 1 0 0 0 1.447.894L9 14.118l5.553 2.776A1 1 0 0 0 16 16V3a1 1 0 0 0-1-1H3Z" />
                    </svg>
                    <span class="ms-3">Riwayat Peminjaman</span>
                </a>
            </li>
            @endrole


            {{-- Profil & Logout --}}
            <li class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-4">
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center p-2 rounded-lg text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 2a6 6 0 0 1 4.472 10.158A7.978 7.978 0 0 1 10 18a7.978 7.978 0 0 1-4.472-5.842A6 6 0 0 1 10 2Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ms-3">Profil</span>
                </a>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full p-2 text-left text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 18 16">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                        </svg>
                        <span class="ms-3">Logout</span>
                    </button>
                </form>
            </li>


        </ul>
    </div>
</aside>
