<x-app-layout>
    @section('page-title', '')

    <x-slot name="header">
        <div
            class="flex items-center gap-3 bg-white/50 w-fit px-4 py-1.5 rounded-xl border border-gray-200/50 shadow-sm group">
            <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-4 h-4 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 6.75V15m6-6v8.25m.75 3.3-5.625-2.25L5.25 17.25V6.75L10.125 4.5l5.625 2.25L20.625 4.5V15l-4.875 2.25z" />
                </svg>
                <a href="{{ route('karyawan.maps.index') }}"
                    class="text-xs font-bold uppercase tracking-wider hover:text-red-600 transition-colors">{{ __('Peta Risiko') }}</a>
            </div>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <h2 class="font-extrabold text-lg text-gray-800 leading-tight tracking-tight uppercase">
                {{ $map->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="map-viewer"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.mapData = @json($map);
    </script>
    @vite('resources/js/map-viewer.jsx')
</x-app-layout>