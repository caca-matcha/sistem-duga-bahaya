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
                            Pusat Laporan Bahaya
                        </h2>
                        <p class="text-xs text-gray-500 font-medium mt-1 tracking-wide uppercase">
                            Lapor bahaya, cek lokasi, dan pantau status laporan Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Content Start --}}

            {{-- Welcome Card (More Colorful: Vibrant Gradient) --}}
            <div
                class="relative overflow-hidden bg-gradient-to-br from-red-600 to-red-800 rounded-[24px] p-8 mb-8 shadow-xl shadow-red-900/20 group">
                {{-- Abstract Pattern --}}
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-48 h-48 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2v-2zm0-6h2v4h-2v-4z" />
                    </svg>
                </div>

                {{-- Gradient Mesh (Subtle Overlay) --}}
                <div class="absolute inset-0 bg-white/5 opacity-50 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-white text-[11px] font-bold tracking-widest uppercase border border-white/10">
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
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
                        <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight leading-tight mb-2">
                            {{ $greeting }}, <span class="text-red-200">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </h1>
                        <p class="text-red-100 font-medium text-lg">
                            Ayo mulai aktivitasmu dengan mengutamakan keselamatan kerja. 🛡️
                        </p>
                    </div>

                    {{-- Simple Call to Action or Icon --}}
                    <div class="hidden md:block">
                        <div
                            class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-500 shadow-inner">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pesan Sukses --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms
                    class="mb-8 p-4 bg-emerald-50 text-emerald-700 rounded-2xl shadow-sm border border-emerald-100 flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Pesan Error Validasi --}}
            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-50 text-red-700 rounded-2xl shadow-sm border border-red-100">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <p class="font-bold text-sm">Gagal Menyimpan Laporan:</p>
                    </div>
                    <ul class="mt-1 list-disc list-inside ml-8 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. Kartu Statistik (Ringkasan Kinerja) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">

                {{-- Card 1: Total Laporan --}}
                <div
                    class="bg-white p-6 rounded-[20px] shadow-sm border-2 border-red-200 hover:border-red-400 hover:shadow-md transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[11px] font-bold text-red-600 uppercase tracking-widest">Total Laporan</p>
                        <div
                            class="w-10 h-10 bg-red-100 rounded-[14px] flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-4xl font-black text-gray-900">{{ $totalLaporan ?? 0 }}</p>
                    </div>
                    <p class="text-xs text-gray-400 font-medium">Laporan yang telah Anda ajukan</p>
                </div>

                {{-- Card 2: Menunggu Validasi --}}
                <div
                    class="bg-white p-6 rounded-[20px] shadow-sm border-2 border-amber-200 hover:border-amber-400 hover:shadow-md transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[11px] font-bold text-amber-600 uppercase tracking-widest">Menunggu Validasi</p>
                        <div
                            class="w-10 h-10 bg-amber-100 rounded-[14px] flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-4xl font-black text-gray-900">{{ $menungguValidasi ?? 0 }}</p>
                    </div>
                    <p class="text-xs text-gray-400 font-medium">Menunggu ditinjau oleh Supervisor/SHE</p>
                </div>

                {{-- Card 3: Aksi Selesai --}}
                <div
                    class="bg-white p-6 rounded-[20px] shadow-sm border-2 border-emerald-200 hover:border-emerald-400 hover:shadow-md transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-widest">Aksi Selesai</p>
                        <div
                            class="w-10 h-10 bg-emerald-100 rounded-[14px] flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-4xl font-black text-gray-900">{{ $sudahDivalidasi ?? 0 }}</p>
                    </div>
                    <p class="text-xs text-gray-400 font-medium">Laporan yang sudah selesai ditindaklanjuti</p>
                </div>

                {{-- Card 4: Ditolak --}}
                <div
                    class="bg-white p-6 rounded-[20px] shadow-sm border-2 border-rose-200 hover:border-rose-400 hover:shadow-md transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[11px] font-bold text-rose-600 uppercase tracking-widest">Ditolak</p>
                        <div
                            class="w-10 h-10 bg-rose-100 rounded-[14px] flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-4xl font-black text-gray-900">{{ $ditolak ?? 0 }}</p>
                    </div>
                    <p class="text-xs text-gray-400 font-medium">Laporan yang dibatalkan atau ditolak</p>
                </div>

            </div>



            {{-- 1.5. Tugas Saya (PIC/Leader) --}}
            @if(isset($assignedHazards) && $assignedHazards->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-black text-gray-900 mb-4 flex items-center">
                        <span class="bg-red-100 text-red-600 p-1.5 rounded-lg mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </span>
                        Tugas Saya (PIC/Leader)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($assignedHazards as $task)
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                    <svg class="w-24 h-24 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2v-2zm0-6h2v4h-2v-4z" />
                                    </svg>
                                </div>
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-3">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-600">
                                            #{{ $task->id }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide {{ $task->status === 'menunggu validasi' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $task->status }}
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-gray-900 mb-1 line-clamp-1" title="{{ $task->deskripsi_bahaya }}">
                                        {{ $task->deskripsi_bahaya }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mb-4 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $task->area_gedung }} - {{ $task->area_name }}
                                    </p>

                                    <div
                                        class="flex items-center justify-between mt-4 text-xs font-semibold text-gray-500 border-t pt-3">
                                        <span>Target:
                                            {{ $task->target_penyelesaian ? \Carbon\Carbon::parse($task->target_penyelesaian)->translatedFormat('d M Y') : 'Belum ditentukan' }}</span>
                                    </div>

                                    <a href="{{ route('karyawan.hazards.show', $task->id) }}"
                                        class="mt-4 w-full flex items-center justify-center px-4 py-2 {{ $task->status === 'menunggu verifikasi' ? 'bg-blue-600 shadow-blue-200' : 'bg-red-600 shadow-red-200' }} text-white text-sm font-bold rounded-xl hover:opacity-90 transition shadow-lg">
                                        {{ $task->status === 'menunggu verifikasi' ? 'Cek Status' : 'Tindak Lanjut' }}
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($task->status === 'menunggu verifikasi')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            @endif
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 2. Daftar Laporan (Tabel) dan Aksi Utama --}}
            <div class="bg-white shadow-xl shadow-gray-100/50 rounded-[32px] overflow-hidden border border-gray-100">
                <div class="p-6 sm:p-8">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b border-gray-50">
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Riwayat Laporan</h2>
                            <p class="text-xs text-gray-500 mt-1">Daftar semua bahaya yang Anda laporkan atau ditugaskan
                                kepada Anda (PIC).</p>
                        </div>

                        <div class="flex gap-3 mt-4 md:mt-0 w-full md:w-auto">
                            <a href="{{ route('karyawan.maps.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 6.75V15m6-6v8.25m.75 3.3-5.625-2.25L5.25 17.25V6.75L10.125 4.5l5.625 2.25L20.625 4.5V15l-4.875 2.25z">
                                    </path>
                                </svg>
                                Peta Risiko
                            </a>

                            <a href="{{ route('karyawan.hazards.create') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Laporan
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter --}}
                    <form id="filter-form" method="GET" action="{{ route('karyawan.dashboard') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            {{-- Search Input --}}
                            <div class="col-span-1 md:col-span-8 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search"
                                    placeholder="Cari berdasarkan ID, deskripsi, atau area..."
                                    value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            </div>

                            {{-- Filter Status --}}
                            <div class="col-span-1 md:col-span-3">
                                <select name="status"
                                    class="w-full py-2.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu validasi" {{ request('status') == 'menunggu validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>
                                        Diproses</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                    </option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="menunggu verifikasi" {{ request('status') == 'menunggu verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui</option>
                                </select>
                            </div>

                            {{-- Search Button --}}
                            <div class="col-span-1 md:col-span-1">
                                <button type="submit"
                                    class="w-full py-2.5 bg-gray-800 text-white rounded-xl text-sm font-bold hover:bg-gray-900 transition-colors">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="overflow-hidden border border-gray-100 rounded-2xl">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        ID</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Pelapor</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Deskripsi Bahaya</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Area</th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Status</th>
                                    <th class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse ($hazards as $hazard)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="font-mono text-xs font-bold text-gray-500">#{{ $hazard->id }}</span>
                                            @if ($hazard->pic_id == Auth::id() && $hazard->user_id != Auth::id())
                                                <span
                                                    class="ml-1 text-[9px] font-black text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100 uppercase tracking-tighter">PIC</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::parse($hazard->tgl_observasi)->translatedFormat('d M Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $hazard->nama ?? ($hazard->pelapor->name ?? 'N/A') }}
                                            </div>
                                            @if(($hazard->pelapor->role ?? '') === 'magang')
                                                <div class="text-[9px] text-gray-400 italic">({{ $hazard->pelapor->name }})
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-600 line-clamp-1 font-medium">
                                                {{ $hazard->deskripsi_bahaya }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-md">{{ $hazard->area_gedung }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $statusClasses = [
                                                    'menunggu validasi' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                    'diproses' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'menunggu verifikasi' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                                ];
                                                $class = $statusClasses[$hazard->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $class }}">
                                                {{ $hazard->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('karyawan.hazards.show', $hazard->id) }}"
                                                class="text-gray-400 hover:text-red-600 font-bold text-xs transition-colors group inline-flex items-center">
                                                Detail
                                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-bold text-gray-900 mb-1">Belum ada laporan</p>
                                                <p class="text-xs text-gray-500">Mulai laporkan bahaya di sekitar Anda.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
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