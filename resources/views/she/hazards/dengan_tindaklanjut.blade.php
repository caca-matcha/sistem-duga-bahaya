<x-app-layout>
    @section('page-title', '')
    
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                {{-- Back Button --}}
                <a href="{{ route('she.hazards.index') }}" class="inline-flex items-center justify-center p-2 rounded-full text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition" title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                {{-- Existing Header Content --}}
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Validasi Laporan #{{ $hazard->id }} <span class="text-gray-400 font-normal">| Dengan Tindak Lanjut</span>
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
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
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
                                <h3 class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span class="mr-2 p-1 bg-gray-100 rounded font-mono text-[10px] leading-none text-gray-500">01</span>
                                    Informasi Laporan Awal
                                </h3>
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 space-y-4 shadow-sm">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Pelapor</dt>
                                            <dd class="text-base font-semibold text-gray-800 mt-0.5">{{ $hazard->pelapor->name ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Tanggal Observasi</dt>
                                            <dd class="text-base font-semibold text-gray-800 mt-0.5">{{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}</dd>
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Lokasi Lengkap</dt>
                                        <dd class="text-base font-semibold text-gray-800 mt-0.5">{{ collect([$hazard->area_gedung, $hazard->area_name])->filter()->join(' -> ') }}</dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Deskripsi Bahaya</dt>
                                        <dd class="text-base text-gray-700 leading-relaxed italic">"{{ $hazard->deskripsi_bahaya }}"</dd>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-200">
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Faktor Penyebab</dt>
                                            <dd class="text-base font-semibold text-gray-800 mt-0.5">{{ $faktor_penyebab ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Kategori STOP6</dt>
                                            <dd class="text-base font-semibold text-gray-800 mt-0.5">{{ $final_kategori_stop6 ?? 'N/A' }}</dd>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {{-- HASIL VALIDASI SHE --}}
                            <section>
                                <h3 class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span class="mr-2 p-1 bg-indigo-100 rounded font-mono text-[10px] leading-none text-indigo-600">02</span>
                                    Hasil Validasi Risiko oleh SHE
                                </h3>
                                @php
                                    $tingkatKeparahanMap = [5 => 'A - Kecelakaan fatal', 3 => 'B - Hilang hari kerja', 1 => 'C - Luka ringan'];
                                    $kemungkinanTerjadiMap = [1 => '1 - Sangat Jarang', 2 => '2 - Jarang', 3 => '3 - Kadang-Kadang', 4 => '4 - Sering', 5 => '5 - Sangat Sering'];
                                @endphp
                                <div class="bg-white rounded-xl p-5 border border-indigo-100 shadow-md shadow-indigo-50/50 space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <dt class="text-[10px] font-bold text-indigo-400 uppercase">Tingkat Keparahan</dt>
                                            <dd class="mt-1 text-base font-bold text-gray-900 flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                                {{ $tingkatKeparahanMap[$final_tingkat_keparahan ?? ''] ?? 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-indigo-400 uppercase">Kemungkinan Terjadi</dt>
                                            <dd class="mt-1 text-base font-bold text-gray-900 flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                                                {{ $kemungkinanTerjadiMap[$final_kemungkinan_terjadi ?? ''] ?? 'N/A' }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="pt-4 mt-2 border-t border-indigo-50 flex items-center justify-between">
                                        <dt class="text-xs font-bold text-gray-500 uppercase">Skor Risiko Final:</dt>
                                        <dd>
                                            <span class="px-5 py-2 rounded-lg font-black text-lg shadow-inner" style="background-color: {{ getRiskColor($final_risk_score) }}; color: {{ getTextColor($final_risk_score) }};">
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
                                <h3 class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span class="mr-2 p-1 bg-emerald-100 rounded font-mono text-[10px] leading-none text-emerald-600">03</span>
                                    Formulir Rencana Tindak Lanjut
                                </h3>
                                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}" class="space-y-6">
                                        @csrf
                                        @method('PUT')
                                        
                                        {{-- Hidden inputs --}}
                                        <input type="hidden" name="status" value="diproses">
                                        <input type="hidden" name="final_tingkat_keparahan" value="{{ $final_tingkat_keparahan }}">
                                        <input type="hidden" name="final_kemungkinan_terjadi" value="{{ $final_kemungkinan_terjadi }}">
                                        <input type="hidden" name="faktor_penyebab" value="{{ $faktor_penyebab }}">
                                        <input type="hidden" name="final_kategori_stop6" value="{{ $final_kategori_stop6 }}">
                                        
                                        {{-- Upaya Penanggulangan --}}
                                        <div>
                                            <label class="block text-xs font-black text-gray-700 uppercase tracking-wide">Upaya Penanggulangan</label>
                                            <p class="text-[11px] text-gray-500 italic mt-0.5 mb-3">Isi satu atau lebih upaya yang akan dilakukan berdasarkan hirarki.</p>
                                            @php
                                                $options = ['Eliminasi', 'Substitusi', 'Rekayasa (Engineering)', 'Administrasi', 'APD'];
                                            @endphp
                                            <div class="space-y-4">
                                                @foreach ($options as $opt)
                                                    <div class="relative">
                                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1 tracking-tight">{{ $opt }}</label>
                                                        <input type="text" name="upaya_penanggulangan[{{ $opt }}]" placeholder="Deskripsikan upaya {{ strtolower($opt) }}..." class="block w-full px-3 py-2 text-sm bg-gray-50 border-gray-200 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 transition-all border outline-none">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Rencana Tindakan --}}
                                        <div>
                                            <label class="block text-xs font-black text-gray-700 uppercase tracking-wide">Rencana Tindakan Perbaikan</label>
                                            <textarea name="tindakan_perbaikan" rows="4" class="mt-2 w-full text-sm bg-gray-50 rounded-lg border-gray-200 shadow-sm focus:ring-emerald-500 focus:border-emerald-500" required></textarea>
                                        </div>
                                        
                                        {{-- Warning Target Penyelesaian --}}
                                        <div class="p-3 bg-amber-50 border-l-4 border-amber-200 text-amber-700 mb-6" role="alert">
                                            <p class="font-bold text-sm">Penting:</p>
                                            <p class="text-sm">Pastikan Target Penyelesaian ini realistis dan dapat dipenuhi. Tanggal hari ini adalah <strong>{{ \Carbon\Carbon::now()->format('d F Y') }}</strong>.</p>
                                        </div>
                                        {{-- Target Penyelesaian --}}
                                        <div>
                                            <label class="block text-xs font-black text-gray-700 uppercase tracking-wide">Target Penyelesaian</label>
                                            <div class="grid grid-cols-2 gap-4 mt-2">
                                                {{-- DROPDOWN DURASI --}}
                                                <div>
                                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1 tracking-tight">Pilih Durasi Cepat</label>
                                                    <select id="durasi" class="w-full text-sm rounded-md border-gray-300 shadow-sm" required>
                                                        <option value="">Pilih Durasi</option>
                                                        <optgroup label="Hari">
                                                            <option value="hari_1">1 Hari</option>
                                                            <option value="hari_2">2 Hari</option>
                                                            <option value="hari_3">3 Hari</option>
                                                            <option value="hari_4">4 Hari</option>
                                                            <option value="hari_5">5 Hari</option>
                                                            <option value="hari_6">6 Hari</option>
                                                        </optgroup>
                                                        <optgroup label="Minggu">
                                                            <option value="minggu_1">1 Minggu</option>
                                                            <option value="minggu_2">2 Minggu</option>
                                                            <option value="minggu_3">3 Minggu</option>
                                                        </optgroup>
                                                        <optgroup label="Bulan">
                                                            <option value="bulan_1">1 Bulan</option>
                                                            <option value="bulan_2">2 Bulan</option>
                                                            <option value="bulan_3">3 Bulan</option>
                                                            <option value="bulan_4">4 Bulan</option>
                                                            <option value="bulan_5">5 Bulan</option>
                                                            <option value="bulan_6">6 Bulan</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                {{-- TANGGAL TARGET OTOMATIS --}}
                                                <div>
                                                    <label for="target_penyelesaian" class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1 tracking-tight">Atau Pilih Tanggal</label>
                                                    <input type="date" name="target_penyelesaian" id="target_penyelesaian" class="w-full text-sm rounded-md border-gray-300 bg-gray-100 shadow-sm" required>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Checkbox Konfirmasi --}}
                                        <div class="pt-4 border-t border-gray-100">
                                            <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 group transition-all hover:bg-indigo-50">
                                                <label for="konfirmasi_rencana" class="flex items-center cursor-pointer">
                                                    <input type="checkbox" id="konfirmasi_rencana" name="konfirmasi_rencana" class="w-5 h-5 rounded border-indigo-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer transition-all">
                                                    <span class="ml-3 text-sm font-semibold text-gray-700 select-none">Saya yakin rencana tindak lanjut ini sudah benar.</span>
                                                </label>
                                            </div>
                                        </div>
                        
                                        <div class="flex items-center justify-end space-x-4 pt-2">
                                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                                                Batalkan
                                            </a>
                                            <button type="submit" id="submit_rencana" disabled class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200/50 transition-all opacity-40 cursor-not-allowed hover:bg-indigo-700 active:scale-95">
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
        document.addEventListener('DOMContentLoaded', function() {
            const durasiSelect = document.getElementById('durasi');
            const targetInput = document.getElementById('target_penyelesaian');

            durasiSelect.addEventListener('change', function () {
                const value = this.value;
                let today = new Date();

                if (!value) {
                    // Jika user kembali ke "Pilih Durasi", jangan ubah input tanggal
                    return;
                }

                let [jenis, jumlah] = value.split("_");
                jumlah = parseInt(jumlah);

                if (jenis === "hari") {
                    today.setDate(today.getDate() + jumlah);
                } else if (jenis === "minggu") {
                    today.setDate(today.getDate() + (jumlah * 7));
                } else if (jenis === "bulan") {
                    today.setMonth(today.getMonth() + jumlah);
                }

                let year = today.getFullYear();
                let month = String(today.getMonth() + 1).padStart(2, "0");
                let day = String(today.getDate()).padStart(2, "0");

                targetInput.value = `${year}-${month}-${day}`;
                
                // Reset durasi select ke default untuk memungkinkan user memilih tanggal manual tanpa bingung
                this.value = '';
            });

            // Jika user memilih tanggal manual, pastikan select durasi tidak terpilih
            targetInput.addEventListener('input', function() {
                durasiSelect.value = '';
            });

            const konfirmasiCheckbox = document.getElementById('konfirmasi_rencana');
            const submitButton = document.getElementById('submit_rencana');

            konfirmasiCheckbox.addEventListener('change', function() {
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
</x-app-layout>

