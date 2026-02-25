<x-app-layout>
    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight capitalize leading-none">
                            Peta Risiko Bahaya</h2>
                        <p class="text-gray-400 font-medium mt-1 tracking-tight uppercase tracking-wider text-[11px]">
                            Lihat peta risiko dan titik bahaya di area operasional.</p>
                    </div>
                </div>

                {{-- Quick Stats Backdrop --}}
                <div
                    class="hidden lg:flex items-center gap-6 px-6 py-3 bg-gray-50/50 border border-gray-100 rounded-2xl">
                    <div class="flex items-center gap-2">
                        <span class="flex h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Live
                            Monitoring</span>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Session Status -->
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 text-green-700 p-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">

                    {{-- Pabrik Maps Section (Visual Viewer) --}}
                    @if($pabrikMap)
                        <div class="mb-10">
                            <div
                                class="flex items-center gap-3 mb-6 bg-gray-50/80 w-fit px-4 py-2 rounded-xl border border-gray-100/50 shadow-sm transition-all hover:bg-white hover:shadown-md group">
                                <div class="flex items-center gap-2 text-gray-400">
                                    <svg class="w-4 h-4 group-hover:text-red-500 transition-colors" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 6.75V15m6-6v8.25m.75 3.3-5.625-2.25L5.25 17.25V6.75L10.125 4.5l5.625 2.25L20.625 4.5V15l-4.875 2.25z" />
                                    </svg>
                                    <span class="text-xs font-bold uppercase tracking-wider">{{ __('Peta Risiko') }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                                <h4 class="text-sm font-extrabold text-red-600 tracking-wide uppercase">
                                    {{ $pabrikMap->name }}
                                </h4>
                            </div>
                            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm p-2 bg-gray-50">
                                <div id="map-viewer"></div>
                            </div>
                            <p class="mt-3 text-xs text-gray-500 italic text-left">
                                <i class="fas fa-info-circle mr-1"></i> Klik pada area gedung di peta untuk melihat detail
                                risiko di dalam gedung tersebut.
                            </p>
                        </div>

                        <script>
                            window.mapData = @json($pabrikMap);
                            window.gedungMaps = @json($gedungMaps);
                        </script>
                        @vite(['resources/js/map-viewer.jsx'])
                    @else
                        <div class="mb-8 p-6 bg-gray-50 border border-dashed border-gray-300 rounded-2xl text-center">
                            <div
                                class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A2 2 0 013 15.382V6m0 0l5.447 2.724a2 2 0 01.553 1.382V19m0 0L15 21m0 0l5.447-2.724A2 2 0 0021 16.618V7m0 0l-5.447 2.724a2 2 0 00-.553 1.382V4m0 0L9 2m0 0l6 2m6 11l-3-9m0 0a3 3 0 10-3 3M9 7l.342 1.026a2 2 0 001.71 1.358h3.083a2 2 0 001.71-1.358L16.2 7">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-gray-700 font-bold mb-1">Visualisasi Navigasi Belum Tersedia</h4>
                            <p class="text-gray-500 text-sm max-w-md mx-auto">Admin SHE belum mengunggah peta tipe "Pabrik".
                                Anda masih dapat mengakses peta gedung secara langsung melalui daftar di bawah.</p>
                        </div>
                    @endif

                    {{-- Gedung Maps Section --}}
                    <div>
                        <div
                            class="flex items-center gap-3 mb-6 bg-gray-50/80 w-fit px-4 py-2 rounded-xl border border-gray-100/50 shadow-sm transition-all hover:bg-white hover:shadown-md group">
                            <div class="flex items-center gap-2 text-gray-400">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Area Kerja</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                            <h4 class="text-sm font-extrabold text-gray-700 tracking-wide uppercase">
                                Daftar Gedung / Pabrik
                            </h4>
                        </div>
                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Nama Gedung</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Ukuran Grid</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">View</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse ($gedungMaps as $map)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-left">
                                                {{ $map->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                                {{ $map->rows }} x {{ $map->cols }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('karyawan.maps.show', $map->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition font-bold text-xs uppercase tracking-wider">
                                                    Lihat Detail Risiko
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">
                                                Belum ada peta gedung yang tersedia.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>