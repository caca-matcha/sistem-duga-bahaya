<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan Bahaya') . ' #' . $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column: Report Details -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Informasi Laporan</h3>
                            <dl class="divide-y divide-gray-200">
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">ID Laporan</dt>
                                    <dd class="text-gray-900 font-semibold">{{ $hazard->id }}</dd>
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
                                    <dt class="text-gray-500">Aktivitas Kerja</dt>
                                    <dd class="text-gray-900">{{ $hazard->aktivitas_kerja ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 text-sm font-medium">
                                    <dt class="text-gray-500">Deskripsi Bahaya</dt>
                                    <dd class="text-gray-900 mt-1 p-2 bg-gray-50 rounded-md border">{{ $hazard->deskripsi_bahaya }}</dd>
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
                                    <dd class="text-gray-900 font-bold">{{ $hazard->skor_resiko }}</dd>
                                </div>
                                <div class="py-3 text-sm font-medium">
                                    <dt class="text-gray-500">Ide Penanggulangan</dt>
                                    <dd class="text-gray-900 mt-1 p-2 bg-gray-50 rounded-md border">{{ $hazard->ide_penanggulangan ?? 'N/A' }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-500">Status Laporan</dt>
                                    <dd class="text-gray-900">
                                        <!-- Status Badge Logic -->
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($hazard->status == 'disetujui') bg-green-100 text-green-800
                                            @elseif ($hazard->status == 'diproses') bg-blue-100 text-blue-800
                                            @elseif ($hazard->status == 'ditolak') bg-red-100 text-red-800
                                            @elseif ($hazard->status == 'selesai') bg-purple-100 text-purple-800
                                            @else bg-yellow-100 text-yellow-800 @endif
                                        ">
                                            {{ ucfirst($hazard->status) }}
                                        </span>
                                    </dd>
                                </div>
                                @if ($hazard->status == 'ditolak' && $hazard->alasan_penolakan)
                                    <div class="py-3 text-sm font-medium bg-red-50 p-3 rounded-lg border border-red-200">
                                        <dt class="text-gray-600 font-bold">Alasan Penolakan</dt>
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

                        <!-- Right Column: Photo Documentation & Actions -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Foto Dokumentasi</h3>
                            @if ($hazard->foto_bukti)
                                <!-- Replace with actual image asset helper in your application -->
                                <img src="{{ asset('storage/' . $hazard->foto_bukti) }}" alt="Foto Dokumentasi" class="max-w-full h-auto rounded-lg shadow-xl border object-cover">
                            @else
                                <div class="bg-gray-100 p-8 rounded-lg text-center text-gray-500">
                                    Tidak ada foto dokumentasi.
                                </div>
                            @endif

                            <h3 class="text-xl font-bold text-gray-800 mt-8 mb-6 border-b pb-2">Aksi Proses Laporan</h3>
                            <div class="flex flex-col space-y-3">
                                
                                @if ($hazard->status == 'menunggu')
                                    <!-- Aksi untuk status MENUNGGU: Setujui atau Tolak -->
                                    <form action="{{ route('she.hazards.updateStatus', $hazard) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-3 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Setujui Laporan
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('she.hazards.tolakForm', $hazard) }}" class="w-full inline-flex justify-center rounded-md border border-red-300 shadow-sm px-4 py-3 bg-white text-base font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Tolak Laporan
                                    </a>

                                @elseif ($hazard->status == 'disetujui')
                                    <!-- Aksi untuk status DISETUJUI: Tandai Diproses (Tindak Lanjut) -->
                                    <form action="{{ route('she.hazards.updateStatus', $hazard) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="diproses">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-3 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            Tindak Lanjut / Tandai Diproses
                                        </button>
                                    </form>
                                
                                @elseif ($hazard->status == 'diproses')
                                    <!-- Aksi untuk status DIPROSES: Tandai Selesai -->
                                    <a href="{{ route('she.hazards.selesaiForm', $hazard) }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-3 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Tandai Selesai
                                    </a>
                                
                                @else
                                    <!-- Status DITOLAK atau SELESAI: Tidak ada aksi -->
                                    <div class="p-4 bg-gray-100 rounded-lg text-center">
                                        <p class="text-sm text-gray-600 italic">Laporan ini sudah **{{ ucfirst($hazard->status) }}** dan tidak memerlukan aksi lebih lanjut.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>