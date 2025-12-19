<!-- Sidebar Container -->
<aside class="flex flex-col w-64 bg-white border-r border-gray-200 min-h-screen sticky top-0 h-screen transition-all duration-300">
    
    <!-- BRAND / LOGO SECTION -->
    <div class="flex flex-col items-center justify-center py-5 border-b border-gray-100 bg-white z-10">
        <a href="{{ route('dashboard') }}" class="group transition-transform duration-300 hover:scale-105"> 
            <img src="{{ asset('images/logo-DharmaPolimetal.png') }}" 
                 alt="Logo Dharma Polimetal" 
                 class="h-10 w-auto mt-2 mb-2 drop-shadow-sm"> 
        </a>
        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.15em]">
            PT Dharma Polimetal Tbk.
        </p>
    </div>

    <!-- NAVIGATION LINKS (Scrollable) -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scrollbar-thin scrollbar-thumb-gray-200">
        
        {{-- Helper untuk class active/inactive agar kodingan lebih rapi --}}
        @php
            $activeClass = 'bg-red-50 text-red-700 font-semibold shadow-sm ring-1 ring-red-100';
            $inactiveClass = 'text-gray-600 hover:bg-gray-50 hover:text-red-600 transition-all duration-200';
            $iconActive = 'text-red-600';
            $iconInactive = 'text-gray-400 group-hover:text-red-500';
        @endphp

        {{-- ================= ROLE: SHE (ADMIN) ================= --}}
        @if(Auth::user()->role == 'she')
            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2">Main Menu</p>
            
            <a href="{{ route('she.dashboard') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('she.dashboard') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('she.dashboard') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <a href="{{ route('she.hazards.index') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('she.hazards.*') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('she.hazards.*') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Laporan Bahaya
            </a>

            <a href="{{ route('she.maps.index') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('she.maps.*') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('she.maps.*') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" /></svg>
                Peta Risiko
            </a>

            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Administrasi</p>

            <a href="{{ route('she.users.index') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('she.users.*') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('she.users.*') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A10.99 10.99 0 002.5 10.5a10.99 10.99 0 003.5-5.697m11 5.697a10.99 10.99 0 00-3.5-5.697" /></svg>
                Kelola User
            </a>
            
            <a href="{{ route('karyawan.maps.index') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('karyawan.maps.*') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('karyawan.maps.*') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                View Employee Mode
            </a>

        {{-- ================= ROLE: SUPERVISOR ================= --}}
        @elseif(Auth::user()->role == 'supervisor')
            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2">Supervisor Menu</p>

            <a href="{{ route('supervisor.maps.index') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('supervisor.maps.*') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('supervisor.maps.*') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" /></svg>
                Peta Risiko
            </a>
            
            <a href="#" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('supervisor.reports.*') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('supervisor.reports.*') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Laporan
            </a>

        {{-- ================= ROLE: KARYAWAN ================= --}}
        @elseif(strtolower(Auth::user()->role) == 'karyawan')
            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2">Menu Karyawan</p>

            <a href="{{ route('karyawan.dashboard') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('karyawan.dashboard') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('karyawan.dashboard') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <a href="{{ route('karyawan.hazards.create') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('karyawan.hazards.create') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('karyawan.hazards.create') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Buat Laporan Baru
            </a>
            
            <a href="{{ route('karyawan.maps.index') }}" 
               class="group flex items-center px-3 py-2 rounded-lg text-sm mb-1 {{ request()->routeIs('karyawan.maps.*') ? $activeClass : $inactiveClass }}">
                <svg class="h-5 w-5 mr-3 {{ request()->routeIs('karyawan.maps.*') ? $iconActive : $iconInactive }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" /></svg>
                Lihat Peta Risiko
            </a>
        @endif
    </div>

    <!-- USER PROFILE SECTION (Sticky Bottom) -->
    <div class="border-t border-gray-200 p-3 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center min-w-0">
                <div class="flex-shrink-0">
                    <div class="h-9 w-9 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-base border-2 border-white shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-3 min-w-0">
                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500 truncate capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
            
            <!-- Logout Button with Tooltip Effect -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-white rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                        title="Log Out">
                    <svg class="h-5 w-5 transform rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>