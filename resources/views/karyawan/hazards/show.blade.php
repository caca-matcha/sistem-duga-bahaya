<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Detail Laporan Bahaya') }} <span class="text-indigo-500 font-medium">#{{ $hazard->id }}</span>
            </h2>
        </div>
    </x-slot>

    <!-- Style Kustom untuk Animasi Status (Ring Berkedip) -->
    <style>
        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
            }
        }
        .ring-animate {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ===========================
                SECTION 1: STATUS TRACKER (HORIZONTAL TIMELINE) - IMPROVED
                ============================ --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 p-8">
                <div class="mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-extrabold text-gray-900 flex items-center gap-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Status Progres Laporan
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Lacak posisi laporan bahaya secara real-time.</p>
                </div>

                <div class="w-full">
                    <div class="flex flex-col md:flex-row justify-between relative">
                        
                        <!-- Garis Penghubung (Background Line) - Diperbaiki untuk Desktop -->
                        <div class="hidden md:block absolute top-6 left-0 right-0 h-1 bg-gray-200 rounded"></div>

                        @foreach ($timelineData as $index => $item)
                            @php
                                $totalItems = count($timelineData);
                                $isCompleted = $item['is_active'] && !$item['is_current'];
                                $isCurrent = $item['is_current'];
                                $isPending = !$item['is_active'] && !$item['is_current'];

                                // Menentukan kelas warna untuk bulatan
                                $colorClass = 'bg-gray-200 text-gray-500'; // Default untuk pending
                                if ($isCompleted) {
                                    $colorClass = ($item['status'] == 'Ditolak') ? 'bg-red-600 text-white' : 'bg-green-600 text-white';
                                } elseif ($isCurrent) {
                                    $colorClass = 'bg-indigo-600 text-white';
                                }

                                // Menentukan kelas untuk ring animasi
                                $ringClass = $isCurrent ? 'ring-4 ring-offset-2 ring-indigo-500 scale-110 ring-animate' : '';
                                
                                // Menentukan kelas untuk teks
                                $textClass = 'text-gray-500';
                                if($isCurrent) {
                                    $textClass = 'text-indigo-600';
                                } elseif ($isCompleted) {
                                    $textClass = 'text-gray-900';
                                }

                                // Menentukan kelas untuk garis progress
                                $lineClass = 'bg-gray-200';
                                if ($index > 0 && ($timelineData[$index - 1]['is_active'] || $item['is_current'])) {
                                    $lineClass = 'bg-indigo-500';
                                }

                                // Menentukan kelas untuk garis vertikal mobile
                                $mobileLineClass = ($item['is_active']) ? 'bg-indigo-300' : 'bg-gray-200';
                            @endphp

                            <div class="relative flex-1 flex flex-row md:flex-col items-start md:items-center gap-4 md:gap-2 mb-8 md:mb-0 group">
                                
                                {{-- Garis Progress (Active Color) - Desktop --}}
                                @if($index > 0)
                                <div class="hidden md:block absolute top-6 -left-1/2 w-full h-1 {{ $lineClass }}"></div>
                                @endif
                                
                                {{-- Garis Vertikal untuk Mobile --}}
                                @if($index < $totalItems - 1)
                                <div class="md:hidden absolute left-5 top-12 w-0.5 h-full {{ $mobileLineClass }}"></div>
                                @endif
                                
                                {{-- Indikator Bulat --}}
                                <div class="relative z-10 flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full border-4 border-white shadow-lg transition-all duration-500 {{ $colorClass }} {{ $ringClass }}">
                                    @if ($item['status'] === 'Laporan Dibuat')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    @elseif ($item['status'] === 'Diproses')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    @elseif ($item['status'] === 'Selesai')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @elseif ($item['status'] === 'Ditolak')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                </div>

                                {{-- Text Content --}}
                                <div class="flex flex-col md:items-center md:text-center mt-0 md:mt-2">
                                    <div class="text-base font-extrabold {{ $textClass }}">
                                        {{ $item['status'] }}
                                    </div>
                                    @if($item['date'])
                                        <div class="text-xs text-gray-600 font-medium mt-0.5">
                                            {{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}
                                        </div>
                                        <div class="text-[10px] text-gray-400">
                                            Pukul: {{ \Carbon\Carbon::parse($item['date'])->format('H:i') }}
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-300 mt-1 font-medium">- Belum Dilakukan -</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Alert Penolakan --}}
                @if (isset($hazard) && $hazard->status == 'ditolak' && $hazard->alasan_penolakan)
                <div class="mt-8 bg-red-100 border border-red-300 rounded-xl p-5 flex items-start gap-4 shadow-inner">
                    <svg class="w-7 h-7 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="font-bold text-lg text-red-800">LAPORAN DITOLAK</h4>
                        <p class="text-sm text-red-700 mt-1">Alasan Penolakan: <span class="font-semibold">{{ $hazard->alasan_penolakan }}</span></p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ===========================
                SECTION 2: MAIN CONTENT (GRID) - IMPROVED
                ============================ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI (DETAIL INFORMASI) - 2/3 --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Card 1: Informasi Dasar --}}
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="px-6 py-4 bg-indigo-50/50 border-b border-indigo-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-indigo-900">Informasi Pelapor & Lokasi</h3>
                            <span class="text-xs font-mono text-indigo-400">ID: {{ $hazard->id }}</span>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Data Pelapor --}}
                                <div class="p-5 rounded-xl border border-blue-200 bg-blue-50/70">
                                    <h4 class="text-xs font-bold text-blue-800 uppercase tracking-widest mb-3 border-b border-blue-100 pb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Pelapor
                                    </h4>
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm text-gray-500">Nama</dt>
                                            <dd class="text-base font-bold text-gray-900">{{ $hazard->pelapor->name ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm text-gray-500">NPK</dt>
                                            <dd class="text-base font-semibold text-gray-800">{{ $hazard->NPK }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm text-gray-500">Departemen</dt>
                                            <dd class="text-base font-semibold text-gray-800">{{ $hazard->dept }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                {{-- Data Lokasi --}}
                                <div class="p-5 rounded-xl border border-teal-200 bg-teal-50/70">
                                    <h4 class="text-xs font-bold text-teal-800 uppercase tracking-widest mb-3 border-b border-teal-100 pb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243m11.314 0a1.998 1.998 0 01-2.828 0l-4.243-4.243m11.314 0a1.998 1.998 0 01-2.828 0l-4.243-4.243m11.314 0a1.998 1.998 0 01-2.828 0l-4.243-4.243"></path></svg>
                                        Lokasi & Kategori
                                    </h4>
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm text-gray-500">Gedung</dt>
                                            <dd class="text-base font-semibold text-gray-800">{{ $hazard->area_gedung }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm text-gray-500">Nama Area</dt>
                                            <dd class="text-base font-semibold text-gray-800">{{ $hazard->area_name }}</dd>
                                        </div>
                                        @if ($hazard->lokasi_detail_manual)
                                        <div>
                                            <dt class="text-sm text-gray-500">Detail Lokasi Manual</dt>
                                            <dd class="text-base font-semibold text-gray-800">{{ $hazard->lokasi_detail_manual }}</dd>
                                        </div>
                                        @endif
                                        <div>
                                            <dt class="text-sm text-gray-500">Kategori STOP6</dt>
                                            <dd class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800 mt-1 shadow-sm">
                                                {{ $hazard->kategori_stop6 }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Analisis --}}
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                            <h3 class="font-bold text-lg text-gray-800">Analisis & Tindak Lanjut</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            
                            {{-- Risk Matrix Badge --}}
                            <div class="flex flex-wrap gap-4 pt-2">
                                <div class="flex items-center gap-2 px-4 py-2 bg-yellow-50 rounded-full border border-yellow-200 shadow-sm">
                                    <span class="text-xs text-yellow-700 font-medium">Tingkat Keparahan:</span>
                                    <span class="font-extrabold text-yellow-800">{{ $hazard->tingkat_keparahan }}</span>
                                </div>
                                <div class="flex items-center gap-2 px-4 py-2 bg-orange-50 rounded-full border border-orange-200 shadow-sm">
                                    <span class="text-xs text-orange-700 font-medium">Kemungkinan Terjadi:</span>
                                    <span class="font-extrabold text-orange-800">{{ $hazard->kemungkinan_terjadi }}</span>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-base font-bold text-gray-700 mb-2">Deskripsi Bahaya</h4>
                                <div class="p-4 bg-gray-50 border-l-4 border-gray-400 rounded-r-lg text-gray-800 text-base leading-relaxed shadow-inner">
                                    {{ $hazard->deskripsi_bahaya }}
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-base font-bold text-gray-700 mb-2">Ide Penanggulangan dari Pelapor</h4>
                                <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-r-lg text-gray-800 text-base leading-relaxed shadow-inner">
                                    {{ $hazard->ide_penanggulangan ?? 'Tidak ada ide penanggulangan dari pelapor.' }}
                                </div>
                            </div>
                            @if ($hazard->status === 'diproses' && $hazard->target_penyelesaian)
                                @php
                                    $dueDate = \Carbon\Carbon::parse($hazard->target_penyelesaian);
                                    $daysRemaining = now()->diffInDays($dueDate, false);
                                @endphp
                                <div class="p-4 rounded-lg shadow-sm
                                    @if ($daysRemaining < 0)
                                        bg-red-100 border-l-4 border-red-500 text-red-700
                                    @elseif ($daysRemaining <= 7)
                                        bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700
                                    @else
                                        bg-blue-100 border-l-4 border-blue-500 text-blue-700
                                    @endif
                                ">
                                    <h4 class="font-bold text-base mb-1">Target Penyelesaian</h4>
                                    <p class="text-sm">
                                        {{ $dueDate->format('d M Y') }}
                                        @if ($daysRemaining < 0)
                                            (<span class="font-semibold">Terlambat {{ abs($daysRemaining) }} hari</span>)
                                        @elseif ($daysRemaining === 0)
                                            (<span class="font-semibold">Jatuh tempo hari ini</span>)
                                        @elseif ($daysRemaining <= 7)
                                            (<span class="font-semibold">Jatuh tempo dalam {{ $daysRemaining }} hari</span>)
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (SIDEBAR) - 1/3 --}}
                <div class="space-y-8">
                    {{-- Foto Bukti --}}
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Bukti Foto
                            </h3>
                        </div>
                        <div class="p-4">
                            @if ($hazard->foto_bukti)
                                <div class="group relative rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <img src="{{ url('storage/' . $hazard->foto_bukti) }}" 
                                            class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105 cursor-zoom-in"
                                            onclick="window.open(this.src, '_blank')"
                                            onerror="this.onerror=null; this.src='https://placehold.co/600x400/CCCCCC/666666?text=Foto+Bukti+Tidak+Ditemukan';"
                                            alt="Bukti Hazard">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors pointer-events-none"></div>
                                </div>
                                <p class="text-xs text-center text-gray-400 mt-2">Klik foto untuk melihat ukuran penuh</p>
                            @else
                                <div class="h-48 flex flex-col items-center justify-center bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl text-gray-400">
                                    <svg class="w-12 h-12 mb-2 opacity-50 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-medium">Tidak ada foto dilampirkan</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Informasi Penyelesaian (Jika ada) --}}
                    @if (isset($hazard) && ($hazard->ditangani_oleh || $hazard->ditangani_pada || $hazard->report_selesai))
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="font-bold text-lg text-gray-800">Detail Penanganan</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($hazard->ditangani_oleh)
                            <div class="flex items-center gap-4 p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                                <div class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Ditangani Oleh</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $hazard->ditanganiOleh->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if ($hazard->report_selesai)
                            <div class="flex items-center gap-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="w-10 h-10 rounded-full bg-green-200 flex items-center justify-center text-green-700 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Diselesaikan Pada</span>
                                    <span class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($hazard->report_selesai)->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="flex justify-center mt-12 mb-8">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center px-8 py-3 bg-gray-800 border-2 border-gray-800 rounded-full font-bold text-base text-white tracking-wider hover:bg-gray-900 active:bg-gray-900 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg hover:shadow-xl transform hover:scale-[1.01]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Laporan
            </a>
        </div>
    </div>
</x-app-layout>