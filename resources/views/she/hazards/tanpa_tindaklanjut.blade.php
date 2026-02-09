<x-app-layout>
    @section('page-title', '')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Back Button --}}
                <a href="{{ route('she.hazards.index') }}"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-all border border-transparent hover:border-gray-200"
                    title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>

                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-50 rounded-xl border border-amber-100 shadow-sm shadow-amber-200/50">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="font-extrabold text-2xl text-gray-800 tracking-tight">
                        Validasi Laporan #{{ $hazard->id }} <span class="text-gray-300 font-medium italic">| Tanpa
                            Tindak
                            Lanjut</span>
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#FAFBFC] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 rounded-[32px] overflow-hidden">
                <div class="p-8 md:p-12 text-gray-900">

                    {{-- Tampilan Error Validasi --}}
                    @if ($errors->any())
                        <div
                            class="mb-8 p-5 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-start gap-4">
                            <div class="p-2 bg-red-100 rounded-lg text-red-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm">Terdapat kesalahan input:</p>
                                <ul class="list-disc list-inside text-xs mt-1 opacity-80">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                        {{-- SISI KIRI: INFORMASI & HASIL VALIDASI (COL-6) --}}
                        <div class="lg:col-span-6 space-y-12">
                            {{-- INFORMASI AWAL --}}
                            <section>
                                <h3
                                    class="flex items-center text-[11px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-6">
                                    <span
                                        class="mr-3 p-1 bg-emerald-50 rounded font-mono text-[10px] leading-none border border-emerald-100">01</span>
                                    Informasi Laporan Awal
                                </h3>
                                <div
                                    class="bg-white rounded-3xl p-8 border border-gray-100 space-y-8 shadow-sm relative overflow-hidden">
                                    {{-- Dots Pattern Decor --}}
                                    <div class="absolute top-0 right-0 p-4 opacity-[0.03]">
                                        <svg width="60" height="60" viewBox="0 0 20 20">
                                            <circle cx="2" cy="2" r="1.5" fill="black" />
                                            <circle cx="10" cy="2" r="1.5" fill="black" />
                                            <circle cx="18" cy="2" r="1.5" fill="black" />
                                            <circle cx="2" cy="10" r="1.5" fill="black" />
                                            <circle cx="10" cy="10" r="1.5" fill="black" />
                                            <circle cx="18" cy="10" r="1.5" fill="black" />
                                            <circle cx="2" cy="18" r="1.5" fill="black" />
                                            <circle cx="10" cy="18" r="1.5" fill="black" />
                                            <circle cx="18" cy="18" r="1.5" fill="black" />
                                        </svg>
                                    </div>

                                    <div class="grid grid-cols-2 gap-8">
                                        <div>
                                            <dt
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                                                Pelapor</dt>
                                            <dd class="text-lg font-extrabold text-gray-800">
                                                {{ $hazard->pelapor->name ?? 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                                                Tanggal Observasi</dt>
                                            <dd class="text-lg font-extrabold text-gray-800">
                                                {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}
                                            </dd>
                                        </div>
                                    </div>

                                    <div class="pt-6 border-t border-gray-50">
                                        <dt
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                                            Lokasi Lengkap</dt>
                                        <dd class="text-base font-bold text-gray-700">
                                            {{ collect([$hazard->area_gedung, $hazard->area_name])->filter()->join(' -> ') }}
                                        </dd>
                                    </div>

                                    <div class="pt-6 border-t border-gray-50">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                                            Deskripsi Bahaya</dt>
                                        <dd class="text-base text-gray-600 leading-relaxed font-medium italic">
                                            "{{ $hazard->deskripsi_bahaya }}"</dd>
                                    </div>

                                    <div class="grid grid-cols-2 gap-8 pt-6 border-t border-gray-50">
                                        <div>
                                            <dt
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                                                Faktor Penyebab</dt>
                                            <dd class="text-base font-bold text-gray-800">
                                                {{ $faktor_penyebab ?? 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                                                Kategori STOP6</dt>
                                            <dd class="text-base font-bold text-gray-800">
                                                {{ $final_kategori_stop6 ?? 'N/A' }}
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {{-- HASIL VALIDASI SHE --}}
                            <section>
                                <h3
                                    class="flex items-center text-[11px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-6">
                                    <span
                                        class="mr-3 p-1 bg-indigo-50 rounded font-mono text-[10px] leading-none border border-indigo-100">02</span>
                                    Hasil Validasi Risiko oleh SHE
                                </h3>
                                @php
                                    $tingkatKeparahanMap = [5 => 'A - Kecelakaan fatal', 3 => 'B - Hilang hari kerja', 1 => 'C - Luka ringan'];
                                    $kemungkinanTerjadiMap = [1 => '1 - Sangat Jarang', 2 => '2 - Jarang', 3 => '3 - Kadang-Kadang', 4 => '4 - Sering', 5 => '5 - Sangat Sering'];
                                @endphp
                                <div
                                    class="bg-[#F8FAFF] rounded-[32px] p-8 border border-indigo-50 shadow-sm space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div>
                                            <dt
                                                class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">
                                                Tingkat Keparahan</dt>
                                            <dd class="text-base font-extrabold text-gray-900 flex items-center">
                                                <span
                                                    class="w-2 h-2 rounded-full bg-indigo-500 mr-3 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></span>
                                                {{ $tingkatKeparahanMap[$final_tingkat_keparahan ?? ''] ?? 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt
                                                class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">
                                                Kemungkinan Terjadi</dt>
                                            <dd class="text-base font-extrabold text-gray-900 flex items-center">
                                                <span
                                                    class="w-2 h-2 rounded-full bg-indigo-500 mr-3 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></span>
                                                {{ $kemungkinanTerjadiMap[$final_kemungkinan_terjadi ?? ''] ?? 'N/A' }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div
                                        class="pt-8 mt-4 border-t border-indigo-100/50 flex items-center justify-between">
                                        <dt class="text-sm font-black text-gray-400 uppercase tracking-widest">Skor
                                            Risiko Final:</dt>
                                        <dd>
                                            <div class="flex items-center gap-4">
                                                <span
                                                    class="px-8 py-4 rounded-2xl font-black text-3xl shadow-lg border-b-4 border-black/10"
                                                    style="background-color: {{ getRiskColor($final_risk_score) }}; color: {{ getTextColor($final_risk_score) }};">
                                                    {{ $final_risk_score ?? '0' }}
                                                </span>
                                            </div>
                                        </dd>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- SISI KANAN: UPAYA & SUBMIT (COL-6) --}}
                        <div class="lg:col-span-6 space-y-8">
                            {{-- FORM KONFIRMASI & SUBMIT --}}
                            <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}"
                                class="space-y-8">
                                @csrf
                                @method('PUT')

                                {{-- FORM TINDAK LANJUT (Moved Inside Form) --}}
                                <section>
                                    <h3
                                        class="flex items-center text-[11px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-6">
                                        <span
                                            class="mr-3 p-1 bg-emerald-50 rounded font-mono text-[10px] leading-none border border-emerald-100">03</span>
                                        Upaya Penanggulangan Existing
                                    </h3>
                                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                                        <div class="mb-8 p-1">
                                            <label
                                                class="block text-sm font-black text-gray-800 uppercase tracking-wider mb-1">Hirarki
                                                Pengendalian</label>
                                            <p class="text-[11px] text-gray-400 font-medium leading-relaxed">Ringkasan
                                                upaya
                                                yang dilakukan berdasarkan hirarki keselamatan.</p>
                                        </div>

                                        @php
                                            $options = ['Eliminasi', 'Substitusi', 'Rekayasa (Engineering)', 'Administrasi', 'APD'];
                                        @endphp
                                        <div class="space-y-5">
                                            @foreach ($options as $opt)
                                                <div class="group">
                                                    <label
                                                        class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest group-focus-within:text-emerald-500 transition-colors">{{ $opt }}</label>
                                                    <input type="text" name="upaya_penanggulangan[{{ $opt }}]"
                                                        placeholder="Deskripsikan upaya {{ strtolower($opt) }}..."
                                                        class="block w-full px-5 py-4 text-sm font-medium bg-[#F9FAFB] border-gray-100 rounded-2xl shadow-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all border outline-none placeholder:text-gray-300">
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Custom Remark/Action SHE (Added as requested) --}}
                                        <div class="mt-8 pt-8 border-t border-gray-50 group">
                                            <label
                                                class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest group-focus-within:text-emerald-500 transition-colors">Tindakan / Catatan SHE</label>
                                            <textarea name="tindakan_perbaikan" rows="3" placeholder="Masukkan catatan atau konfirmasi tindakan..."
                                                class="block w-full px-5 py-4 text-sm font-medium bg-[#F9FAFB] border-gray-100 rounded-2xl shadow-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition-all border outline-none placeholder:text-gray-300">SHE akan tetap pantau area yg terlapor secara berkala untuk memastikan upaya penanggulangan sudah dilakukan.</textarea>
                                            <p class="text-[10px] text-gray-400 mt-2 ml-1 font-medium italic">* Pesan ini akan tampil pada detail laporan yang dilihat oleh pelapor.</p>
                                        </div>
                                    </div>
                                </section>

                                {{-- Hidden inputs --}}
                                <input type="hidden" name="status" value="selesai">
                                <input type="hidden" name="final_tingkat_keparahan"
                                    value="{{ $final_tingkat_keparahan }}">
                                <input type="hidden" name="final_kemungkinan_terjadi"
                                    value="{{ $final_kemungkinan_terjadi }}">
                                <input type="hidden" name="final_kategori_stop6" value="{{ $final_kategori_stop6 }}">
                                <input type="hidden" name="faktor_penyebab" value="{{ $faktor_penyebab }}">
                                <input type="hidden" name="target_penyelesaian" value="{{ now()->toDateString() }}">

                                <div class="p-6 bg-amber-50 rounded-3xl border border-amber-100 flex gap-5">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-500 shadow-sm border border-white/50">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-sm text-amber-900 leading-relaxed font-bold py-1">
                                        Pernyataan: <span class="font-medium text-amber-800">Anda akan menyelesaikan
                                            laporan ini secara langsung. SHE akan tetap pantau area yg terlapor secara
                                            berkala untuk memastikan upaya penanggulangan sudah dilakukan.
                                            Status laporan akan otomatis berubah menjadi</span> <span
                                            class="font-black underline decoration-2 underline-offset-4">SELESAI</span>.
                                    </div>
                                </div>

                                {{-- Checkbox Konfirmasi --}}
                                <div
                                    class="bg-[#F8FAFF] p-6 rounded-3xl border border-indigo-50 group transition-all hover:bg-white hover:shadow-xl hover:shadow-indigo-500/5 group">
                                    <label for="konfirmasi_selesai" class="flex items-center cursor-pointer">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" id="konfirmasi_selesai" name="konfirmasi_selesai"
                                                class="w-6 h-6 rounded-lg border-gray-200 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-2 cursor-pointer transition-all">
                                        </div>
                                        <span
                                            class="ml-4 text-[15px] font-extrabold text-gray-700 select-none group-hover:text-indigo-600 transition-colors tracking-tight">Saya
                                            yakin untuk menyelesaikan laporan ini.</span>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between pt-6">
                                    <a href="{{ url()->previous() }}"
                                        class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors flex items-center gap-2 group">
                                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Batalkan
                                    </a>
                                    <button type="submit" id="submit-button"
                                        class="inline-flex items-center px-10 py-5 bg-emerald-600 text-white text-[15px] font-black rounded-3xl shadow-xl shadow-emerald-200 transition-all opacity-40 cursor-not-allowed hover:bg-emerald-700 active:scale-[0.98] ring-4 ring-white"
                                        disabled>
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Selesaikan Laporan
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const confirmCheckbox = document.getElementById('konfirmasi_selesai');
            const submitButton = document.getElementById('submit-button');

            confirmCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-40', 'cursor-not-allowed', 'shadow-none');
                } else {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-40', 'cursor-not-allowed', 'shadow-none');
                }
            });
        });
    </script>
</x-app-layout>