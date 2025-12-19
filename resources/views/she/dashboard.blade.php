<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            {{ __('SHE Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- SECTION 1: STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Card -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-md">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Laporan</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalReports }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>

                <!-- Validated Card -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-md">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Laporan Divalidasi</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $validatedReports }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Pending Card -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-md">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Menunggu Validasi</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalReports - $validatedReports }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: CHARTS (SIDE BY SIDE) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Risk Level Chart -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        Distribusi Tingkat Risiko
                    </h3>
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="riskLevelChart"></canvas>
                    </div>
                </div>

                <!-- Top Locations Chart -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Top 5 Lokasi Risiko Tertinggi
                    </h3>
                    <div class="relative h-64 w-full">
                        <canvas id="topRiskLocationsChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: LISTS (NOTIFICATIONS & LATEST) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- WARNING / NOTIFICATION (Col Span 1) --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">
                        <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex items-center justify-between">
                            <h3 class="text-red-800 font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Area Perlu Perhatian
                            </h3>
                            <span class="bg-red-200 text-red-800 text-xs font-bold px-2 py-1 rounded-full">{{ $dangerousAreas->count() }}</span>
                        </div>
                        <div class="p-4">
                            @if ($dangerousAreas->isEmpty())
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-10 w-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm text-gray-500 mt-2">Semua area dalam batas aman.</p>
                                </div>
                            @else
                                <ul class="space-y-3">
                                    @foreach ($dangerousAreas as $area)
                                        <li class="flex items-start bg-red-50 p-3 rounded-lg border border-red-100">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <div class="h-2 w-2 rounded-full bg-red-500"></div>
                                            </div>
                                            <div class="ml-3 w-full">
                                                <p class="text-sm font-bold text-gray-800">{{ $area->area_gedung }}</p>
                                                <p class="text-xs text-red-600 mt-0.5">
                                                    {{ $area->hazard_count }} laporan (7 hari terakhir)
                                                </p>
                                                <div class="w-full bg-red-200 rounded-full h-1.5 mt-2">
                                                    <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ min(100, ($area->hazard_count * 10)) }}%"></div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- LATEST REPORTS (Col Span 2) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-full">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">Laporan Masuk Terbaru</h3>
                            <a href="{{ route('she.hazards.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua &rarr;</a>
                        </div>
                        <div class="p-0">
                            @if ($latestReports->isEmpty())
                                <div class="p-6 text-center text-gray-500">Belum ada laporan terbaru.</div>
                            @else
                                <ul class="divide-y divide-gray-50">
                                    @foreach ($latestReports as $report)
                                        <li class="p-4 hover:bg-gray-50 transition-colors flex items-start gap-4">
                                            <div class="flex-shrink-0 mt-1">
                                                @if($report->status == 'menunggu validasi')
                                                    <span class="flex h-3 w-3 relative">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                                                    </span>
                                                @elseif($report->status == 'selesai')
                                                    <span class="inline-block h-3 w-3 rounded-full bg-green-500"></span>
                                                @else
                                                    <span class="inline-block h-3 w-3 rounded-full bg-blue-500"></span>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $report->deskripsi_bahaya }}
                                                </p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <p class="text-xs text-gray-500">
                                                        {{ $report->pelapor->name ?? 'Anonim' }} &bull; {{ $report->area_gedung }}
                                                    </p>
                                                    <span class="text-xs text-gray-400">&bull; {{ $report->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $report->status == 'selesai' ? 'bg-green-100 text-green-800' : 
                                                      ($report->status == 'diproses' ? 'bg-blue-100 text-blue-800' : 
                                                      'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div> {{-- End Grid Lists --}}

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- CHART 1: RISK LEVEL ---
            const riskCounts = @json($riskCounts);
            const riskCtx = document.getElementById('riskLevelChart').getContext('2d');
            
            new Chart(riskCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Rendah', 'Sedang', 'Tinggi'],
                    datasets: [{
                        data: [riskCounts.low, riskCounts.medium, riskCounts.high],
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444'], // Green, Amber, Red
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
                        backgroundColor: '#6366F1', // Indigo 500
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