<x-app-layout>
<style>
    /* Efek cahaya merah ketika hover/focus */
    .glow-red:hover,
    .glow-red:focus {
        box-shadow: 0 0 5px rgba(255, 0, 0, 0.45);
        border-color: rgb(220, 38, 38);
        transition: 0.2s;
    }

    /* Warna merah untuk opsi yang di-hover dan opsi yang dipilih */
    #dept option:hover,
    #area_gedung option:hover,
    #aktivitas_kerja option:hover,
    #kategori_stop6 option:hover,
    #tingkat_keparahan option:hover,
    #kemungkinan_terjadi option:hover,
    
    #dept option:checked,
    #area_gedung option:checked,
    #aktivitas_kerja option:checked,
    #kategori_stop6 option:checked,
    #tingkat_keparahan option:checked,
    #kemungkinan_terjadi option:checked {
        background-color: rgb(220, 38, 38) !important;
        color: white !important;
    }
</style>


    <div class="py-12">
        <div class="max-w-6xl mx-auto py-10 px-10">
            <h1 class="text-2xl font-bold text-center mb-8">Form Laporan Duga Bahaya</h1>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded-lg shadow-xl mb-6">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="max-w-6xl mx-auto py-10 px-10 bg-red-100 rounded-lg shadow-xl m-6">
                <form method="POST" action="{{ route('karyawan.hazards.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- DATA KARYAWAN --}}
                    <div class="bg-white p-6 mt-8">
                        <h2 class="text-lg font-semibold text-red-600 border-b border-red-400 pb-2 mb-6">Data Karyawan</h2>

                        <div class="grid grid-cols-1 gap-6 mb-8">
                            <div>
                                <label class="block font-medium mb-1">Nama</label>
                                <input type="text" disabled value="{{ auth()->user()->name }}"
                                    class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
                            </div>

                            <div>
                                <label for="NPK" class="block font-medium mb-1">NPK</label>
                                <input id="NPK" name="NPK" value="{{ old('NPK') }}" type="text"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('NPK') border-red-500 @enderror">
                            </div>

                            <div>
                                <label for="dept" class="block font-medium mb-1">Departemen</label>
                                <select id="dept" name="dept"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('dept') border-red-500 @enderror">
                                    <option value="">Pilih Departemen</option>
                                    @foreach (['Produksi','Quality','Engineering','Finance','Human Resource'] as $d)
                                        <option value="{{ $d }}" {{ old('dept') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- DATA OBSERVASI --}}
                        <h2 class="text-lg font-semibold text-red-600 border-b border-red-400 pb-2 mb-6">Data Observasi</h2>

                        <div class="grid grid-cols-1 gap-6 mb-8">
                            <div>
                                <label for="tgl_observasi" class="block font-medium mb-1">Tanggal Observasi</label>
                                <input id="tgl_observasi" name="tgl_observasi" type="date" value="{{ old('tgl_observasi') }}"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('tgl_observasi') border-red-500 @enderror">
                            </div>

                            <div>
                                <label for="area_gedung" class="block font-medium mb-1">Area Gedung</label>
                                <select id="area_gedung" name="area_gedung"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('area_gedung') border-red-500 @enderror">
                                    <option value="">Pilih Gedung</option>
                                    @foreach (['Gedung A','Gedung B','Gedung C','Gedung D','Gedung E','Gedung F','Gedung G'] as $g)
                                        <option value="{{ $g }}" {{ old('area_gedung') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="aktivitas_kerja" class="block font-medium mb-1">Aktivitas Kerja</label>
                                {{-- data-old digunakan agar JS bisa set nilai lama (old) setelah populate --}}
                                <select id="aktivitas_kerja" name="aktivitas_kerja"
                                    data-old="{{ old('aktivitas_kerja') ?? '' }}"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('aktivitas_kerja') border-red-500 @enderror">
                                    <option value="">Pilih Aktivitas Kerja</option>
                                </select>
                            </div>

                        {{-- DETAIL BAHAYA --}}
                        <h2 class="text-lg font-semibold text-red-600 border-b border-red-400 pb-2 mb-6">Detail Bahaya</h2>

                        <div class="grid grid-cols-1 gap-6 mb-8">
                            <div>
                                <label for="deskripsi_bahaya" class="block font-medium mb-1">Deskripsi Bahaya</label>
                                <p class="text-sm text-gray-500 mb-2">Contoh pengisian: Operator terlihat berjalan di luar safety line, berpotensi tertabrak forklift.</p>
                                <textarea id="deskripsi_bahaya" name="deskripsi_bahaya" rows="4"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('deskripsi_bahaya') border-red-500 @enderror">{{ old('deskripsi_bahaya') }}</textarea>
                            </div>
                            
                            <div>
                                <label for="foto_bukti" class="block font-medium mb-1">Foto Temuan</label>
                                <p class="text-sm text-gray-500 mb-2">
                                    Format: JPG, JPEG, PNG. Ukuran maksimal: 5MB.
                                </p>
                                <input id="foto_bukti" name="foto_bukti" type="file" 
                                    accept=".jpg, .jpeg, .png" 
                                    class="w-full rounded-lg border-gray-300 glow-red p-2
                                        @error('foto_bukti') border-red-500 @enderror">
                                @error('foto_bukti')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kategori_stop6" class="block font-medium mb-1"> Jenis Bahaya </label>
                                    <p class="text-sm text-gray-500 mb-1">
                                        Contoh Kategori (STOP 6):
                                    </p>
                                    <ul class="text-sm text-gray-500 mb-0 list-none pl-10">
                                        <li><strong>A</strong> = Aparatus (Bahaya terjepit, tergores, terpotong, tersayat)</li>
                                        <li><strong>B</strong> = Big Heavy (Bahaya tertimpa benda berat / terbentur)</li>
                                        <li><strong>C</strong> = Car (Tertabrak kendaraan, alat angkut, alat transportasi)</li>
                                        <li><strong>D</strong> = Drop (Terjatuh dari ketinggian, terpeleset, tersandung)</li>
                                        <li><strong>E</strong> = Electrical (Tersengat aliran listrik)</li>
                                        <li><strong>F</strong> = Fire (Terpapar panas, ledakan, kebakaran)</li>
                                        <li><strong>O</strong> = Others (Paparan bahan kimia, pencemaran lingkungan, gigitan/sengatan hewan, dll.)</li>
                                    </ul>
                            </div>

                            <select id="kategori_stop6" name="kategori_stop6"
                                class="w-full rounded-lg border-gray-300 glow-red @error('kategori_stop6') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>

                                <option value="A" {{ old('kategori_stop6') === 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('kategori_stop6') === 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('kategori_stop6') === 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('kategori_stop6') === 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ old('kategori_stop6') === 'E' ? 'selected' : '' }}>E</option>
                                <option value="F" {{ old('kategori_stop6') === 'F' ? 'selected' : '' }}>F</option>
                                <option value="O" {{ old('kategori_stop6') === 'O' ? 'selected' : '' }}>O</option>
                            </select>

                            @error('kategori_stop6')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            </div>

                            <div>
                                <label for="tingkat_keparahan" class="block font-medium mb-1">Tingkat Keparahan (Severity)</label>
                                <select id="tingkat_keparahan" name="tingkat_keparahan"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('tingkat_keparahan') border-red-500 @enderror">
                                    <option value="">Pilih Tingkat Keparahan</option>
                                    <option value="5" {{ old('tingkat_keparahan') == '5' ? 'selected' : '' }}>A - Kecelakaan fatal</option>
                                    <option value="3" {{ old('tingkat_keparahan') == '3' ? 'selected' : '' }}>B - Hilang hari kerja</option>
                                    <option value="1" {{ old('tingkat_keparahan') == '1' ? 'selected' : '' }}>C - Luka ringan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-medium mb-1">Kemungkinan Terjadi</label>
                                <select id="kemungkinan_terjadi" name="kemungkinan_terjadi"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('kemungkinan_terjadi') border-red-500 @enderror">
                                    <option value="">Pilih Kemungkinan Terjadi</option>
                                    <option value="1" {{ old('kemungkinan_terjadi') == 1 ? 'selected' : '' }}>1 - Sangat Jarang</option>
                                    <option value="2" {{ old('kemungkinan_terjadi') == 2 ? 'selected' : '' }}>2 - Jarang</option>
                                    <option value="3" {{ old('kemungkinan_terjadi') == 3 ? 'selected' : '' }}>3 - Kadang-Kadang</option>
                                    <option value="4" {{ old('kemungkinan_terjadi') == 4 ? 'selected' : '' }}>4 - Sering</option>
                                    <option value="5" {{ old('kemungkinan_terjadi') == 5 ? 'selected' : '' }}>5 - Sangat Sering</option>
                                </select>
                            </div>

                            {{-- Nilai Risk --}}
                                {{-- INPUT HIDDEN UNTUK MENGIRIM NILAI RISK KE CONTROLLER --}}
                                <input type="hidden" id="risk_score_hidden" name="risk_score" value="{{ old('risk_score') }}">

                            {{-- Kategori Risk --}}
                                {{-- INPUT HIDDEN UNTUK MENGIRIM KATEGORI RISK KE CONTROLLER --}}
                                <input type="hidden" id="kategori_resiko_hidden" name="kategori_resiko" value="{{ old('kategori_resiko') }}">

                            <div>
                                <label for="ide_penanggulangan" class="block font-medium mb-1">Ide Penanggulangan</label>
                                <textarea id="ide_penanggulangan" name="ide_penanggulangan" rows="3"
                                    class="w-full rounded-lg border-gray-300 glow-red @error('ide_penanggulangan') border-red-500 @enderror">{{ old('ide_penanggulangan') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-lg rounded-lg shadow">
                                Kirim Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- SCRIPT RISK MATRIX & DEPENDENT DROPDOWN --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const severity = document.getElementById('tingkat_keparahan');
                const probability = document.getElementById('kemungkinan_terjadi');
                // Tambahkan referensi ke input hidden
                const nilaiRiskHidden = document.getElementById('risk_score_hidden');
                const kategoriRiskHidden = document.getElementById('kategori_resiko_hidden');

                // The riskColors array has been removed as it was unused in this view.
                // It is now available globally via the getRiskColorsArray() helper function if needed.

                /* =============================
                    DEPENDENT DROPDOWN — FIXED
                ==============================*/
                const aktivitasByGedung = {
                    "Gedung A": ["Packing", "Produksi", "Sorting"],
                    "Gedung B": ["Maintenance Mesin", "Inspeksi Kualitas"],
                    "Gedung C": ["Administrasi", "Meeting", "Training"],
                    "Gedung D": ["Warehouse", "Forklift", "Loading / Unloading"],
                    "Gedung E": ["Laboratorium", "Pengujian Sampel"],
                    "Gedung F": ["Welding", "Assembly", "Finishing"],
                    "Gedung G": ["Area Umum", "Kantin", "Mushola"]
                };

                const areaSelect = document.getElementById('area_gedung');
                const aktivitasSelect = document.getElementById('aktivitas_kerja');

                // Helper: populate aktivitasSelect based on gedung
                function populateAktivitas(gedung, setOldValue = true) {
                    aktivitasSelect.innerHTML = `<option value="">Pilih Aktivitas Kerja</option>`;

                    if (!gedung || !aktivitasByGedung[gedung]) return;

                    aktivitasByGedung[gedung].forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item;
                        opt.textContent = item;
                        aktivitasSelect.appendChild(opt);
                    });

                    // Jika ada old value dari server, set selected
                    if (setOldValue) {
                        const oldVal = aktivitasSelect.dataset.old || '';
                        if (oldVal) {
                            aktivitasSelect.value = oldVal;
                        }
                    }
                }

                // Pasang event listener change pada areaSelect
                areaSelect.addEventListener('change', function () {
                    // Ketika user ganti area, jangan gunakan old value, tapi reset
                    populateAktivitas(this.value, false); 
                });

                /* =============================
                    RISK MATRIX FUNCTION
                ==============================*/
                function updateRisk() {
                    let s = parseInt(severity.value);
                    let p = parseInt(probability.value);

                    console.log('Update Risk triggered. Severity:', s, 'Probability:', p);

                    if (!s || !p) {
                        nilaiRiskHidden.value = "";
                        kategoriRiskHidden.value = "";
                        console.log('Risk/Kategori reset to empty.');
                        return;
                    }

                    let risk = s * p;

                    let kategori;
                    if (risk <= 4) kategori = "Low";
                    else if (risk <= 9) kategori = "Medium";
                    else kategori = "High";

                    nilaiRiskHidden.value = risk;
                    kategoriRiskHidden.value = kategori;
                    console.log('Risk Score:', risk, 'Kategori Resiko:', kategori);
                }

                severity.addEventListener('change', function() {
                    console.log('Severity changed to:', this.value);
                    updateRisk();
                });
                probability.addEventListener('change', function() {
                    console.log('Probability changed to:', this.value);
                    updateRisk();
                });

                // ---- PERBAIKAN INISIALISASI PADA LOAD (SETELAH GAGAL VALIDASI) ----
                // 1) Populate aktivitas kalau ada area_gedung terpilih (old value)
                const selectedGedungOnLoad = areaSelect.value || '';
                if (selectedGedungOnLoad) {
                    // Gunakan 'true' untuk setOldValue, agar nilai aktivitas_kerja yang lama (old) terpilih
                    populateAktivitas(selectedGedungOnLoad, true); 
                }

                // 2) Jika sebelumnya user mengisi tingkat keparahan/kemungkinan, hitung risk awal
                // updateRisk(); // Call unconditionally, will reset if values are not selected
                updateRisk();
            });
        </script>
    </div>
</x-app-layout>