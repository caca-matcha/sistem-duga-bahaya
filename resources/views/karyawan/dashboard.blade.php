<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl shadow-md border-l-4 border-green-500">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Pesan Error Validasi (jika user kembali dari form) --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl shadow-md border-l-4 border-red-500">
                    <p class="font-bold">Gagal Menyimpan Laporan:</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="text-3xl font-bold text-gray-800 mb-6">Pusat Laporan Bahaya</h1>
            <p class="text-gray-600 mb-8">Selamat datang kembali, {{ Auth::user()->name }}! Berikut ringkasan laporan bahaya Anda.</p>

            {{-- 1. Kartu (Cards) Statistik (Membutuhkan variabel dari Controller) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-indigo-50 p-6 rounded-xl shadow-lg border border-gray-100">
                    <p class="text-sm font-medium text-gray-500">Total Laporan</p>
                    <p class="text-2xl font-extrabold text-indigo-600 mt-2">{{ $totalLaporan }}</p>
                </div>

                <div class="bg-yellow-100 p-6 rounded-xl shadow-lg border border-gray-100">
                    <p class="text-sm font-medium text-yellow-600">Menunggu Validasi</p>
                    <p class="text-2xl font-extrabold text-yellow-600 mt-2">{{ $menungguValidasi }}</p>
                </div>

                <div class="bg-green-100 p-6 rounded-xl shadow-lg border border-gray-100">
                    <p class="text-sm font-medium text-green-600">Disetujui / Selesai</p>
                    <p class="text-2xl font-extrabold text-green-600 mt-2">{{ $sudahDivalidasi }}</p>
                </div>

                <div class="bg-red-100 p-6 rounded-xl shadow-lg border border-gray-100">
                    <p class="text-sm font-medium text-red-600">Ditolak</p>
                    <p class="text-2xl font-extrabold text-red-600 mt-2">{{ $ditolak }}</p>
                </div>

            </div>
            {{-- END Cards --}}

            {{-- 2. Daftar Laporan (Tabel) --}}
            <div class="bg-white p-6 mt-8">
            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Daftar Laporan Anda</h2>

                    {{-- Link Tambah Laporan --}}
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('karyawan.hazards.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            + Buat Laporan Baru
                        </a>
                    </div>


                    {{-- Search and Filter --}}
                    <form method="GET" action="{{ route('karyawan.dashboard') }}" class="mb-4">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                            <input type="text" name="search" placeholder="Cari deskripsi, area..." 
                                   value="{{ request('search') }}"
                                   class="w-full md:w-1/3 border-gray-300 rounded-lg shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <select name="status" class="w-full md:w-auto border-gray-300 rounded-lg shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <option value="">Semua Status</option>
                                <option value="menunggu validasi" {{ request('status') == 'menunggu validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700">
                                Filter
                            </button>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Observasi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi Bahaya</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area Gedung</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($hazards as $hazard)
                                    <tr class="hover:bg-gray-50">
                                        {{-- PERBAIKAN: Menggunakan tgl_observasi --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $hazard->tgl_observasi ? \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $hazard->deskripsi_bahaya }}">
                                            {{ \Illuminate\Support\Str::limit($hazard->deskripsi_bahaya, 50) }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $hazard->area_gedung }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                // PERBAIKAN: Menambahkan status 'Menunggu Validasi', 'Diproses', dan 'Selesai'
                                                $badgeClasses = [
                                                    'Menunggu Validasi' => 'bg-yellow-100 text-yellow-800',
                                                    'Diproses' => 'bg-blue-100 text-blue-800',
                                                    'Disetujui' => 'bg-blue-100 text-blue-800',
                                                    'Ditolak' => 'bg-red-100 text-red-800',
                                                    'Selesai' => 'bg-green-100 text-green-800',
                                                ];
                                                $status = ucfirst($hazard->status);
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Ganti '#' dengan rute ke halaman detail jika ada --}}
                                            <a href="{{ route('karyawan.hazards.show', $hazard) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Anda belum memiliki laporan bahaya yang tercatat.
                                            <a href="{{ route('karyawan.hazards.create') }}" class="text-red-600 hover:underline font-medium">Buat laporan pertama Anda.</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>