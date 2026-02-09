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
    sidebarMinimized: localStorage.getItem('sidebarMinimized') !== 'false'
}" x-init="
    $watch('sidebarMinimized', value => localStorage.setItem('sidebarMinimized', value));
    document.documentElement.classList.remove('sidebar-minimized-pre');
" class="relative">

    <!-- TOGGLE BUTTON MOBILE (Image 1 Style - Attached) -->
    <button @click="sidebarMinimized = !sidebarMinimized"
        class="lg:hidden fixed top-1/2 -translate-y-1/2 bg-white/30 backdrop-blur-md border-y border-r border-white/40 text-red-600 rounded-r-xl p-2 shadow-sm hover:bg-white/50 z-[80] transition-all duration-500 hover:scale-105 active:scale-95"
        :class="sidebarMinimized ? 'left-0' : 'left-64'" :title="sidebarMinimized ? 'Buka Menu' : 'Tutup Menu'">
        <svg x-show="!sidebarMinimized" class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
        </svg>
        <svg x-show="sidebarMinimized" class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <!-- TOGGLE BUTTON PC (Image 2 Style) -->
    <button @click="sidebarMinimized = !sidebarMinimized"
        class="hidden lg:flex fixed top-10 w-7 h-7 bg-white border border-gray-200 text-gray-500 rounded-full shadow-sm hover:shadow-md hover:text-red-600 items-center justify-center z-[80] transition-all duration-500 hover:scale-110 active:scale-95"
        :class="sidebarMinimized ? 'left-[66px]' : 'left-[242px]'"
        :title="sidebarMinimized ? 'Buka Menu' : 'Tutup Menu'">
        <svg x-show="!sidebarMinimized" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
        </svg>
        <svg x-show="sidebarMinimized" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <!-- MOBILE BACKDROP (Shows when sidebar is open on mobile) -->
    <div x-show="!sidebarMinimized" x-cloak x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-400" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarMinimized = true"
        class="lg:hidden fixed inset-0 bg-white/40 backdrop-blur-[2px] z-[65] transition-opacity">
    </div>

    <!-- Sidebar -->
    <aside x-bind:class="{
            'w-64': !sidebarMinimized,
            'w-0 lg:w-20': sidebarMinimized
        }"
        class="fixed lg:sticky lg:top-0 inset-y-0 left-0 z-[70] flex flex-col bg-white border-r border-gray-200 h-screen transition-[width] duration-500 ease-in-out">

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

            {{-- Configuration for iOS Style Icons --}}
            @php
                // Link base styles
                $linkCommon = 'group flex items-center rounded-2xl mb-2 transition-all duration-300';
                $linkActive = 'text-gray-900 font-bold';
                $linkInactive = 'text-gray-500 hover:text-red-600';

                // Icon Container styles (The "Squaricle")
                $iconBoxCommon = 'flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-[14px] transition-all duration-500 ease-out border border-transparent';

                // Active: Red bg, White icon, Shadow
                $iconBoxActive = 'bg-red-600 text-white shadow-lg shadow-red-200 scale-100 border-red-500';

                // Inactive: Gray bg, Gray icon -> Hover: Red bg tint, Red icon, Scale up
                $iconBoxInactive = 'bg-gray-50 text-gray-400 group-hover:bg-red-50 group-hover:text-red-600 group-hover:scale-110 group-hover:border-red-100 group-hover:shadow-sm';
            @endphp

            {{-- ================= ROLE: SHE (ADMIN) ================= --}}
            @if(strtolower(Auth::user()->role) == 'she')
                <p x-show="!sidebarMinimized"
                    class="px-3 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 mt-2">Main Menu</p>
                <div x-show="sidebarMinimized" class="h-3"></div>

                {{-- Dashboard --}}
                <a href="{{ route('she.dashboard') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('she.dashboard') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Dashboard' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('she.dashboard') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 4h7v7H4V4zm0 9h7v7H4v-7zm9-9h7v7h-7V4zm0 9h7v7h-7v-7z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Dashboard</span>
                </a>

                {{-- Laporan Bahaya --}}
                <a href="{{ route('she.hazards.index') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('she.hazards.*') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Laporan Bahaya' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('she.hazards.*') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2.2c-.6 0-1.1.3-1.4.8L1.4 18.5c-.3.5-.3 1.1 0 1.6.3.5.8.8 1.4.8h18.3c.6 0 1.1-.3 1.4-.8.3-.5.3-1.1 0-1.6L13.4 3c-.3-.5-.8-.8-1.4-.8zM11 8h2v5h-2V8zm1 9c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Laporan Bahaya</span>
                </a>

                {{-- Peta Risiko --}}
                <a href="{{ route('she.maps.index') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('she.maps.*') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Peta Risiko' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('she.maps.*') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M15 19l-6-2.1 -5.2 2.6c-.4.2-.9.1-1.2-.2C2.1 19 2 18.6 2 18.2V5.6c0-.4.2-.7.5-.9.3-.2.7-.3 1.1-.1L9 7.1l6-2.1 5.2 2.6c.4.2.9.1 1.2-.2.4-.3.5-.7.5-1.1v12.6c0 .4-.2.7-.5.9-.3.2-.7.3-1.1.1L15 19z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Peta Risiko</span>
                </a>

                {{-- Master Lokasi --}}
                <a href="{{ route('she.locations.index') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('she.locations.*') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Master Lokasi' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('she.locations.*') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" fill-rule="evenodd"
                            clip-rule="evenodd">
                            <path
                                d="M19 9c0 5.2-7 13-7 13S5 14.2 5 9c0-3.9 3.1-7 7-7s7 3.1 7 7zm-7 2.5c1.4 0 2.5-1.1 2.5-2.5S13.4 6.5 12 6.5 9.5 7.6 9.5 9s1.1 2.5 2.5 2.5z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Master Lokasi</span>
                </a>

                <p x-show="!sidebarMinimized"
                    class="px-3 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 mt-6">System</p>
                <div x-show="sidebarMinimized" class="h-6 border-t border-gray-100 mt-2 mx-2"></div>

                {{-- Kelola User --}}
                <a href="{{ route('she.users.index') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('she.users.*') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Kelola User' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('she.users.*') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M9 11c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm7 0c1.7 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.3 3 3 3zm-7 2c-2.7 0-8 1.3-8 4v2h16v-2c0-2.7-5.3-4-8-4zm7 0c-.3 0-.6 0-.9.1 1.2 1.2 1.9 2.8 1.9 4.6v1.3H22v-2c0-2.7-5.3-4-8-4z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Kelola User</span>
                </a>

                {{-- ================= ROLE: SUPERVISOR ================= --}}
            @elseif(strtolower(Auth::user()->role) == 'supervisor')
                <p x-show="!sidebarMinimized"
                    class="px-3 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 mt-2">Menu</p>
                <div x-show="sidebarMinimized" class="h-4"></div>

                {{-- Peta Risiko --}}
                <a href="{{ route('supervisor.maps.index') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('supervisor.maps.*') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Peta Risiko Gedung' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('supervisor.maps.*') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M15 19l-6-2.1 -5.2 2.6c-.4.2-.9.1-1.2-.2C2.1 19 2 18.6 2 18.2V5.6c0-.4.2-.7.5-.9.3-.2.7-.3 1.1-.1L9 7.1l6-2.1 5.2 2.6c.4.2.9.1 1.2-.2.4-.3.5-.7.5-1.1v12.6c0 .4-.2.7-.5.9-.3.2-.7.3-1.1.1L15 19z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Peta Risiko Gedung</span>
                </a>

                {{-- Laporan --}}
                <a href="#" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('supervisor.reports.*') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Laporan' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('supervisor.reports.*') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Laporan</span>
                </a>

                {{-- ================= ROLE: KARYAWAN ================= --}}
            @elseif(strtolower(Auth::user()->role) == 'karyawan')
                <p x-show="!sidebarMinimized"
                    class="px-3 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 mt-2">Menu Karyawan
                </p>
                <div x-show="sidebarMinimized" class="h-4"></div>

                {{-- Dashboard --}}
                <a href="{{ route('karyawan.dashboard') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('karyawan.dashboard') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Dashboard' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('karyawan.dashboard') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 4h7v7H4V4zm0 9h7v7H4v-7zm9-9h7v7h-7V4zm0 9h7v7h-7v-7z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Dashboard</span>
                </a>

                {{-- Buat Laporan --}}
                <a href="{{ route('karyawan.hazards.create') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('karyawan.hazards.create') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Buat Laporan Baru' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('karyawan.hazards.create') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Buat Laporan Baru</span>
                </a>

                {{-- Lihat Peta --}}
                <a href="{{ route('karyawan.maps.index') }}" @click="sidebarOpen = false"
                    class="{{ $linkCommon }} {{ request()->routeIs('karyawan.maps.*') ? $linkActive : $linkInactive }}"
                    :class="sidebarMinimized ? 'justify-center mx-auto' : 'px-2'"
                    :title="sidebarMinimized ? 'Lihat Peta Risiko' : ''">

                    <div class="{{ $iconBoxCommon }} {{ request()->routeIs('karyawan.maps.*') ? $iconBoxActive : $iconBoxInactive }}"
                        :class="sidebarMinimized ? '' : 'mr-3'">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M15 19l-6-2.1 -5.2 2.6c-.4.2-.9.1-1.2-.2C2.1 19 2 18.6 2 18.2V5.6c0-.4.2-.7.5-.9.3-.2.7-.3 1.1-.1L9 7.1l6-2.1 5.2 2.6c.4.2.9.1 1.2-.2.4-.3.5-.7.5-1.1v12.6c0 .4-.2.7-.5.9-.3.2-.7.3-1.1.1L15 19z" />
                        </svg>
                    </div>

                    <span x-show="!sidebarMinimized" class="text-[13px] whitespace-nowrap">Lihat Peta Risiko</span>
                </a>
            @endif
        </div>

        <!-- USER PROFILE SECTION (Sticky Bottom) -->
        <div x-bind:class="sidebarMinimized ? 'p-2' : 'p-3'"
            class="absolute bottom-0 left-0 w-full border-t border-gray-200 bg-gray-50 transition-all duration-500 overflow-hidden shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center min-w-0" :class="sidebarMinimized ? 'mx-auto' : ''">
                    <div class="flex-shrink-0">
                        <div
                            class="h-9 w-9 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-base border-2 border-white shadow-sm ring-1 ring-red-50 transition-all duration-500">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div x-show="!sidebarMinimized" class="ml-3 min-w-0">
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