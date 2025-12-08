<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    {{-- Navigasi Umum (Dashboard) --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- NAVIGASI KHUSUS PERAN SHE --}}
                    @if (Auth::user()->role === 'she')
                        {{-- Tautan Dashboard SHE (Opsional, sudah ada di tautan umum) --}}
                        {{-- <x-nav-link :href="route('she.dashboard')" :active="request()->routeIs('she.dashboard')">
                            {{ __('Dashboard SHE') }}
                        </x-nav-link> --}}

                        {{-- Hazard Reports --}}
                        <x-nav-link :href="route('she.hazards.index')" :active="request()->routeIs('she.hazards.*')">
                            {{ __('Hazard Reports') }}
                        </x-nav-link>
                        
                        {{-- REPORTS BARU --}}
                        <x-nav-link :href="route('she.reports.index')" :active="request()->routeIs('she.reports.*')">
                            <svg class="h-6 w-6 mr-3 stroke-current text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            {{ __('Laporan/Reports') }}
                        </x-nav-link>

                        {{-- Kelola Peta --}}
                        <x-nav-link :href="route('she.maps.index')" :active="request()->routeIs('she.maps.*')">
                            {{ __('Kelola Peta') }}
                        </x-nav-link>

                        {{-- Kelola User --}}
                        <x-nav-link :href="route('she.users.index')" :active="request()->routeIs('she.users.*')">
                            {{ __('Kelola User') }}
                        </x-nav-link>
                    @endif

                    {{-- NAVIGASI KHUSUS PERAN KARYAWAN --}}
                    @if (Auth::user()->role === 'karyawan')
                        {{-- Karyawan Hazard Index (Ini adalah Dashboard Karyawan) --}}
                        <x-nav-link :href="route('karyawan.dashboard')" :active="request()->routeIs('karyawan.dashboard')">
                            {{ __('Laporan Saya') }}
                        </x-nav-link>

                        {{-- Map Karyawan (Melihat Peta) --}}
                        <x-nav-link :href="route('karyawan.maps.index')" :active="request()->routeIs('karyawan.maps.*')">
                            {{ __('Lihat Peta Bahaya') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- RESPONSIVE NAVIGASI KHUSUS PERAN SHE --}}
            @if (Auth::user()->role === 'she')
                <x-responsive-nav-link :href="route('she.hazards.index')" :active="request()->routeIs('she.hazards.*')">
                    {{ __('Hazard Reports') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('she.reports.index')" :active="request()->routeIs('she.reports.*')">
                    {{ __('Laporan/Reports') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('she.maps.index')" :active="request()->routeIs('she.maps.*')">
                    {{ __('Kelola Peta') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('she.users.index')" :active="request()->routeIs('she.users.*')">
                    {{ __('Kelola User') }}
                </x-responsive-nav-link>
            @endif

            {{-- RESPONSIVE NAVIGASI KHUSUS PERAN KARYAWAN --}}
            @if (Auth::user()->role === 'karyawan')
                <x-responsive-nav-link :href="route('karyawan.dashboard')" :active="request()->routeIs('karyawan.dashboard')">
                    {{ __('Laporan Saya') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('karyawan.maps.index')" :active="request()->routeIs('karyawan.maps.*')">
                    {{ __('Lihat Peta Bahaya') }}
                </x-responsive-nav-link>
            @endif

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>