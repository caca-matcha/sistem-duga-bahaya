<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verifikasi & Tandai Selesai Laporan #{{ $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg border-gray-200">
                <div class="p-6 text-gray-900">

                    {{-- INFORMASI LAPORAN --}}
                    <h3 class="text-xl font-bold mb-6 pb-2 border-b">
                        Informasi Laporan Awal
                    </h3>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 mb-10">
                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Pelapor</dt>
                            <dd class="text-sm mt-1">{{ $hazard->pelapor->name ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Departemen</dt>
                            <dd class="text-sm mt-1">{{ $hazard->dept }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Tanggal Observasi</dt>
                            <dd class="text-sm mt-1">
                                {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Area Kerja</dt>
                            <dd class="text-sm mt-1">{{ $hazard->area_gedung }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-semibold text-gray-500">Deskripsi Bahaya</dt>
                            <dd class="text-sm mt-2 p-3 bg-gray-50 rounded-md border">
                                {{ $hazard->deskripsi_bahaya }}
                            </dd>
                        </div>
                    </dl>

                    {{-- RENCANA TINDAKAN --}}
                    <h3 class="text-xl font-bold mb-6 pb-2 border-b">
                        Rencana Tindakan
                    </h3>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 mb-10">
                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Faktor Penyebab</dt>
                            <dd class="text-sm mt-1">{{ $hazard->faktor_penyebab ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Validasi Tingkat Keparahan</dt>
                            <dd class="text-sm mt-1">{{ $hazard->final_tingkat_keparahan ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Validasi Kemungkinan Terjadi</dt>
                            <dd class="text-sm mt-1">{{ $hazard->final_kemungkinan_terjadi ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Rencana Tindakan Perbaikan</dt>
                            <dd class="text-sm mt-1">{{ $hazard->tindakan_perbaikan ?? 'N/A' }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-semibold text-gray-500">Upaya Penanggulangan</dt>
                            <dd class="text-sm mt-2">

                                @php
                                    $upaya = is_array($hazard->upaya_penanggulangan)
                                        ? $hazard->upaya_penanggulangan
                                        : json_decode($hazard->upaya_penanggulangan, true);
                                @endphp

                                @if (!empty($upaya))
                                    <ul class="list-disc list-inside ml-2 space-y-1">
                                        @foreach ($upaya as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="italic text-gray-500">Belum ada upaya yang dipilih</span>
                                @endif

                            </dd>
                        </div>
                    </dl>

                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM VERIFIKASI --}}
                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="selesai">

                            {{-- Foto Bukti --}}
                            <div>
                                <label for="foto_bukti_penyelesaian" class="block font-medium text-sm text-gray-700">
                                    Foto Bukti Penyelesaian
                                </label>

                                <input
                                    type="file"
                                    id="foto_bukti_penyelesaian"
                                    name="foto_bukti_penyelesaian"
                                    required
                                    class="mt-2 block w-full text-sm text-gray-600
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0
                                           file:bg-green-50 file:text-green-700
                                           hover:file:bg-green-100"
                                >

                                @error('foto_bukti_penyelesaian')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex items-center justify-end space-x-3 mt-10">
                            
                            <a href="{{ route('she.hazards.show', $hazard) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-m font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-m font-semibold rounded-md hover:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 transition">
                                Tandai Selesai
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
