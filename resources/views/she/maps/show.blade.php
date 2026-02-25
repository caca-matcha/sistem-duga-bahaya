<x-app-layout>
    @section('page-title', '')
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm font-medium text-gray-500">
            <a href="{{ route('she.maps.index') }}"
                class="hover:text-red-600 transition-all duration-200 flex items-center gap-1.5 group">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 6.75V15m6-6v8.25m.75 3.3-5.625-2.25L5.25 17.25V6.75L10.125 4.5l5.625 2.25L20.625 4.5V15l-4.875 2.25z" />
                </svg>
                Peta Risiko
            </a>
            <svg class="h-3 w-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-900 font-bold text-lg tracking-tight">{{ $map->name }}</span>
        </div>
        <div class="mt-1">
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Grid Editor Interface</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="grid-editor"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.mapData = @json($map);
    </script>
    @vite('resources/js/grid-editor.jsx')
</x-app-layout>