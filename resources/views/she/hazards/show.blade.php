<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan Bahaya') . ' #' . $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: Report Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Laporan</h3>
                            <dl class="divide-y divide-gray-200">
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">ID Laporan</dt>
                                    <dd class="text-gray-900">{{ $hazard->id }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Pelapor</dt>
                                    <dd class="text-gray-900">{{ $hazard->pelapor->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">NPK Pelapor</dt>
                                    <dd class="text-gray-900">{{ $hazard->NPK ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Departemen Pelapor</dt>
                                    <dd class="text-gray-900">{{ $hazard->dept ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Tanggal Observasi</dt>
                                    <dd class="text-gray-900">{{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Area Kerja</dt>
                                    <dd class="text-gray-900">{{ $hazard->area_gedung }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Line/Lokasi Spesifik</dt>
                                    <dd class="text-gray-900">{{ $hazard->line ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 text-sm font-medium">
                                    <dt class="text-gray-500">Deskripsi Bahaya</dt>
                                    <dd class="text-gray-900 mt-1">{{ $hazard->deskripsi_bahaya }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Jenis Bahaya</dt>
                                    <dd class="text-gray-900">{{ $hazard->jenis_bahaya }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Faktor Penyebab</dt>
                                    <dd class="text-gray-900">{{ $hazard->faktor_penyebab }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Tingkat Keparahan (Severity)</dt>
                                    <dd class="text-gray-900">{{ $hazard->tingkat_keparahan }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Kemungkinan Terjadi (Likelihood)</dt>
                                    <dd class="text-gray-900">{{ $hazard->kemungkinan_terjadi }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Skor Risiko</dt>
                                    <dd class="text-gray-900">{{ $hazard->skor_resiko }}</dd>
                                </div>
                                <div class="py-3 text-sm font-medium">
                                    <dt class="text-gray-500">Ide Penanggulangan</dt>
                                    <dd class="text-gray-900 mt-1">{{ $hazard->ide_penanggulangan ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Status Laporan</dt>
                                    <dd class="text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{
                                            $hazard->status == 'disetujui' ? 'bg-green-100 text-green-800' :
                                            ($hazard->status == 'diproses' ? 'bg-blue-100 text-blue-800' :
                                            ($hazard->status == 'ditolak' ? 'bg-red-100 text-red-800' :
                                            ($hazard->status == 'selesai' ? 'bg-purple-100 text-purple-800' :
                                            'bg-gray-100 text-gray-800')))
                                        }}">
                                            {{ ucfirst($hazard->status) }}
                                        </span>
                                    </dd>
                                </div>
                                @if ($hazard->status == 'ditolak' && $hazard->alasan_penolakan)
                                    <div class="py-3 text-sm font-medium">
                                        <dt class="text-gray-500">Alasan Penolakan</dt>
                                        <dd class="text-red-700 mt-1">{{ $hazard->alasan_penolakan }}</dd>
                                    </div>
                                @endif
                                @if ($hazard->ditangani_oleh)
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-500">Ditangani Oleh</dt>
                                        <dd class="text-gray-900">{{ $hazard->penanganan->name ?? 'N/A' }}</dd>
                                    </div>
                                @endif
                                @if ($hazard->ditangani_pada)
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-500">Ditangani Pada</dt>
                                        <dd class="text-gray-900">{{ \Carbon\Carbon::parse($hazard->ditangani_pada)->format('d M Y H:i') }}</dd>
                                    </div>
                                @endif
                                @if ($hazard->report_selesai)
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-500">Laporan Selesai Pada</dt>
                                        <dd class="text-gray-900">{{ \Carbon\Carbon::parse($hazard->report_selesai)->format('d M Y H:i') }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Right Column: Photo Documentation -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Dokumentasi</h3>
                            @if ($hazard->foto_temuan)
                                <img src="{{ asset('storage/' . $hazard->foto_temuan) }}" alt="Foto Temuan" class="max-w-full h-auto rounded-lg shadow-md">
                            @else
                                <p class="text-gray-600">Tidak ada foto dokumentasi.</p>
                            @endif

                            <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Aksi</h3>
                            <div class="flex flex-col space-y-2">
                                @if ($hazard->status == 'menunggu' || $hazard->status == 'diproses')
                                    <form action="{{ route('she.hazards.updateStatus', $hazard) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                                            Setujui Laporan
                                        </button>
                                    </form>
                                    <a href="{{ route('she.hazards.tolakForm', $hazard) }}" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                        Tolak Laporan
                                    </a>
                                    <form action="{{ route('she.hazards.updateStatus', $hazard) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="diproses">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                            Tandai Diproses
                                        </button>
                                    </form>
                                @endif
                                @if ($hazard->status == 'disetujui' || $hazard->status == 'diproses')
                                    <a href="{{ route('she.hazards.selesaiForm', $hazard) }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:text-sm">
                                        Tandai Selesai
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
