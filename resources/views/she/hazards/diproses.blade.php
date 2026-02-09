<x-app-layout>
    @section('page-title', '')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                {{-- Back Button --}}
                <a href="{{ route('she.hazards.index') }}"
                    class="inline-flex items-center justify-center p-2 rounded-full text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition"
                    title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                {{-- Existing Header Content --}}
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.94.577 1.636 1.636 1.065 2.572a1.724 1.724 0 002.573 1.066z" />
                        </svg>
                    </div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Validasi & Rencana Tindakan — Laporan #{{ $hazard->id }}
                    </h2>
                </div>
            </div>
        </div>
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

    {{-- Notifikasi Jatuh Tempo --}}
    @php
        if ($hazard->target_penyelesaian) {
            $dueDate = \Carbon\Carbon::parse($hazard->target_penyelesaian);
            $daysRemaining = now()->diffInDays($dueDate, false); // `false` to get signed difference
        } else {
            $daysRemaining = null;
        }
    @endphp

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Notifikasi --}}
            @if ($daysRemaining !== null)
                <div class="mb-6">
                    @if ($daysRemaining >= 0 && $daysRemaining <= 7)
                        <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-r-lg" role="alert">
                            <p class="font-bold">Perhatian</p>
                            @if ($daysRemaining > 0)
                                <p>Target penyelesaian untuk laporan ini akan jatuh tempo dalam <strong>{{ $daysRemaining }}
                                        hari</strong> lagi (pada tanggal {{ $dueDate->format('d M Y') }}).</p>
                            @else
                                <p>Target penyelesaian untuk laporan ini jatuh tempo <strong>hari ini</strong>.</p>
                            @endif
                        </div>
                    @elseif($daysRemaining < 0)
                        <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg" role="alert">
                            <p class="font-bold">Terlambat</p>
                            <p>Target penyelesaian untuk laporan ini telah melewati batas waktu sejauh
                                <strong>{{ abs($daysRemaining) }} hari</strong> (seharusnya selesai pada
                                {{ $dueDate->format('d M Y') }}).
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white shadow-sm border border-gray-200 rounded-2xl overflow-hidden">
                <div class="p-6 text-gray-900">

                    {{-- Tampilan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <p class="font-bold text-sm">Terdapat beberapa kesalahan:</p>
                            </div>
                            <ul class="list-disc list-inside text-xs space-y-1 ml-7">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        {{-- SISI KIRI: INFORMASI LAPORAN AWAL --}}
                        <div class="space-y-6">
                            <section>
                                <h3
                                    class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span
                                        class="mr-2 p-1 bg-gray-100 rounded font-mono text-[10px] leading-none text-gray-500">01</span>
                                    Informasi Laporan Awal
                                </h3>
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 space-y-4 shadow-sm">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                                Pelapor</dt>
                                            <dd class="text-base font-semibold text-gray-800 mt-0.5">
                                                {{ $hazard->pelapor->name ?? 'N/A' }} ({{ $hazard->NPK }})
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                                Tanggal Observasi</dt>
                                            <dd class="text-base font-semibold text-gray-800 mt-0.5">
                                                {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                            Lokasi Lengkap</dt>
                                        <dd class="text-base font-semibold text-gray-800 mt-0.5">
                                            {{ collect([$hazard->area_gedung, $hazard->area_name])->filter()->join(' -> ') }}
                                        </dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">
                                            Deskripsi Bahaya</dt>
                                        <dd class="text-base text-gray-700 leading-relaxed italic">
                                            "{{ $hazard->deskripsi_bahaya }}"</dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">
                                            Ide Penanggulangan</dt>
                                        <dd class="text-base text-gray-700 leading-relaxed italic">
                                            "{{ $hazard->ide_penanggulangan ?? 'Tidak ada ide' }}"</dd>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h3
                                    class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span
                                        class="mr-2 p-1 bg-rose-100 rounded font-mono text-[10px] leading-none text-rose-600">02</span>
                                    Analisis Risiko Awal (Oleh Pelapor)
                                </h3>
                                @php
                                    $tingkatKeparahanMap = [5 => 'A - Kecelakaan fatal', 3 => 'B - Hilang hari kerja', 1 => 'C - Luka ringan'];
                                    $kemungkinanTerjadiMap = [1 => '1 - Sangat Jarang', 2 => '2 - Jarang', 3 => '3 - Kadang-Kadang', 4 => '4 - Sering', 5 => '5 - Sangat Sering'];
                                    $initialRiskScore = ($hazard->tingkat_keparahan && $hazard->kemungkinan_terjadi) ? $hazard->tingkat_keparahan * $hazard->kemungkinan_terjadi : null;
                                @endphp
                                <div
                                    class="bg-white rounded-xl p-5 border border-rose-100 shadow-md shadow-rose-50/50 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <dt class="text-[10px] font-bold text-rose-400 uppercase">Tingkat Keparahan
                                            </dt>
                                            <dd class="mt-1 text-base font-bold text-gray-900">
                                                {{ $tingkatKeparahanMap[$hazard->tingkat_keparahan] ?? 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-rose-400 uppercase">Kemungkinan
                                                Terjadi</dt>
                                            <dd class="mt-1 text-base font-bold text-gray-900">
                                                {{ $kemungkinanTerjadiMap[$hazard->kemungkinan_terjadi] ?? 'N/A' }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="pt-4 mt-2 border-t border-rose-50 flex items-center justify-between">
                                        <dt class="text-xs font-bold text-gray-500 uppercase">Skor Risiko Awal:</dt>
                                        <dd>
                                            <span class="px-4 py-1.5 rounded-lg font-black text-base shadow-inner"
                                                style="background-color: {{ getRiskColor($initialRiskScore) }}; color: {{ getTextColor($initialRiskScore) }};">
                                                {{ $initialRiskScore ?? 'N/A' }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- SISI KANAN: FORM VALIDASI SHE --}}
                        <div class="space-y-8">
                            <section>
                                <h3
                                    class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span
                                        class="mr-2 p-1 bg-emerald-100 rounded font-mono text-[10px] leading-none text-emerald-600">03</span>
                                    Validasi & Rencana Tindakan SHE
                                </h3>
                                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                    <form method="POST" id="diproses_form"
                                        action="{{ route('she.hazards.validasi.submit', $hazard) }}" class="space-y-6">
                                        @csrf

                                        {{-- Final Kategori STOP 6 --}}
                                        <div>
                                            <label for="final_kategori_stop6"
                                                class="block text-xs font-black text-gray-700 uppercase tracking-wide">Final
                                                Kategori STOP 6</label>
                                            <p class="text-[11px] text-gray-500 italic mt-0.5 mb-2">Pilih kategori yang
                                                paling sesuai setelah divalidasi.</p>
                                            <select id="final_kategori_stop6" name="final_kategori_stop6"
                                                class="mt-1 block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach(['A', 'B', 'C', 'D', 'E', 'F', 'O'] as $cat)
                                                    <option value="{{ $cat }}" @selected(old('final_kategori_stop6', $hazard->kategori_stop6) == $cat)>{{ $cat }}</option>
                                                @endforeach
                                            </select>
                                            </select>
                                        </div>

                                        {{-- PIC & Leader Assignment --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="pic_id"
                                                    class="block text-xs font-black text-gray-700 uppercase tracking-wide">PIC
                                                    (Eksekutor)</label>
                                                <select id="pic_id" name="pic_id" class="tom-select-search">
                                                    <option value="">Pilih PIC</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" @selected(old('pic_id', $hazard->pic_id) == $user->id)>{{ $user->name }}
                                                            {{ $user->npk ? '(' . $user->npk . ')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-[10px] text-gray-400 mt-1">Kosongkan jika belum
                                                    ditentukan.</p>
                                            </div>
                                            <div>
                                                <label for="leader_id"
                                                    class="block text-xs font-black text-gray-700 uppercase tracking-wide">Leader
                                                    (Pengawas Area)</label>
                                                <select id="leader_id" name="leader_id" class="tom-select-search">
                                                    <option value="">Pilih Leader</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}" @selected(old('leader_id', $hazard->leader_id) == $user->id)>{{ $user->name }}
                                                            {{ $user->npk ? '(' . $user->npk . ')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p class="text-[10px] text-gray-400 mt-1">Kosongkan jika belum
                                                    ditentukan.</p>
                                            </div>
                                        </div>

                                        {{-- Faktor Penyebab --}}
                                        <div>
                                            <label for="faktor_penyebab"
                                                class="block text-xs font-black text-gray-700 uppercase tracking-wide">Faktor
                                                Penyebab</label>
                                            <select id="faktor_penyebab" name="faktor_penyebab"
                                                class="mt-1 block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                required>
                                                <option value="">Pilih Faktor Penyebab</option>
                                                <option value="Unsafe Action" @selected(old('faktor_penyebab', $hazard->faktor_penyebab) == 'Unsafe Action')>Unsafe Action</option>
                                                <option value="Unsafe Condition" @selected(old('faktor_penyebab', $hazard->faktor_penyebab) == 'Unsafe Condition')>Unsafe Condition
                                                </option>
                                            </select>
                                        </div>

                                        {{-- Keparahan & Kemungkinan Final --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="final_tingkat_keparahan"
                                                    class="block text-xs font-black text-gray-700 uppercase tracking-wide">Final
                                                    Tingkat Keparahan</label>
                                                <select id="final_tingkat_keparahan" name="final_tingkat_keparahan"
                                                    class="mt-1 block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                    <option value="">Pilih Tingkat Keparahan</option>
                                                    <option value="5" @selected(old('final_tingkat_keparahan', $hazard->tingkat_keparahan) == 5)>A - Kecelakaan fatal</option>
                                                    <option value="3" @selected(old('final_tingkat_keparahan', $hazard->tingkat_keparahan) == 3)>B - Hilang hari kerja</option>
                                                    <option value="1" @selected(old('final_tingkat_keparahan', $hazard->tingkat_keparahan) == 1)>C - Luka ringan</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="final_kemungkinan_terjadi"
                                                    class="block text-xs font-black text-gray-700 uppercase tracking-wide">Final
                                                    Kemungkinan Terjadi</label>
                                                <select id="final_kemungkinan_terjadi" name="final_kemungkinan_terjadi"
                                                    class="mt-1 block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                    required>
                                                    <option value="">Pilih Kemungkinan</option>
                                                    @foreach($kemungkinanTerjadiMap as $value => $label)
                                                        <option value="{{ $value }}"
                                                            @selected(old('final_kemungkinan_terjadi', $hazard->kemungkinan_terjadi) == $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Final Risk Score Display --}}
                                        <div class="text-center pt-2">
                                            <dt class="text-xs font-bold text-gray-500 uppercase mb-2">Skor Risiko Final
                                            </dt>
                                            <span id="final_risk_score_display"
                                                class="inline-block px-6 py-2 rounded-lg font-black text-xl shadow-inner transition-colors">N/A</span>
                                        </div>

                                        {{-- BUTTON ACTION --}}
                                        <div <div
                                            class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-6 border-t border-gray-100">
                                            <button type="submit" name="action" value="forward_to_pic"
                                                formaction="{{ route('she.hazards.forwardToPic', $hazard) }}"
                                                class="w-full inline-flex justify-center items-center px-5 py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-200/50 transition-all hover:bg-emerald-700 active:scale-95">
                                                Teruskan ke PIC
                                            </button>
                                            <button type="submit" name="action" value="dengan_tindak_lanjut"
                                                class="w-full inline-flex justify-center items-center px-5 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200/50 transition-all hover:bg-indigo-700 active:scale-95">
                                                Validasi dengan Rencana
                                            </button>
                                            <button type="submit" name="action" value="tanpa_tindak_lanjut"
                                                formaction="{{ route('she.hazards.validasi.submitTanpaTindakLanjut', $hazard) }}"
                                                class="w-full inline-flex justify-center items-center px-5 py-3 border border-gray-300 text-gray-700 text-sm font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                                                Validasi Tanpa Rencana
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sev = document.getElementById('final_tingkat_keparahan');
            const prob = document.getElementById('final_kemungkinan_terjadi');
            const disp = document.getElementById('final_risk_score_display');

            function getTextColor(bgColor) {
                if (!bgColor) return '#1f2937';
                // Simple logic: if luminance is high, use dark text.
                const hex = bgColor.replace('#', '');
                const r = parseInt(hex.substring(0, 2), 16);
                const g = parseInt(hex.substring(2, 4), 16);
                const b = parseInt(hex.substring(4, 6), 16);
                const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
                return luminance > 0.5 ? '#1f2937' : '#FFFFFF';
            }

            function calc() {
                const s = parseInt(sev.value);
                const p = parseInt(prob.value);
                const riskColors = @json(getRiskColorsArray());

                if (!s || !p) {
                    disp.textContent = "N/A";
                    disp.style.backgroundColor = "#E5E7EB";
                    disp.style.color = "#4B5563";
                    return;
                }

                const risk = s * p;
                disp.textContent = risk;

                const colorIndex = Math.min(Math.max(risk - 1, 0), 24);
                const bgColor = riskColors[colorIndex];
                disp.style.backgroundColor = bgColor;
                disp.style.color = getTextColor(bgColor);
            }

        sev.addEventListener('change', calc);
            prob.addEventListener('change', calc);
            calc(); // Initial calculation on page load
        });
    </script>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <style>
            /* Custom Tom Select Styling - Premium Look */
            .ts-wrapper {
                border-radius: 0.75rem !important;
            }
            .ts-control {
                background: linear-gradient(to bottom, #ffffff, #f9fafb) !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 0.75rem !important;
                padding: 0.625rem 1rem !important;
                font-size: 0.875rem !important;
                font-weight: 500 !important;
                min-height: 44px !important;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
                transition: all 0.2s ease !important;
            }
            .ts-control:hover {
                border-color: #10b981 !important;
            }
            .ts-wrapper.focus .ts-control {
                border-color: #10b981 !important;
                box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
                background: #ffffff !important;
            }
            .ts-control input {
                font-size: 0.875rem !important;
            }
            .ts-control .item {
                background: transparent !important;
                color: #111827 !important;
            }
            .ts-dropdown {
                border-radius: 0.75rem !important;
                border: 2px solid #e5e7eb !important;
                box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15) !important;
                margin-top: 4px !important;
                overflow: hidden !important;
            }
            .ts-dropdown .ts-dropdown-content {
                max-height: 250px !important;
                padding: 0.5rem !important;
            }
            .ts-dropdown .option {
                padding: 0.625rem 1rem !important;
                border-radius: 0.5rem !important;
                margin-bottom: 2px !important;
                font-size: 0.875rem !important;
                transition: all 0.15s ease !important;
            }
            .ts-dropdown .option:hover {
                background-color: #f0fdf4 !important;
            }
            .ts-dropdown .option.active {
                background: linear-gradient(135deg, #10b981, #059669) !important;
                color: #ffffff !important;
                font-weight: 600 !important;
            }
            .ts-dropdown .no-results {
                padding: 1rem !important;
                text-align: center !important;
                color: #6b7280 !important;
            }
            /* Search input styling */
            .ts-control input::placeholder {
                color: #9ca3af !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.tom-select-search').forEach(function(el) {
                    new TomSelect(el, {
                        allowEmptyOption: true,
                        placeholder: el.options[0]?.text || 'Pilih...',
                        searchField: ['text'],
                        sortField: { field: 'text', direction: 'asc' },
                        render: {
                            no_results: function(data, escape) {
                                return '<div class="no-results">Tidak ditemukan: <strong>' + escape(data.input) + '</strong></div>';
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>