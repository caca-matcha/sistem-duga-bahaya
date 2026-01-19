<x-app-layout>
    @section('page-title', '')
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('she.locations.index') }}" class="inline-flex items-center justify-center p-2 rounded-full text-indigo-600 hover:bg-indigo-100 transition" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Edit Lokasi: ') . $location->name }}
            </h2>
        </div>
    </x-slot>


    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-12">
                    <div class="mb-8 border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Lokasi</h3>
                        <p class="text-sm text-gray-500">Perbarui detail lokasi: <span class="font-semibold text-indigo-600">{{ $location->name }}</span></p>
                    </div>

                    <form method="POST" action="{{ route('she.locations.update', $location) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lokasi</label>
                            <input type="text" name="name" id="name" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150" 
                                value="{{ old('name', $location->name) }}" required autofocus
                                placeholder="Masukkan nama lokasi">
                            @error('name')
                                <p class="text-red-500 text-xs mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Lokasi</label>
                                <div class="mt-1 flex items-center px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 sm:text-sm cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Area
                                </div>
                                <input type="hidden" name="type" value="Area">
                            </div>

                            <div>
                                <label for="map_id" class="block text-sm font-semibold text-gray-700 mb-1">Induk Peta/Gedung</label>
                                <select name="map_id" id="map_id" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150" 
                                    required>
                                    <option value="">-- Pilih Peta/Gedung --</option>
                                    @foreach ($maps as $map)
                                        <option value="{{ $map->id }}" @selected(old('map_id', $location->map_id) == $map->id)>
                                            {{ $map->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('map_id')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="location_id_string" class="block text-sm font-semibold text-gray-700 mb-1">ID Lokasi (Unik)</label>
                            <input type="text" name="location_id_string" id="location_id_string" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150" 
                                value="{{ old('location_id_string', $location->location_id_string) }}" required>
                            
                            <div class="mt-2 bg-blue-50 border-l-4 border-blue-400 p-3">
                                <p class="text-xs text-blue-700 leading-relaxed">
                                    <strong>Saran Format:</strong> <code class="bg-blue-100 px-1 rounded text-blue-800">NAMA_AREA_KODEGEDUNG</code> 
                                    <br>Contoh: <span class="italic text-blue-600">KANTIN2_GE</span>. Kode gedung 'GE' otomatis menentukan nama gedung di laporan.
                                </p>
                            </div>
                            
                            @error('location_id_string')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <a href="{{ route('she.locations.index') }}" 
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                Batal
                            </a>
                            <button type="submit" 
                                class="px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>