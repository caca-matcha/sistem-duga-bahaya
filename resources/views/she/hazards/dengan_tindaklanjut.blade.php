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
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Validasi Laporan #{{ $hazard->id }} <span class="text-gray-400 font-normal">| Dengan Tindak
                            Lanjut</span>
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
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

                        {{-- SISI KIRI: INFORMASI & HASIL VALIDASI --}}
                        <div class="space-y-8">
                            {{-- INFORMASI AWAL --}}
                            <section>
                                <h3
                                    class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span
                                        class="mr-2 p-1 bg-gray-100 rounded font-mono text-[10px] leading-none text-gray-500">01</span>
                                    Informasi Laporan Awal
                                </h3>
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 space-y-4 shadow-sm">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                Pelapor</dt>
                                            <dd class="text-sm font-bold text-gray-800 mt-0.5">
                                                {{ $hazard->nama ?? ($hazard->pelapor->name ?? 'N/A') }}
                                                @if(($hazard->pelapor->role ?? '') === 'magang')
                                                    <span
                                                        class="ml-1 text-[10px] text-gray-400 font-medium italic">({{ $hazard->pelapor->name }})</span>
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                NPK</dt>
                                            <dd class="text-sm font-bold text-gray-800 mt-0.5">
                                                {{ $hazard->NPK ?? ($hazard->pelapor->npk ?? '-') }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                Departemen</dt>
                                            <dd class="text-sm font-bold text-gray-800 mt-0.5">
                                                {{ $hazard->pelapor->department ?? '-' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                Tanggal Observasi</dt>
                                            <dd class="text-sm font-bold text-gray-800 mt-0.5">
                                                {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->translatedFormat('d M Y') }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                            Lokasi Lengkap</dt>
                                        <dd class="text-sm font-bold text-gray-800 mt-0.5">
                                            {{ collect([$hazard->area_gedung, $hazard->area_name])->filter()->join(' -> ') }}
                                        </dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                            Deskripsi Bahaya</dt>
                                        <dd class="text-sm font-bold text-gray-800 leading-relaxed">
                                            {{ $hazard->deskripsi_bahaya }}
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-200">
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                Faktor Penyebab</dt>
                                            <dd class="text-sm font-bold text-gray-800 mt-0.5">
                                                {{ $faktor_penyebab ?? 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                Kategori STOP6</dt>
                                            <dd class="text-sm font-bold text-gray-800 mt-0.5">
                                                {{ $final_kategori_stop6 ?? 'N/A' }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Ide
                                            Penanggulangan</dt>
                                        <dd class="text-sm font-bold text-gray-800 leading-relaxed">
                                            {{ $hazard->ide_penanggulangan ?? 'N/A' }}
                                        </dd>
                                    </div>
                                </div>
                            </section>

                            {{-- HASIL VALIDASI SHE --}}
                            <section>
                                <h3
                                    class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span
                                        class="mr-2 p-1 bg-indigo-100 rounded font-mono text-[10px] leading-none text-indigo-600">02</span>
                                    Hasil Validasi Risiko oleh SHE
                                </h3>
                                @php
                                    $tingkatKeparahanMap = [5 => 'A - Kecelakaan fatal', 3 => 'B - Hilang hari kerja', 1 => 'C - Luka ringan'];
                                    $kemungkinanTerjadiMap = [1 => '1 - Sangat Jarang', 2 => '2 - Jarang', 3 => '3 - Kadang-Kadang', 4 => '4 - Sering', 5 => '5 - Sangat Sering'];
                                @endphp
                                <div
                                    class="bg-white rounded-xl p-5 border border-indigo-100 shadow-md shadow-indigo-50/50 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <dt class="text-[10px] font-bold text-indigo-400 uppercase">Tingkat
                                                Keparahan</dt>
                                            <dd class="mt-1 text-base font-bold text-gray-900 flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                                {{ $tingkatKeparahanMap[$final_tingkat_keparahan ?? ''] ?? 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-indigo-400 uppercase">Kemungkinan
                                                Terjadi</dt>
                                            <dd class="mt-1 text-base font-bold text-gray-900 flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                                {{ $kemungkinanTerjadiMap[$final_kemungkinan_terjadi ?? ''] ?? 'N/A' }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="pt-4 mt-2 border-t border-indigo-50 flex items-center justify-between">
                                        <dt class="text-xs font-bold text-gray-500 uppercase">Skor Risiko Final:</dt>
                                        <dd>
                                            <span class="px-5 py-2 rounded-lg font-black text-lg shadow-inner"
                                                style="background-color: {{ getRiskColor($final_risk_score) }}; color: {{ getTextColor($final_risk_score) }};">
                                                {{ $final_risk_score ?? 'N/A' }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- SISI KANAN: FORM RENCANA TINDAK LANJUT --}}
                        <div class="space-y-8">
                            <section>
                                <h3
                                    class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span
                                        class="mr-2 p-1 bg-emerald-100 rounded font-mono text-[10px] leading-none text-emerald-600">03</span>
                                    Formulir Rencana Tindak Lanjut
                                </h3>
                                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}"
                                        class="space-y-6">
                                        @csrf
                                        @method('PUT')

                                        {{-- Hidden inputs --}}
                                        <input type="hidden" name="status" value="diproses">
                                        <input type="hidden" name="final_tingkat_keparahan"
                                            value="{{ $final_tingkat_keparahan }}">
                                        <input type="hidden" name="final_kemungkinan_terjadi"
                                            value="{{ $final_kemungkinan_terjadi }}">
                                        <input type="hidden" name="faktor_penyebab" value="{{ $faktor_penyebab }}">
                                        <input type="hidden" name="final_kategori_stop6"
                                            value="{{ $final_kategori_stop6 }}">
                                        {{-- Upaya Penanggulangan --}}
                                        <div>
                                            <label
                                                class="block text-xs font-black text-gray-700 uppercase tracking-wide">Upaya
                                                Penanggulangan</label>
                                            <p class="text-[11px] text-gray-500 italic mt-0.5 mb-3">Isi satu atau
                                                lebih
                                                upaya yang akan dilakukan berdasarkan hirarki.</p>
                                            @php
                                                $options = ['Eliminasi', 'Substitusi', 'Rekayasa (Engineering)', 'Administrasi', 'APD'];
                                            @endphp
                                            <div class="space-y-4">
                                                @foreach ($options as $opt)
                                                    <div class="relative">
                                                        <label
                                                            class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1 tracking-tight">{{ $opt }}</label>
                                                        <input type="text" name="upaya_penanggulangan[{{ $opt }}]"
                                                            placeholder="Deskripsikan upaya {{ strtolower($opt) }}..."
                                                            class="block w-full px-3 py-2 text-sm bg-gray-50 border-gray-200 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all border outline-none">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>



                                        {{-- Rencana Tindakan (Instructions from SHE) --}}
                                        <div>
                                            <label
                                                class="block text-xs font-black text-gray-700 uppercase tracking-wide">Instruksi
                                                Rencana Perbaikan</label>
                                            <p class="text-[11px] text-gray-500 italic mt-0.5 mb-2">Berikan
                                                instruksi detail kepada petugas yang ditunjuk tentang apa yang harus
                                                diperbaiki.</p>
                                            <textarea name="rencana_perbaikan" rows="4"
                                                placeholder="Contoh: Tambahkan guard pada mesin, Pasang rambu peringatan, dll..."
                                                class="mt-2 w-full text-sm bg-gray-50 rounded-lg border-gray-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                                                required></textarea>
                                        </div>

                                        {{-- PIC Assignment --}}
                                        <div>
                                            <label for="pic_id"
                                                class="block text-xs font-black text-gray-700 uppercase tracking-wide">Orang
                                                yang ditunjuk oleh SHE</label>
                                            <p class="text-[11px] text-gray-500 italic mt-0.5 mb-2">Pilih petugas
                                                yang akan melaksanakan perbaikan.</p>
                                            <select id="pic_id" name="pic_id" class="tom-select-search" required>
                                                <option value="">Pilih Petugas</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        {{ $user->npk ? '(' . $user->npk . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Checkbox Konfirmasi --}}
                                        <div class="pt-4 border-t border-gray-100">
                                            <div
                                                class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 group transition-all hover:bg-indigo-50">
                                                <label for="konfirmasi_rencana"
                                                    class="flex items-center cursor-pointer">
                                                    <input type="checkbox" id="konfirmasi_rencana"
                                                        name="konfirmasi_rencana"
                                                        class="w-5 h-5 rounded border-indigo-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer transition-all">
                                                    <span
                                                        class="ml-3 text-sm font-semibold text-gray-700 select-none">Saya
                                                        yakin rencana tindak lanjut ini sudah benar.</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-end space-x-4 pt-2">
                                            <a href="{{ url()->previous() }}"
                                                class="inline-flex items-center px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                                                Batalkan
                                            </a>
                                            <button type="submit" id="submit_rencana" disabled
                                                class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200/50 transition-all opacity-40 cursor-not-allowed hover:bg-indigo-700 active:scale-95">
                                                Submit Rencana
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
        document.addEventListener('DOMContentLoaded', function () {

            const konfirmasiCheckbox = document.getElementById('konfirmasi_rencana');
            const submitButton = document.getElementById('submit_rencana');

            konfirmasiCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-40', 'cursor-not-allowed');
                } else {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-40', 'cursor-not-allowed');
                }
            });
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
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
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
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15) !important;
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
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.tom-select-search').forEach(function (el) {
                    new TomSelect(el, {
                        allowEmptyOption: true,
                        placeholder: el.options[0]?.text || 'Pilih...',
                        searchField: ['text'],
                        sortField: { field: 'text', direction: 'asc' },
                        render: {
                            no_results: function (data, escape) {
                                return '<div class="no-results">Tidak ditemukan: <strong>' + escape(data.input) + '</strong></div>';
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>