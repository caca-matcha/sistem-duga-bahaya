<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Laporan Bahaya #{{ $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses/Error --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl">
                <div class="p-6 text-gray-900">

                    <div class="grid grid-cols-1 gap-10">

                        {{-- ===================================
                            COLUMN 1: INFORMASI LAPORAN AWAL
                        ==================================== --}}
                        <div class="md:col-span-1">
                            <h3 class="text-xl font-bold mb-4 text-indigo-700 border-b pb-2">1. Laporan Awal Pelapor</h3>

                            <dl class="space-y-4">
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
                                <div class="pt-2">                  
                                     @php
                                        $tingkatKeparahanMap = [
                                            5 => 'A - Kecelakaan fatal',
                                            3 => 'B - Hilang hari kerja',
                                            1 => 'C - Luka ringan',
                                        ];
                                        $kemungkinanTerjadiMap = [
                                            1 => '1 - Sangat Jarang',
                                            2 => '2 - Jarang',
                                            3 => '3 - Kadang-Kadang',
                                            4 => '4 - Sering',
                                            5 => '5 - Sangat Sering',
                                        ];
                                    @endphp
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Area Kerja</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->area_gedung }}</span>
                                </div>
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Aktivitas Kerja</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->aktivitas_kerja }}</span>
                                </div>
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Kategori STOP6</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->kategori_stop6 }}</span>
                                </div>
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Tingkat Keparahan Awal</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $tingkatKeparahanMap[$hazard->tingkat_keparahan] ?? 'N/A' }}</span>
                                </div>
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Kemungkinan Terjadi Awal</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $kemungkinanTerjadiMap[$hazard->kemungkinan_terjadi] ?? 'N/A' }}</span>
                                </div>                                </div>
                                </div>

                                {{-- Initial Risk Score --}}
                                <div class="flex justify-between items-center text-sm font-bold pt-4 border-t">
                                    <span class="text-gray-500">Skor Risiko Awal (Pelapor)</span>
                                    <span class="px-3 py-1 rounded-full font-semibold text-xs"
                                        style="background-color: {{ getRiskColor($hazard->risk_score) }}; color: {{ getTextColor($hazard->risk_score) }};">
                                        {{ $hazard->risk_score }} ({{ $hazard->kategori_resiko }})
                                    </span>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="pt-4">
                                    <div class="text-sm text-gray-500 font-medium">Deskripsi Bahaya</div>
                                    <div class="p-3 bg-gray-50 border rounded-md text-sm mt-1 break-words">
                                        {{ $hazard->deskripsi_bahaya }}
                                    </div>
                                </div>
                                
                                {{-- Ide penanggulangan --}}
                                <div>
                                    <div class="text-sm text-gray-500 font-medium">Ide Penanggulangan Pelapor</div>
                                    <div class="p-3 bg-gray-50 border rounded-md text-sm mt-1 break-words">
                                        {{ $hazard->ide_penanggulangan ?? 'Tidak ada ide' }}
                                    </div>
                                </div>
                            </dl>
                        </div>


                        {{-- ===================================





                            COLUMN 2: VALIDASI & TINDAK LANJUT SHE
                        ==================================== --}}
                        <div class="md:col-span-1 border-l pl-8">
                            <h3 class="text-xl font-bold mb-4  text-indigo-700 border-b pb-2 mt-6">2. Validasi & Penanganan SHE</h3>

                            @if ($hazard->status == 'ditolak')
                                {{-- ALASAN PENOLAKAN --}}
                                <div class="p-4 bg-red-50 border border-red-300 rounded-lg">
                                    <div class="font-bold text-red-700 mb-1">Status: DITOLAK</div>
                                    <div class="text-sm text-red-600">Alasan Penolakan:</div>
                                    <p class="text-sm italic mt-1">{{ $hazard->alasan_penolakan }}</p>
                                </div>
                            @elseif ($hazard->status == 'menunggu validasi')
                                {{-- Menunggu Proses Validasi --}}
                                <div class="p-4 bg-yellow-50 border border-yellow-300 rounded-lg text-sm text-yellow-800">
                                    Laporan ini menunggu proses validasi dan perencanaan tindakan dari Tim SHE.
                                </div>
                            @else
                                {{-- Data Penanganan (Status DIPROSES atau SELESAI) --}}
                                <dl class="space-y-4">
                                    
                                 {{-- Final Risk Score --}}
                            <div class="flex justify-between items-center text-sm font-bold pt-2 border-b border-dashed">
                                <span class="text-green-700 font-bold">SKOR RISIKO FINAL</span>

                                @php
                                    $finalRiskScore = $hazard->final_tingkat_keparahan * $hazard->final_kemungkinan_terjadi;

                                    // Array warna untuk skor 1–25
                                    $riskColors = [
                                        "#d1fae5","#a7f3d0","#6ee7b7","#34d399","#10b981", // 1–5
                                        "#fef08a","#fde047","#facc15","#fbbf24","#f59e0b", // 6–10
                                        "#fdba74","#fb923c","#f97316","#f87171","#ef4444", // 11–15
                                        "#ef4444","#f87171","#f97316","#fb7185","#f43f5e", // 16–20
                                        "#ffe4e1","#ffb3b3","#ff8080","#ff4d4d","#ff1a1a"  // 21–25
                                    ];

                                    // Ambil warna sesuai skor (indeks = skor-1)
                                    $finalRiskColor = $riskColors[max(0, min($finalRiskScore-1, 24))];
                                @endphp

                                 <span class="px-3 py-1 rounded-full font-semibold text-xs"
                                    style="background-color: {{ getRiskColor($finalRiskScore) }}; color: {{ getTextColor($finalRiskScore) }};">
                                    {{ $finalRiskScore }}
                                </span>
                            </div>


                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Validasi Tingkat Keparahan</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->final_tingkat_keparahan ?? 'N/A' }}</span>
                                </div>
                                    <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Validasi Kemungkinan Terjadi</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $hazard->final_kemungkinan_terjadi ?? 'N/A' }}</span>
                                </div>
                                    
                                    <div class="pt-4">
                                        <div class="text-sm text-gray-500 font-medium">Faktor Penyebab</div>
                                        <div class="p-3 bg-gray-50 border rounded-md text-sm mt-1 break-words">
                                            {{ $hazard->faktor_penyebab ?? 'N/A' }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-sm text-gray-500 font-medium">Upaya Penanggulangan (Hirarki)</div>
                                        @php
                                            $upaya = is_array($hazard->upaya_penanggulangan) ? $hazard->upaya_penanggulangan : json_decode($hazard->upaya_penanggulangan, true);
                                        @endphp
                                        @if (!empty($upaya))
                                            <ul class="list-disc list-inside ml-2 text-sm mt-1">
                                                @foreach ($upaya as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="p-3 bg-gray-50 border rounded-md text-sm mt-1 italic">Belum ada upaya yang dipilih</p>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <div class="text-sm text-gray-500 font-medium">Rencana Tindakan Perbaikan</div>
                                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-md text-sm mt-1 break-words">
                                            {{ $hazard->tindakan_perbaikan ?? 'N/A' }}
                                        </div>
                                    </div>
                                    
                                    @if ($hazard->ditangani_oleh)
                                        <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Divalidasi Oleh</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $hazard->ditanganiOleh->name ?? 'N/A' }}</span>
                                    </div>
                                    @endif
                                    @if ($hazard->ditangani_pada)
                                        <div class="flex justify-between items-center">
                                                                                <span class="text-sm text-gray-500">Divalidasi Pada</span>
                                                                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hazard->ditangani_pada)->format('d M Y H:i') }}</span>
                                                                            </div>                                    @endif

                                    {{-- Data SELESAI --}}
                                    @if ($hazard->status == 'selesai')
                                        <div class="pt-4 border-t border-dashed">
                                            <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">Selesai Pada</span>
                                            <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($hazard->report_selesai)->format('d M Y H:i') }}</span>
                                        </div>
                                    @endif

                                </dl>
                            @endif
                        </div>


                        {{-- ===================================
                            COLUMN 3: FOTO & AKSI BUTTONS
                        ==================================== --}}
                        <div class="md:col-span-1 border-l pl-8">
                            <h3 class="text-xl font-bold mb-4  text-indigo-700 border-b pb-2 mt-6">3. Dokumentasi & Aksi</h3>
                            @if ($hazard->foto_bukti)
                                <a href="{{ asset('storage/' . $hazard->foto_bukti) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $hazard->foto_bukti) }}"
                                        alt="Foto Bukti Bahaya"
                                        class="rounded-lg shadow-md border object-cover max-h-48 w-full hover:shadow-lg transition">
                                </a>
                            @else
                                <div class="p-8 bg-gray-100 rounded-lg text-center text-gray-500 text-sm">
                                    Tidak ada foto bahaya.
                                </div>
                            @endif

                            @if ($hazard->status == 'selesai' && $hazard->foto_bukti_penyelesaian)
                                <h4 class="font-semibold text-gray-600 mt-6 mb-2 border-t pt-4">Foto Bukti Penyelesaian</h4>
                                <a href="{{ asset('storage/' . $hazard->foto_bukti_penyelesaian) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $hazard->foto_bukti_penyelesaian) }}"
                                        alt="Foto Bukti Penyelesaian"
                                        class="rounded-lg shadow-md border object-cover max-h-48 w-full hover:shadow-lg transition">
                                </a>
                            @endif

                            {{-- ACTION BUTTONS BASED ON STATUS --}}
                            <div class="flex justify-center space-y-3 space-x-3 mt-6">
                                @php
                                    // Define utility classes for buttons
                                    $baseBtn = 'inline-block py-2 px-4 rounded-md text-sm font-semibold transition duration-150 ease-in-out text-center shadow-md';
                                @endphp

                                {{-- Status: BARU/MENUNGGU --}}
                                @if ($hazard->status == 'menunggu validasi')
                                    <a href="{{ route('she.hazards.diprosesForm', $hazard) }}" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 {{ $baseBtn }}">
                                        ➜ Validasi
                                    </a>
                                    <a href="{{ route('she.hazards.tolakForm', $hazard) }}" class="text-white bg-red-600 hover:bg-red-700 focus:ring-red-500 {{ $baseBtn }}">
                                        ✖ Tolak
                                    </a>
                                
                                {{-- Status: DIPROSES --}}
                                @elseif ($hazard->status == 'diproses')
                                    <a href="{{ route('she.hazards.selesaiForm', $hazard) }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-green-500 {{ $baseBtn }}">
                                        ✔ Verifikasi & Tandai Selesai
                                    </a>
                                    {{-- Opsi untuk mengedit rencana perbaikan --}}
                                    <a href="{{ route('she.hazards.diprosesForm', $hazard) }}?edit=true" class="text-indigo-600 bg-white border border-indigo-600 hover:bg-indigo-50 {{ $baseBtn }}">
                                        ⟲ Edit Rencana Tindakan
                                    </a>

                                {{-- Status: SELESAI atau DITOLAK --}}
                            @else
                                <div class="p-4 bg-gray-100 rounded-lg text-center text-gray-600 italic border border-gray-200 whitespace-nowrap overflow-x-auto">
                                    Laporan ini telah {{ strtoupper($hazard->status) }}. Tidak ada aksi lebih lanjut.
                                </div>
                            @endif
                            </div>
                        </div>
                    </div> {{-- END GRID --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>