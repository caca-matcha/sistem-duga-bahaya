<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Validasi Laporan #{{ $hazard->id }} (Tanpa Tindak Lanjut)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl overflow-hidden">
                <div class="p-6 text-gray-900">

                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg">
                            <p class="font-bold">Terdapat beberapa kesalahan:</p>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- INFORMASI AWAL --}}
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b">
                        Informasi Laporan Awal
                    </h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-8">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pelapor</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $hazard->pelapor->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Observasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Deskripsi Bahaya</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $hazard->deskripsi_bahaya }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Faktor Penyebab</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $faktor_penyebab ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Kategori STOP6</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $final_kategori_stop6 ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                    
                    {{-- HASIL VALIDASI SHE --}}
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b">
                        Hasil Validasi Risiko oleh SHE
                    </h3>
                    @php
                        $tingkatKeparahanMap = [5 => 'A - Kecelakaan fatal', 3 => 'B - Hilang hari kerja', 1 => 'C - Luka ringan'];
                        $kemungkinanTerjadiMap = [1 => '1 - Sangat Jarang', 2 => '2 - Jarang', 3 => '3 - Kadang-Kadang', 4 => '4 - Sering', 5 => '5 - Sangat Sering'];
                    @endphp
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-8">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Final Tingkat Keparahan</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $tingkatKeparahanMap[$final_tingkat_keparahan ?? ''] ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Final Kemungkinan Terjadi</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $kemungkinanTerjadiMap[$final_kemungkinan_terjadi ?? ''] ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Skor Risiko Final</dt>
                            <dd class="mt-1">
                                <span class="px-3 py-1 rounded-full font-semibold text-xs" style="background-color: {{ getRiskColor($final_risk_score) }}; color: {{ getTextColor($final_risk_score) }};">
                                    {{ $final_risk_score ?? 'N/A' }}
                                </span>
                            </dd>
                        </div>
                    </dl>

                    {{-- FORM TINDAK LANJUT --}}
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b">
                        Formulir Peningkatan Upaya Penanggulangan
                    </h3>
                     {{-- Upaya Penanggulangan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Upaya Penanggulangan</label>
                                 <p class="text-xs text-gray-500 mb-2">Isi satu atau lebih upaya yang akan dilakukan.</p>
                                @php
                                    $options = ['Eliminasi', 'Substitusi', 'Rekayasa (Engineering)', 'Administrasi', 'APD'];
                                @endphp
                                <div class="mt-2 space-y-3 mb-4">
                                @foreach ($options as $opt)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ strtoupper($opt) }}</label>
                                        <input type="text" name="upaya_penanggulangan[{{ $opt }}]" placeholder="Tulis upaya di sini..." class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                @endforeach
                                </div>
                            </div>
                    
                    {{-- FORM KONFIRMASI --}}
                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}">
                        @csrf
                        @method('PUT')

                        {{-- Hidden inputs to carry over the final values --}}
                        <input type="hidden" name="status" value="selesai">
                        <input type="hidden" name="final_tingkat_keparahan" value="{{ $final_tingkat_keparahan }}">
                        <input type="hidden" name="final_kemungkinan_terjadi" value="{{ $final_kemungkinan_terjadi }}">
                        <input type="hidden" name="final_kategori_stop6" value="{{ $final_kategori_stop6 }}">
                        <input type="hidden" name="faktor_penyebab" value="{{ $faktor_penyebab }}">
                        <input type="hidden" name="tindakan_perbaikan" value="Validasi tanpa tindak lanjut.">
                        <input type="hidden" name="target_penyelesaian" value="{{ now()->toDateString() }}">
                        
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800">
                            <p>Anda akan menyelesaikan laporan ini secara langsung tanpa menambahkan rencana tindak lanjut. Laporan akan dianggap **SELESAI**.</p>
                        </div>

                        {{-- Checkbox Konfirmasi --}}
                        <div class="mt-6">
                            <label for="konfirmasi_selesai" class="inline-flex items-center">
                                <input type="checkbox" id="konfirmasi_selesai" name="konfirmasi_selesai" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Saya yakin untuk menyelesaikan laporan ini tanpa tindak lanjut.</span>
                            </label>
                        </div>
            
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-m font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" id="submit-button" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-m font-semibold rounded-md shadow transition opacity-50 cursor-not-allowed" disabled>
                                Submit & Selesaikan
                            </button>
                        </div>
                    </form>
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
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        });
    </script>
</x-app-layout>
