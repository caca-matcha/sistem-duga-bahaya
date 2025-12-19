<x-app-layout>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Bagian Selamat Datang dan Pesan --}}
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Pusat Laporan Bahaya</h1>
                <p class="text-lg text-gray-500">Selamat datang kembali, {{ Auth::user()->name }}! Laporkan bahaya, pantau tindakan, dan jaga keselamatan area kerja kita.</p>
            </div>

            {{-- Pesan Sukses --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms
                     class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl shadow-lg border-l-4 border-green-500 flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-600 hover:text-green-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            {{-- Pesan Error Validasi --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl shadow-lg border-l-4 border-red-500">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                {{-- Card 1: Total Laporan --}}
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-indigo-100 transform hover:scale-[1.01] transition duration-300">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Laporan</p>
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-4xl font-extrabold text-indigo-600 mt-4">{{ $totalLaporan ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Laporan yang telah Anda ajukan.</p>
                </div>

                {{-- Card 2: Menunggu Validasi --}}
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-yellow-100 transform hover:scale-[1.01] transition duration-300">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-yellow-600 uppercase">Menunggu Validasi</p>
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-4xl font-extrabold text-yellow-600 mt-4">{{ $menungguValidasi ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Menunggu ditinjau oleh Supervisor/SHE.</p>
                </div>

                {{-- Card 3: Disetujui / Selesai --}}
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-green-100 transform hover:scale-[1.01] transition duration-300">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-green-600 uppercase">Aksi Selesai</p>
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-4xl font-extrabold text-green-600 mt-4">{{ $sudahDivalidasi ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Laporan yang sudah selesai ditindaklanjuti.</p>
                </div>

                {{-- Card 4: Ditolak --}}
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-red-100 transform hover:scale-[1.01] transition duration-300">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-red-600 uppercase">Ditolak</p>
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-4xl font-extrabold text-red-600 mt-4">{{ $ditolak ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Laporan yang dibatalkan atau ditolak.</p>
                </div>

            </div>
            {{-- END Cards --}}

            {{-- 2. Daftar Laporan (Tabel) dan Aksi Utama --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Riwayat Laporan Anda</h2>

                        {{-- Link Tambah Laporan Dibuat Lebih Menonjol --}}
                        <a href="{{ route('karyawan.hazards.create') }}" 
                           class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider shadow-lg hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring ring-red-300 transition ease-in-out duration-150 transform hover:scale-[1.03]">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Buat Laporan Bahaya Baru
                        </a>
                    </div>

                    {{-- Search and Filter --}}
                    <form method="GET" action="{{ route('karyawan.dashboard') }}" class="mb-6">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            
                            <input type="text" name="search" placeholder="Cari deskripsi atau area spesifik..." 
                                   value="{{ request('search') }}"
                                   class="w-full md:flex-grow border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition duration-150">
                            
                            <select name="status" class="w-full md:w-56 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition duration-150">
                                <option value="">Semua Status</option>
                                <option value="menunggu validasi" {{ request('status') == 'menunggu validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            </select>
                            
                            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-700 inline-flex items-center justify-center space-x-2 transition duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                                <span>Filter Data</span>
                            </button>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="overflow-x-auto border border-gray-100 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Laporan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Observasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi Bahaya</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Area</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($hazards as $hazard)
                                    <tr class="hover:bg-red-50/30 transition duration-150">
                                        {{-- ID Laporan (Baru) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $hazard->id }}
                                        </td>
                                        
                                        {{-- Tanggal Observasi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $hazard->tgl_observasi ? \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') : '-' }}
                                        </td>
                                        
                                        {{-- Deskripsi Bahaya --}}
                                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $hazard->deskripsi_bahaya }}">
                                            {{ \Illuminate\Support\Str::limit($hazard->deskripsi_bahaya, 45) }}
                                        </td>
                                        
                                        {{-- Area Gedung (Lebih Detail) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ collect([$hazard->area_gedung, $hazard->area_name])->filter()->join(' -> ') }}
                                        </td>
                                        
                                        {{-- Status (Menggunakan Badge yang Jelas) --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $status = ucfirst($hazard->status);
                                                $badgeClasses = [
                                                    'Menunggu Validasi' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
                                                    'Diproses' => 'bg-blue-100 text-blue-800 border border-blue-300',
                                                    'Disetujui' => 'bg-indigo-100 text-indigo-800 border border-indigo-300',
                                                    'Ditolak' => 'bg-red-100 text-red-800 border border-red-300',
                                                    'Selesai' => 'bg-green-100 text-green-800 border border-green-300',
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm {{ $badgeClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        
                                        {{-- Aksi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('karyawan.hazards.show', $hazard) }}" 
                                               class="text-red-600 hover:text-red-800 font-semibold transition duration-150 hover:underline">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-lg text-gray-500 bg-gray-50/50">
                                            <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 19.172A4 4 0 018 17.586V15M3 15h18a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2zM12 21v-4m0-4h.01"></path></svg>
                                            <p class="font-bold mb-2">Belum Ada Laporan</p>
                                            <p class="text-sm">Anda belum memiliki laporan bahaya yang tercatat. Mari mulai menjaga keselamatan!</p>
                                            <a href="{{ route('karyawan.hazards.create') }}" class="mt-3 inline-block text-red-600 hover:underline font-bold">
                                                Buat laporan pertama Anda sekarang.
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginasi --}}
                    {{-- Asumsi variabel $hazards adalah objek Paginator --}}
                    @if (isset($hazards) && method_exists($hazards, 'links'))
                        <div class="mt-6">
                            {{ $hazards->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>