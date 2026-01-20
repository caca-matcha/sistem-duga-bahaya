<x-app-layout>
    @section('page-title', '')

    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Detail Peta: ') . $map->name }}
        </h2>
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
