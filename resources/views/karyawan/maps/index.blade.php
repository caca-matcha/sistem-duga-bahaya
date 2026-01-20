<x-app-layout>
    @section('page-title', '')
    
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            {{ __('Peta Risiko Tersedia') }}
        </h2>
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
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 text-left">Peta Risiko Bahaya</h3>
                            <p class="text-sm text-gray-500 text-left">Pilih peta untuk melihat zona risiko di area tersebut.</p>
                        </div>
                    </div>

                    {{-- Pabrik Maps Section (Visual Viewer) --}}
                    @if($pabrikMap)
                        <div class="mb-10">
                            <h4 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                                <span class="w-2 h-6 bg-red-600 rounded-full"></span>
                                Navigasi Utama: {{ $pabrikMap->name }}
                            </h4>
                            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm p-2 bg-gray-50">
                                <div id="map-viewer"></div>
                            </div>
                            <p class="mt-3 text-xs text-gray-500 italic text-left">
                                <i class="fas fa-info-circle mr-1"></i> Klik pada area gedung di peta untuk melihat detail risiko di dalam gedung tersebut.
                            </p>
                        </div>

                        <script>
                            window.mapData = @json($pabrikMap);
                            window.gedungMaps = @json($gedungMaps);
                        </script>
                        @vite(['resources/js/map-viewer.jsx'])
                    @else
                        <div class="mb-8 p-6 bg-gray-50 border border-dashed border-gray-300 rounded-2xl text-center">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V6m0 0l5.447 2.724a2 2 0 01.553 1.382V19m0 0L15 21m0 0l5.447-2.724A2 2 0 0021 16.618V7m0 0l-5.447 2.724a2 2 0 00-.553 1.382V4m0 0L9 2m0 0l6 2m6 11l-3-9m0 0a3 3 0 10-3 3M9 7l.342 1.026a2 2 0 001.71 1.358h3.083a2 2 0 001.71-1.358L16.2 7"></path>
                                </svg>
                            </div>
                            <h4 class="text-gray-700 font-bold mb-1">Visualisasi Navigasi Belum Tersedia</h4>
                            <p class="text-gray-500 text-sm max-w-md mx-auto">Admin SHE belum mengunggah peta tipe "Pabrik". Anda masih dapat mengakses peta gedung secara langsung melalui daftar di bawah.</p>
                        </div>
                    @endif

                    {{-- Gedung Maps Section --}}
                    <div>
                        <h4 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-2 h-6 bg-gray-300 rounded-full"></span>
                            Daftar Gedung / Area
                        </h4>
                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Gedung</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ukuran Grid</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">View</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse ($gedungMaps as $map)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-left">{{ $map->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">{{ $map->rows }} x {{ $map->cols }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('karyawan.maps.show', $map->id) }}" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition font-bold text-xs uppercase tracking-wider">
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
