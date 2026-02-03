<x-app-layout>
    @section('page-title', '')

    <x-slot name="header">
        <div
            class="flex items-center gap-3 bg-white/50 w-fit px-4 py-1.5 rounded-xl border border-gray-200/50 shadow-sm group">
            <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-4 h-4 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
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