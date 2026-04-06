<x-app-layout>
    @section('page-title', '')


    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-[96%] mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- SECTION: WELCOME (V2 - Premium Aurora Style) --}}
            @php
                $hour = date('H');
                $greeting = 'Selamat Datang';
                if ($hour >= 5 && $hour < 11) {
                    $greeting = 'Selamat Pagi';
                } elseif ($hour >= 11 && $hour < 15) {
                    $greeting = 'Selamat Siang';
                } elseif ($hour >= 15 && $hour < 18) {
                    $greeting = 'Selamat Sore';
                } else {
                    $greeting = 'Selamat Malam';
                }
            @endphp
            <div
                class="relative overflow-hidden bg-white p-6 md:p-8 rounded-[32px] shadow-xl shadow-gray-200/50 border border-gray-100 group transition-all duration-500 hover:shadow-2xl hover:shadow-red-200/20">
                <!-- Aurora Decorative Elements -->
                <div
                    class="absolute -right-20 -top-20 bg-gradient-to-br from-red-600/20 to-rose-400/20 blur-[80px] rounded-full w-80 h-80 group-hover:scale-125 transition-transform duration-1000">
                </div>
                <div
                    class="absolute -left-10 -bottom-10 bg-gradient-to-tr from-blue-400/10 to-indigo-400/10 blur-[60px] rounded-full w-60 h-60 opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                </div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex-1">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest mb-4 border border-red-100/50">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            SHE System Active
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight">
                            {{ $greeting }}, <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-rose-500">{{ explode(' ', Auth::user()->name)[0] }}!</span>
                            <span class="inline-block animate-bounce-slow">✨</span>
                        </h2>
                        <p class="text-gray-500 font-bold mt-3 leading-relaxed max-w-2xl text-sm md:text-base">
                            Senang melihat Anda kembali! Mari kita pastikan lingkungan kerja hari ini tetap aman, sehat,
                            dan terjaga bersama-sama demi keselamatan kita semua.
                        </p>
                    </div>

                    <div class="hidden xl:flex items-center gap-6">
                        <div class="h-px w-12 bg-gray-200"></div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status
                                Laporan</p>
                            @if($pendingReports > 0)
                                <div class="flex items-center justify-end gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                    <p class="text-sm font-bold text-amber-600">Butuh Validasi</p>
                                </div>
                            @elseif($processedReports > 0)
                                <div class="flex items-center justify-end gap-2">
                                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                                    <p class="text-sm font-bold text-indigo-600">Sedang Diproses</p>
                                </div>
                            @else
                                <div class="flex items-center justify-end gap-2">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></span>
                                    <p class="text-sm font-bold text-emerald-600">Semua Terkendali</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div
                        class="flex items-center gap-5 bg-gray-50/80 backdrop-blur-sm p-4 rounded-[24px] border border-white shadow-inner">
                        <div
                            class="h-14 w-14 rounded-2xl bg-white shadow-lg flex items-center justify-center text-red-600 transition-transform group-hover:rotate-12 duration-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p
                                class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] leading-none mb-1.5">
                                Overview Hari Ini</p>
                            <p class="text-base font-black text-gray-800 tracking-tight">
                                {{ now()->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 1: STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Total Card -->
                <a href="{{ route('she.hazards.index', ['tab' => 'semua']) }}"
                    class="block relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute -right-6 -bottom-6 bg-blue-50 rounded-full w-32 h-32 opacity-50 group-hover:scale-110 transition-transform">
                    </div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-tight">Total Laporan</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-2 tracking-tighter">{{ $totalReports }}
                            </h3>
                            <div
                                class="mt-2 flex items-center text-xs text-blue-600 font-bold bg-blue-50 px-2 py-1 rounded-lg w-fit">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Semua Data
                            </div>
                        </div>
                        <div
                            class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-xl shadow-lg shadow-blue-200">
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 7V3.5L18.5 9H13z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Validated Card -->
                <a href="{{ route('she.hazards.index', ['tab' => 'selesai']) }}"
                    class="block relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute -right-6 -bottom-6 bg-emerald-50 rounded-full w-32 h-32 opacity-50 group-hover:scale-110 transition-transform">
                    </div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-tight">Divalidasi</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-2 tracking-tighter">
                                {{ $validatedReports }}
                            </h3>
                            <div
                                class="mt-2 flex items-center text-xs text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded-lg w-fit">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{$totalReports > 0 ? round(($validatedReports / $totalReports) * 100) : 0}}% Selesai
                            </div>
                        </div>
                        <div
                            class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-xl shadow-lg shadow-emerald-200">
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1.9 14.3l-3.7-3.7 1.4-1.4 2.3 2.3 5.3-5.3 1.4 1.4-6.7 6.7z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Processed Card (New) -->
                <a href="{{ route('she.hazards.index', ['tab' => 'diproses']) }}"
                    class="block relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute -right-6 -bottom-6 bg-indigo-50 rounded-full w-32 h-32 opacity-50 group-hover:scale-110 transition-transform">
                    </div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-tight">Diproses</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-2 tracking-tighter">
                                {{ $processedReports }}
                            </h3>
                            <div
                                class="mt-2 flex items-center text-xs text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded-lg w-fit">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                                Sedang Ditangani
                            </div>
                        </div>
                        <div
                            class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-xl shadow-lg shadow-indigo-200">
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 20c4.4 0 8-3.6 8-8s-3.6-8-8-8-8 3.6-8 8 3.6 8 8 8zm0-14c3.3 0 6 2.7 6 6s-2.7 6-6 6-6-2.7-6-6 2.7-6 6-6zM12.5 7H11v6l5.2 3.2.8-1.3-4.5-2.7V7z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Pending Card -->
                <a href="{{ route('she.hazards.index', ['tab' => 'baru']) }}"
                    class="block relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute -right-6 -bottom-6 bg-amber-50 rounded-full w-32 h-32 opacity-50 group-hover:scale-110 transition-transform">
                    </div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-tight">Menunggu</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-2 tracking-tighter">
                                {{ $pendingReports }}
                            </h3>
                            <div
                                class="mt-2 flex items-center text-xs text-amber-600 font-bold bg-amber-50 px-2 py-1 rounded-lg w-fit">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Perlu Tindakan
                            </div>
                        </div>
                        <div
                            class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-xl shadow-lg shadow-amber-200">
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            
            {{-- SECTION: CRITICAL NOTIFICATIONS --}}
            @if($overdueHazards->isNotEmpty() || $dueSoonHazards->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($overdueHazards->isNotEmpty())
                <a href="{{ route('she.hazards.needs-follow-up') }}" class="group bg-red-50 border border-red-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm animate-pulse-slow hover:bg-red-100 transition-all cursor-pointer">
                    <div class="h-10 w-10 flex-shrink-0 bg-red-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-200 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xs font-black text-red-800 uppercase tracking-widest">Laporan Overdue</h4>
                        <p class="text-sm font-bold text-red-600">{{ $overdueHazards->count() }} laporan telah melewati target penyelesaian!</p>
                    </div>
                    <div class="text-red-400 group-hover:text-red-600 group-hover:translate-x-1 transition-transform">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
                @endif

                @if($dueSoonHazards->isNotEmpty())
                <a href="{{ route('she.hazards.needs-follow-up') }}" class="group bg-amber-50 border border-amber-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:bg-amber-100 transition-all cursor-pointer">
                    <div class="h-10 w-10 flex-shrink-0 bg-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-amber-200 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-xs font-black text-amber-800 uppercase tracking-widest">Segera Berakhir</h4>
                        <p class="text-sm font-bold text-amber-600">{{ $dueSoonHazards->count() }} laporan akan mencapai target dalam 3 hari ke depan.</p>
                    </div>
                    <div class="text-amber-400 group-hover:text-amber-600 group-hover:translate-x-1 transition-transform">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
                @endif
            </div>
            @endif

            {{-- SECTION 2: CHARTS (SIDE BY SIDE) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Risk Level Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-600">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M11 2v20c-5.07-.5-9-4.79-9-10s3.93-9.5 9-10zm2 0v8.5H22c-.46-4.17-3.33-7.5-7-8.5zm0 11.5V22c3.67-1 6.54-4.33 7-8.5h-7z" />
                                </svg>
                            </div>
                            Distribusi Tingkat Risiko
                        </h3>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                    </path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white/80 backdrop-blur-md border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="py-1">
                                    <button @click="downloadChart('riskLevelChart', 'Distribusi-Risiko'); open = false"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 w-full text-left transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download PNG
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64 w-full flex justify-center">
                            <canvas id="riskLevelChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Locations Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <div class="p-1.5 bg-red-100 rounded-lg text-red-600">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zm5.6 8H19v6h-2.8v-6z" />
                                </svg>
                            </div>
                            Top 5 Lokasi Risiko Tertinggi
                        </h3>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                    </path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white/80 backdrop-blur-md border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="py-1">
                                    <button
                                        @click="downloadChart('topRiskLocationsChart', 'Top-Lokasi-Risiko'); open = false"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 w-full text-left transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download PNG
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64 w-full">
                            <canvas id="topRiskLocationsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- STOP6 Category Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <div class="p-1.5 bg-amber-100 rounded-lg text-amber-600">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                </svg>
                            </div>
                            Top 5 Potensi Kecelakaan Kerja (STOP-6)
                        </h3>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                    </path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white/80 backdrop-blur-md border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="py-1">
                                    <button
                                        @click="downloadChart('stop6Chart', 'Kategori-STOP6'); open = false"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 w-full text-left transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download PNG
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64 w-full flex justify-center">
                            <canvas id="stop6Chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: LISTS (NOTIFICATIONS & LATEST) --}}
            {{-- SECTION 3: LISTS (NOTIFICATIONS & LATEST) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- HIGH-RISK HAZARDS --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden h-full flex flex-col">
                        <div class="px-6 py-4 border-b border-red-100 flex items-center justify-between bg-red-50/50">
                            <h3 class="text-base font-bold text-red-800 flex items-center gap-2">
                                <div class="p-1.5 bg-red-100 rounded-lg text-red-600">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M12 2L1 21h22L12 2zm0 15c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm1-4h-2V8h2v5z" />
                                    </svg>
                                </div>
                                Laporan Berisiko Tinggi Aktif
                            </h3>
                            @if($hazardsPerluPerhatian->isNotEmpty())
                                <span
                                    class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm shadow-red-200">{{ $hazardsPerluPerhatian->count() }}</span>
                            @endif
                        </div>
                        <div class="p-0 flex-grow overflow-y-auto max-h-[30rem] custom-scrollbar">
                            @if ($hazardsPerluPerhatian->isEmpty())
                                <div class="p-8 text-center flex flex-col items-center justify-center h-48">
                                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-gray-900 font-bold">Aman Terkendali</h4>
                                    <p class="text-sm text-gray-500 mt-1">Tidak ada laporan berisiko tinggi yang aktif saat
                                        ini.</p>
                                </div>
                            @else
                                <ul class="divide-y divide-red-50">
                                    @foreach ($hazardsPerluPerhatian as $hazard)
                                        <li class="group">
                                            <a href="{{ route('she.hazards.show', $hazard) }}"
                                                class="block p-4 hover:bg-red-50/50 transition-colors">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 mt-1">
                                                        <span class="flex h-3 w-3 relative">
                                                            <span
                                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                            <span
                                                                class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                                        </span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p
                                                            class="text-sm font-bold text-gray-900 group-hover:text-red-700 transition-colors line-clamp-2">
                                                            {{ $hazard->deskripsi_bahaya }}
                                                        </p>
                                                        <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                                                            <span
                                                                class="flex items-center gap-1 bg-white border border-red-100 px-2 py-0.5 rounded text-red-600 font-bold">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                                </svg>
                                                                Score: {{ $hazard->risk_score }}
                                                            </span>
                                                            <span>&bull;</span>
                                                            <span class="truncate">{{ $hazard->area_gedung }}</span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="text-gray-400 group-hover:translate-x-1 transition-transform transform">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
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

                {{-- LATEST REPORTS --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <div class="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z" />
                                    </svg>
                                </div>
                                Laporan Terbaru
                            </h3>
                            <a href="{{ route('she.hazards.index') }}"
                                class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 transition-colors">
                                Lihat Semua
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="p-0 flex-grow overflow-y-auto max-h-[30rem] custom-scrollbar">
                            @if ($latestReports->isEmpty())
                                <div class="p-8 text-center text-gray-500">
                                    <p>Belum ada laporan terbaru.</p>
                                </div>
                            @else
                                <ul class="divide-y divide-gray-50">
                                    @foreach ($latestReports as $report)
                                                            <li class="group">
                                                                <a href="{{ route('she.hazards.show', $report) }}"
                                                                    class="block p-4 hover:bg-gray-50 transition-colors">
                                                                    <div class="flex gap-4">
                                                                        <div class="flex-shrink-0 mt-1">
                                                                            <img class="h-8 w-8 rounded-full bg-gray-200 border border-gray-100 object-cover"
                                                                                src="https://ui-avatars.com/api/?name={{ urlencode($report->pelapor->name ?? 'A') }}&background=random&color=fff&size=64"
                                                                                alt="{{ $report->pelapor->name ?? 'Anonim' }}">
                                                                        </div>
                                                                        <div class="flex-1 min-w-0">
                                                                            <p
                                                                                class="text-sm font-medium text-gray-900 overflow-hidden text-ellipsis whitespace-nowrap group-hover:text-blue-600 transition-colors">
                                                                                {{ $report->pelapor->name ?? 'Anonim' }}
                                                                            </p>
                                                                            <p class="text-xs text-gray-500 truncate mt-0.5">
                                                                                {{ $report->deskripsi_bahaya }}
                                                                            </p>
                                                                            <div class="flex items-center gap-2 mt-2">
                                                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide
                                                                                                                                                                                                                                                                                                                                                                                    {{ $report->status == 'selesai' ? 'bg-green-50 text-green-700 border border-green-100' :
                                        ($report->status == 'diproses' ? 'bg-blue-50 text-blue-700 border border-blue-100' :
                                            'bg-amber-50 text-amber-700 border border-amber-100') }}">
                                                                                    {{ ucfirst($report->status) }}
                                                                                </span>
                                                                                <span class="text-[10px] text-gray-400 flex items-center gap-1">
                                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                                    </svg>
                                                                                    {{ $report->created_at->diffForHumans() }}
                                                                                </span>
                                                                            </div>
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
            {{-- End Grid Lists --}}

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // GLOBAL FUNCTION FOR CHART EXPORT
            window.downloadChart = function (canvasId, filename) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                // Create a temporary link element
                const link = document.createElement('a');
                link.download = `${filename}-${new Date().toISOString().slice(0, 10)}.png`;

                // To set white background for transparent charts
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = canvas.width;
                tempCanvas.height = canvas.height;
                const ctx = tempCanvas.getContext('2d');

                // Fill white background
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

                // Draw the original chart on top
                ctx.drawImage(canvas, 0, 0);

                link.href = tempCanvas.toDataURL('image/png', 1.0);
                link.click();
            };

            document.addEventListener('DOMContentLoaded', function () {
                // --- CHART 1: RISK LEVEL (DYNAMIC) ---
                const riskCounts = @json($riskCounts);

                // Define all possible categories, their labels, and colors.
                // This ensures a consistent order and color scheme.
                const riskConfig = {
                    'Low': { label: 'Rendah', color: '#10B981' }, // Green-500
                    'Medium': { label: 'Sedang', color: '#3B82F6' }, // Blue-500 (will be merged)
                    'Medium-High': { label: 'Sedang & Menengah', color: '#F59E0B' }, // Amber-500
                    'High': { label: 'Tinggi', color: '#F97316' }, // Orange-500 (will be merged)
                    'Extreme': { label: 'Tinggi & Ekstrem', color: '#EF4444' }, // Red-500
                };

                // Aggregate data as requested
                const aggregatedData = {
                    'Low': riskCounts['Low'] || 0,
                    'Medium-High': (riskCounts['Medium'] || 0) + (riskCounts['Medium-High'] || 0),
                    'Extreme': (riskCounts['High'] || 0) + (riskCounts['Extreme'] || 0)
                };

                const chartLabels = [];
                const chartData = [];
                const chartColors = [];

                // Populate chart data based on the final aggregated data
                // We use the config to ensure a consistent order and color
                if (aggregatedData['Low'] > 0) {
                    chartLabels.push(riskConfig['Low'].label);
                    chartData.push(aggregatedData['Low']);
                    chartColors.push(riskConfig['Low'].color);
                }
                if (aggregatedData['Medium-High'] > 0) {
                    chartLabels.push(riskConfig['Medium-High'].label);
                    chartData.push(aggregatedData['Medium-High']);
                    chartColors.push(riskConfig['Medium-High'].color);
                }
                if (aggregatedData['Extreme'] > 0) {
                    chartLabels.push(riskConfig['Extreme'].label);
                    chartData.push(aggregatedData['Extreme']);
                    chartColors.push(riskConfig['Extreme'].color);
                }

                const riskCtx = document.getElementById('riskLevelChart').getContext('2d');

                new Chart(riskCtx, {
                    type: 'doughnut',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartData,
                            backgroundColor: chartColors,
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
                        },
                        cutout: '70%',
                    }
                });

                // --- CHART 2: TOP LOCATIONS ---
                const topRiskLocations = @json($topRiskLocations);
                const locCtx = document.getElementById('topRiskLocationsChart').getContext('2d');

                new Chart(locCtx, {
                    type: 'bar',
                    data: {
                        labels: topRiskLocations.map(l => l.area_gedung),
                        datasets: [{
                            label: 'Total Skor Risiko',
                            data: topRiskLocations.map(l => l.total_risk_score),
                            backgroundColor: '#EF4444', // Red 500
                            borderRadius: 4,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [2, 4], color: '#E5E7EB' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
                // --- CHART 3: STOP6 CATEGORIES ---
                const stop6Counts = @json($stop6Counts);
                
                // Helper to generate distinct pleasant colors
                const generateColors = (count) => {
                    const colors = [
                        '#F59E0B', '#3B82F6', '#EF4444', '#10B981', '#8B5CF6', 
                        '#EC4899', '#06B6D4', '#F97316', '#64748B', '#84CC16'
                    ];
                    return Array.from({length: count}, (_, i) => colors[i % colors.length]);
                };

                const stop6CategoryMap = {
                    'A': 'Terjepit (A)',
                    'B': 'Tertimpa (B)',
                    'C': 'Kendaraan (C)',
                    'D': 'Jatuh (D)',
                    'E': 'Listrik (E)',
                    'F': 'Panas/Api (F)',
                    'O': 'Lainnya (O)'
                };

                const stop6Labels = Object.keys(stop6Counts).map(key => stop6CategoryMap[key] || key);
                const stop6Data = Object.values(stop6Counts);
                
                const stop6Ctx = document.getElementById('stop6Chart').getContext('2d');

                new Chart(stop6Ctx, {
                    type: 'polarArea',
                    data: {
                        labels: stop6Labels,
                        datasets: [{
                            data: stop6Data,
                            backgroundColor: generateColors(stop6Labels.length).map(color => color + '80'), // Add transparency
                            borderColor: generateColors(stop6Labels.length),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: { right: 10 } // Give extra space so legend isn't clipped
                        },
                        plugins: {
                            legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.raw + ' Laporan';
                                    }
                                }
                            }
                        },
                        scales: {
                            r: {
                                ticks: { display: false },
                                grid: { color: '#f3f4f6' }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>