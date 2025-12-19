<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <span class="text-indigo-600">#{{ $hazard->id }}</span>
                <span>Detail Laporan Bahaya</span>
            </h2>
            
            {{-- Status Badge di Header --}}
            <div class="mt-4 md:mt-0">
                @php
                    $statusColors = [
                        'menunggu validasi' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'selesai' => 'bg-green-100 text-green-800 border-green-200',
                        'ditolak' => 'bg-red-100 text-red-800 border-red-200',
                    ];
                    $statusClass = $statusColors[$hazard->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wide border {{ $statusClass }}">
                    {{ $hazard->status }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 shadow-sm rounded-r-md flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- LEFT COLUMN: DETAIL INFORMASI (2/3 width) --}}
                <div class="lg:col-span-2 space-y-6">

                    @php
                        $isSelesai = $hazard->status == 'selesai';
                        $isDitolak = $hazard->status == 'ditolak';
                        $isDiproses = $hazard->status == 'diproses';
                        $isMenunggu = $hazard->status == 'menunggu validasi';

                        // Define timeline steps
                        $steps = [];

                        // 1. Dibuat
                        $steps[] = [
                            'title' => 'Laporan Dibuat',
                            'date' => $hazard->created_at,
                            'date_info' => \Carbon\Carbon::parse($hazard->created_at)->isoFormat('D MMM YYYY'),
                            'is_complete' => true,
                            'icon' => '<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4zM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10zM5 13h10v2H5v-2z"></path></svg>',
                            'color' => 'bg-green-100',
                            'text_color' => 'text-gray-900'
                        ];

                        // 2. Divalidasi / Diproses
                        if ($hazard->ditangani_pada) {
                            $dateInfo = \Carbon\Carbon::parse($hazard->ditangani_pada)->isoFormat('D MMM YYYY');
                            if (!$isDitolak && $hazard->target_penyelesaian) {
                                $dateInfo = \Carbon\Carbon::parse($hazard->ditangani_pada)->isoFormat('D MMM') . ' - ' . \Carbon\Carbon::parse($hazard->target_penyelesaian)->isoFormat('D MMM YYYY');
                            }

                            $steps[] = [
                                'title' => $isDitolak ? 'Laporan Ditolak' : 'Laporan Divalidasi',
                                'date' => $hazard->ditangani_pada,
                                'date_info' => $dateInfo, // Key baru untuk tampilan
                                'is_complete' => true,
                                'icon' => $isDitolak
                                    ? '<svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                                    : '<svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>',
                                'color' => $isDitolak ? 'bg-red-100' : 'bg-blue-100',
                                'text_color' => 'text-gray-900',
                            ];
                        }

                        // 3. Selesai
                        if ($isSelesai) {
                            $steps[] = [
                                'title' => 'Tindakan Selesai',
                                'date' => $hazard->report_selesai ?? $hazard->ditangani_pada,
                                'date_info' => \Carbon\Carbon::parse($hazard->report_selesai ?? $hazard->ditangani_pada)->isoFormat('D MMM YYYY'),
                                'is_complete' => true,
                                'icon' => '<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                                'color' => 'bg-green-100',
                                'text_color' => 'text-gray-900'
                            ];
                        }
                    @endphp

                    <!-- CARD TIMELINE -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                             <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <h3 class="text-lg font-bold text-gray-800">Linimasa Laporan</h3>
                        </div>
                        <div class="p-6">
                            <ol class="space-y-8">
                                @foreach ($steps as $step)
                                <li class="flex items-start gap-4">
                                    <span class="flex items-center justify-center w-8 h-8 {{ $step['color'] }} rounded-full ring-4 ring-white flex-shrink-0">
                                        {!! $step['icon'] !!}
                                    </span>
                                    <div>
                                        <h3 class="font-bold text-md {{ $step['text_color'] }}">{{ $step['title'] }}</h3>
                                        <time class="block text-sm font-normal leading-none text-gray-500">{{ $step['date_info'] }}</time>
                                    </div>
                                </li>
                                @endforeach

                                {{-- Placeholder for next step --}}
                                @if(!$isSelesai && !$isDitolak)
                                <li class="flex items-start gap-4">
                                    <span class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full ring-4 ring-white flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16z"></path></svg>
                                    </span>
                                    <div>
                                        <h3 class="font-bold text-gray-400">
                                            @if($isMenunggu)
                                                Menunggu Validasi SHE
                                            @elseif($isDiproses)
                                                Menunggu Tindakan Selesai
                                            @endif
                                        </h3>
                                    </div>
                                </li>
                                @endif
                            </ol>
                        </div>
                    </div>


                    {{-- CARD 1: INFORMASI PELAPOR & LOKASI --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Pelapor</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Pelapor</label>
                                <div class="font-medium text-gray-900 mt-1">{{ $hazard->pelapor->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">NPK: {{ $hazard->NPK }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Departemen</label>
                                <div class="font-medium text-gray-900 mt-1">{{ $hazard->dept }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal Observasi</label>
                                <div class="font-medium text-gray-900 mt-1">
                                    {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="md:col-span-3 border-t pt-6 mt-6">
                                 <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Detail Lokasi</label>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Area Gedung</label>
                                <div class="font-semibold text-gray-900 mt-1">{{ $hazard->area_gedung }}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Area Type</label>
                                <div class="font-semibold text-gray-900 mt-1">{{ $hazard->area_type }}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Area Name</label>
                                <div class="font-semibold text-gray-900 mt-1">{{ $hazard->area_name }}</div>
                            </div>
                            <div class="md:col-span-3">
                                <label class="text-sm font-medium text-gray-500">Area ID (Kode Line)</label>
                                <div class="font-semibold text-gray-900 mt-1">{{ $hazard->area_id }}</div>
                            </div>

                            {{-- LOKASI DETAIL MANUAL --}}
                            @if($hazard->lokasi_detail_manual)
                            <div class="md:col-span-3 mt-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                <label class="text-sm font-medium text-yellow-800 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Catatan Lokasi Spesifik (Manual)</span>
                                </label>
                                <div class="font-semibold text-gray-900 mt-1 pl-6">{{ $hazard->lokasi_detail_manual }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- CARD 2: DETAIL BAHAYA & RISIKO AWAL --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <h3 class="text-lg font-bold text-gray-800">Analisa Bahaya Awal</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                                    <span class="block text-xs font-bold text-red-600 uppercase">Faktor Penyebab</span>
                                    <span class="block text-gray-900 font-medium mt-1">{{ $hazard->faktor_penyebab }}</span>
                                </div>
                                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                                    <span class="block text-xs font-bold text-red-600 uppercase">Kategori STOP6</span>
                                    <span class="block text-gray-900 font-medium mt-1">{{ $hazard->kategori_stop6 }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700">Deskripsi Bahaya</label>
                                <div class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 text-sm leading-relaxed">
                                    {{ $hazard->deskripsi_bahaya }}
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700">Usulan Penanggulangan</label>
                                <div class="mt-2 p-4 bg-blue-50 rounded-lg border border-blue-100 text-blue-900 text-sm leading-relaxed">
                                    {{ $hazard->ide_penanggulangan ?? 'Tidak ada usulan.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: VALIDASI SHE (Hanya Muncul Jika Sudah Diproses/Selesai/Ditolak) --}}
                    @if($hazard->status != 'menunggu validasi')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 relative">
                        <div class="absolute top-0 left-0 w-1 h-full {{ $hazard->status == 'ditolak' ? 'bg-red-500' : 'bg-green-500' }}"></div>
                        
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 class="text-lg font-bold text-gray-800">Hasil Validasi & Penanganan</h3>
                            </div>
                            @if($hazard->ditangani_oleh)
                                <div class="text-xs text-gray-500 text-right">
                                    Oleh: <span class="font-bold">{{ $hazard->ditanganiOleh->name }}</span><br>
                                    {{ \Carbon\Carbon::parse($hazard->ditangani_pada)->format('d M Y H:i') }}
                                </div>
                            @endif
                        </div>

                        <div class="p-6">
                            @if ($hazard->status == 'ditolak')
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex gap-4">
                                    <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div>
                                        <h4 class="font-bold text-red-800">Laporan Ditolak</h4>
                                        <p class="text-sm text-red-700 mt-1">{{ $hazard->alasan_penolakan }}</p>
                                    </div>
                                </div>
                            @else
                                {{-- Skor Risiko Final Visual --}}
                                @php
                                    $finalRiskScore = $hazard->final_tingkat_keparahan * $hazard->final_kemungkinan_terjadi;
                                    
                                    // Logic Warna Sederhana untuk PHP View
                                    $riskColorClass = 'bg-gray-100 text-gray-800';
                                    if($finalRiskScore <= 5) $riskColorClass = 'bg-green-100 text-green-800 border-green-200';
                                    elseif($finalRiskScore <= 10) $riskColorClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                    elseif($finalRiskScore <= 15) $riskColorClass = 'bg-orange-100 text-orange-800 border-orange-200';
                                    else $riskColorClass = 'bg-red-100 text-red-800 border-red-200';
                                @endphp

                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                                    <div>
                                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Skor Risiko Final</span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-2xl font-black">{{ $finalRiskScore }}</span>
                                            <span class="text-sm text-gray-600">
                                                (Sev: {{ $hazard->final_tingkat_keparahan }} x Prob: {{ $hazard->final_kemungkinan_terjadi }})
                                            </span>
                                        </div>
                                    </div>
                                    <div class="px-4 py-2 rounded-lg border {{ $riskColorClass }} font-bold text-sm">
                                        Level Risiko
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-700">Faktor Penyebab</label>
                                        <p class="text-sm text-gray-900 mt-1">{{ $hazard->faktor_penyebab }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-semibold text-gray-700">Upaya Penanggulangan Terpilih</label>
                                        @php
                                            $upaya = []; 
                                            if (is_array($hazard->upaya_penanggulangan)) {
                                                $upaya = $hazard->upaya_penanggulangan;
                                            } elseif (!empty($hazard->upaya_penanggulangan)) {
                                                $upaya = ['lain-lain' => $hazard->upaya_penanggulangan]; 
                                            }
                                        @endphp
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @forelse ($upaya as $key => $value)
                                                @if(!empty($value))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ $key }}: {{ $value }}
                                                    </span>
                                                @endif
                                            @empty
                                                <span class="text-sm italic text-gray-500">Tidak ada upaya spesifik.</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-sm font-semibold text-gray-700">Rencana Tindakan Perbaikan</label>
                                        <div class="mt-2 p-4 bg-green-50 rounded-lg border border-green-100 text-green-900 text-sm">
                                            {{ $hazard->tindakan_perbaikan }}
                                        </div>
                                    </div>

                                    <!-- MODIFIED TARGET PENYELESAIAN SECTION -->
                                    <div class="mt-2">
                                        <label class="text-sm font-semibold text-gray-700 mb-2 block">Target Penyelesaian</label>
                                        
                                        @php
                                            $targetDate = \Carbon\Carbon::parse($hazard->target_penyelesaian);
                                            $now = \Carbon\Carbon::now();
                                            $diffDays = $now->diffInDays($targetDate, false); // false allows negative values
                                            $isPast = $targetDate->isPast() && !$targetDate->isToday();
                                            $isCompleted = $hazard->status == 'selesai';

                                            // Determine styles based on urgency
                                            if ($isCompleted) {
                                                $cardClass = 'bg-gray-50 border-gray-200';
                                                $iconClass = 'text-gray-400';
                                                $textClass = 'text-gray-600';
                                                $badgeClass = 'bg-gray-200 text-gray-600 border-gray-300';
                                                $statusText = 'Ditutup';
                                            } elseif ($diffDays < 0) { // Overdue
                                                $cardClass = 'bg-red-50 border-red-200';
                                                $iconClass = 'text-red-500';
                                                $textClass = 'text-red-700';
                                                $badgeClass = 'bg-red-100 text-red-700 border-red-200';
                                                $statusText = 'Terlambat ' . abs(round($diffDays)) . ' Hari';
                                            } elseif ($diffDays <= 3) { // Warning (within 3 days)
                                                $cardClass = 'bg-orange-50 border-orange-200';
                                                $iconClass = 'text-orange-500';
                                                $textClass = 'text-orange-800';
                                                $badgeClass = 'bg-orange-100 text-orange-800 border-orange-200';
                                                $statusText = round($diffDays) == 0 ? 'Hari Ini' : round($diffDays) . ' Hari Lagi';
                                            } else { // Safe
                                                $cardClass = 'bg-blue-50 border-blue-200';
                                                $iconClass = 'text-blue-500';
                                                $textClass = 'text-blue-800';
                                                $badgeClass = 'bg-blue-100 text-blue-800 border-blue-200';
                                                $statusText = $targetDate->diffForHumans();
                                            }
                                        @endphp

                                        <div class="flex items-center justify-between p-4 rounded-lg border {{ $cardClass }}">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-white p-2 rounded-md shadow-sm">
                                                    <svg class="w-6 h-6 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold uppercase tracking-wider {{ $textClass }} opacity-70">Tenggat Waktu</p>
                                                    <p class="text-lg font-bold {{ $textClass }}">
                                                        {{ $targetDate->isoFormat('dddd, D MMMM YYYY') }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="px-3 py-1 rounded-full text-xs font-bold border uppercase tracking-wide {{ $badgeClass }}">
                                                {{ $statusText }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END MODIFIED SECTION -->                                   

                                    @if($hazard->status == 'selesai')
                                        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                            <span class="text-sm font-bold text-green-700">✔ Selesai Ditangani</span>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($hazard->report_selesai)->format('d M Y H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

                {{-- RIGHT COLUMN: SIDEBAR (1/3 width) --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- CARD: ACTION BUTTONS (Sticky) --}}
                    @if (!in_array($hazard->status, ['selesai', 'ditolak']))
                    <div class="bg-white shadow-lg rounded-xl border border-indigo-100 sticky top-6 z-10">
                        <div class="p-6">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Aksi Tersedia</h3>
                            
                            <div class="flex flex-col gap-3">
                                @if ($hazard->status == 'menunggu validasi')
                                    <a href="{{ route('she.hazards.diprosesForm', $hazard) }}" 
                                       class="w-full justify-center inline-flex items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition ease-in-out duration-150">
                                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Validasi Laporan
                                    </a>
                                    <a href="{{ route('she.hazards.tolakForm', $hazard) }}" 
                                       class="w-full justify-center inline-flex items-center px-4 py-3 bg-white border border-red-300 rounded-lg font-semibold text-sm text-red-700 hover:bg-red-50 focus:outline-none shadow-sm transition ease-in-out duration-150">
                                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Tolak Laporan
                                    </a>
                                @elseif ($hazard->status == 'diproses')
                                    <a href="{{ route('she.hazards.selesaiForm', $hazard) }}" 
                                       class="w-full justify-center inline-flex items-center px-4 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 focus:outline-none shadow-md transition ease-in-out duration-150">
                                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Verifikasi Selesai
                                    </a>
                                    <a href="{{ url()->previous() }}?edit=true" 
                                       class="w-full justify-center inline-flex items-center px-4 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none shadow-sm transition ease-in-out duration-150">
                                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                                        Kembali
                                    </a>
                                @else
                                    <div class="text-center py-4 bg-gray-50 rounded-lg border border-gray-200 border-dashed">
                                        <span class="text-gray-500 text-sm">Tidak ada aksi lanjutan.</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- CARD: DOKUMENTASI FOTO --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-800">Dokumentasi Awal</h3>
                        </div>
                        <div class="p-4">
                            @if ($hazard->foto_bukti)
                                <div class="group relative bg-gray-100 rounded-lg overflow-hidden border border-gray-200 aspect-video">
                                    <img src="{{ asset('storage/' . $hazard->foto_bukti) }}" 
                                         alt="Foto Bahaya" 
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                    <a href="{{ asset('storage/' . $hazard->foto_bukti) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-300">
                                        <span class="text-white opacity-0 group-hover:opacity-100 font-medium px-4 py-2 bg-black bg-opacity-50 rounded-full text-sm">Lihat Fullsize</span>
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded border-dashed border border-gray-300 text-gray-400 text-sm">
                                    No Image Available
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($hazard->status == 'selesai' && $hazard->foto_bukti_penyelesaian)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-green-100">
                            <div class="bg-green-50 px-6 py-3 border-b border-green-100">
                                <h3 class="text-sm font-bold text-green-800">Bukti Penyelesaian</h3>
                            </div>
                            <div class="p-4">
                                <div class="group relative bg-gray-100 rounded-lg overflow-hidden border border-green-200 aspect-video">
                                    <img src="{{ asset('storage/' . $hazard->foto_bukti_penyelesaian) }}" 
                                         alt="Bukti Selesai" 
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                    <a href="{{ asset('storage/' . $hazard->foto_bukti_penyelesaian) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-300">
                                        <span class="text-white opacity-0 group-hover:opacity-100 font-medium px-4 py-2 bg-black bg-opacity-50 rounded-full text-sm">Lihat Fullsize</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div> {{-- End Grid --}}
        </div>
    </div>
</x-app-layout>