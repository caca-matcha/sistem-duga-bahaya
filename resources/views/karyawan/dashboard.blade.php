<x-app-layout>
    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight capitalize leading-none">
                            Pusat Laporan Bahaya</h2>
                        <p
                            class="text-gray-500 font-medium mt-1 tracking-tight uppercase tracking-wider text-[9px] text-gray-400">
                            Selamat datang kembali, <span
                                class="font-bold text-red-600">{{ Auth::user()->name }}</span>!
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gradient-to-br from-gray-50 via-red-50/30 to-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Content Start --}}

            {{-- Pesan Sukses --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms
                    class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 rounded-2xl shadow-lg border-l-4 border-green-500 flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 hover:text-green-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Pesan Error Validasi --}}
            @if ($errors->any())
                <div
                    class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 text-red-700 rounded-2xl shadow-lg border-l-4 border-red-500">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <p class="font-bold">Gagal Menyimpan Laporan:</p>
                    </div>
                    <ul class="mt-2 list-disc list-inside ml-9">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. Kartu Statistik (Ringkasan Kinerja) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

                {{-- Card 1: Total Laporan --}}
                <div
                    class="group relative bg-gradient-to-br from-white to-red-50/50 p-6 rounded-2xl shadow-lg border border-red-100/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-red-500/5 rounded-full blur-3xl group-hover:bg-red-500/10 transition-all duration-300">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-bold text-red-600 uppercase tracking-wider">Total Laporan</p>
                            <div
                                class="p-3 bg-red-100 rounded-xl group-hover:bg-red-200 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-5xl font-black text-red-600 mb-2">{{ $totalLaporan ?? 0 }}</p>
                        <p class="text-xs text-gray-500 font-medium">Laporan yang telah Anda ajukan</p>
                    </div>
                </div>

                {{-- Card 2: Menunggu Validasi --}}
                <div
                    class="group relative bg-gradient-to-br from-white to-yellow-50/50 p-6 rounded-2xl shadow-lg border border-yellow-100/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-yellow-500/5 rounded-full blur-3xl group-hover:bg-yellow-500/10 transition-all duration-300">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-bold text-yellow-700 uppercase tracking-wider">Menunggu Validasi</p>
                            <div
                                class="p-3 bg-yellow-100 rounded-xl group-hover:bg-yellow-200 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-5xl font-black text-yellow-600 mb-2">{{ $menungguValidasi ?? 0 }}</p>
                        <p class="text-xs text-gray-500 font-medium">Menunggu ditinjau oleh Supervisor/SHE</p>
                    </div>
                </div>

                {{-- Card 3: Disetujui / Selesai --}}
                <div
                    class="group relative bg-gradient-to-br from-white to-green-50/50 p-6 rounded-2xl shadow-lg border border-green-100/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full blur-3xl group-hover:bg-green-500/10 transition-all duration-300">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-bold text-green-700 uppercase tracking-wider">Aksi Selesai</p>
                            <div
                                class="p-3 bg-green-100 rounded-xl group-hover:bg-green-200 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-5xl font-black text-green-600 mb-2">{{ $sudahDivalidasi ?? 0 }}</p>
                        <p class="text-xs text-gray-500 font-medium">Laporan yang sudah selesai ditindaklanjuti</p>
                    </div>
                </div>

                {{-- Card 4: Ditolak --}}
                <div
                    class="group relative bg-gradient-to-br from-white to-rose-50/50 p-6 rounded-2xl shadow-lg border border-rose-100/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-rose-500/5 rounded-full blur-3xl group-hover:bg-rose-500/10 transition-all duration-300">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-bold text-rose-700 uppercase tracking-wider">Ditolak</p>
                            <div
                                class="p-3 bg-rose-100 rounded-xl group-hover:bg-rose-200 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-5xl font-black text-rose-600 mb-2">{{ $ditolak ?? 0 }}</p>
                        <p class="text-xs text-gray-500 font-medium">Laporan yang dibatalkan atau ditolak</p>
                    </div>
                </div>

            </div>
            {{-- END Cards --}}

            {{-- 2. Daftar Laporan (Tabel) dan Aksi Utama --}}
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b-2 border-gray-100">
                        <div>
                            <h2 class="text-3xl font-black text-gray-900 mb-1">Riwayat Laporan Anda</h2>
                            <p class="text-sm text-gray-500">Pantau status dan detail setiap laporan bahaya</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
                            <a href="{{ route('karyawan.maps.index') }}"
                                class="group inline-flex items-center px-6 py-3.5 bg-white border-2 border-red-600 rounded-xl font-bold text-sm text-red-600 uppercase tracking-wider shadow-lg hover:shadow-xl hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13v-6m0-6V4m0 6h12M9 7l5.447 2.724A1 1 0 0015 10.618V19.382a1 1 0 00-1.447.894L9 20m0-6a3 3 0 100-6 3 3 0 000 6z">
                                    </path>
                                </svg>
                                Lihat Peta Risiko
                            </a>

                            <a href="{{ route('karyawan.hazards.create') }}"
                                class="group inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider shadow-lg hover:shadow-2xl hover:from-red-700 hover:to-red-800 active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Laporan Bahaya Baru
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter --}}
                    <form id="filter-form" method="GET" action="{{ route('karyawan.dashboard') }}" class="mb-8">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 bg-gradient-to-br from-gray-50 to-red-50/20 p-6 rounded-2xl border border-gray-200/50 shadow-inner">

                            {{-- Search Input --}}
                            <div class="lg:col-span-4">
                                <input type="text" name="search"
                                    placeholder="🔍 Cari ID, tgl, deskripsi, area, status..."
                                    value="{{ request('search') }}"
                                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 transition duration-200 placeholder:text-gray-400">
                            </div>

                            {{-- Filter Status --}}
                            <div class="lg:col-span-1">
                                <select name="status"
                                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 transition duration-200">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu validasi" {{ request('status') == 'menunggu validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>
                                        Diproses</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                    </option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui</option>
                                </select>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="lg:col-span-1">
                                <button type="submit"
                                    class="w-full px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-red-700 hover:to-red-800 inline-flex items-center justify-center space-x-2 transition-all duration-200 transform hover:scale-105 font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                    <span>Cari</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="overflow-x-auto border border-gray-200 rounded-2xl shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-red-50/30">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        ID Laporan</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Tanggal Observasi</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Deskripsi Bahaya</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Area</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Status</th>
                                    <th class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($hazards as $hazard)
                                    <tr class="hover:bg-red-50/30 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="text-sm font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-lg">#{{ $hazard->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d F Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs">
                                            <p class="line-clamp-2">{{ $hazard->deskripsi_bahaya }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="text-sm text-gray-600 font-medium bg-gray-50 px-3 py-1 rounded-lg">{{ $hazard->area_gedung }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $statusConfig = [
                                                    'menunggu validasi' => ['bg' => 'bg-gradient-to-r from-yellow-100 to-amber-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300'],
                                                    'disetujui' => ['bg' => 'bg-gradient-to-r from-green-100 to-emerald-100', 'text' => 'text-green-800', 'border' => 'border-green-300'],
                                                    'selesai' => ['bg' => 'bg-gradient-to-r from-green-100 to-emerald-100', 'text' => 'text-green-800', 'border' => 'border-green-300'],
                                                    'ditolak' => ['bg' => 'bg-gradient-to-r from-red-100 to-rose-100', 'text' => 'text-red-800', 'border' => 'border-red-300'],
                                                    'diproses' => ['bg' => 'bg-gradient-to-r from-blue-100 to-cyan-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300'],
                                                ];
                                                $config = $statusConfig[$hazard->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300'];
                                            @endphp
                                            <span
                                                class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-xl border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }} shadow-sm">
                                                {{ ucfirst($hazard->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('karyawan.hazards.show', $hazard->id) }}"
                                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                                                Lihat
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-lg font-semibold text-gray-500 mb-1">Tidak ada laporan bahaya
                                                    ditemukan</p>
                                                <p class="text-sm text-gray-400">Mulai laporkan bahaya untuk meningkatkan
                                                    keselamatan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    @if (isset($hazards) && method_exists($hazards, 'links'))
                        <div class="mt-8">
                            {{ $hazards->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>