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
                    <p class="text-gray-500 font-medium mt-1 tracking-tight uppercase tracking-wider text-[11px] text-gray-400">
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
                                                Departemen</dt>
                                            <dd class="text-base font-semibold text-gray-800 mt-0.5">
                                                {{ $hazard->dept }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                            Tanggal Observasi</dt>
                                        <dd class="text-base font-semibold text-gray-800 mt-0.5">
                                            {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}
                                        </dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                            Detail Lokasi</dt>
                                        <dd class="text-base font-semibold text-gray-800 mt-0.5">
                                            {{ collect([$hazard->area_gedung, $hazard->area_type, $hazard->area_name])->filter()->join(' -> ') }}
                                            <span class="block text-xs text-gray-500 mt-1">ID Lokasi:
                                                {{ $hazard->area_id }}</span>
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
                                            Kategori STOP6</dt>
                                        <dd class="text-base font-semibold text-gray-800 mt-0.5">
                                            {{ $hazard->final_kategori_stop6 }}
                                        </dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">
                                            Cara Penanggulangan (Ide Awal)</dt>
                                        <dd class="text-base text-gray-700 leading-relaxed italic">
                                            "{{ $hazard->ide_penanggulangan ?? 'N/A' }}"</dd>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200">
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">
                                            Tindakan Perbaikan (Rencana SHE)</dt>
                                        <dd class="text-base font-semibold text-gray-800 mt-0.5">
                                            {{ $hazard->tindakan_perbaikan ?? 'N/A' }}
                                        </dd>
                                    </div>
                                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-2">
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
                                enctype="multipart/form-data" class="space-y-6">
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
                                                        <option value="{{ $value }}" {{ (old('final_kemungkinan_terjadi', $hazard->final_kemungkinan_terjadi) == $value) ? 'selected' : '' }}>{{ $label }}</option>
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

                                        {{-- Foto Bukti --}}
                                        <div class="pt-2">
                                            <label
                                                class="block text-xs font-black text-gray-700 uppercase tracking-wide mb-3">Bukti
                                                Penyelesaian</label>

                                            @if ($hazard->foto_bukti_penyelesaian)
                                                @php
                                                    $extension = pathinfo($hazard->foto_bukti_penyelesaian, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                                                @endphp
                                                <div
                                                    class="mb-4 p-2 bg-gray-50 border border-gray-200 rounded-xl inline-block">
                                                    <a href="{{ route('files.public', ['path' => $hazard->foto_bukti_penyelesaian]) }}"
                                                        target="_blank" class="block group relative">
                                                        @if($isImage)
                                                            <img src="{{ route('files.public', ['path' => $hazard->foto_bukti_penyelesaian]) }}"
                                                                alt="Foto Bukti"
                                                                class="rounded-lg object-cover w-40 h-40 transition group-hover:opacity-90">
                                                        @else
                                                            <div
                                                                class="w-40 h-40 flex flex-col items-center justify-center bg-white rounded-lg border border-dashed border-gray-300 transition group-hover:bg-gray-50">
                                                                <svg class="w-12 h-12 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                    </path>
                                                                </svg>
                                                                <span
                                                                    class="mt-2 text-[10px] font-bold text-gray-500 uppercase">{{ $extension }}
                                                                    File</span>
                                                            </div>
                                                        @endif
                                                        <div
                                                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                            <span
                                                                class="bg-black/50 text-white text-[10px] px-2 py-1 rounded">Lihat
                                                                Detail</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <p class="text-[10px] text-gray-400 mb-2">* Upload file baru untuk mengganti
                                                    file di atas.</p>
                                            @endif

                                            <div class="flex items-start gap-4">
                                                <div class="flex-1">
                                                    <input type="file" id="foto_bukti_penyelesaian"
                                                        name="foto_bukti_penyelesaian"
                                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                        onchange="previewFile(event)"
                                                        class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-all">
                                                    <p id="file_name_display"
                                                        class="mt-1 text-[10px] text-gray-500 italic hidden"></p>
                                                    @error('foto_bukti_penyelesaian')
                                                        <p class="text-[10px] text-red-600 mt-1 font-bold">{{ $message }}
                                                        </p>
                                                    @enderror
                                                </div>
                                                <div id="preview_container" class="hidden">
                                                    <img id="image_preview" src="#" alt="Preview"
                                                        class="rounded-lg shadow-md border border-green-200 object-cover w-20 h-20 hidden">
                                                    <div id="file_icon_preview"
                                                        class="w-20 h-20 flex flex-col items-center justify-center bg-green-50 rounded-lg border border-green-200 hidden">
                                                        <svg class="w-8 h-8 text-green-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
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
            function previewFile(event) {
                const file = event.target.files[0];
                const previewContainer = document.getElementById('preview_container');
                const imagePreview = document.getElementById('image_preview');
                const fileIconPreview = document.getElementById('file_icon_preview');
                const fileNameDisplay = document.getElementById('file_name_display');

                if (file) {
                    fileNameDisplay.textContent = file.name;
                    fileNameDisplay.classList.remove('hidden');
                    previewContainer.classList.remove('hidden');

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function () {
                            imagePreview.src = reader.result;
                            imagePreview.classList.remove('hidden');
                            fileIconPreview.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.classList.add('hidden');
                        fileIconPreview.classList.remove('hidden');
                    }
                } else {
                    fileNameDisplay.classList.add('hidden');
                    previewContainer.classList.add('hidden');
                }
            }

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