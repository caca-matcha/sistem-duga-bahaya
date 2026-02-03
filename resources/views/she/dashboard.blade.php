<x-app-layout>
    @section('page-title', '')

    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight capitalize leading-none">
                            SHE Dashboard</h2>
                        <p
                            class="text-gray-500 font-medium mt-1 tracking-tight uppercase tracking-wider text-[9px] text-gray-400">
                            Ringkasan cepat dan visualisasi data laporan bahaya.</p>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

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
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Laporan</p>
                            <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $totalReports }}</h3>
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
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
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
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Divalidasi</p>
                            <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $validatedReports }}</h3>
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
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Diproses</p>
                            <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $processedReports }}</h3>
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
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
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
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Menunggu</p>
                            <h3 class="text-3xl font-black text-gray-800 mt-2">{{ $pendingReports }}
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
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            {{-- SECTION 2: CHARTS (SIDE BY SIDE) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Risk Level Chart -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                </svg>
                            </div>
                            Distribusi Tingkat Risiko
                        </h3>
                        <button class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                        </button>
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
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            Top 5 Lokasi Risiko Tertinggi
                        </h3>
                        <button class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64 w-full">
                            <canvas id="topRiskLocationsChart"></canvas>
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
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
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
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                document.addEventListener('DO             MContentLoaded', function () {
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
                                legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } }
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
                });
            </script>
    @endpush
</x-app-layout>