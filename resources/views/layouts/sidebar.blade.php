<!-- Sidebar -->
<div class="flex flex-col w-64 bg-white text-gray-800 shadow-lg">
    <!-- Logo -->
    <div class="flex items-center justify-center h-20 border-b border-gray-200">
        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-gray-900 hover:text-gray-800">
             <x-application-logo class="block h-10 w-auto fill-current text-red-600" />
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-2 py-4 space-y-2">
        @if(Auth::user()->role == 'she')
            {{-- Admin (SHE) Links --}}
            <a href="{{ route('she.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg transition duration-200 hover:bg-red-50 hover:text-red-700 @if(request()->routeIs('she.dashboard')) bg-red-50 text-red-700 border-l-4 border-red-600 @else text-gray-700 @endif">
                <svg class="h-6 w-6 mr-3 stroke-current @if(request()->routeIs('she.dashboard')) text-red-700 @else text-gray-600 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('she.maps.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition duration-200 hover:bg-red-50 hover:text-red-700 @if(request()->routeIs('she.maps.*')) bg-red-50 text-red-700 border-l-4 border-red-600 @else text-gray-700 @endif">
                <svg class="h-6 w-6 mr-3 stroke-current @if(request()->routeIs('she.maps.*')) text-red-700 @else text-gray-600 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" /></svg>
                Maps
            </a>
            <a href="{{ route('she.users.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition duration-200 hover:bg-red-50 hover:text-red-700 @if(request()->routeIs('she.users.*')) bg-red-50 text-red-700 border-l-4 border-red-600 @else text-gray-700 @endif">
                <svg class="h-6 w-6 mr-3 stroke-current @if(request()->routeIs('she.users.*')) text-red-700 @else text-gray-600 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A10.99 10.99 0 002.5 10.5a10.99 10.99 0 003.5-5.697m11 5.697a10.99 10.99 0 00-3.5-5.697" /></svg>
                Users
            </a>
            <a href="{{ route('karyawan.maps.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition duration-200 hover:bg-red-50 hover:text-red-700 @if(request()->routeIs('karyawan.maps.*')) bg-red-50 text-red-700 border-l-4 border-red-600 @else text-gray-700 @endif">
                <svg class="h-6 w-6 mr-3 stroke-current @if(request()->routeIs('karyawan.maps.*')) text-red-700 @else text-gray-600 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" /></svg>
                View Employee Risk Maps
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 rounded-lg transition duration-200 hover:bg-red-50 hover:text-red-700 text-gray-700">
                <svg class="h-6 w-6 mr-3 stroke-current text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Reports
            </a>

        @elseif(Auth::user()->role == 'supervisor')
            {{-- Supervisor Links --}}
            <a href="{{ route('supervisor.maps.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition duration-200 hover:bg-red-50 hover:text-red-700 @if(request()->routeIs('supervisor.maps.*')) bg-red-50 text-red-700 border-l-4 border-red-600 @else text-gray-700 @endif">
                <svg class="h-6 w-6 mr-3 stroke-current @if(request()->routeIs('supervisor.maps.*')) text-red-700 @else text-gray-600 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" /></svg>
                View Risk Maps
            </a>
            <a href="#" class="flex items-center px-4 py-2.5 rounded-lg transition duration-200 hover:bg-red-50 hover:text-red-700 text-gray-700">
                <svg class="h-6 w-6 mr-3 stroke-current text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Reports
            </a>

        @elseif(Auth::user()->role == 'karyawan')
            {{-- Employee (Karyawan) Links --}}
            <a href="{{ route('karyawan.maps.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition duration-200 hover:bg-red-50 hover:text-red-700 @if(request()->routeIs('karyawan.maps.*')) bg-red-50 text-red-700 border-l-4 border-red-600 @else text-gray-700 @endif">
                <svg class="h-6 w-6 mr-3 stroke-current @if(request()->routeIs('karyawan.maps.*')) text-red-700 @else text-gray-600 @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z" /></svg>
                View Risk Maps
            </a>
        @endif
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                 <svg class="h-10 w-10 rounded-full fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="text-xs text-gray-600 hover:text-red-700">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>