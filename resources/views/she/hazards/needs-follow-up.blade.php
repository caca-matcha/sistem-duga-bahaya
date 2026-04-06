<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-[96%] mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER SECTION (Match Aurora Theme) --}}
            <div class="relative overflow-hidden bg-white p-6 md:p-8 rounded-[32px] shadow-xl shadow-gray-200/50 border border-gray-100 group transition-all duration-500">
                <div class="absolute -right-20 -top-20 bg-gradient-to-br from-amber-400/20 to-red-400/20 blur-[80px] rounded-full w-80 h-80"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest mb-4 border border-amber-100/50">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            Needs Attention
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight">
                            Laporan <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-red-600">Perlu Tindak Lanjut</span> ⚠️
                        </h2>
                        <p class="text-gray-500 font-bold mt-2 text-sm md:text-base max-w-2xl">
                            Daftar laporan bahaya yang telah melewati target penyelesaian atau akan segera berakhir. Segera tindak lanjuti untuk mencegah risiko.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('she.dashboard') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-gray-900 px-5 py-3 rounded-2xl font-bold text-sm shadow-sm transition-all focus:ring-4 focus:ring-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                
                {{-- OVERDUE SECTION --}}
                <div class="bg-white rounded-[24px] shadow-lg shadow-red-100/50 border border-red-100 overflow-hidden flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-red-100 bg-gradient-to-r from-red-50/80 to-white flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-100 text-red-600 rounded-xl shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-red-800">Laporan Overdue</h3>
                        </div>
                        @if($overdueHazards->isNotEmpty())
                        <span class="bg-red-500 text-white text-xs font-black px-3 py-1 rounded-full shadow-sm shadow-red-200">{{ $overdueHazards->count() }} Terlambat</span>
                        @endif
                    </div>
                    
                    <div class="flex-grow p-0 overflow-y-auto max-h-[28rem] custom-scrollbar">
                        @if ($overdueHazards->isEmpty())
                            <div class="p-12 text-center flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-gray-900 font-bold text-lg">Aman Terkendali</h4>
                                <p class="text-gray-500 mt-1">Luar Biasa! Tidak ada laporan yang terlambat saat ini.</p>
                            </div>
                        @else
                            <ul class="divide-y divide-red-50">
                                @foreach ($overdueHazards as $hazard)
                                    <li>
                                        <a href="{{ route('she.hazards.show', $hazard) }}" class="group block p-6 hover:bg-red-50/50 transition-all relative overflow-hidden">
                                            <div class="absolute left-0 top-0 h-full w-1 bg-red-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-white border border-red-100 rounded-2xl flex items-center justify-center text-red-600 shadow-sm group-hover:scale-110 transition-transform group-hover:bg-red-600 group-hover:text-white">
                                                        <span class="font-black text-sm">#{{ $hazard->id }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-gray-900 group-hover:text-red-700 transition-colors line-clamp-2">
                                                        {{ $hazard->deskripsi_bahaya }}
                                                    </p>
                                                    
                                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <div class="flex items-center gap-2 text-xs text-gray-600 font-bold">
                                                            <div class="w-5 h-5 bg-gray-100 rounded flex items-center justify-center text-gray-500">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                            </div>
                                                            <span class="truncate">{{ $hazard->area_gedung }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-xs text-red-600 font-black">
                                                            <div class="w-5 h-5 bg-red-100 rounded flex items-center justify-center text-red-500">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            </div>
                                                            <span>Terlambat {{ \Carbon\Carbon::parse($hazard->target_penyelesaian)->diffForHumans(null, true) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hidden sm:block flex-shrink-0 text-right space-y-1">
                                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Target</p>
                                                    <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($hazard->target_penyelesaian)->translatedFormat('d M Y') }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                {{-- DUE SOON SECTION --}}
                <div class="bg-white rounded-[24px] shadow-lg shadow-amber-100/50 border border-amber-100 overflow-hidden flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-amber-100 bg-gradient-to-r from-amber-50/80 to-white flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-100 text-amber-600 rounded-xl shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-amber-800">Segera Berakhir (Due Soon)</h3>
                        </div>
                        @if($dueSoonHazards->isNotEmpty())
                        <span class="bg-amber-500 text-white text-xs font-black px-3 py-1 rounded-full shadow-sm shadow-amber-200">{{ $dueSoonHazards->count() }} Harus Dicek</span>
                        @endif
                    </div>
                    
                    <div class="flex-grow p-0 overflow-y-auto max-h-[28rem] custom-scrollbar">
                        @if ($dueSoonHazards->isEmpty())
                            <div class="p-12 text-center flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-gray-900 font-bold text-lg">Santai Dulu</h4>
                                <p class="text-gray-500 mt-1">Tidak ada laporan yang akan jatuh tempo dalam 3 hari ke depan.</p>
                            </div>
                        @else
                            <ul class="divide-y divide-amber-50">
                                @foreach ($dueSoonHazards as $hazard)
                                    <li>
                                        <a href="{{ route('she.hazards.show', $hazard) }}" class="group block p-6 hover:bg-amber-50/50 transition-all relative overflow-hidden">
                                            <div class="absolute left-0 top-0 h-full w-1 bg-amber-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            
                                            <div class="flex items-start gap-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-white border border-amber-100 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm group-hover:scale-110 transition-transform group-hover:bg-amber-500 group-hover:text-white">
                                                        <span class="font-black text-sm">#{{ $hazard->id }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-gray-900 group-hover:text-amber-800 transition-colors line-clamp-2">
                                                        {{ $hazard->deskripsi_bahaya }}
                                                    </p>
                                                    
                                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <div class="flex items-center gap-2 text-xs text-gray-600 font-bold">
                                                            <div class="w-5 h-5 bg-gray-100 rounded flex items-center justify-center text-gray-500">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                            </div>
                                                            <span class="truncate">{{ $hazard->area_gedung }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-xs text-amber-600 font-black">
                                                            <div class="w-5 h-5 bg-amber-100 rounded flex items-center justify-center text-amber-500">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            </div>
                                                            <span>Tempo dlm {{ \Carbon\Carbon::parse($hazard->target_penyelesaian)->diffInDays() }} hari</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hidden sm:block flex-shrink-0 text-right space-y-1">
                                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Target</p>
                                                    <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($hazard->target_penyelesaian)->translatedFormat('d M Y') }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>