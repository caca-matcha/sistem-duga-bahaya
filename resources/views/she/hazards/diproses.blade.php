<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Validasi & Rencana Tindakan — Laporan #{{ $hazard->id }}
        </h2>
    </x-slot>

    {{-- Notifikasi Jatuh Tempo --}}
    @php
        if ($hazard->target_penyelesaian) {
            $dueDate = \Carbon\Carbon::parse($hazard->target_penyelesaian);
            $daysRemaining = now()->diffInDays($dueDate, false); // `false` to get signed difference
        } else {
            $daysRemaining = null;
        }
    @endphp

    @if ($daysRemaining !== null)
        @if ($daysRemaining >= 0 && $daysRemaining <= 7)
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-6">
                <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700" role="alert">
                    <p class="font-bold">Perhatian</p>
                    @if ($daysRemaining > 0)
                        <p>Target penyelesaian untuk laporan ini akan jatuh tempo dalam <strong>{{ $daysRemaining }} hari</strong> lagi (pada tanggal {{ $dueDate->format('d M Y') }}).</p>
                    @else
                        <p>Target penyelesaian untuk laporan ini jatuh tempo <strong>hari ini</strong>.</p>
                    @endif
                </div>
            </div>
        @elseif($daysRemaining < 0)
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-6">
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700" role="alert">
                    <p class="font-bold">Terlambat</p>
                    <p>Target penyelesaian untuk laporan ini telah melewati batas waktu sejauh <strong>{{ abs($daysRemaining) }} hari</strong> (seharusnya selesai pada {{ $dueDate->format('d M Y') }}).</p>
                </div>
            </div>
        @endif
    @endif

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
                            {{-- Pelapor & NPK --}}
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Pelapor</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $hazard->pelapor->name ?? 'N/A' }} ({{ $hazard->NPK }})</dd>
                            </div>
                            {{-- Departemen & Tanggal Observasi --}}
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Departemen</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $hazard->dept }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Tanggal Observasi</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}</dd>
                            </div>
                            {{-- Area Details --}}
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Area Gedung</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $hazard->area_gedung }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Area Type</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $hazard->area_type }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Area Name</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $hazard->area_name }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Area ID</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $hazard->area_id }}</dd>
                            </div>
                            {{-- Kategori STOP6 Awal --}}
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Kategori STOP6 Awal</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $hazard->kategori_stop6 }}</dd>
                            </div>
                        </dl>
                        
                        {{-- Maps untuk Tingkat Keparahan dan Kemungkinan Terjadi --}}
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

                        <dl class="space-y-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <dt class="text-sm font-medium text-gray-500">Tingkat Keparahan Awal</dt>
                                <dd class="text-sm mt-1">{{ $tingkatKeparahanMap[$hazard->tingkat_keparahan] ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between items-center">
                                <dt class="text-sm font-medium text-gray-500">Kemungkinan Terjadi Awal</dt>
                                <dd class="text-sm mt-1">{{ $kemungkinanTerjadiMap[$hazard->kemungkinan_terjadi] ?? 'N/A' }}</dd>
                            </div>

                            @php
                                // Calculate initial risk score directly in the view for robustness
                                $initialSeverity = $hazard->tingkat_keparahan ?? 0;
                                $initialProbability = $hazard->kemungkinan_terjadi ?? 0;
                                $initialRiskScore = ($initialSeverity && $initialProbability) ? $initialSeverity * $initialProbability : null;

                                // Determine initial risk category based on the calculated score
                                $initialKategori = 'N/A';
                                if ($initialRiskScore !== null) {
                                    if ($initialRiskScore <= 5) $initialKategori = 'Low';
                                    elseif ($initialRiskScore <= 12) $initialKategori = 'Medium';
                                    else $initialKategori = 'High';
                                }
                            @endphp
                            <div class="flex justify-between items-center text-sm font-bold pt-4 border-t">
                                <dt class="text-sm font-medium text-gray-500">Skor Risiko Awal (Pelapor)</dt>
                                <dd class="text-sm mt-1">
                                    <span class="px-3 py-1 rounded-full font-semibold text-xs" style="background-color: {{ getRiskColor($initialRiskScore) }}; color: {{ getTextColor($initialRiskScore) }};">
                                        {{ $initialRiskScore ?? 'N/A' }} ({{ $initialKategori }})
                                    </span>
                                </dd>
                            </div>
                            <div class="pt-4">
                                <dt class="text-sm font-medium text-gray-500">Deskripsi Bahaya</dt>
                                <dd class="text-sm bg-gray-50 p-3 rounded-md mt-1">{{ $hazard->deskripsi_bahaya }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ide Penanggulangan Pelapor</dt>
                                <dd class="text-sm bg-gray-50 p-3 rounded-md mt-1">{{ $hazard->ide_penanggulangan ?? 'Tidak ada ide' }}</dd>
                            </div>
                        </dl>
                    </div>

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
                    <form method="POST" id="diproses_form" action="{{ route('she.hazards.validasi.submit', $hazard) }}">
                        @csrf
                        {{-- The form's default method is POST, so no need for @method('POST') --}}

                        {{-- The status is determined by which button is clicked, so no hidden status needed here --}}

                        <div class="pt-4"></div>
                        <h3 class="text-lg font-bold mb-4 pb-2 border-b mt-10">
                            Validasi dan Rencana Tindakan SHE
                        </h3>

                        <div class="space-y-6">

                            {{-- Final Kategori STOP 6 --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Final Kategori STOP 6
                                </label>
                                <p class="text-xs text-gray-500 mt-1">
                                    Contoh Kategori (STOP 6):
                                </p>
                                <ul class="text-xs text-gray-500 mb-2 list-none pl-6">
                                    <li><strong>A</strong> = Aparatus (Bahaya terjepit, tergores, terpotong, tersayat)</li>
                                    <li><strong>B</strong> = Big Heavy (Bahaya tertimpa benda berat / terbentur)</li>
                                    <li><strong>C</strong> = Car (Tertabrak kendaraan, alat angkut, transportasi)</li>
                                    <li><strong>D</strong> = Drop (Terjatuh dari ketinggian, terpeleset, tersandung)</li>
                                    <li><strong>E</strong> = Electrical (Tersengat listrik)</li>
                                    <li><strong>F</strong> = Fire (Terpapar panas, ledakan, kebakaran)</li>
                                    <li><strong>O</strong> = Others (Bahan kimia, lingkungan, gigitan/sengatan hewan, dll.)</li>
                                </ul>

                                <select id="final_kategori_stop6" name="final_kategori_stop6"
                                    class="mt-2 w-full rounded-md border-gray-300 shadow-sm"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="A" {{ old('final_kategori_stop6', $hazard->kategori_stop6 ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('final_kategori_stop6', $hazard->kategori_stop6 ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('final_kategori_stop6', $hazard->kategori_stop6 ?? '') == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('final_kategori_stop6', $hazard->kategori_stop6 ?? '') == 'D' ? 'selected' : '' }}>D</option>
                                    <option value="E" {{ old('final_kategori_stop6', $hazard->kategori_stop6 ?? '') == 'E' ? 'selected' : '' }}>E</option>
                                    <option value="F" {{ old('final_kategori_stop6', $hazard->kategori_stop6 ?? '') == 'F' ? 'selected' : '' }}>F</option>
                                    <option value="O" {{ old('final_kategori_stop6', $hazard->kategori_stop6 ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                </select>
                            </div>

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
                                $sevFinal = old('final_tingkat_keparahan', $hazard->final_tingkat_keparahan ?? 0);
                                $probFinal = old('final_kemungkinan_terjadi', $hazard->final_kemungkinan_terjadi ?? 0);
                                $finalRiskScore = ($sevFinal && $probFinal) ? (int)$sevFinal * (int)$probFinal : null;
                            @endphp
                            <div class="flex justify-between items-center text-sm font-bold pt-2 border-b border-dashed">
                                <span class="text-green-700 font-bold">SKOR RISIKO FINAL</span>
                                <span id="final_risk_score_display" class="px-3 py-1 rounded-full font-semibold text-xs" style="background-color: {{ getRiskColor($finalRiskScore) }}; color: {{ getTextColor($finalRiskScore) }};">
                                    {{ $finalRiskScore ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        {{-- BUTTON ACTION --}}
                        <div class="flex justify-center mt-10"> 
                            <button type="submit" name="action" value="dengan_tindak_lanjut" class="px-5 py-2 bg-indigo-600 text-white text-m font-semibold rounded-md shadow hover:bg-indigo-700 transition">
                                Validasi dengan tindak lanjut
                            </button>
                            <button type="submit" name="action" value="tanpa_tindak_lanjut" formaction="{{ route('she.hazards.validasi.submitTanpaTindakLanjut', $hazard) }}" class="px-5 py-2 ml-3 border border-gray-300 text-gray-700 text-m font-semibold rounded-md shadow hover:bg-gray-50">
                                Validasi tanpa tindak lanjut
                            </button>               
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

        function calc() {
            const s = parseInt(sev.value);
            const p = parseInt(prob.value);

            if (!s || !p) {
                disp.textContent = "N/A";
                disp.style.backgroundColor = "#9CA3AF";
                return;
            }

            const risk = s * p;
            disp.textContent = risk;

            const riskColors = @json(getRiskColorsArray());
            const colorIndex = Math.min(Math.max(risk - 1, 0), 24);
            disp.style.backgroundColor = riskColors[colorIndex];
            disp.style.color = (risk <= 10) ? '#1f2937' : '#FFFFFF';
        }

        sev.addEventListener('change', calc);
        prob.addEventListener('change', calc);
        calc();
    });
    </script>
</x-app-layout>