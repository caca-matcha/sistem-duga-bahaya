<x-app-layout>
    @section('page-title', '')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                {{-- Back Button --}}
                <a href="{{ route('she.hazards.show', $hazard) }}"
                    class="inline-flex items-center justify-center p-2 rounded-full text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition"
                    title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        Verifikasi & Tandai Selesai <span class="text-gray-400 font-normal">| Laporan
                            #{{ $hazard->id }}</span>
                    </h2>
                    <p
                        class="text-gray-500 font-medium mt-1 tracking-tight uppercase tracking-wider text-[11px] text-gray-400">
                        Arsip laporan bahaya yang telah divalidasi dan diselesaikan.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm border border-gray-200 rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">

                    {{-- Tampilan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                            <ul class="list-disc list-inside text-xs space-y-1 ml-2 font-bold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                        {{-- SISI KIRI: INFORMASI LAPORAN --}}
                        <div class="space-y-8">
                            <section>
                                <h3
                                    class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                    <span
                                        class="mr-2 p-1 bg-gray-100 rounded font-mono text-[10px] leading-none text-gray-500">01</span>
                                    Informasi Laporan Awal
                                </h3>
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 space-y-4 shadow-sm">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Pelapor --}}
                                        <div>
                                            <dt
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">
                                                Informasi Pelapor</dt>
                                            <dd class="flex items-center gap-4">
                                                <div
                                                    class="h-12 w-12 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-base shadow-sm flex-shrink-0">
                                                    {{ substr($hazard->nama ?? ($hazard->pelapor->name ?? 'U'), 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 text-lg leading-tight">
                                                        {{ $hazard->nama ?? ($hazard->pelapor->name ?? 'N/A') }}
                                                        @if(($hazard->pelapor->role ?? '') === 'magang')
                                                            <span
                                                                class="ml-1 text-sm text-gray-400 font-medium italic whitespace-nowrap">(Magang
                                                                1)</span>
                                                        @endif
                                                    </div>
                                                    <div
                                                        class="flex items-center gap-1.5 mt-0.5 text-sm text-gray-500 font-medium tracking-tight">
                                                        <span
                                                            class="uppercase">{{ $hazard->dept ?? ($hazard->pelapor->department ?? '-') }}</span>
                                                        <span class="text-gray-300">•</span>
                                                        <span
                                                            class="text-gray-400 font-medium">{{ $hazard->NPK ?? ($hazard->pelapor->npk ?? '-') }}</span>
                                                    </div>
                                                </div>
                                            </dd>
                                        </div>

                                        {{-- Tanggal Observasi --}}
                                        <div class="md:text-right pt-2 md:pt-8">
                                            <dt
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                Tanggal Observasi</dt>
                                            <dd class="text-sm font-bold text-gray-800">
                                                {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->translatedFormat('l, d F Y') }}
                                            </dd>
                                        </div>
                                    </div>

                                    {{-- Detail Lokasi --}}
                                    <div class="pt-3 border-t border-gray-100">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                            Detail Lokasi</dt>
                                        <dd class="text-sm font-bold text-gray-800">
                                            {{ collect([$hazard->area_gedung, $hazard->area_name])->filter()->join(' -> ') }}
                                            <span class="text-[10px] text-gray-400 font-medium ml-2 uppercase">ID:
                                                {{ $hazard->area_id }}</span>
                                        </dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-100">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                            Deskripsi Bahaya</dt>
                                        <dd class="text-sm text-gray-700 leading-relaxed font-medium">
                                            {{ $hazard->deskripsi_bahaya }}
                                        </dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-100">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                            Kategori STOP6</dt>
                                        <dd class="text-sm font-bold text-gray-800">
                                            {{ $hazard->final_kategori_stop6 }}
                                        </dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-100">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                            Cara Penanggulangan (Ide Awal)</dt>
                                        <dd class="text-sm text-gray-700 leading-relaxed font-medium">
                                            {{ $hazard->ide_penanggulangan ?? 'N/A' }}
                                        </dd>
                                    </div>

                                    <div class="pt-3 border-t border-gray-100">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                            Instruksi Rencana Perbaikan (SHE)</dt>
                                        <dd class="text-sm font-bold text-blue-800">
                                            {{ $hazard->rencana_perbaikan ?? 'N/A' }}
                                        </dd>
                                    </div>

                                    <div class="pt-3 border-t border-gray-100">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                            Upaya Terpilih (Hirarki Pengendalian)</dt>
                                        <dd class="flex flex-wrap gap-2 mt-1">
                                            @if($hazard->upaya_penanggulangan && count($hazard->upaya_penanggulangan) > 0)
                                                @foreach($hazard->upaya_penanggulangan as $upaya => $detail)
                                                    @if(!empty($detail))
                                                        <div
                                                            class="flex flex-col p-2 bg-emerald-50 border border-emerald-100 rounded-lg">
                                                            <span
                                                                class="text-[9px] font-black text-emerald-600 uppercase">{{ $upaya }}</span>
                                                            <span class="text-xs text-gray-700">{{ $detail }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-xs text-gray-500 italic">Tidak ada upaya spesifik yang
                                                    dipilih.</span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- SISI KANAN: FORM VERIFIKASI SELESAI --}}
                        <div class="space-y-8">
                            <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}"
                                class="space-y-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="selesai">

                                <section>
                                    <h3
                                        class="flex items-center text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                                        <span
                                            class="mr-2 p-1 bg-green-100 rounded font-mono text-[10px] leading-none text-green-600">02</span>
                                        Edit Rencana & Bukti Penyelesaian
                                    </h3>

                                    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm space-y-6">
                                        {{-- Final Risk Assessment --}}
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="final_tingkat_keparahan"
                                                    class="block text-xs font-black text-gray-700 uppercase tracking-wide mb-1">Final
                                                    Tingkat Keparahan</label>
                                                <select id="final_tingkat_keparahan" name="final_tingkat_keparahan"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                                                    @foreach ([1 => 'C - Luka ringan', 3 => 'B - Hilang hari kerja', 5 => 'A - Kecelakaan fatal'] as $value => $label)
                                                        <option value="{{ $value }}" {{ (old('final_tingkat_keparahan', $hazard->final_tingkat_keparahan) == $value) ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="final_kemungkinan_terjadi"
                                                    class="block text-xs font-black text-gray-700 uppercase tracking-wide mb-1">Final
                                                    Kemungkinan Terjadi</label>
                                                <select id="final_kemungkinan_terjadi" name="final_kemungkinan_terjadi"
                                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm">
                                                    @foreach ([1 => '1 - Sangat Jarang', 2 => '2 - Jarang', 3 => '3 - Kadang-Kadang', 4 => '4 - Sering', 5 => '5 - Sangat Sering'] as $value => $label)
                                                        <option value="{{ $value }}" {{ (old('final_kemungkinan_terjadi', $hazard->final_kemungkinan_terjadi) == $value) ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Score Display --}}
                                        <div
                                            class="pt-4 border-t border-dashed border-gray-200 flex items-center justify-between bg-gray-50/50 p-3 rounded-lg">
                                            <span class="text-xs font-bold text-gray-500 uppercase">Skor Risiko
                                                Final</span>
                                            <span id="final_risk_score_display"
                                                class="px-5 py-2 rounded-lg font-black text-lg shadow-inner">
                                                {{-- Calculated by JS --}}
                                            </span>
                                        </div>

                                        {{-- Deskripsi Tindakan PIC --}}
                                        <div class="pt-4 border-t border-gray-100">
                                            <label
                                                class="block text-xs font-black text-gray-700 uppercase tracking-wide mb-2">Tindakan
                                                Yang Telah Dilakukan (PIC)</label>
                                            <div
                                                class="p-4 bg-emerald-50/50 border border-emerald-100 rounded-xl italic text-sm text-gray-700 font-medium">
                                                "{{ $hazard->tindakan_perbaikan ?? 'Tidak ada deskripsi tindakan.' }}"
                                            </div>
                                        </div>

                                        {{-- Bukti Penyelesaian dari PIC --}}
                                        <div class="pt-4 border-t border-gray-100">
                                            <label
                                                class="block text-xs font-black text-gray-700 uppercase tracking-wide mb-3">Bukti
                                                Penyelesaian (Dari Orang yang ditunjuk oleh SHE)</label>

                                            @if ($hazard->foto_bukti_penyelesaian)
                                                @php
                                                    $path = $hazard->foto_bukti_penyelesaian;
                                                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                                                    $targetDate = $hazard->target_penyelesaian ? \Carbon\Carbon::parse($hazard->target_penyelesaian) : null;
                                                    $actualDate = $hazard->report_selesai ? \Carbon\Carbon::parse($hazard->report_selesai) : now();
                                                    $isOverdue = $targetDate && $actualDate->gt($targetDate);
                                                @endphp

                                                <div class="flex flex-col gap-4">
                                                    {{-- Display Evidence --}}
                                                    <div
                                                        class="p-3 bg-gray-50 border border-gray-200 rounded-xl inline-block w-full">
                                                        <a href="{{ route('files.public', ['path' => $path]) }}"
                                                            target="_blank" class="group flex items-center gap-4">
                                                            @if($isImage)
                                                                <img src="{{ route('files.public', ['path' => $path]) }}"
                                                                    class="w-20 h-20 object-cover rounded-lg border border-gray-300">
                                                            @else
                                                                <div
                                                                    class="w-20 h-20 flex items-center justify-center bg-white rounded-lg border border-gray-300">
                                                                    <span
                                                                        class="text-xs font-bold text-gray-500 uppercase">{{ $extension }}</span>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="text-sm font-bold text-gray-800">Bukti Tersedia
                                                                </p>
                                                                <p class="text-xs text-blue-600 group-hover:underline">Klik
                                                                    untuk melihat detail</p>
                                                            </div>
                                                        </a>
                                                    </div>

                                                    {{-- Timeline & Overdue Warning --}}
                                                    <div
                                                        class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl border {{ $isOverdue ? 'border-red-200 bg-red-50' : 'border-gray-100' }}">
                                                        <div class="{{ $targetDate ? '' : 'text-gray-400' }}">
                                                            <dt
                                                                class="text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none mb-1">
                                                                Orang yang ditunjuk oleh SHE:
                                                                {{ $hazard->pic->name ?? '-' }}
                                                            </dt>
                                                            <dt
                                                                class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                Target
                                                            </dt>
                                                            <dd
                                                                class="text-sm font-bold {{ $targetDate ? 'text-gray-800' : 'italic text-gray-400' }}">
                                                                {{ $targetDate ? $targetDate->translatedFormat('d M Y') : 'Belum ditentukan petugas' }}
                                                            </dd>
                                                        </div>
                                                        <div>
                                                            <dt
                                                                class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                Realisasi
                                                            </dt>
                                                            <dd
                                                                class="text-sm font-bold {{ $isOverdue ? 'text-red-600' : 'text-green-600' }}">
                                                                {{ $actualDate->translatedFormat('d M Y') }}
                                                            </dd>
                                                        </div>
                                                        @if($isOverdue)
                                                            <div class="col-span-2 pt-2 border-t border-red-200">
                                                                <div class="flex items-center text-red-700 gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                    <span class="text-xs font-bold uppercase">Melewati Batas
                                                                        Waktu!</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div
                                                    class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                        </path>
                                                    </svg>
                                                    <div>
                                                        <p class="font-bold text-sm">File belum diupload oleh Petugas</p>
                                                        <p class="text-xs">Mohon hubungi petugas
                                                            <strong>({{ $hazard->pic->name ?? 'N/A' }})</strong> untuk
                                                            melengkapi bukti.
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </section>

                                {{-- FORM KONFIRMASI --}}
                                <section class="space-y-6">
                                    <div
                                        class="p-3 bg-indigo-50 border border-indigo-100 rounded-xl flex gap-3 shadow-sm">
                                        <div class="flex-shrink-0">
                                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="text-[11px] text-indigo-800 leading-relaxed font-medium">
                                            Pernyataan: Dengan mencentang kotak di bawah, Anda menyatakan bahwa rencana
                                            tindak lanjut ini telah diproses dan status laporan akan berubah menjadi
                                            <span class="font-bold underline">SELESAI</span>.
                                        </div>
                                    </div>

                                    <div
                                        class="bg-green-50/50 p-4 rounded-xl border border-green-100 group transition-all hover:bg-green-50">
                                        <label for="konfirmasi_rencana" class="flex items-center cursor-pointer">
                                            <input type="checkbox" id="konfirmasi_rencana" name="konfirmasi_rencana"
                                                class="w-5 h-5 rounded border-green-300 text-green-600 shadow-sm focus:ring-green-500 cursor-pointer transition-all">
                                            <span class="ml-3 text-sm font-semibold text-gray-700 select-none">Saya
                                                menyatakan laporan ini telah selesai.</span>
                                        </label>
                                    </div>

                                    <div class="flex items-center justify-end space-x-4 pt-4">
                                        <a href="{{ route('she.hazards.show', $hazard) }}"
                                            class="inline-flex items-center px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                                            Batalkan
                                        </a>
                                        <button type="submit" id="submit_rencana"
                                            class="inline-flex items-center px-8 py-3 bg-green-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-green-200/50 transition-all opacity-40 cursor-not-allowed hover:bg-green-700 active:scale-95"
                                            disabled>
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Simpan & Selesaikan Laporan
                                        </button>
                                    </div>
                                </section>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
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
                        disp.style.backgroundColor = "#E5E7EB";
                        disp.style.color = "#9CA3AF";
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

            document.addEventListener('DOMContentLoaded', function () {
                const konfirmasiCheckbox = document.getElementById('konfirmasi_rencana');
                const submitButton = document.getElementById('submit_rencana');

                function updateButtonState() {
                    const isChecked = konfirmasiCheckbox.checked;
                    submitButton.disabled = !isChecked;
                    if (isChecked) {
                        submitButton.classList.remove('opacity-40', 'cursor-not-allowed');
                    } else {
                        submitButton.classList.add('opacity-40', 'cursor-not-allowed');
                    }
                }

                konfirmasiCheckbox.addEventListener('change', updateButtonState);
                updateButtonState(); // Initial state
            });
        </script>
    @endpush
</x-app-layout>