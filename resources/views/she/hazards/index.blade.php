<x-app-layout>
<x-slot name="header">
<div class="flex flex-col md:flex-row md:items-center md:justify-between">
<h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
<svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
</svg>
{{ __('Manajemen Laporan Duga Bahaya (SHE)') }}
</h2>
<div class="mt-4 md:mt-0 text-sm text-gray-500">
Memantau keselamatan kerja area
</div>
</div>
</x-slot>

{{-- 
    LOGIC TAB DENGAN ALPINE.JS 
    Mengambil hash dari URL (misal #diproses) agar saat pagination diklik, 
    tab tidak kembali ke awal.
    
    PENYEBAB TOMBOL TIDAK KLIKABLE: 
    Masalah z-index. Ditambahkan 'relative z-10' ke kontainer utama untuk memastikan 
    konten tabel berada di atas potensi overlay tak terlihat (seperti modal backdrop).
--}}
<div class="py-8 bg-gray-50 min-h-screen relative z-10" 
     x-data="{ 
        activeTab: window.location.hash ? window.location.hash.replace('#', '') : 'baru',
        setTab(tab) {
            this.activeTab = tab;
            window.location.hash = tab;
        }
     }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Success Notification --}}
        @if (session('success'))
            <div x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-show="show"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg" 
                 role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">

            <!-- MODERN NAVIGATION TABS -->
            <div class="border-b border-gray-200 bg-white px-6 pt-4">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    {{-- Tab Baru --}}
                    <button @click="setTab('baru')"
                        :class="activeTab === 'baru' 
                            ? 'border-indigo-500 text-indigo-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <svg :class="activeTab === 'baru' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500'" class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Laporan Baru
                        @if($hazardsMenungguValidasi->count() > 0)
                            <span class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2.5 rounded-full text-xs font-bold md:inline-block hidden">
                                {{ $hazardsMenungguValidasi->count() }}
                            </span>
                        @endif
                    </button>

                    {{-- Tab Diproses --}}
                    <button @click="setTab('diproses')"
                        :class="activeTab === 'diproses' 
                            ? 'border-blue-500 text-blue-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <svg :class="activeTab === 'diproses' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'" class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Sedang Diproses
                        @if($hazardsDiproses->count() > 0)
                            <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs font-bold md:inline-block hidden">
                                {{ $hazardsDiproses->count() }}
                            </span>
                        @endif
                    </button>

                    {{-- Tab Selesai --}}
                    <button @click="setTab('selesai')"
                        :class="activeTab === 'selesai' 
                            ? 'border-green-500 text-green-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <svg :class="activeTab === 'selesai' ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500'" class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat / Selesai
                    </button>
                </nav>
            </div>

            <!-- CONTENT AREA -->
            <div class="p-6">
                
                {{-- ================= TAB: LAPORAN BARU ================= --}}
                {{-- Semua tab diberi transisi yang sama untuk UX yang lebih baik --}}
                <div x-show="activeTab === 'baru'" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-2" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Menunggu Validasi SHE</h3>
                        <span class="text-xs text-gray-500">Perlu tindakan segera</span>
                    </div>

                    @if($hazardsMenungguValidasi->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="mt-2 text-sm text-gray-500 font-medium">Bagus! Tidak ada laporan baru yang menunggu validasi.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID & Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi Singkat</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Risiko</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($hazardsMenungguValidasi as $hazard)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-indigo-600">#{{ $hazard->id }}</div>
                                                <div class="text-xs text-gray-500">{{ $hazard->tgl_observasi->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $hazard->nama }}</div>
                                                <div class="text-xs text-gray-500">{{ $hazard->dept }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 line-clamp-2 max-w-xs">{{ $hazard->deskripsi_bahaya }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $hazard->risk_score >= 15 ? 'bg-red-100 text-red-800' : ($hazard->risk_score >= 8 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                    {{ $hazard->risk_score }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">{{ $hazard->kategori_resiko }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                {{-- Tombol "Review" sudah benar, asumsikan route sudah terdefinisi --}}
                                                <a href="{{ route('she.hazards.show', $hazard) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                                    Review
                                                    <svg class="ml-1.5 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- ================= TAB: DIPROSES ================= --}}
                <div x-show="activeTab === 'diproses'" 
                     x-cloak 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-2" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Sedang Dalam Penanganan</h3>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC / Penanggung Jawab</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Risiko</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($hazardsDiproses as $hazard)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500">#{{ $hazard->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $hazard->nama }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                                    {{ substr($hazard->ditanganiOleh?->name ?? '?', 0, 2) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $hazard->ditanganiOleh?->name ?? 'Belum ditentukan' }}</div>
                                                    <div class="text-xs text-gray-500">SHE Team</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Diproses
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm text-gray-900 font-bold">{{ $hazard->risk_score }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Tombol "Lihat Detail" sudah benar, asumsikan route sudah terdefinisi --}}
                                            <a href="{{ route('she.hazards.show', $hazard) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold transition-colors">Lihat Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 italic">
                                            Tidak ada laporan yang sedang diproses.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{-- Catatan: Paginasi ini akan kembali ke tab 'baru' saat diklik 
                                     kecuali Anda memodifikasi view Paginator Laravel untuk 
                                     menyertakan hash fragment (e.g., ?page=2#diproses) --}}
                        {{ $hazardsDiproses->links() }}
                    </div>
                </div>

                {{-- ================= TAB: SELESAI / DITOLAK ================= --}}
                <div x-show="activeTab === 'selesai'" 
                     x-cloak 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-2" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Riwayat Laporan Selesai</h3>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Akhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Penyelesaian</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($hazardsSelesai as $hazard)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $hazard->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $hazard->nama }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($hazard->status == 'selesai')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    <svg class="mr-1.5 h-3 w-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                    <svg class="mr-1.5 h-3 w-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $hazard->ditangani_pada ? \Carbon\Carbon::parse($hazard->ditangani_pada)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Ikon mata sudah benar, asumsikan route sudah terdefinisi --}}
                                            <a href="{{ route('she.hazards.show', $hazard) }}" class="text-gray-400 hover:text-indigo-600 transition-colors" title="Lihat Detail">
                                                <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 italic">
                                            Belum ada riwayat laporan selesai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $hazardsSelesai->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


</x-app-layout>