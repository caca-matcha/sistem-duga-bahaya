<x-app-layout>
    @section('page-title', '')

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-0">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                    {{ __('Detail Laporan Bahaya') }}
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-sm rounded-full font-mono font-medium border border-red-200">
                        #{{ $hazard->id }}
                    </span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Lihat detail kronologi dan analisis bahaya.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Style Kustom untuk Animasi Status (Ring Berkedip) -->
    <style>
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 12px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .ring-animate {
            animation: pulse-ring 2.5s cubic-bezier(0.25, 0.8, 0.25, 1) infinite;
        }
    </style>

    <div class="py-6 sm:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ===========================
                SECTION 1: STATUS TRACKER (HORIZONTAL TIMELINE)
                ============================ --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200/60 p-6 md:p-8">
                <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Status Progres
                        </h3>
                        <p class="text-sm text-gray-500 mt-1 ml-10">Lacak tahapan penyelesaian laporan secara real-time.</p>
                    </div>
                </div>

                <div class="w-full px-2">
                    <div class="flex flex-col md:flex-row justify-between relative">
                        
                        <!-- Garis Penghubung (Background Line) - Desktop -->
                        <div class="hidden md:block absolute top-6 left-0 right-0 h-0.5 bg-gray-100 rounded overflow-hidden"></div>

                        @foreach ($timelineData as $index => $item)
                            @php
                                $totalItems = count($timelineData);
                                $isCompleted = $item['is_active'] && !$item['is_current'];
                                $isCurrent = $item['is_current'];
                                
                                // Color logic
                                $colorClass = 'bg-white border-gray-200 text-gray-300'; // Default pending
                                if ($isCompleted) {
                                    $colorClass = ($item['status'] == 'Ditolak') ? 'bg-red-500 border-red-500 text-white shadow-md shadow-red-200' : 'bg-green-500 border-green-500 text-white shadow-md shadow-green-200';
                                } elseif ($isCurrent) {
                                    $colorClass = 'bg-red-600 border-red-600 text-white shadow-lg shadow-red-200';
                                }

                                // Ring animation logic
                                $ringClass = $isCurrent ? 'ring-animate z-20 scale-110' : 'z-10';
                                
                                // Text coloring
                                $textClass = 'text-gray-400 font-medium';
                                if($isCurrent) {
                                    $textClass = 'text-red-700 font-bold';
                                } elseif ($isCompleted) {
                                    $textClass = ($item['status'] == 'Ditolak') ? 'text-red-700 font-semibold' : 'text-gray-900 font-semibold';
                                }

                                // Connecting line logic
                                $lineClass = 'bg-gray-100';
                                if ($index > 0 && ($timelineData[$index - 1]['is_active'] || $item['is_current'])) {
                                    $lineClass = ($item['status'] == 'Ditolak') ? 'bg-red-500' : 'bg-green-500';
                                    if ($isCurrent) $lineClass = 'bg-red-500'; 
                                }

                                // Mobile vertical line
                                $mobileLineClass = ($item['is_active']) ? 'bg-red-400' : 'bg-gray-200';
                            @endphp

                            <div class="relative flex-1 flex flex-row md:flex-col items-start md:items-center gap-4 md:gap-3 mb-8 md:mb-0 group last:mb-0">
                                
                                {{-- Garis Progress - Desktop --}}
                                @if($index > 0)
                                <div class="hidden md:block absolute top-6 -left-1/2 w-full h-0.5 {{ $lineClass }} transition-colors duration-500"></div>
                                @endif
                                
                                {{-- Garis Vertikal - Mobile --}}
                                @if($index < $totalItems - 1)
                                <div class="md:hidden absolute left-[1.35rem] top-12 w-0.5 h-[calc(100%+2rem)] {{ $mobileLineClass }}"></div>
                                @endif
                                
                                {{-- Indikator Bulat --}}
                                <div class="relative flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full border-2 transition-all duration-300 {{ $colorClass }} {{ $ringClass }}">
                                    @if ($item['status'] === 'Laporan Dibuat')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    @elseif ($item['status'] === 'Diproses')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    @elseif ($item['status'] === 'Selesai')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @elseif ($item['status'] === 'Ditolak')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                </div>

                                {{-- Text Content --}}
                                <div class="flex flex-col md:items-center md:text-center mt-1">
                                    <div class="text-sm {{ $textClass }}">
                                        {{ $item['status'] }}
                                    </div>
                                    @if($item['date'])
                                        <div class="flex items-center gap-1 mt-1">
                                            <span class="text-xs text-gray-500 font-medium bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                                                {{ \Carbon\Carbon::parse($item['date'])->format('d M y') }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 mt-0.5">
                                            {{ \Carbon\Carbon::parse($item['date'])->format('H:i') }} WIB
                                        </span>
                                    @else
                                        <div class="text-[11px] text-gray-300 mt-1 italic">Menunggu</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Alert Penolakan --}}
                @if (isset($hazard) && $hazard->status == 'ditolak' && $hazard->alasan_penolakan)
                <div class="mt-10 bg-red-50 border border-red-200 rounded-xl p-5 flex items-start gap-4 animate-fade-in-up">
                    <div class="p-2 bg-red-100 rounded-lg text-red-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-900">Laporan Dikembalikan/Ditolak</h4>
                        <p class="text-sm text-red-700 mt-1 leading-relaxed">
                            Alasan: <span class="font-semibold italic">"{{ $hazard->alasan_penolakan }}"</span>
                        </p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ===========================
                SECTION 2: MAIN CONTENT (GRID)
                ============================ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- KOLOM KIRI (DETAIL INFORMASI) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Card 1: Informasi Dasar --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Informasi & Lokasi
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Data Pelapor --}}
                                <div class="bg-white p-5 rounded-xl border border-gray-200 hover:border-red-300 hover:shadow-md transition-all duration-300 group">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="p-2 bg-red-50 rounded-lg text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Data Pelapor</h4>
                                    </div>
                                    <dl class="space-y-4">
                                        <div>
                                            <dt class="text-xs text-gray-400 uppercase font-semibold">Nama Lengkap</dt>
                                            <dd class="text-sm font-bold text-gray-800 mt-0.5">{{ $hazard->pelapor->name ?? '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm text-gray-500">NPK</dt>
                                            <dd class="text-base font-semibold text-gray-800">{{ $hazard->NPK }}</dd>
                                            <div class="mt-3">
                                                <dt class="text-sm text-gray-500">Departemen</dt>
                                                <dd class="text-base font-semibold text-gray-800">{{ $hazard->dept }}</dd>
                                            </div>
                                        </div>
                                    </dl>
                                </div>

                                {{-- Data Lokasi --}}
                                <div class="bg-white p-5 rounded-xl border border-gray-200 hover:border-teal-300 hover:shadow-md transition-all duration-300 group">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="p-2 bg-teal-50 rounded-lg text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243m11.314 0a1.998 1.998 0 01-2.828 0l-4.243-4.243m11.314 0a1.998 1.998 0 01-2.828 0l-4.243-4.243m11.314 0a1.998 1.998 0 01-2.828 0l-4.243-4.243"></path></svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Area & Kategori</h4>
                                    </div>
                                    <dl class="space-y-4">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <dt class="text-xs text-gray-400 uppercase font-semibold">Gedung</dt>
                                                <dd class="text-sm font-bold text-gray-800 mt-0.5">{{ $hazard->area_gedung }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs text-gray-400 uppercase font-semibold">Area</dt>
                                                <dd class="text-sm font-bold text-gray-800 mt-0.5">{{ $hazard->area_name }}</dd>
                                            </div>
                                        </div>
                                        @if ($hazard->lokasi_detail_manual)
                                        <div>
                                            <dt class="text-xs text-gray-400 uppercase font-semibold">Detail Lokasi</dt>
                                            <dd class="text-sm text-gray-600 mt-0.5 italic">"{{ $hazard->lokasi_detail_manual }}"</dd>
                                        </div>
                                        @endif
                                        <div class="pt-2 border-t border-gray-100">
                                            <dt class="text-xs text-gray-400 uppercase font-semibold mb-1">Kategori STOP-6</dt>
                                            <dd>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-800 text-white shadow-sm">
                                                    {{ $hazard->kategori_stop6 }}
                                                </span>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Analisis & Matriks --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Analisis Risiko
                            </h3>
                        </div>
                        <div class="p-6 space-y-8">
                            
                            {{-- Risk Matrix Display --}}
                            <div class="flex flex-col sm:flex-row gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex-1 flex flex-col justify-center items-center p-3 bg-white rounded-lg border border-gray-100 shadow-sm">
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Keparahan</span>
                                    <span class="text-lg font-black text-gray-800">{{ $hazard->tingkat_keparahan }}</span>
                                </div>
                                <div class="flex-1 flex flex-col justify-center items-center p-3 bg-white rounded-lg border border-gray-100 shadow-sm">
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Kemungkinan</span>
                                    <span class="text-lg font-black text-gray-800">{{ $hazard->kemungkinan_terjadi }}</span>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        Deskripsi Bahaya
                                    </h4>
                                    <div class="bg-white border-l-4 border-red-400 pl-4 py-2 text-gray-700 text-sm leading-relaxed">
                                        {{ $hazard->deskripsi_bahaya }}
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        Usulan Perbaikan (Pelapor)
                                    </h4>
                                    <div class="bg-white border-l-4 border-green-400 pl-4 py-2 text-gray-700 text-sm leading-relaxed">
                                        {{ $hazard->ide_penanggulangan ?? 'Tidak ada ide penanggulangan.' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Target Date Alert --}}
                            @if ($hazard->status === 'diproses' && $hazard->target_penyelesaian)
                                @php
                                    $dueDate = \Carbon\Carbon::parse($hazard->target_penyelesaian);
                                    $daysRemaining = now()->diffInDays($dueDate, false);
                                    
                                    $alertColor = 'bg-red-50 border-red-200 text-red-800';
                                    if ($daysRemaining < 0) $alertColor = 'bg-red-50 border-red-200 text-red-800';
                                    elseif ($daysRemaining <= 3) $alertColor = 'bg-orange-50 border-orange-200 text-orange-800';
                                @endphp
                                <div class="mt-4 p-4 rounded-xl border {{ $alertColor }} flex items-start gap-3">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div>
                                        <p class="text-xs font-bold uppercase opacity-75">Target Penyelesaian</p>
                                        <p class="font-bold text-base">{{ $dueDate->format('d F Y') }}</p>
                                        <p class="text-xs mt-1 font-medium">
                                            @if ($daysRemaining < 0)
                                                Terlambat {{ abs($daysRemaining) }} hari
                                            @elseif ($daysRemaining === 0)
                                                Hari ini
                                            @else
                                                {{ $daysRemaining }} hari lagi
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (SIDEBAR) --}}
                <div class="space-y-6">
                    {{-- Foto Bukti --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 text-sm">Bukti Temuan</h3>
                            @if ($hazard->foto_bukti)
                                <span class="text-[10px] bg-gray-200 px-2 py-0.5 rounded text-gray-600">JPG/PNG</span>
                            @endif
                        </div>
                        <div class="p-4">
                            @if ($hazard->foto_bukti)
                                <div class="group relative rounded-xl overflow-hidden border border-gray-200 bg-gray-100 shadow-inner aspect-video">
                                    <img src="{{ url('storage/' . $hazard->foto_bukti) }}" 
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 cursor-zoom-in"
                                            onclick="window.open(this.src, '_blank')"
                                            onerror="this.onerror=null; this.src='https://placehold.co/600x400/f3f4f6/9ca3af?text=Foto+Corrupt';"
                                            alt="Bukti Hazard">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center pointer-events-none">
                                        <span class="opacity-0 group-hover:opacity-100 bg-black/60 text-white px-3 py-1 rounded-full text-xs backdrop-blur-sm transition-opacity">
                                            Klik untuk perbesar
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="aspect-video flex flex-col items-center justify-center bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl text-gray-400">
                                    <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-medium">Tidak ada foto</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Informasi Penyelesaian (Status Logs) --}}
                    @if (isset($hazard) && ($hazard->ditangani_oleh || $hazard->ditangani_pada || $hazard->report_selesai))
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 bg-red-50/30">
                            <h3 class="font-bold text-red-900 text-sm">Log Penyelesaian</h3>
                        </div>
                        <div class="p-0">
                            <ul class="divide-y divide-gray-100">
                                @if ($hazard->ditangani_oleh)
                                <li class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5 p-1.5 bg-red-100 text-red-600 rounded-full shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">PIC / Penanggung Jawab</p>
                                            <p class="text-sm font-semibold text-gray-800">{{ $hazard->ditanganiOleh->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                
                                @if ($hazard->report_selesai)
                                <li class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5 p-1.5 bg-green-100 text-green-600 rounded-full shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold">Waktu Penyelesaian</p>
                                            <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($hazard->report_selesai)->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>