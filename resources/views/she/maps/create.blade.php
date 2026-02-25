<x-app-layout>
    @section('page-title', '')
    <x-slot name="header">
        <div class="flex items-center gap-3 py-1">
            <a href="{{ route('she.maps.index') }}"
                class="group inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-100 hover:bg-red-50 transition-all duration-200 shadow-sm"
                title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="flex items-center gap-2 text-sm font-bold uppercase tracking-[0.2em] text-gray-400">
                <span>Maps</span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <h2 class="text-gray-900 font-black tracking-tight tracking-normal capitalize text-xl">
                    Tambah Peta Baru
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
                                <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em]">Informasi Peta
                                </h3>
                            </div>
                            <p class="text-sm text-gray-400 font-medium">Silakan lengkapi detail arsitektur grid dan
                                identitas peta risiko.</p>
                        </div>
                    </div>

                    <form action="{{ route('she.maps.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Nama Peta -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Peta</label>
                            <input type="text" name="name" id="name"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                value="{{ old('name') }}" required autofocus placeholder="Masukkan nama peta">
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

                        <!-- Tipe Peta (Read-only Style) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Peta</label>
                            <div
                                class="flex items-center px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 sm:text-sm cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 6.75V15m6-6v8.25m.75 3.3-5.625-2.25L5.25 17.25V6.75L10.125 4.5l5.625 2.25L20.625 4.5V15l-4.875 2.25z" />
                                </svg>
                                {{ $type }}
                            </div>
                            <input type="hidden" name="type" id="type_hidden" value="{{ $type }}">
                        </div>

                        <!-- Parent Map (Conditional) -->
                        @if ($type !== 'Pabrik')
                            <div>
                                <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-1">Peta Induk
                                    (Opsional)</label>
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
                            <label for="background_image" class="block text-sm font-semibold text-gray-700 mb-1">Gambar
                                Latar Belakang (Opsional)</label>
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
                                <label for="rows" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah
                                    Baris</label>
                                <input type="number" name="rows" id="rows"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('rows') }}" required placeholder="Contoh: 10">
                                @error('rows')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cols -->
                            <div>
                                <label for="cols" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah
                                    Kolom</label>
                                <input type="number" name="cols" id="cols"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    value="{{ old('cols') }}" required placeholder="Contoh: 10">
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