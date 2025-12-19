<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tindak Lanjut Laporan #{{ $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl overflow-hidden">
                <div class="p-6 text-gray-900">

                    {{-- INFORMASI AWAL --}}
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b">
                        Informasi Laporan
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
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Area Gedung</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $hazard->area_gedung }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Area Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $hazard->area_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Area Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $hazard->area_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Area ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $hazard->area_id }}</dd>
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
                        Formulir Rencana Tindak Lanjut
                    </h3>
                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}">
                        @csrf
                        @method('PUT')
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
                        {{-- Hidden inputs to carry over the final values --}}
                        <input type="hidden" name="status" value="diproses">
                        <input type="hidden" name="final_tingkat_keparahan" value="{{ $final_tingkat_keparahan }}">
                        <input type="hidden" name="final_kemungkinan_terjadi" value="{{ $final_kemungkinan_terjadi }}">
                        <input type="hidden" name="faktor_penyebab" value="{{ $faktor_penyebab }}">
                        <input type="hidden" name="final_kategori_stop6" value="{{ $final_kategori_stop6 }}">
                        
                        <div class="space-y-6">
                            {{-- Upaya Penanggulangan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Upaya Penanggulangan</label>
                                 <p class="text-xs text-gray-500 mb-2">Isi satu atau lebih upaya yang akan dilakukan.</p>
                                @php
                                    $options = ['Eliminasi', 'Substitusi', 'Rekayasa (Engineering)', 'Administrasi', 'APD'];
                                @endphp
                                <div class="mt-2 space-y-3">
                                @foreach ($options as $opt)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">{{ strtoupper($opt) }}</label>
                                        <input type="text" name="upaya_penanggulangan[{{ $opt }}]" placeholder="Tulis upaya di sini..." class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                @endforeach
                                </div>
                            </div>

                            {{-- Rencana Tindakan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rencana Tindakan Perbaikan</label>
                                <textarea name="tindakan_perbaikan" rows="4" class="mt-2 w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                            </div>

                            <div class="p-4 bg-blue-50 border-l-4 border-blue-200 text-blue-700 mb-6" role="alert">
                                <p class="font-bold">Penting:</p>
                                <p>Pastikan Target Penyelesaian ini realistis dan dapat dipenuhi. Tanggal hari ini adalah <strong>{{ \Carbon\Carbon::now()->format('d F Y') }}</strong>.</p>
                            </div>

                            {{-- Target Penyelesaian --}}
                            <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Target Penyelesaian
                            </label>

                            {{-- DROPDOWN DURASI --}}
                            <select id="durasi" class="mt-2 w-full rounded-md border-gray-300 shadow-sm" required>
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

                            {{-- TANGGAL TARGET OTOMATIS (READONLY + ABU-ABU) --}}
                            <div class="mt-4">
                                <label for="target_penyelesaian" class="block text-sm font-medium text-gray-700">
                                    Tanggal Target Penyelesaian
                                </label>
                                <input type="date" name="target_penyelesaian" id="target_penyelesaian"
                                    class="mt-2 w-full rounded-md border-gray-200 bg-gray-100 text-gray-500 shadow-sm cursor-not-allowed"
                                    readonly required>
                            </div>
                        </div>
                    </div>

                    {{-- SCRIPT PERHITUNGAN OTOMATIS --}}
                    <script>
                    document.getElementById("durasi").addEventListener("change", function () {
                        const value = this.value;
                        const targetInput = document.getElementById("target_penyelesaian");
                        let today = new Date();

                        if (!value) {
                            targetInput.value = "";
                            return;
                        }

                        let [jenis, jumlah] = value.split("_");
                        jumlah = parseInt(jumlah);

                        if (jenis === "hari") {
                            today.setDate(today.getDate() + jumlah);
                        } 
                        else if (jenis === "minggu") {
                            today.setDate(today.getDate() + (jumlah * 7));
                        } 
                        else if (jenis === "bulan") {
                            today.setMonth(today.getMonth() + jumlah);
                        }

                        let year = today.getFullYear();
                        let month = String(today.getMonth() + 1).padStart(2, "0");
                        let day = String(today.getDate()).padStart(2, "0");

                        targetInput.value = `${year}-${month}-${day}`;
                    });
                    </script>


                        {{-- Checkbox Konfirmasi --}}
                        <div class="mt-6">
                            <label for="konfirmasi_rencana" class="inline-flex items-center">
                                <input type="checkbox" id="konfirmasi_rencana" name="konfirmasi_rencana" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Saya menyatakan bahwa rencana tindak lanjut ini sudah benar dan dapat dipertanggungjawabkan.</span>
                            </label>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-m font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" id="submit_rencana" disabled class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-m font-semibold rounded-md shadow transition opacity-50 cursor-not-allowed">
                                Submit Rencana Tindak Lanjut
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
</x-app-layout>
