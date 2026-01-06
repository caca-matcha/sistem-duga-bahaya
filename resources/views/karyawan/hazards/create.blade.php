<x-app-layout>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Form --}}
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900">Formulir Laporan Duga Bahaya</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Lengkapi data di bawah ini untuk melaporkan potensi bahaya K3.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pengisian:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('karyawan.hazards.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- SECTION 1: DATA PELAPOR --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl mb-6 border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center">
                        <div class="bg-indigo-100 rounded-full p-2 mr-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">1. Data Pelapor</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelapor</label>
                            <input type="text" disabled value="{{ auth()->user()->name }}" class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed shadow-sm">
                        </div>
                        <div>
                            <label for="NPK" class="block text-sm font-medium text-gray-700 mb-1">NPK <span class="text-red-500">*</span></label>
                            <input id="NPK" name="NPK" value="{{ old('NPK') }}" type="text" placeholder="Contoh: 12345" 
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition duration-200 shadow-sm">
                        </div>
                        <div>
                            <label for="dept" class="block text-sm font-medium text-gray-700 mb-1">Departemen <span class="text-red-500">*</span></label>
                            <select id="dept" name="dept" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition duration-200 shadow-sm">
                                <option value="">Pilih Departemen</option>
                                @foreach (['Maintenance','Quality Assurance / Quality Control (QA/QC)','Engineering','Finance','Human Resource', 'Warehouse / Logistics','Planning & Control (PPC / PPIC)', 'Tooling', 'Utility / Facility'] as $d)
                                    <option value="{{ $d }}" {{ old('dept') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: LOKASI & WAKTU --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl mb-6 border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center">
                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">2. Lokasi & Waktu Observasi</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tgl_observasi" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kejadian <span class="text-red-500">*</span></label>
                            <input id="tgl_observasi" name="tgl_observasi" type="date" value="{{ old('tgl_observasi', date('Y-m-d')) }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition duration-200 shadow-sm">
                        </div>
                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kejadian <span class="text-red-500">*</span></label>
                            <select id="location_id" name="location_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition duration-200 shadow-sm"
                                data-old-location="{{ old('location_id') }}">
                                <option value="">Memuat lokasi...</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="lokasi_detail_manual" class="block text-sm font-medium text-gray-700 mb-1">Detail Tambahan Lokasi</label>
                            <textarea id="lokasi_detail_manual" name="lokasi_detail_manual" rows="2" placeholder="Contoh: Di dekat mesin press No. 5, pilar C-12"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition duration-200 shadow-sm">{{ old('lokasi_detail_manual') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1 italic">Isi jika perlu memberikan detail yang lebih spesifik dari pilihan lokasi.</p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: DETAIL TEMUAN --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl mb-6 border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center">
                        <div class="bg-red-100 rounded-full p-2 mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">3. Detail Temuan</h2>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        {{-- Kategori STOP6 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Bahaya (Kategori STOP-6) <span class="text-red-500">*</span></label>
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
                                    <label class="relative flex items-center p-3 rounded-lg border cursor-pointer hover:bg-red-50 transition-colors focus-within:ring-2 focus-within:ring-red-500">
                                        <input type="radio" name="kategori_stop6" value="{{ $key }}" class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500" {{ old('kategori_stop6') === $key ? 'checked' : '' }}>
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
                                <label for="deskripsi_bahaya" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Bahaya <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mb-1 italic">Contoh pengisian: Operator terlihat berjalan di luar safety line, berpotensi tertabrak forklift.</p>
                                <textarea id="deskripsi_bahaya" name="deskripsi_bahaya" rows="5" placeholder="Tuliskan detail temuan bahaya..."
                                    class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 transition duration-200 shadow-sm">{{ old('deskripsi_bahaya') }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti <span class="text-red-500">*</span></label>
                                <p class="text-xs text-gray-500 mb-1 italic">Ambil foto secara jelas agar potensi bahaya dapat terlihat dengan baik.</p>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="foto_bukti" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none">
                                                <span>Upload file</span>
                                                <input id="foto_bukti" name="foto_bukti" type="file" class="sr-only" accept=".jpg,.jpeg,.png">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                    </div>
                                </div>
                                <p id="file-name-display" class="text-xs text-gray-600 mt-2 italic"></p>
                                @error('foto_bukti')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Ide Penanggulangan --}}
                        <div>
                            <label for="ide_penanggulangan" class="block text-sm font-medium text-gray-700 mb-1">Usulan Perbaikan / Pencegahan</label>
                            <textarea id="ide_penanggulangan" name="ide_penanggulangan" rows="3" placeholder="Apa saran Anda untuk menghilangkan bahaya ini?"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 transition duration-200 shadow-sm">{{ old('ide_penanggulangan') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: ANALISIS RISIKO (Risk Matrix) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl mb-8 border border-gray-100 relative">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 rounded-full p-2 mr-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800">4. Rate Risiko Bahaya</h2>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            {{-- Input Columns --}}
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tingkat_keparahan" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Keparahan (Severity) <span class="text-red-500">*</span></label>
                                    <select id="tingkat_keparahan" name="tingkat_keparahan" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition duration-200 shadow-sm">
                                        <option value="">Pilih Severity</option>
                                        <option value="5" {{ old('tingkat_keparahan') == '5' ? 'selected' : '' }}>Kecelakaan Fatal (A)</option>
                                        <option value="3" {{ old('tingkat_keparahan') == '3' ? 'selected' : '' }}>Hilang Hari Kerja (B)</option>
                                        <option value="1" {{ old('tingkat_keparahan') == '1' ? 'selected' : '' }}>Luka Ringan (C)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="kemungkinan_terjadi" class="block text-sm font-medium text-gray-700 mb-1">Kemungkinan Terjadi (Probability) <span class="text-red-500">*</span></label>
                                    <select id="kemungkinan_terjadi" name="kemungkinan_terjadi" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition duration-200 shadow-sm">
                                        <option value="">Pilih Probability</option>
                                        <option value="1" {{ old('kemungkinan_terjadi') == 1 ? 'selected' : '' }}>Sangat Jarang</option>
                                        <option value="2" {{ old('kemungkinan_terjadi') == 2 ? 'selected' : '' }}>Jarang</option>
                                        <option value="3" {{ old('kemungkinan_terjadi') == 3 ? 'selected' : '' }}>Kadang-Kadang</option>
                                        <option value="4" {{ old('kemungkinan_terjadi') == 4 ? 'selected' : '' }}>Sering</option>
                                        <option value="5" {{ old('kemungkinan_terjadi') == 5 ? 'selected' : '' }}>Sangat Sering</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="flex items-center justify-end gap-4 pb-8">
                    <a href="{{ route('karyawan.dashboard') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition shadow-sm">
                        Batal
                    </a>
                    <button class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-lg rounded-lg shadow">
                        Kirim Laporan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            const locationSelect = document.getElementById('location_id');
            const oldLocationValue = locationSelect.getAttribute('data-old-location');

            // --- 1. Ambil data lokasi dari API dan bangun dropdown ---
            fetch('/api/locations')
                .then(response => response.json())
                .then(data => {
                    locationSelect.innerHTML = '<option value="">-- Pilih Lokasi --</option>';

                    // Kelompokkan lokasi berdasarkan map/gedung
                    const groupedByMap = data.reduce((acc, location) => {
                        const mapName = location.map ? location.map.name : 'Lainnya'; // Kelompokkan di 'Lainnya' jika tidak ada peta
                        if (!acc[mapName]) {
                            acc[mapName] = [];
                        }
                        acc[mapName].push(location);
                        return acc;
                    }, {});

                    // Buat <optgroup> untuk setiap peta/gedung
                    for (const mapName in groupedByMap) {
                        const group = document.createElement('optgroup');
                        group.label = mapName;
                        
                        groupedByMap[mapName].forEach(location => {
                            const option = document.createElement('option');
                            option.value = location.id;
                            option.textContent = `${location.name} (${location.location_id_string}) - ${location.type}`;
                            group.appendChild(option);
                        });

                        locationSelect.appendChild(group);
                    }
                    
                    // Set nilai lama jika ada (untuk kasus validation error)
                    if (oldLocationValue) {
                        locationSelect.value = oldLocationValue;
                    }

                })
                .catch(error => {
                    console.error('Error fetching master locations:', error);
                    locationSelect.innerHTML = '<option value="">Gagal memuat Master Lokasi</option>';
                });


            // --- 2. FILE INPUT PREVIEW NAME ---
            const fileInput = document.getElementById('foto_bukti');
            const fileNameDisplay = document.getElementById('file-name-display');
            fileInput.addEventListener('change', function() {
                if(this.files && this.files.length > 0) {
                    fileNameDisplay.textContent = "File dipilih: " + this.files[0].name;
                } else {
                    fileNameDisplay.textContent = "";
                }
            });
        });
    </script>
</x-app-layout>
