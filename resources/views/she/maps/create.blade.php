<x-app-layout>
    @section('page-title', '')
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('she.maps.index') }}" class="inline-flex items-center justify-center p-2 rounded-full text-red-600 hover:bg-red-100 transition" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Peta Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-12">
                    <!-- Header Form -->
                    <div class="mb-8 border-b border-gray-100 pb-4 text-left">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Peta</h3>
                        <p class="text-sm text-gray-500">Silakan isi detail peta baru yang akan ditambahkan ke sistem.</p>
                    </div>

                    <form action="{{ route('she.maps.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nama Peta -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Peta</label>
                            <input type="text" name="name" id="name" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150" 
                                value="{{ old('name') }}" required autofocus
                                placeholder="Masukkan nama peta">
                            @error('name')
                                <p class="text-red-500 text-xs mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tipe Peta (Read-only Style) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Peta</label>
                            <div class="flex items-center px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 sm:text-sm cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                {{ $type }}
                            </div>
                            <input type="hidden" name="type" id="type_hidden" value="{{ $type }}">
                            @if ($type === 'Pabrik' && $existingPabrikMap)
                                <p class="text-red-500 text-xs mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Peta dengan tipe 'Pabrik' sudah ada. Anda tidak dapat membuat lebih dari satu peta 'Pabrik'.
                                </p>
                            @endif
                        </div>

                        <!-- Parent Map (Conditional) -->
                        @if ($type !== 'Pabrik')
                        <div>
                            <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-1">Peta Induk (Opsional)</label>
                            <select name="parent_id" id="parent_id" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150">
                                <option value="">-- Tidak Ada Peta Induk --</option>
                                @foreach($maps as $mapOption)
                                    <option value="{{ $mapOption->id }}" @selected(old('parent_id') == $mapOption->id)>
                                        {{ $mapOption->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <!-- Background Image -->
                        <div>
                            <label for="background_image" class="block text-sm font-semibold text-gray-700 mb-1">Gambar Latar Belakang (Opsional)</label>
                            <input type="file" name="background_image" id="background_image" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition duration-150">
                            @error('background_image')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Grid Rows & Cols -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Rows -->
                            <div>
                                <label for="rows" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Baris</label>
                                <input type="number" name="rows" id="rows" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150" 
                                    value="{{ old('rows') }}" required
                                    placeholder="Contoh: 10">
                                @error('rows')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cols -->
                            <div>
                                <label for="cols" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Kolom</label>
                                <input type="number" name="cols" id="cols" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150" 
                                    value="{{ old('cols') }}" required
                                    placeholder="Contoh: 10">
                                @error('cols')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <a href="{{ route('she.maps.index') }}" 
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                                Batal
                            </a>
                            <button type="submit" 
                                class="px-5 py-2.5 bg-red-600 border border-transparent rounded-lg font-bold text-sm text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform active:scale-95">
                                Simpan Peta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
