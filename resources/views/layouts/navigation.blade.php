<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-blue-500">

        <div class="pt-3 pb-3 border-b-[1.5px] border-white">
            <a href="{{ route('dashboard') }}" class="text-center block text-3xl text-white font-semibold">
                Pasperpus
            </a>
        </div>

       <ul class="space-y-2 font-medium pt-3">

          {{-- Dashboard --}}
          <li>
             <a href="{{ route('dashboard') }}"
                class="flex items-center p-2 rounded-lg
                       {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:bg-blue-500' }}">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                     <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                     <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
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
                       {{ request()->routeIs('kategori.*') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                 <path fill-rule="evenodd" d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z" clip-rule="evenodd" />
                 </svg>
                <span class="ms-3">Kategori Buku</span>
             </a>
          </li>
          @endif

          {{-- Daftar Buku --}}
          <li>
             <a href="{{ route('buku.index') }}"
                class="flex items-center p-2 rounded-lg
                       {{ request()->routeIs('buku.*') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                 <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                 </svg>
                <span class="ms-3">Daftar Buku</span>
             </a>
          </li>
          @if(Auth::user()->hasRole('pustakawan'))
          {{-- Peminjaman Buku --}}
          <li>
             <a href="{{ route('peminjaman.index') }}"
                class="flex items-center p-2 rounded-lg
                       {{ request()->routeIs('peminjaman.*') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                 <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                 </svg>
                <span class="ms-3">Peminjaman Buku</span>
             </a>
          </li>

          {{-- Reservasi Buku --}}
          <li>
             <a href="{{ route('reservasi.index') }}"
                class="flex items-center p-2 rounded-lg
                       {{ request()->routeIs('reservasi.*') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                 <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                 </svg>
                <span class="ms-3">Reservasi Buku</span>
             </a>
          </li>

          {{-- Pengembalian Buku --}}
          <li>
             <a href="{{ route('pengembalian.index') }}"
                class="flex items-center p-2 rounded-lg
                       {{ request()->routeIs('pengembalian.*') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                 <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                 </svg>
                <span class="ms-3">Pengembalian Buku</span>
             </a>
          </li>

          {{-- Data Siswa --}}
          <li>
             <a href="{{ route('siswa.index') }}"
                class="flex items-center p-2 rounded-lg
                       {{ request()->routeIs('siswa.*') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                </svg>
                <span class="ms-3">Data Siswa</span>
             </a>
          </li>

          {{-- Data Guru --}}
          <li>
             <a href="{{ route('guru.index') }}"
                class="flex items-center p-2 rounded-lg
                       {{ request()->routeIs('guru.*') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                </svg>
                <span class="ms-3">Data Guru</span>
             </a>
          </li>
          @endif

          {{-- Untuk user biasa --}}
          @if(!Auth::user()->hasRole('pustakawan'))
          <li>
             <a href="{{ route('peminjaman.tampil') }}"
                class="flex items-center p-2 rounded-lg
                       {{ request()->routeIs('peminjaman.tampil') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                      </svg>

                <span class="ms-3">Riwayat Peminjaman</span>
             </a>
          </li>
          @endif
          <hr class="border-t-[1.5px] border-gray-200 ">
          {{-- Profil & Logout --}}
          <li>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center p-2 rounded-lg
                      {{ request()->routeIs('profile.*') ? 'bg-gray-100 text-blue-500 font-semibold' : 'text-white hover:text-blue-500 hover:bg-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                </svg>
               <span class="ms-3">Profil</span>
            </a>
         </li>

          <li>
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center  w-full text-white hover:text-blue-500 hover:bg-white p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm10.72 4.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H9a.75.75 0 0 1 0-1.5h10.94l-1.72-1.72a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                   <span class="ms-3">Logout</span>
                </button>
             </form>
          </li>

       </ul>
    </div>
 </aside>
