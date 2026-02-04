<!-- Inline Script to prevent "Initialization Flash" -->
<script>
    (function () {
        const minimized = localStorage.getItem('sidebarMinimized') === 'true';
        if (minimized) {
            document.documentElement.classList.add('sidebar-minimized-pre');
        }
    })();
</script>

<!-- Sidebar Container - Responsive -->
<div x-data="{ 
    sidebarOpen: false, 
    sidebarMinimized: localStorage.getItem('sidebarMinimized') === 'true' 
}" x-init="
    $watch('sidebarMinimized', value => localStorage.setItem('sidebarMinimized', value));
    document.documentElement.classList.remove('sidebar-minimized-pre');
" class="relative">
    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden" style="display: none;">
    </div>

    <!-- Hamburger Button (Mobile Only) -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden fixed bottom-6 right-6 z-50 p-4 bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 transition-all duration-300 hover:scale-110">
        <svg x-show="!sidebarOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="sidebarOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <!-- Sidebar -->
    <aside x-bind:class="{
            'translate-x-0': sidebarOpen,
            '-translate-x-full lg:translate-x-0 h-screen md:block hidden': !sidebarOpen,
            'lg:w-64': !sidebarMinimized,
            'lg:w-20': sidebarMinimized
        }"
        class="fixed lg:sticky lg:top-0 inset-y-0 left-0 z-[70] flex flex-col bg-white border-r border-gray-200 lg:h-screen transition-[width,transform] duration-500 ease-in-out">

        <!-- Toggle Button (Large Screen Only) -->
        <button @click="sidebarMinimized = !sidebarMinimized"
            class="hidden lg:flex absolute -right-3 top-20 bg-white border border-gray-200 rounded-full p-1 text-gray-400 hover:text-red-600 hover:border-red-200 shadow-sm z-[60] transition-all duration-300 active:scale-90"
            :title="sidebarMinimized ? 'Expand Sidebar' : 'Collapse Sidebar'">
            <svg x-show="!sidebarMinimized" x-cloak class="w-4 h-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <svg x-show="sidebarMinimized" x-cloak class="w-4 h-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- BRAND / LOGO SECTION -->
        <div
            class="flex flex-col items-center justify-center py-4 border-b border-gray-100 bg-white z-10 overflow-hidden shrink-0">
            <a href="{{ route('dashboard') }}"
                class="group transition-transform duration-500 hover:scale-105 flex flex-col items-center">
                <img src="{{ asset('images/logo-DharmaPolimetal.png') }}" alt="Logo Dharma Polimetal"
                    class="h-9 w-auto mt-1 mb-1 drop-shadow-sm transition-all duration-500"
                    :class="sidebarMinimized ? 'h-7' : 'h-9'">
            </a>
            <p x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="text-[9px] font-extrabold text-gray-400 uppercase tracking-[0.15em] whitespace-nowrap px-4">
                PT Dharma Polimetal Tbk.
            </p>
        </div>

        <!-- NAVIGATION LINKS (Scrollable) -->
        <div class="flex-1 overflow-y-auto py-4 space-y-1 scrollbar-thin scrollbar-thumb-gray-200 px-3"
            :class="sidebarMinimized ? 'px-2' : 'px-3'">

            {{-- Helper untuk class active/inactive --}}
            @php
                $activeClass = 'bg-red-50 text-red-700 font-bold shadow-sm ring-1 ring-red-100';
                $inactiveClass = 'text-gray-600 hover:bg-gray-50 hover:text-red-600 transition-all duration-200';
                $iconActive = 'text-red-600 font-bold';
                $iconInactive = 'text-gray-400 group-hover:text-red-500';
            @endphp

            {{-- ================= ROLE: SHE (ADMIN) ================= --}}
            @if(strtolower(Auth::user()->role) == 'she')
                <p x-show="!sidebarMinimized"
                    class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 mt-3">Main Menu</p>
                <div x-show="sidebarMinimized" class="h-3"></div>

                <a href="{{ route('she.dashboard') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-[13px] mb-1 transition-all duration-300 {{ request()->routeIs('she.dashboard') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-10 w-10 mx-auto' : 'px-3 py-1.5'"
                    :title="sidebarMinimized ? 'Dashboard' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('she.dashboard') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Dashboard</span>
                </a>

                <a href="{{ route('she.hazards.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-[13px] mb-1 transition-all duration-300 {{ request()->routeIs('she.hazards.*') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-10 w-10 mx-auto' : 'px-3 py-1.5'"
                    :title="sidebarMinimized ? 'Laporan Bahaya' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('she.hazards.*') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Laporan Bahaya</span>
                </a>

                <a href="{{ route('she.maps.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-[13px] mb-1 transition-all duration-300 {{ request()->routeIs('she.maps.*') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-10 w-10 mx-auto' : 'px-3 py-1.5'"
                    :title="sidebarMinimized ? 'Peta Risiko' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('she.maps.*') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Peta Risiko</span>
                </a>

                <a href="{{ route('she.locations.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-[13px] mb-1 transition-all duration-300 {{ request()->routeIs('she.locations.*') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-10 w-10 mx-auto' : 'px-3 py-1.5'"
                    :title="sidebarMinimized ? 'Master Lokasi' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('she.locations.*') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Master Lokasi</span>
                </a>

                <p x-show="!sidebarMinimized"
                    class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Administrasi</p>
                <div x-show="sidebarMinimized" class="h-6 border-t border-gray-100 mt-2 mx-2"></div>

                <a href="{{ route('she.users.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-[13px] mb-1 transition-all duration-300 {{ request()->routeIs('she.users.*') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-10 w-10 mx-auto' : 'px-3 py-1.5'"
                    :title="sidebarMinimized ? 'Kelola User' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('she.users.*') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A10.99 10.99 0 002.5 10.5a10.99 10.99 0 003.5-5.697m11 5.697a10.99 10.99 0 00-3.5-5.697" />
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Kelola User</span>
                </a>

                {{-- ================= ROLE: SUPERVISOR ================= --}}
            @elseif(strtolower(Auth::user()->role) == 'supervisor')
                <p x-show="!sidebarMinimized"
                    class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2">Supervisor Menu</p>
                <div x-show="sidebarMinimized" class="h-4"></div>

                <a href="{{ route('supervisor.maps.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-[13px] mb-1 transition-all duration-300 {{ request()->routeIs('supervisor.maps.*') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-10 w-10 mx-auto' : 'px-3 py-1.5'"
                    :title="sidebarMinimized ? 'Peta Risiko Gedung' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('supervisor.maps.*') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Peta Risiko
                        Gedung</span>
                </a>

                <a href="#" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-[13px] mb-1 transition-all duration-300 {{ request()->routeIs('supervisor.reports.*') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-10 w-10 mx-auto' : 'px-3 py-1.5'"
                    :title="sidebarMinimized ? 'Laporan' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('supervisor.reports.*') ? $iconActive : $iconInactive }} transition-all"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="!sidebarMinimized" class="whitespace-nowrap">Laporan</span>
                </a>

                {{-- ================= ROLE: KARYAWAN ================= --}}
            @elseif(strtolower(Auth::user()->role) == 'karyawan')
                <p x-show="!sidebarMinimized"
                    class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2">Menu Karyawan</p>
                <div x-show="sidebarMinimized" class="h-4"></div>

                <a href="{{ route('karyawan.dashboard') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-[13px] mb-1 transition-all duration-300 {{ request()->routeIs('karyawan.dashboard') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-10 w-10 mx-auto' : 'px-3 py-1.5'"
                    :title="sidebarMinimized ? 'Dashboard' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('karyawan.dashboard') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Dashboard</span>
                </a>

                <a href="{{ route('karyawan.hazards.create') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-sm mb-1 transition-all duration-300 {{ request()->routeIs('karyawan.hazards.create') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-11 w-11 mx-auto' : 'px-3 py-2'"
                    :title="sidebarMinimized ? 'Buat Laporan Baru' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('karyawan.hazards.create') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Buat Laporan Baru</span>
                </a>

                <a href="{{ route('karyawan.maps.index') }}" @click="sidebarOpen = false"
                    class="group flex items-center rounded-lg text-sm mb-1 transition-all duration-300 {{ request()->routeIs('karyawan.maps.*') ? $activeClass : $inactiveClass }}"
                    :class="sidebarMinimized ? 'px-0 justify-center h-11 w-11 mx-auto' : 'px-3 py-2'"
                    :title="sidebarMinimized ? 'Lihat Peta Risiko' : ''">
                    <svg class="h-5 w-5 {{ request()->routeIs('karyawan.maps.*') ? $iconActive : $iconInactive }} transition-all duration-500"
                        :class="sidebarMinimized ? 'm-0' : 'mr-3'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                    <span x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="whitespace-nowrap">Lihat Peta Risiko</span>
                </a>
            @endif
        </div>

        <!-- USER PROFILE SECTION (Sticky Bottom) -->
        <div
            class="absolute bottom-0 left-0 w-full border-t border-gray-200 p-3 bg-gray-50 transition-all duration-500 overflow-hidden shrink-0">
            <div class="flex items-center" :class="sidebarMinimized ? 'justify-center' : 'justify-between'">
                <div class="flex items-center min-w-0">
                    <div class="flex-shrink-0">
                        <div
                            class="h-9 w-9 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-base border-2 border-white shadow-sm ring-1 ring-red-50 transition-all duration-500">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div x-show="!sidebarMinimized" x-cloak
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-4" class="ml-3 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-500 truncate capitalize font-medium">{{ Auth::user()->role }}
                        </p>
                    </div>
                </div>

                <!-- Logout Button -->
                <div x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-white rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            title="Log Out">
                            <svg class="h-5 w-5 transform rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>
</div>

<style>
    /* Prevent flash of expanded sidebar if it should be minimized */
    .sidebar-minimized-pre aside {
        width: 80px !important;
        /* matches w-20 */
    }

    .sidebar-minimized-pre [x-show="!sidebarMinimized"] {
        display: none !important;
    }
</style>