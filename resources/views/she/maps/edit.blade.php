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
                    Edit Peta: {{ $map->name }}
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
                                <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em]">Konfigurasi Peta
                                </h3>
                            </div>
                            <p class="text-sm text-gray-400 font-medium">Perbarui struktur grid dan identitas peta
                                risiko: <span class="font-bold text-red-600">{{ $map->name }}</span></p>
                        </div>
                    </div>

                    <form action="{{ route('she.maps.update', $map->id) }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nama Peta -->
                        <div>
                            <label for="name"
                                class="block text-sm font-semibold text-gray-700 mb-1 leading-none uppercase tracking-widest text-[10px] mb-2">Nama
                                Peta</label>
                            <input type="text" name="name" id="name"
                                class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 py-3 px-4"
                                value="{{ old('name', $map->name) }}" required autofocus
                                placeholder="Masukkan nama peta">
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

                        <!-- Tipe Peta (Read-only Style for consistency) -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1 leading-none uppercase tracking-widest text-[10px] mb-2">Tipe
                                Peta</label>
                            <div
                                class="flex items-center px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-gray-500 font-bold sm:text-sm cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 6.75V15m6-6v8.25m.75 3.3-5.625-2.25L5.25 17.25V6.75L10.125 4.5l5.625 2.25L20.625 4.5V15l-4.875 2.25z" />
                                </svg>
                                {{ $map->type }}
                            </div>
                            <input type="hidden" name="type" value="{{ $map->type }}">
                        </div>

                        <!-- Parent Map (Conditional) -->
                        @if ($map->type !== 'Pabrik')
                            <div>
                                <label for="parent_id"
                                    class="block text-sm font-semibold text-gray-700 mb-1 leading-none uppercase tracking-widest text-[10px] mb-2">Peta
                                    Induk (Lokasi)</label>
                                <select name="parent_id" id="parent_id"
                                    class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 py-3 px-4">
                                    <option value="">-- Tidak Ada Peta Induk --</option>
                                    @foreach($maps as $mapOption)
                                        @if($mapOption->id !== $map->id)
                                            <option value="{{ $mapOption->id }}" @selected(old('parent_id', $map->parent_id) == $mapOption->id)>
                                                {{ $mapOption->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="parent_id" value="">
                        @endif

                        <!-- Background Image -->
                        <div>
                            <label for="background_image"
                                class="block text-sm font-semibold text-gray-700 mb-1 leading-none uppercase tracking-widest text-[10px] mb-2">Gambar
                                Latar Belakang (Opsional)</label>

                            @if ($map->background_image)
                                <div class="mb-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-6">
                                    <div class="relative group">
                                        <img src="{{ route('files.public', ['path' => $map->background_image]) }}"
                                            alt="Current background image"
                                            class="h-32 w-48 object-cover rounded-xl shadow-sm border border-white ring-1 ring-gray-200">
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                            <span
                                                class="text-white text-[10px] font-black tracking-widest uppercase">Current
                                                Image</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider italic">
                                            Ganti Gambar?</p>
                                        <p class="text-xs text-gray-400 leading-relaxed max-w-xs">Pilih file baru di bawah
                                            jika ingin mengganti gambar latar belakang saat ini.</p>
                                    </div>
                                </div>
                            @endif

                            <div class="relative">
                                <input type="file" name="background_image" id="background_image"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-red-50 file:text-red-600 hover:file:bg-red-600 hover:file:text-white transition-all cursor-pointer bg-white border border-gray-100 rounded-xl">
                            </div>
                            @error('background_image')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Grid Rows & Cols -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                            <!-- Rows -->
                            <div>
                                <label for="rows"
                                    class="block text-sm font-semibold text-gray-700 mb-1 leading-none uppercase tracking-widest text-[10px] mb-2">Jumlah
                                    Baris</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-xs font-bold">R</span>
                                    </div>
                                    <input type="number" name="rows" id="rows"
                                        class="block w-full pl-8 rounded-xl border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 py-3 px-4"
                                        value="{{ old('rows', $map->rows) }}" required placeholder="Contool: 10">
                                </div>
                                @error('rows')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cols -->
                            <div>
                                <label for="cols"
                                    class="block text-sm font-semibold text-gray-700 mb-1 leading-none uppercase tracking-widest text-[10px] mb-2">Jumlah
                                    Kolom</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-xs font-bold">C</span>
                                    </div>
                                    <input type="number" name="cols" id="cols"
                                        class="block w-full pl-8 rounded-xl border-gray-200 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150 py-3 px-4"
                                        value="{{ old('cols', $map->cols) }}" required placeholder="Contoh: 10">
                                </div>
                                @error('cols')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-8 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <a href="{{ route('she.maps.index') }}"
                                class="px-6 py-3 text-xs font-black uppercase tracking-widest text-gray-500 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-8 py-3 bg-gray-900 border-b-4 border-gray-700 rounded-xl font-black text-xs text-white uppercase tracking-[0.2em] shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all transform active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>