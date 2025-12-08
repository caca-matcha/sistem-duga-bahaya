<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Validasi & Rencana Tindakan — Laporan #{{ $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl overflow-hidden">
                <div class="p-6 text-gray-900">

                    {{-- ============================
                        SECTION: INFORMASI AWAL
                    ============================ --}}
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b">
                        Informasi Laporan Awal
                    </h3>

                    <div class="grid grid-cols-1 gap-10">

                     <dl class="space-y-4">
                                <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Pelapor</span>
                                <span class="text-sm font-medium text-gray-900">{{ $hazard->pelapor->name ?? 'N/A' }}</span>
                            </div>

                                                   <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">NPK Pelapor</span>
                                <span class="text-sm font-medium text-gray-900">{{ $hazard->NPK }}</span>
                            </div>
                                <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Departemen</span>
                                <span class="text-sm font-medium text-gray-900">{{ $hazard->dept }}</span>
                            </div>
                                <div class="flex justify-between items-center">
                                                                <span class="text-sm text-gray-500">Tanggal Observasi</span>
                                                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}</span>
                                                            </div>                                
                                <div class="pt-2">
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Area Kerja</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->area_gedung }}</span>
                                </div>
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Aktivitas Kerja</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->aktivitas_kerja }}</span>
                                </div>
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Kategori STOP6</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->kategori_stop6 }}</span>
                                </div>
                        

                        @php
                            $tingkatKeparahanMap = [
                                5 => 'A - Kecelakaan fatal',
                                3 => 'B - Hilang hari kerja',
                                1 => 'C - Luka ringan',
                            ];
                            $kemungkinanTerjadiMap = [
                                1 => '1 - Sangat Jarang',
                                2 => '2 - Jarang',
                                3 => '3 - Kadang-Kadang',
                                4 => '4 - Sering',
                                5 => '5 - Sangat Sering',
                            ];
                        @endphp

                    <div class="grid grid-cols-1 gap-10">
                     <dl class="space-y-4"></dl>
                            <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-500">Tingkat Keparahan Awal</dt>
                            <dd class="text-sm mt-1">{{ $tingkatKeparahanMap[$hazard->tingkat_keparahan] ?? 'N/A' }}</dd>
                        </div>
                            <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-500">Kemungkinan Terjadi Awal</dt>
                            <dd class="text-sm mt-1">{{ $kemungkinanTerjadiMap[$hazard->kemungkinan_terjadi] ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <div class="flex justify-between items-center text-sm font-bold pt-4 border-t">
                            
                            <dt class="text-sm font-medium text-gray-500">Skor Risiko Awal (Pelapor)</dt>
                            
                            <dd class="text-sm mt-1">
                                
                                <span class="px-3 py-1 rounded-full font-semibold text-xs" style="background-color: {{ getRiskColor($hazard->risk_score) }}; color: {{ getTextColor($hazard->risk_score) }};">
                                    {{ $hazard->risk_score }} ({{ $hazard->kategori_resiko }})
                                </span>
                            </dd>
                        </div>
                        <div class="pt-4">
                            <dt class="text-sm font-medium text-gray-500">Deskripsi Bahaya</dt>
                            <dd class="text-sm bg-gray-50 p-3 rounded-md mt-1">{{ $hazard->deskripsi_bahaya }}</dd>
                        </div>

                        
                            <dt class="text-sm font-medium text-gray-500">Ide Penanggulangan Pelapor</dt>
                            <dd class="text-sm bg-gray-50 p-3 rounded-md mt-1">{{ $hazard->ide_penanggulangan ?? 'Tidak ada ide' }}</dd>
                        </div>
                    </dl>


                    {{-- Error Alert --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ============================
                           FORM VALIDASI SHE
                    ============================ --}}
                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="diproses">
                        <input type="hidden" id="risk_score_hidden" name="risk_score" value="{{ $hazard->risk_score }}">
                        <input type="hidden" id="kategori_resiko_hidden" name="kategori_resiko" value="{{ $hazard->kategori_resiko }}">

                        <div class="pt-4"></div>
                        <h3 class="text-lg font-bold mb-4 pb-2 border-b mt-10">
                            Validasi dan Rencana Tindakan SHE
                        </h3>

                        <div class="space-y-6">

                            {{-- Faktor Penyebab --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Faktor Penyebab
                                </label>
                                <select name="faktor_penyebab" class="mt-2 w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Pilih Faktor Penyebab</option>
                                    <option value="Unsafe Action" {{ old('faktor_penyebab', $hazard->faktor_penyebab) == 'Unsafe Action' ? 'selected' : '' }}>Unsafe Action</option>
                                    <option value="Unsafe Condition" {{ old('faktor_penyebab', $hazard->faktor_penyebab) == 'Unsafe Condition' ? 'selected' : '' }}>Unsafe Condition</option>
                                </select>
                            </div>

                            {{-- Keparahan & Kemungkinan --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @php
                                    $tingkatKeparahanMap = [
                                        5 => 'A - Kecelakaan fatal',
                                        3 => 'B - Hilang hari kerja',
                                        1 => 'C - Luka ringan',
                                    ];
                                    $kemungkinanTerjadiMap = [
                                        1 => '1 - Sangat Jarang',
                                        2 => '2 - Jarang',
                                        3 => '3 - Kadang-Kadang',
                                        4 => '4 - Sering',
                                        5 => '5 - Sangat Sering',
                                    ];
                                @endphp
                            
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Final Tingkat Keparahan
                                    </label>
                                    <select id="final_tingkat_keparahan" name="final_tingkat_keparahan" class="mt-2 w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Pilih Tingkat Keparahan</option>
                                        <option value="5" {{ old('final_tingkat_keparahan', $hazard->tingkat_keparahan) == 5 ? 'selected' : '' }}>A - Kecelakaan fatal</option>
                                        <option value="3" {{ old('final_tingkat_keparahan', $hazard->tingkat_keparahan) == 3 ? 'selected' : '' }}>B - Hilang hari kerja</option>
                                        <option value="1" {{ old('final_tingkat_keparahan', $hazard->tingkat_keparahan) == 1 ? 'selected' : '' }}>C - Luka ringan</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Final Kemungkinan Terjadi
                                    </label>
                                    <select id="final_kemungkinan_terjadi" name="final_kemungkinan_terjadi" class="mt-2 w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">Pilih Kemungkinan Terjadi</option>
                                        <option value="1" {{ old('final_kemungkinan_terjadi', $hazard->kemungkinan_terjadi) == 1 ? 'selected' : '' }}>1 - Sangat Jarang</option>
                                        <option value="2" {{ old('final_kemungkinan_terjadi', $hazard->kemungkinan_terjadi) == 2 ? 'selected' : '' }}>2 - Jarang</option>
                                        <option value="3" {{ old('final_kemungkinan_terjadi', $hazard->kemungkinan_terjadi) == 3 ? 'selected' : '' }}>3 - Kadang-Kadang</option>
                                        <option value="4" {{ old('final_kemungkinan_terjadi', $hazard->kemungkinan_terjadi) == 4 ? 'selected' : '' }}>4 - Sering</option>
                                        <option value="5" {{ old('final_kemungkinan_terjadi', $hazard->kemungkinan_terjadi) == 5 ? 'selected' : '' }}>5 - Sangat Sering</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Final Risk Score --}}
                            @php
                                $sevFinal = $hazard->final_tingkat_keparahan ?? 0;
                                $probFinal = $hazard->final_kemungkinan_terjadi ?? 0;
                                $finalRiskScore = ($sevFinal && $probFinal) ? $sevFinal * $probFinal : null;
                            @endphp
                            <div class="flex justify-between items-center text-sm font-bold pt-2 border-b border-dashed">
                                <span class="text-green-700 font-bold">SKOR RISIKO FINAL</span>
                                <span id="final_risk_score_display" class="px-3 py-1 rounded-full font-semibold text-xs" style="background-color: {{ getRiskColor($finalRiskScore) }}; color: {{ getTextColor($finalRiskScore) }};">
                                    {{ $finalRiskScore ?? 'N/A' }}
                                </span>
                            </div>

                            {{-- Upaya Penanggulangan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Upaya Penanggulangan (Boleh isi lebih dari satu)
                                </label>

                                @php
                                    $options = ['Eliminasi', 'Substitusi', 'Rekayasa (Engineering)', 'Administrasi', 'APD'];
                                    $selected = is_array($hazard->upaya_penanggulangan) 
                                        ? $hazard->upaya_penanggulangan 
                                        : json_decode($hazard->upaya_penanggulangan, true) ?? [];
                                @endphp

                                <div id="penanggulangan_container" class="mt-3 space-y-4">
                                @foreach ($options as $index => $opt)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            {{ $index + 1 }}. {{ strtoupper($opt) }}
                                        </label>
                                        <input type="text" 
                                            name="upaya_penanggulangan_text[{{ $opt }}]" 
                                            value="{{ $selected[$opt] ?? '' }}" 
                                            placeholder="Tulis upaya di sini..." 
                                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                @endforeach
                            </div>

                            </div>

                            {{-- Rencana Tindakan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Rencana Tindakan Perbaikan
                                </label>
                                <textarea id="tindakan_textarea" name="tindakan_perbaikan" rows="4"
                                          class="mt-2 w-full rounded-md border-gray-300 shadow-sm"
                                          required>{{ old('tindakan_perbaikan', $hazard->tindakan_perbaikan) }}</textarea>
                            </div>

                            {{-- Target Penyelesaian --}}
                            <div>
                                <label for="target_penyelesaian" class="block text-sm font-medium text-gray-700">
                                    Target Penyelesaian
                                </label>
                                <input type="date" name="target_penyelesaian" id="target_penyelesaian"
                                       class="mt-2 w-full rounded-md border-gray-300 shadow-sm"
                                       value="{{ old('target_penyelesaian', $hazard->target_penyelesaian ? \Carbon\Carbon::parse($hazard->target_penyelesaian)->format('Y-m-d') : '') }}"
                                       required>
                            </div>

                        </div>

                        {{-- BUTTON ACTION --}}
                        <div class="flex justify-center mt-10"> 
                            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-m font-semibold rounded-md shadow hover:bg-indigo-700 transition">
                                Validasi dengan tindak lanjut
                            </button>
                            <a href="{{ route('she.hazards.show', $hazard) }}" class="px-5 py-2 ml-3 border border-gray-300 text-gray-700 text-m font-semibold rounded-md shadow hover:bg-gray-50">
                                Validasi tanpa tindak lanjut
                            </a>                 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT PERHITUNGAN RISK SCORE & OTOMATIS PENANGGULANGAN --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sev = document.getElementById('final_tingkat_keparahan');
        const prob = document.getElementById('final_kemungkinan_terjadi');
        const disp = document.getElementById('final_risk_score_display');
        const riskHidden = document.getElementById('risk_score_hidden');
        const kategoriHidden = document.getElementById('kategori_resiko_hidden');

        const checkboxes = document.querySelectorAll('.penanggulangan_checkbox');
        const tindakanText = document.getElementById('tindakan_textarea');

        const standar = 5;
        const templateTindakan = 
`Melakukan upaya penanggulangan sesuai hierarki kontrol seperti:
- Eliminasi / Substitusi
- Rekayasa Teknis
- Administrasi
- Penggunaan APD

Tindakan dilakukan untuk mengurangi risiko yang melebihi standar perusahaan.`;

        function calc() {
            const s = parseInt(sev.value);
            const p = parseInt(prob.value);

            if (!s || !p) {
                disp.textContent = "N/A";
                disp.style.backgroundColor = "#9CA3AF";
                riskHidden.value = '';
                kategoriHidden.value = '';
                return;
            }

            const risk = s * p;
            disp.textContent = risk;
            riskHidden.value = risk;


            // Warna skor
            const riskColors = @json(getRiskColorsArray());
            const colorIndex = Math.min(Math.max(risk - 1, 0), 24);
            disp.style.backgroundColor = riskColors[colorIndex];

            // Also update text color for consistency
            if (risk <= 10) {
                disp.style.color = '#1f2937';
            } else {
                disp.style.color = '#FFFFFF';
            }

            // Otomatis tampil penanggulangan jika > standar (DIMATIKAN SESUAI PERMINTAAN)
            /*
            if (risk > standar) {
                checkboxes.forEach(cb => cb.checked = true);
                if (tindakanText.value.trim() === "" || tindakanText.value === "{{ $hazard->tindakan_perbaikan }}") {
                    tindakanText.value = templateTindakan;
                }
            } else {
                // reset ke original
                @if(is_array($selected))
                    let original = @json($selected);
                @else
                    let original = [];
                @endif
                checkboxes.forEach(cb => cb.checked = original.includes(cb.value));
                if (original.length === 0) {
                    tindakanText.value = "";
                }
            }
            */
        }

        sev.addEventListener('change', calc);
        prob.addEventListener('change', calc);
        calc();
    });
    </script>

</x-app-layout>
