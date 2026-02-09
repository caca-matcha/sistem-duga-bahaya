<x-app-layout>
    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-6 h-6 text-red-600" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 7V3.5L18.5 9H13z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight capitalize leading-none">
                            Reports Management</h2>
                        <p
                            class="text-gray-400 font-medium mt-1.5 tracking-tight uppercase text-[12px]">
                            Manajemen Laporan Duga Bahaya (SHE) & Hasil Temuan.</p>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-2xl p-4 sm:p-8">

                <!-- NAVIGATION TABS -->
                <ul class="flex border-b mb-6" role="tablist">
                    <li class="mr-6">
                        <a href="#tab-baru" class="tab-link font-semibold text-blue-600" data-tab="menunggu validasi">
                            Laporan Baru
                        </a>
                    </li>
                    <li class="mr-6">
                        <a href="#tab-diproses" class="tab-link text-gray-600" data-tab="diproses">
                            Sedang Diproses
                        </a>
                    </li>
                    <li>
                        <a href="#tab-selesai" class="tab-link text-gray-600" data-tab="selesai">
                            Selesai / Ditolak
                        </a>
                    </li>
                </ul>

                <!-- TAB: LAPORAN BARU -->
                <div id="tab-baru" class="tab-content block">
                    <h3 class="text-lg font-bold mb-3">Laporan Baru Masuk</h3>

                    @if($hazardsBaru->isEmpty())
                        <p class="text-gray-500 italic">Tidak ada laporan baru.</p>
                    @else
                        <table class="w-full table-auto border text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-2 border">ID</th>
                                    <th class="p-2 border">Pelapor</th>
                                    <th class="p-2 border">Deskripsi</th>
                                    <th class="p-2 border">Tanggal</th>
                                    <th class="p-2 border">Skor Resiko</th>
                                    <th class="p-2 border">Kategori Resiko</th>
                                    <th class="p-2 border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hazardsBaru as $hazard)
                                    @php
                                        $risk = getRiskClasses($hazard->risk_score);
                                    @endphp
                                    <tr>
                                        <td class="p-2 border">#{{ $hazard->id }}</td>
                                        <td class="p-2 border">{{ $hazard->nama }}</td>
                                        <td class="p-2 border">{{ Str::limit($hazard->deskripsi_bahaya, 40) }}</td>
                                        <td class="p-2 border">{{ $hazard->tgl_observasi->format('d/m/Y') }}</td>
                                        {{-- KOLOM SKOR RESIKO BARU DENGAN WARNA --}}
                                        <td class="p-2 border text-center">
                                          <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                            style="background-color: {{ getRiskColor($hazard->risk_score, $riskColors) }};
                                            color: {{ getTextColor($hazard->risk_score) }};">
                                                {{ $hazard->risk_score }}
                                            </span>

                                        </td>
                                        {{-- KOLOM KATEGORI RESIKO BARU DENGAN WARNA --}}
                                        <td class="p-2 border text-center">
                                           <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                                style="background-color: {{ getRiskColor($hazard->risk_score, $riskColors) }};
                                                        color: {{ getTextColor($hazard->risk_score) }};">
                                                {{ $hazard->risk_score }}
                                            </span>

                                        </td>
                                        <td class="p-2 border text-center">
                                            <a href="{{ route('she.hazards.show', $hazard) }}" 
                                                class="text-blue-600 hover:underline">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- TAB: DIPROSES -->
                <div id="tab-diproses" class="tab-content hidden">
                    <h3 class="text-lg font-bold mb-3">Laporan Sedang Diproses</h3>

                    <table class="w-full table-auto border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 border">ID</th>
                                <th class="p-2 border">Pelapor</th>
                                <th class="p-2 border">Status</th>
                                <th class="p-2 border">PIC</th>
                                <th class="p-2 border">Skor Resiko</th>
                                <th class="p-2 border">Kategori Resiko</th>
                                <th class="p-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hazardsDiproses as $hazard)
                                <tr>
                                    <td class="p-2 border">#{{ $hazard->id }}</td>
                                    <td class="p-2 border">{{ $hazard->nama }}</td>
                                    <td class="p-2 border">
                                        <span class="px-2 py-1 bg-yellow-200 text-xs font-semibold rounded">Diproses</span>
                                    </td>
                                    <td class="p-2 border">{{ $hazard->ditanganiOleh->name ?? '-' }}</td>
                                    {{-- KOLOM SKOR RESIKO DIPROSES DENGAN WARNA --}}
                                    <td class="p-2 border text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                            style="background-color: {{ getRiskColor($hazard->risk_score, $riskColors) }};
                                                    color: {{ getTextColor($hazard->risk_score) }};">
                                            {{ $hazard->risk_score }}
                                        </span>
                                    </td>
                                    {{-- KOLOM KATEGORI RESIKO DIPROSES DENGAN WARNA --}}
                                    <td class="p-2 border text-center">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                            style="background-color: {{ getRiskColor($hazard->risk_score, $riskColors) }};
                                                    color: {{ getTextColor($hazard->risk_score) }};">
                                            {{ $hazard->kategori_resiko) }}
                                        </span>
                                    </td>
                                    <td class="p-2 border text-center">
                                        <a href="{{ route('she.hazards.show', $hazard) }}" 
                                           class="text-blue-600 hover:underline">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $hazardsDiproses->links() }}
                    </div>
                </div>

                <!-- TAB: SELESAI / DITOLAK -->
                <div id="tab-selesai" class="tab-content hidden">
                    <h3 class="text-lg font-bold mb-3">Laporan Selesai / Ditolak</h3>

                    <table class="w-full table-auto border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 border">ID</th>
                                <th class="p-2 border">Pelapor</th>
                                <th class="p-2 border">Status</th>
                                <th class="p-2 border">Skor Resiko</th>
                                <th class="p-2 border">Kategori Resiko</th>
                                <th class="p-2 border">Diselesaikan Pada</th>
                                <th class="p-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hazardsSelesai as $hazard)
                                <tr>
                                    <td class="p-2 border">#{{ $hazard->id }}</td>
                                    <td class="p-2 border">{{ $hazard->nama }}</td>
                                    <td class="p-2 border">
                                        @if($hazard->status == 'selesai')
                                            <span class="px-2 py-1 bg-green-200 text-xs font-semibold rounded">Selesai</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-200 text-xs font-semibold rounded">Ditolak</span>
                                        @endif
                                    </td>
                                    {{-- KOLOM SKOR RESIKO SELESAI / DITOLAK DENGAN WARNA --}}
                                    <td class="p-2 border text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                            style="background-color: {{ getRiskColor($hazard->risk_score, $riskColors) }};
                                                    color: {{ getTextColor($hazard->risk_score) }};">
                                            {{ $hazard->risk_score }}
                                        </span>
                                    </td>
                                    {{-- KOLOM KATEGORI RESIKO SELESAI / DITOLAK DENGAN WARNA --}}
                                    <td class="p-2 border text-center">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                                            style="background-color: {{ getRiskColor($hazard->risk_score, $riskColors) }};
                                                    color: {{ getTextColor($hazard->risk_score) }};">
                                            {{ $hazard->kategori_resiko) }}
                                        </span>
                                    </td>
                                    <td class="p-2 border">
                                        {{ $hazard->report_selesai ? \Carbon\Carbon::parse($hazard->report_selesai)->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="p-2 border text-center">
                                        <a href="{{ route('she.hazards.show', $hazard) }}" 
                                           class="text-blue-600 hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $hazardsSelesai->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const links = document.querySelectorAll('.tab-link');
    const contents = document.querySelectorAll('.tab-content');

    // Logic untuk mengaktifkan tab saat pertama kali dimuat
    const setActiveTab = (tabId) => {
        links.forEach(l => l.classList.remove('text-blue-600', 'font-semibold', 'text-gray-600'));
        contents.forEach(c => c.classList.add('hidden'));

        const targetLink = document.querySelector(`.tab-link[data-tab="${tabId}"]`);
        const targetContent = document.getElementById('tab-' + tabId);

        if (targetLink && targetContent) {
            targetLink.classList.add('text-blue-600', 'font-semibold');
            targetLink.classList.remove('text-gray-600');
            targetContent.classList.remove('hidden');
        } else {
            // Default ke tab-baru jika tidak ditemukan
            document.querySelector('.tab-link[data-tab="menunggu validasi"]').classList.add('text-blue-600', 'font-semibold');
            document.getElementById('tab-baru').classList.remove('hidden');
        }
    };
    
    // Inisialisasi tab saat DOM dimuat
    document.addEventListener('DOMContentLoaded', () => {
        // Cek hash URL untuk tab aktif
        const hash = window.location.hash.replace('#tab-', '');
        setActiveTab(hash || 'baru'); // Default ke 'baru'

        // Event listener untuk klik tab
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const tab = this.dataset.tab;
                setActiveTab(tab);
                // Update URL hash agar tab tetap sama saat di-refresh
                window.history.pushState(null, null, `#tab-${tab}`);
            });
        });
    });
</script>