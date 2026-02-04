<x-app-layout>
    @section('page-title', '')
    <x-slot name="header">
        <div class="flex items-center gap-3 py-1">
            <a href="{{ route('she.locations.index') }}"
                class="group inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-100 hover:bg-red-50 transition-all duration-200 shadow-sm"
                title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="flex items-center gap-2 text-sm font-bold uppercase tracking-[0.2em] text-gray-400">
                <span>Location</span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <h2 class="text-gray-900 font-black tracking-tight tracking-normal capitalize text-xl">
                    Tambah Lokasi Baru
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-12">
                    <!-- Header Form -->
                    <div
                        class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-100 pb-8">
                        <div>
                            <div
                                class="flex items-center gap-3 mb-2 bg-gray-50 w-fit px-4 py-1.5 rounded-xl border border-gray-100">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em]">Informasi Lokasi
                                </h3>
                            </div>
                            <p class="text-sm text-gray-400 font-medium">Silakan lengkapi detail identitas dan mapping
                                lokasi risiko.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('she.locations.store') }}" class="space-y-6">
                        @csrf

                        <!-- Nama Lokasi -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lokasi</label>
                            <input type="text" name="name" id="name"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lokasi">
                            @error('name')
                                <p class="text-red-500 text-xs mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Grid Tipe & Induk -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Peta/Gedung (Dipindahkan ke atas sesuai permintaan) -->
                            <div>
                                <label for="map_id" class="block text-sm font-semibold text-gray-700 mb-1">Induk
                                    Peta/Gedung</label>
                                <select name="map_id" id="map_id"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    required>
                                    <option value="">-- Pilih Peta/Gedung --</option>
                                    @foreach ($maps as $map)
                                        <option value="{{ $map->id }}" @selected(old('map_id', request('map_id')) == $map->id)>
                                            {{ $map->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('map_id')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipe Lokasi (Read-only Style) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Lokasi</label>
                                <div
                                    class="flex items-center px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 sm:text-sm cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Area
                                </div>
                                <input type="hidden" name="type" id="type" value="Area">
                            </div>
                        </div>

                        <!-- ID Lokasi (String) -->
                        <div>
                            <label for="location_id_string" class="block text-sm font-semibold text-gray-700 mb-1">ID
                                Lokasi (Unik)</label>
                            <input type="text" name="location_id_string" id="location_id_string"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                value="{{ old('location_id_string') }}" required placeholder="Contoh: KANTIN2_GE">

                            <div class="mt-2 bg-red-50 border-l-4 border-red-400 p-3">
                                <p class="text-xs text-red-700 leading-relaxed">
                                    <strong>Saran Format:</strong> <code
                                        class="bg-red-100 px-1 rounded text-red-800">NAMA_LOKASI</code>_<code
                                        class="bg-red-100 px-1 rounded text-red-800">KODE_GEDUNG</code>
                                    <br>Contoh: <span class="italic text-red-600 font-bold uppercase">KANTIN_GA</span>.
                                    <br>Penamaan yang konsisten memudahkan pencarian dan identifikasi area dalam sistem
                                    Hazard Report.
                                </p>
                            </div>

                            @error('location_id_string')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <a href="{{ route('she.locations.index') }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-5 py-2.5 bg-red-600 border border-transparent rounded-lg font-bold text-sm text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform active:scale-95">
                                Simpan Lokasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>