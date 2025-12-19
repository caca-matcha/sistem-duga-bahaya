<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verifikasi & Tandai Selesai Laporan #{{ $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8 border-b border-gray-200">

                    {{-- INFORMASI AWAL (STATIS) --}}
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
                            {{-- Kategori STOP6 --}}
                            <div class="flex justify-between items-center">
                                <dt class="text-sm text-gray-500">Kategori STOP6</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $hazard->final_kategori_stop6 }}</dd>
                            </div>

                            {{-- Deskripsi Bahaya --}}
                            <div class="pt-4">
                                <dt class="text-sm font-medium text-gray-500">Deskripsi Bahaya</dt>
                                <dd class="text-sm bg-gray-50 p-3 rounded-md mt-1">{{ $hazard->deskripsi_bahaya }}</dd>
                            </div>
                        </dl>

                    {{-- Error Alert --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg">
                            <p class="font-bold">Terjadi Kesalahan:</p>
                            <ul class="list-disc list-inside text-sm mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM EDIT & SELESAI --}}
                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="selesai">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Edit Rencana & Bukti Penyelesaian</h3>

                        {{-- Final Risk Assessment --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="final_tingkat_keparahan" class="block text-sm font-medium text-gray-700">Final Tingkat Keparahan</label>
                                <select id="final_tingkat_keparahan" name="final_tingkat_keparahan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach ([1 => 'C - Luka ringan', 3 => 'B - Hilang hari kerja', 5 => 'A - Kecelakaan fatal'] as $value => $label)
                                        <option value="{{ $value }}" {{ (old('final_tingkat_keparahan', $hazard->final_tingkat_keparahan) == $value) ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="final_kemungkinan_terjadi" class="block text-sm font-medium text-gray-700">Final Kemungkinan Terjadi</label>
                                <select id="final_kemungkinan_terjadi" name="final_kemungkinan_terjadi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach ([1 => '1 - Sangat Jarang', 2 => '2 - Jarang', 3 => '3 - Kadang-Kadang', 4 => '4 - Sering', 5 => '5 - Sangat Sering'] as $value => $label)
                                        <option value="{{ $value }}" {{ (old('final_kemungkinan_terjadi', $hazard->final_kemungkinan_terjadi) == $value) ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
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

                        {{-- Foto Bukti Penyelesaian --}}
                        <div class="mt-6">
                            <label for="foto_bukti_penyelesaian" class="block font-medium text-sm text-gray-700">Bukti Penyelesaian</label>
                            @if ($hazard->foto_bukti_penyelesaian)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $hazard->foto_bukti_penyelesaian) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $hazard->foto_bukti_penyelesaian) }}" alt="Foto Bukti Penyelesaian" class="rounded-lg shadow-md border object-cover w-48 h-48 hover:shadow-lg transition">
                                    </a>
                                    <p class="text-xs text-gray-500 mt-2">Foto bukti sudah ada. Upload file baru untuk menggantinya.</p>
                                </div>
                            @endif
                            <input type="file" id="foto_bukti_penyelesaian" name="foto_bukti_penyelesaian" accept="image/*" onchange="previewImage(event)" class="mt-2 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <img id="image_preview" src="#" alt="Image Preview" class="rounded-lg shadow-md border object-cover w-48 h-48 mt-4 hidden">
                            @error('foto_bukti_penyelesaian')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                         {{-- Checkbox Konfirmasi --}}
                        <div class="mt-6">
                            <label for="konfirmasi_rencana" class="inline-flex items-center">
                                <input type="checkbox" id="konfirmasi_rencana" name="konfirmasi_rencana" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Saya menyatakan bahwa rencana tindak lanjut ini telah diproses dan dapat diselesaikan.</span>
                            </label>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex items-center justify-end space-x-4 mt-10 pt-6 border-t border-gray-200">
                            <a href="{{ route('she.hazards.show', $hazard) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Simpan Perubahan & Tandai Selesai
                            </button>

    @push('scripts')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('image_preview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
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

        document.addEventListener('DOMContentLoaded', function() {
            const konfirmasiCheckbox = document.getElementById('konfirmasi_rencana');
            const submitButton = document.getElementById('submit_rencana');

            // Set initial state
            submitButton.disabled = !konfirmasiCheckbox.checked;
            submitButton.classList.toggle('opacity-50', !konfirmasiCheckbox.checked);
            submitButton.classList.toggle('cursor-not-allowed', !konfirmasiCheckbox.checked);
            submitButton.classList.toggle('hover:bg-indigo-700', konfirmasiCheckbox.checked);

            konfirmasiCheckbox.addEventListener('change', function() {
                submitButton.disabled = !this.checked;
                submitButton.classList.toggle('opacity-50', !this.checked);
                submitButton.classList.toggle('cursor-not-allowed', !this.checked);
                submitButton.classList.toggle('hover:bg-indigo-700', this.checked);
            });
        });
    </script>
    @endpush
</x-app-layout>
