<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('SHE Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Laporan Bahaya</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg shadow">
                            <p class="text-sm text-blue-600">Total Laporan</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $totalReports }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg shadow">
                            <p class="text-sm text-green-600">Laporan Divalidasi</p>
                            <p class="text-2xl font-bold text-green-800">{{ $validatedReports }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg shadow">
                            <p class="text-sm text-yellow-600">Laporan Menunggu Validasi</p>
                            <p class="text-2xl font-bold text-yellow-800">{{ $totalReports - $validatedReports }}</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Laporan Terbaru</h3>
                    <div class="mb-6">
                        @if ($latestReports->isEmpty())
                            <p class="text-gray-600">Tidak ada laporan terbaru.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach ($latestReports as $report)
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $report->deskripsi_bahaya }}</p>
                                            <p class="text-xs text-gray-500">Oleh: {{ $report->pelapor->name ?? 'N/A' }} - Area: {{ $report->area_gedung }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $report->status == 'divalidasi' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Notifikasi Area Berbahaya</h3>
                    <div class="mb-6">
                        @if ($dangerousAreas->isEmpty())
                            <div class="bg-green-50 p-4 rounded-lg shadow">
                                <p class="text-gray-600">Tidak ada notifikasi area berbahaya saat ini.</p>
                            </div>
                        @else
                            <ul class="divide-y divide-red-200">
                                @foreach ($dangerousAreas as $area)
                                    <li class="py-3 flex items-center bg-red-100 p-3 rounded-lg mb-2 shadow">
                                        <svg class="h-6 w-6 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        <p class="text-sm font-medium text-red-800">
                                            Area <span class="font-bold">{{ $area->area_gedung }}</span> memiliki {{ $area->hazard_count }} laporan bahaya dalam 7 hari terakhir!
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Grafik Tingkat Risiko</h3>
                    <div class="mb-6 bg-white p-4 rounded-lg shadow max-w-md mx-auto">
                        <canvas id="riskLevelChart"></canvas>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Top Lokasi dengan Risiko Tertinggi</h3>
                    <div class="mb-6 bg-white p-4 rounded-lg shadow max-w-md mx-auto">
                        <canvas id="topRiskLocationsChart"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data for Risk Level Chart
            const riskCounts = @json($riskCounts);
            const riskLabels = ['Rendah', 'Sedang', 'Tinggi'];
            const riskData = [riskCounts.low, riskCounts.medium, riskCounts.high];
            const riskColors = ['#22C55E', '#F97316', '#EF4444']; // Green, Orange, Red

            new Chart(document.getElementById('riskLevelChart'), {
                type: 'doughnut',
                data: {
                    labels: riskLabels,
                    datasets: [{
                        data: riskData,
                        backgroundColor: riskColors,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Tingkat Risiko'
                        }
                    }
                }
            });

            // Data for Top Risk Locations Chart
            const topRiskLocations = @json($topRiskLocations);
            const locationLabels = topRiskLocations.map(location => location.area_gedung);
            const locationData = topRiskLocations.map(location => location.total_risk_score);
            const locationColors = locationLabels.map(() => '#3B82F6'); // Blue for all bars

            new Chart(document.getElementById('topRiskLocationsChart'), {
                type: 'bar',
                data: {
                    labels: locationLabels,
                    datasets: [{
                        label: 'Total Skor Risiko',
                        data: locationData,
                        backgroundColor: locationColors,
                        borderColor: locationColors.map(() => '#1E40AF'),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: true,
                            text: 'Top Lokasi dengan Risiko Tertinggi'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Skor Risiko'
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
```

                </div>
            </div>
        </div>
    </div>
</x-app-layout>