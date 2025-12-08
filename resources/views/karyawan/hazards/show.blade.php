<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Laporan Bahaya #{{ $hazard->id }}
        </h2>
    </x-slot>


            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">

                    <div class="grid grid-cols-1 gap-10">

                        {{-- ===========================
                            LEFT SIDE — DETAIL LAPORAN
                        ============================ --}}
                        <div>
                            <h3 class="text-xl font-bold mb-4 border-b pb-2">Informasi Laporan</h3>

                            <dl class="space-y-3">

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">ID Laporan</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->id }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Pelapor</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->pelapor->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">NPK Pelapor</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->NPK }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Departemen</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->dept }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Tanggal Observasi</span>
                                    <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Area Kerja</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->area_gedung }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Aktivitas Kerja</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->aktivitas_kerja }}</span>
                                </div>

                                {{-- Deskripsi --}}
                                <div>
                                    <div class="text-sm text-gray-500">Deskripsi Bahaya</div>
                                    <div class="p-3 bg-gray-50 border rounded-md text-sm mt-1">
                                        {{ $hazard->deskripsi_bahaya }}
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Kategori STOP6</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->kategori_stop6 }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Severity</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->tingkat_keparahan }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Likelihood</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->kemungkinan_terjadi }}</span>
                                </div>

                                {{-- Ide penanggulangan --}}
                                <div>
                                    <div class="text-sm text-gray-500">Ide Penanggulangan</div>
                                    <div class="p-3 bg-gray-50 border rounded-md text-sm mt-1">
                                        {{ $hazard->ide_penanggulangan ?? 'N/A' }}
                                    </div>
                                </div>

                        {{-- ===========================
                            RIGHT SIDE — FOTO
                        ============================ --}}
                        <div class="mt-8">
                            <h3 class="text-xl font-bold mb-4 border-b pb-2">Foto Dokumentasi</h3>

                            @if ($hazard->foto_bukti)
                                <img src="{{ url('storage/' . $hazard->foto_bukti) }}"
                                     class="rounded-lg shadow-md border object-cover max-h-72 w-full">
                            @else
                                <div class="p-10 bg-gray-100 rounded-lg text-center text-gray-500">
                                    Tidak ada foto.
                                </div>
                            @endif
                        </div>

                        {{-- Status --}}
                                <div class="mt=8">
                                    <h3 class="text-xl font-bold mb-4 border-b pb-2">Status Laporan</h3>

                                    @php
                                        $badge = [
                                            'baru' => 'bg-yellow-100 text-yellow-800',
                                            'diproses' => 'bg-blue-100 text-blue-800',
                                            'disetujui' => 'bg-green-100 text-green-800',
                                            'selesai' => 'bg-purple-100 text-purple-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp

                                    <span class="px-3 py-1 text-m font-bold rounded-full {{ $badge[$hazard->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($hazard->status) }}
                                    </span>
                                </div>

                                {{-- Alasan penolakan --}}
                                @if ($hazard->status == 'ditolak')
                                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg mt-4 mb-4">
                                        <div class="font-semibold text-red-700">Alasan Penolakan:</div>
                                        <div class="text-red-600 mt-1 text-sm">{{ $hazard->alasan_penolakan }}</div>
                                    </div>
                                @endif


                                {{-- Penanganan --}}
                                @if ($hazard->ditangani_oleh)
                                    <div class="flex justify-between items-center mt-4 mb-4">
                                    <span class="text-sm text-gray-500">Ditangani Oleh</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->ditanganiOleh->name }}</span>
                                </div>
                                @endif

                                @if ($hazard->ditangani_pada)
                                    <div class="flex justify-between items-center mt-4 mb-4">
                                    <span class="text-sm text-gray-500">Ditangani Pada</span>
                                    <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hazard->ditangani_pada)->format('d M Y H:i') }}</span>
                                </div>
                                @endif

                                @if ($hazard->report_selesai)
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Selesai Pada</span>
                                    <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hazard->report_selesai)->format('d M Y H:i') }}</span>
                                </div>
                                @endif

                            </dl>
                        </div>

                <div class="py-10 pb-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                        <div class="mb-10 flex justify-end">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                &larr; Kembali
                            </a> 
                        </div>

                    </div> {{-- END GRID --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
