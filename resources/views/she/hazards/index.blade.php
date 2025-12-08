<!-- resources/views/she/hazards/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Laporan Duga Bahaya (SHE)') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">

                <!-- NAVIGATION TABS -->
                <ul class="flex border-b mb-6" role="tablist">
                    <li class="mr-6">
                        <a href="#tab-baru" class="tab-link font-semibold text-blue-600" data-tab="menunggu validasi">
                            Laporan Baru
                        </a>
                    </li>
                    <li class="mr-6">
                        <a href="#tab-diproses" class="tab-link text-blue-600" data-tab="diproses">
                            Sedang Diproses
                        </a>
                    </li>
                    <li>
                        <a href="#tab-selesai" class="tab-link text-blue-600" data-tab="selesai">
                            Selesai / Ditolak
                        </a>
                    </li>
                </ul>

                <!-- TAB: LAPORAN BARU -->
                <div id="tab-baru" class="tab-content block">
                    <h3 class="text-lg font-bold mb-3">Laporan Menunggu Validasi</h3>

                    @if($hazardsMenungguValidasi->isEmpty())
                        <p class="text-gray-500 italic">Tidak ada laporan menunggu validasi.</p>
                    @else
                        <table class="w-full table-auto border text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-2 border">ID</th>
                                    <th class="p-2 border">Pelapor</th>
                                    <th class="p-2 border">Deskripsi</th>
                                    <th class="p-2 border">Tanggal</th>
                                    <th claas="p-2 border">Skor Resiko</th>
                                    <th class="p-2 border">Kategori Resiko</th>
                                    <th class="p-2 border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hazardsMenungguValidasi as $hazard)
                                    <tr>
                                        <td class="p-2 border">#{{ $hazard->id }}</td>
                                        <td class="p-2 border">{{ $hazard->nama }}</td>
                                        <td class="p-2 border">{{ Str::limit($hazard->deskripsi_bahaya, 40) }}</td>
                                        <td class="p-2 border">{{ $hazard->tgl_observasi->format('d/m/Y') }}</td>
                                        <td class="p-2 border">{{ $hazard->risk_score }}</td>
                                        <td class="p-2 border">{{ $hazard->kategori_resiko}}</td>
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
                                <th claas="p-2 border">Skor Resiko</th>
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
                                    <td class="p-2 border">{{ $hazard->risk_score }}</td>
                                    <td class="p-2 border">{{ $hazard->kategori_resiko}}</td>
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
                                    <td class="p-2 border">
                                        {{ $hazard->ditangani_pada }}
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

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const tab = this.dataset.tab;

            links.forEach(l => l.classList.remove('text-blue-600', 'font-semibold'));
            this.classList.add('text-blue-600', 'font-semibold');

            contents.forEach(c => c.classList.add('hidden'));
            document.getElementById('tab-' + tab).classList.remove('hidden');
        });
    });
</script>
