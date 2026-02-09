<x-app-layout>
    @section('page-title', '')

    <div class="py-8 bg-gradient-to-br from-gray-50 via-red-50/20 to-gray-50 min-h-screen">
        <div class="max-w-[96%] mx-auto sm:px-6 lg:px-8">

            {{-- Header Form --}}
            <div class="mb-6 text-center animate-fade-in">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-glow-red mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl font-black text-gray-900 mb-1">Formulir Laporan Duga Bahaya</h1>
                <p class="text-xs text-gray-600 max-w-xl mx-auto">
                    Lengkapi data di bawah ini untuk melaporkan potensi bahaya K3. Laporan Anda membantu menciptakan
                    lingkungan kerja yang lebih aman.
                </p>
            </div>

            @if ($errors->any())
                <div
                    class="mb-8 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-5 rounded-2xl shadow-soft animate-slide-down">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-base font-bold text-red-800 mb-2">Mohon perbaiki kesalahan berikut:</h3>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start text-sm text-red-700">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('karyawan.hazards.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- SECTION 1: DATA PELAPOR --}}
                <div
                    class="bg-white overflow-hidden shadow-soft sm:rounded-2xl mb-6 border border-gray-100 animate-slide-up">
                    <div
                        class="px-4 py-2.5 bg-gradient-to-r from-gray-50 to-red-50/30 border-b border-gray-100 flex items-center">
                        <div class="bg-gradient-to-br from-red-100 to-red-200 rounded-xl p-2 mr-3 shadow-sm">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">1. Data Pelapor</h2>
                            <p class="text-[10px] text-gray-500 mt-0.5">Informasi pelapor terisi otomatis</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5 grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Pelapor</label>
                            <input type="text" disabled value="{{ auth()->user()->name }}"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed shadow-sm font-medium">
                        </div>
                        <div>
                            <label for="NPK" class="block text-xs font-bold text-gray-700 mb-1.5">NPK <span
                                    class="text-red-500">*</span></label>
                            <input type="text" disabled value="{{ auth()->user()->npk ?? 'N/A' }}"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed shadow-sm font-medium">
                            <input type="hidden" name="NPK" value="{{ auth()->user()->npk }}">
                        </div>
                        <div>
                            <label for="dept" class="block text-xs font-bold text-gray-700 mb-1.5">Departemen <span
                                    class="text-red-500">*</span></label>
                            <input type="text" disabled value="{{ auth()->user()->department ?? 'N/A' }}"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 text-gray-600 cursor-not-allowed shadow-sm font-medium">
                            <input type="hidden" name="dept" value="{{ auth()->user()->department }}">
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: LOKASI & WAKTU --}}
                <div x-data="{
                    selectedMapId: '{{ old('map_id', request('map_id')) }}',
                    allLocations: [],
                    filteredLocations: [],
                    selectedLocationId: '{{ old('location_id', request('location_id')) }}',
                    selectedCellId: '{{ old('cell_id', request('cell_id')) }}',
                    selectedMapImage: '',
                    selectedMapName: '{{ old('area_gedung') }}', // New property for map name
                    maps: [],

                    init() {
                        this.fetchMaps();
                        this.fetchAllLocations();

                        // Watch for changes in selectedMapId and filter locations
                        this.$watch('selectedMapId', (value) => {
                            this.filterLocations(value);
                            this.updateMapImage(value);
                        });
                    },

                    async fetchMaps() {
                        try {
                            const response = await fetch('/api/maps');
                            this.maps = await response.json();
                            // If old map_id exists, ensure it's selected and image is shown
                            if (this.selectedMapId) {
                                this.updateMapImage(this.selectedMapId);
                            }
                        } catch (error) {
                            console.error('Error fetching maps:', error);
                        }
                    },

                    async fetchAllLocations() {
                        try {
                            const response = await fetch('/api/locations');
                            const data = await response.json();
                            this.allLocations = data;
                            this.filterLocations(this.selectedMapId); // Initial filter based on old value
                            // If old location_id exists, ensure it's selected
                            if (this.selectedLocationId) {
                                this.$nextTick(() => {
                                    document.getElementById('location_id').value = this.selectedLocationId;
                                });
                            }
                        } catch (error) {
                            console.error('Error fetching all locations:', error);
                        }
                    },

                    filterLocations(mapId) {
                        if (mapId) {
                            this.filteredLocations = this.allLocations.filter(location => location.map_id == mapId);
                        } else {
                            this.filteredLocations = this.allLocations; // Show all if no map selected
                        }
                        // Reset selected location if the previously selected one is no longer in filtered list
                        if (this.selectedLocationId && !this.filteredLocations.some(loc => loc.id == this.selectedLocationId)) {
                             // Only reset if not explicitly passed via URL
                             if (!new URLSearchParams(window.location.search).has('location_id')) {
                                this.selectedLocationId = '';
                             }
                        }
                    },

                    updateMapImage(mapId) {
                        const map = this.maps.find(m => m.id == mapId);
                        this.selectedMapImage = map ? `{{ asset('storage') }}/${map.background_image}` : '';
                        this.selectedMapName = map ? map.name : ''; // Set map name
                    }
                }" class="bg-white overflow-hidden shadow-soft sm:rounded-2xl mb-6 border border-gray-100 animate-slide-up"
                    style="animation-delay: 0.1s">
                    <div
                        class="px-4 py-2.5 bg-gradient-to-r from-gray-50 to-red-50/30 border-b border-gray-100 flex items-center">
                        <div class="bg-gradient-to-br from-red-100 to-red-200 rounded-xl p-2 mr-3 shadow-sm">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">2. Lokasi & Waktu Observasi</h2>
                            <p class="text-[10px] text-gray-500 mt-0.5">Tentukan lokasi dan waktu kejadian</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="tgl_observasi" class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal
                                Kejadian <span class="text-red-500">*</span></label>
                            <input id="tgl_observasi" name="tgl_observasi" type="date"
                                value="{{ old('tgl_observasi', date('Y-m-d')) }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm input-focus font-medium" required>
                            @error('tgl_observasi')
                                <p class="mt-1.5 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="map_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Gedung (Map)
                                <span class="text-red-500">*</span></label>
                            <select id="map_id" name="map_id" x-model="selectedMapId"
                                class="w-full rounded-xl border-gray-300 shadow-sm input-focus font-medium" required>
                                <option value="">-- Pilih Gedung --</option>
                                <template x-for="map in maps" :key="map.id">
                                    <option :value="map.id" x-text="map.name"></option>
                                </template>
                            </select>
                            <input type="hidden" name="area_gedung" x-model="selectedMapName">
                            <input type="hidden" name="cell_id" x-model="selectedCellId">
                        </div>

                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">Lokasi
                                Kejadian <span class="text-red-500">*</span></label>
                            <select id="location_id" name="location_id" x-model="selectedLocationId"
                                :disabled="!selectedMapId"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 transition duration-200 shadow-sm">
                                <option value="">-- Pilih Lokasi --</option>
                                <template x-for="location in filteredLocations" :key="location.id">
                                    <option :value="location.id"
                                        x-text="`${location.name} (${location.location_id_string}) - ${location.type}`">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="lokasi_detail_manual"
                                class="block text-sm font-medium text-gray-700 mb-1">Detail Tambahan Lokasi</label>
                            <textarea id="lokasi_detail_manual" name="lokasi_detail_manual" rows="2"
                                placeholder="Contoh: Di dekat mesin press No. 5, pilar C-12"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 transition duration-200 shadow-sm">{{ old('lokasi_detail_manual') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1 italic">Isi jika perlu memberikan detail yang lebih
                                spesifik dari pilihan lokasi.</p>
                        </div>

                        {{-- Image Display Area --}}
                        <div x-show="selectedMapImage"
                            class="md:col-span-2 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 shadow-sm relative aspect-video flex items-center justify-center">
                            <img :src="selectedMapImage" alt="Gambar Gedung"
                                class="max-w-full max-h-full object-contain">
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 text-white font-bold text-lg opacity-0 hover:opacity-100 transition-opacity">
                                Visualisasi Gedung
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: DETAIL TEMUAN --}}
                <div class="bg-white overflow-hidden shadow-soft sm:rounded-2xl mb-6 border border-gray-100 animate-slide-up"
                    style="animation-delay: 0.2s">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-gray-50 to-red-50/30 border-b border-gray-100 flex items-center">
                        <div class="bg-gradient-to-br from-red-100 to-red-200 rounded-xl p-2.5 mr-3 shadow-sm">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">3. Detail Temuan</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Deskripsikan bahaya yang ditemukan</p>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Kategori STOP6 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Bahaya (Kategori STOP-6)
                                <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @php
                                    $categories = [
                                        'A' => 'Aparatus (Terjepit/Tergores)',
                                        'B' => 'Big Heavy (Tertimpa)',
                                        'C' => 'Car (Kendaraan)',
                                        'D' => 'Drop (Jatuh/Terpeleset)',
                                        'E' => 'Electrical (Listrik)',
                                        'F' => 'Fire (Api/Panas)',
                                        'O' => 'Others (Kimia/Lainnya)'
                                    ];
                                @endphp
                                @foreach($categories as $key => $label)
                                    <label
                                        class="relative flex items-center p-3 rounded-lg border cursor-pointer hover:bg-red-50 transition-colors focus-within:ring-2 focus-within:ring-red-500">
                                        <input type="radio" name="kategori_stop6" value="{{ $key }}"
                                            class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500" {{ old('kategori_stop6') === $key ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700 font-medium">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('kategori_stop6')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi & Foto --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="deskripsi_bahaya"
                                    class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Bahaya <span
                                        class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mb-1 italic">Contoh pengisian: Operator terlihat
                                    berjalan di luar safety line, berpotensi tertabrak forklift.</p>
                                <textarea id="deskripsi_bahaya" name="deskripsi_bahaya" rows="5"
                                    placeholder="Tuliskan detail temuan bahaya..."
                                    class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 transition duration-200 shadow-sm">{{ old('deskripsi_bahaya') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti</label>
                                <p class="text-xs text-gray-500 mb-1 italic">Ambil foto secara jelas agar potensi bahaya
                                    dapat terlihat dengan baik.</p>

                                <!-- Drop Zone -->
                                <div id="drop-zone"
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                    <div class="space-y-1 text-center">
                                        <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400"
                                            stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="foto_bukti"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none">
                                                <span>Upload file</span>
                                                <input id="foto_bukti" name="foto_bukti" type="file" class="sr-only"
                                                    accept="image/png,image/jpeg,image/jpg">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div id="image-preview-container" class="mt-4 hidden">
                                    <div class="relative">
                                        <img id="image-preview" src="" alt="Preview"
                                            class="w-full h-64 object-cover rounded-lg border-2 border-gray-300">
                                        <button type="button" id="remove-image"
                                            class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-2 hover:bg-red-700 transition shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p id="file-name-display" class="text-xs text-gray-600 mt-2 italic text-center"></p>
                                </div>

                                @error('foto_bukti')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Ide Penanggulangan --}}
                        <div>
                            <label for="ide_penanggulangan" class="block text-xs font-bold text-gray-700 mb-1.5">Usulan
                                Perbaikan / Pencegahan</label>
                            <textarea id="ide_penanggulangan" name="ide_penanggulangan" rows="3"
                                placeholder="Apa saran Anda untuk menghilangkan bahaya ini?"
                                class="w-full rounded-xl border-gray-300 shadow-sm input-focus font-medium">{{ old('ide_penanggulangan') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: ANALISIS RISIKO (Risk Matrix) --}}
                <div class="bg-white overflow-hidden shadow-soft sm:rounded-2xl mb-6 border border-gray-100 relative animate-slide-up"
                    style="animation-delay: 0.3s">
                    <div
                        class="px-4 py-2.5 bg-gradient-to-r from-gray-50 to-yellow-50/30 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl p-2 mr-3 shadow-sm">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-800">4. Rate Risiko Bahaya</h2>
                                <p class="text-[10px] text-gray-500 mt-0.5">Tentukan tingkat keparahan dan kemungkinan
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col md:flex-row gap-4 sm:gap-6">
                            {{-- Input Columns --}}
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label for="tingkat_keparahan"
                                        class="block text-xs font-bold text-gray-700 mb-1.5">Tingkat Keparahan
                                        (Severity) <span class="text-red-500">*</span></label>
                                    <select id="tingkat_keparahan" name="tingkat_keparahan"
                                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition duration-200 shadow-sm">
                                        <option value="">Pilih Severity</option>
                                        <option value="5" {{ old('tingkat_keparahan') == '5' ? 'selected' : '' }}>
                                            Kecelakaan Fatal (A)</option>
                                        <option value="3" {{ old('tingkat_keparahan') == '3' ? 'selected' : '' }}>Hilang
                                            Hari Kerja (B)</option>
                                        <option value="1" {{ old('tingkat_keparahan') == '1' ? 'selected' : '' }}>Luka
                                            Ringan (C)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="kemungkinan_terjadi"
                                        class="block text-xs font-bold text-gray-700 mb-1.5">Kemungkinan Terjadi
                                        (Probability) <span class="text-red-500">*</span></label>
                                    <select id="kemungkinan_terjadi" name="kemungkinan_terjadi"
                                        class="w-full rounded-xl border-gray-300 shadow-sm input-focus font-medium">
                                        <option value="">Pilih Probability</option>
                                        <option value="1" {{ old('kemungkinan_terjadi') == 1 ? 'selected' : '' }}>Sangat
                                            Jarang</option>
                                        <option value="2" {{ old('kemungkinan_terjadi') == 2 ? 'selected' : '' }}>Jarang
                                        </option>
                                        <option value="3" {{ old('kemungkinan_terjadi') == 3 ? 'selected' : '' }}>
                                            Kadang-Kadang</option>
                                        <option value="4" {{ old('kemungkinan_terjadi') == 4 ? 'selected' : '' }}>Sering
                                        </option>
                                        <option value="5" {{ old('kemungkinan_terjadi') == 5 ? 'selected' : '' }}>Sangat
                                            Sering</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="flex items-center justify-end gap-3 pb-8 animate-fade-in" style="animation-delay: 0.4s">
                    <a href="{{ route('karyawan.dashboard') }}"
                        class="btn-secondary inline-flex items-center justify-center group !py-2 !px-4">
                        <svg class="w-4 h-4 mr-1.5 group-hover:-translate-x-1 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="text-xs font-bold">Batal</span>
                    </a>
                    <button type="submit" class="btn-primary inline-flex items-center justify-center group !py-2 !px-4">
                        <svg class="w-4 h-4 mr-1.5 group-hover:rotate-12 transition-transform duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs font-bold">Kirim Laporan Bahaya</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('foto_bukti');
            const imagePreview = document.getElementById('image-preview');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const fileNameDisplay = document.getElementById('file-name-display');
            const removeImageBtn = document.getElementById('remove-image');
            const uploadIcon = document.getElementById('upload-icon');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight drop zone when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('border-red-500', 'bg-red-50');
            }

            function unhighlight(e) {
                dropZone.classList.remove('border-red-500', 'bg-red-50');
            }

            // Handle dropped files
            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    handleFiles(files);
                }
            }

            // Handle file input change
            fileInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    handleFiles(this.files);
                }
            });

            // Handle click on drop zone
            dropZone.addEventListener('click', function (e) {
                if (e.target !== fileInput && !e.target.closest('label[for="foto_bukti"]')) {
                    fileInput.click();
                }
            });

            function handleFiles(files) {
                const file = files[0];

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Hanya file gambar yang diperbolehkan!');
                    return;
                }

                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB!');
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                    dropZone.classList.add('hidden');
                    fileNameDisplay.textContent = `File: ${file.name} (${formatFileSize(file.size)})`;
                };
                reader.readAsDataURL(file);
            }

            // Remove image
            removeImageBtn.addEventListener('click', function () {
                fileInput.value = '';
                imagePreview.src = '';
                imagePreviewContainer.classList.add('hidden');
                dropZone.classList.remove('hidden');
                fileNameDisplay.textContent = '';
            });

            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }
        });
    </script>
</x-app-layout>