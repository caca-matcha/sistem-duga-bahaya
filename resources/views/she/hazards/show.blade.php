<x-app-layout>
    @section('page-title', '')

    {{-- HEADER & BREADCRUMB --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>

                <h2 class="font-bold text-2xl text-gray-900 tracking-tight flex items-center gap-3">
                    <span>Laporan Bahaya</span>
                    <span
                        class="bg-indigo-50 text-indigo-700 text-lg px-3 py-1 rounded-xl border border-indigo-100 font-bold">#{{ $hazard->id }}</span>
                </h2>
                <p class="text-gray-400 font-medium mt-1.5 tracking-tight uppercase text-[11px]">
                    Detail informasi, status penanganan, dan verifikasi temuan bahaya.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('she.hazards.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-[96%] mx-auto sm:px-6 lg:px-8">

            {{-- ALERT SUCCESS --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition
                    class="mb-6 bg-white border-l-4 border-green-500 p-4 shadow-md rounded-r-lg flex justify-between items-start">
                    <div class="flex gap-3">
                        <div class="p-2 bg-green-100 rounded-full shrink-0">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Berhasil!</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- LEFT COLUMN: MAIN CONTENT (8/12) --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- SECTION 1: PELAPOR & LOKASI --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg border border-blue-100/50">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="currentColor" fill-rule="evenodd"
                                        d="M19 9c0 5.2-7 13-7 13S5 14.2 5 9c0-3.9 3.1-7 7-7s7 3.1 7 7zm-7 2.5c1.4 0 2.5-1.1 2.5-2.5S13.4 6.5 12 6.5 9.5 7.6 9.5 9s1.1 2.5 2.5 2.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Pelapor & Lokasi</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                {{-- Kolom Kiri: Orang --}}
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama
                                            Pelapor</label>
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                                {{ substr($hazard->pelapor->name ?? 'U', 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900">
                                                    {{ $hazard->pelapor->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-xs text-gray-500">NPK: {{ $hazard->NPK }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Departemen</label>
                                        <div
                                            class="text-sm font-medium text-gray-800 bg-gray-50 px-3 py-2 rounded-md border border-gray-100 inline-block">
                                            {{ $hazard->dept }}
                                        </div>
                                    </div>
                                    <label
                                        class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal
                                        Observasi</label>
                                    <div class="flex items-center text-gray-700 font-medium">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->translatedFormat('l, d F Y') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Lokasi --}}
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 space-y-3">
                                <div>
                                    <label class="text-xs text-gray-500 block">Lokasi Lengkap</label>
                                    <span
                                        class="font-semibold text-gray-900">{{ collect([$hazard->area_gedung, $hazard->area_name])->filter()->join(' -> ') }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs text-gray-500 block">Tipe Area</label>
                                        <span class="text-sm font-medium text-gray-800">{{ $hazard->area_type }}</span>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 block">Kode Line</label>
                                        <span class="text-sm font-medium text-gray-800">{{ $hazard->area_id }}</span>
                                    </div>
                                </div>
                                @if($hazard->lokasi_detail_manual)
                                    <div class="pt-2 mt-2 border-t border-gray-200">
                                        <label class="text-xs text-yellow-600 font-bold block mb-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Lokasi Spesifik
                                        </label>
                                        <p class="text-sm text-gray-700 italic">"{{ $hazard->lokasi_detail_manual }}"
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: DETAIL BAHAYA --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div class="p-2 bg-red-50 text-red-600 rounded-lg border border-red-100/50">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M12 2.2c-.6 0-1.1.3-1.4.8L1.4 18.5c-.3.5-.3 1.1 0 1.6.3.5.8.8 1.4.8h18.3c.6 0 1.1-.3 1.4-.8.3-.5.3-1.1 0-1.6L13.4 3c-.3-.5-.8-.8-1.4-.8zM11 8h2v5h-2V8zm1 9c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Analisa & Deskripsi Bahaya</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Badges Kategori --}}
                            <div class="flex flex-wrap gap-4">
                                <div
                                    class="bg-white border border-gray-200 rounded-lg p-3 flex-1 min-w-[200px] shadow-sm">
                                    <span class="text-xs text-gray-500 uppercase font-bold">Faktor Penyebab</span>
                                    <div class="font-semibold text-gray-800 mt-1">{{ $hazard->faktor_penyebab }}</div>
                                </div>
                                <div
                                    class="bg-white border border-gray-200 rounded-lg p-3 flex-1 min-w-[200px] shadow-sm">
                                    <span class="text-xs text-gray-500 uppercase font-bold">Kategori STOP6</span>
                                    <div class="font-semibold text-gray-800 mt-1">{{ $hazard->kategori_stop6 }}</div>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Deskripsi Bahaya</label>
                                <div
                                    class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-700 leading-relaxed text-sm">
                                    {{ $hazard->deskripsi_bahaya }}
                                </div>
                            </div>

                            {{-- Usulan --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 flex items-center gap-2">
                                    Usulan Penanggulangan
                                    <span
                                        class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Saran
                                        Pelapor</span>
                                </label>
                                <div
                                    class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-blue-900 leading-relaxed text-sm">
                                    {{ $hazard->ide_penanggulangan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: HASIL VALIDASI (Only if Validated) --}}
                    @if($hazard->status != 'menunggu validasi')
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
                            {{-- Color Strip --}}
                            <div
                                class="absolute top-0 left-0 bottom-0 w-1 {{ $hazard->status == 'ditolak' ? 'bg-red-500' : 'bg-green-500' }}">
                            </div>

                            <div
                                class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 pl-7 flex justify-between items-center">
                                <h3 class="text-lg font-bold text-gray-800">Tindakan & Validasi SHE</h3>
                                @if($hazard->ditangani_oleh)
                                    <div class="text-xs text-right">
                                        <span class="text-gray-500 block">Validator</span>
                                        <span class="font-bold text-gray-700">{{ $hazard->ditanganiOleh->name }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 pl-7">
                                @if ($hazard->status == 'ditolak')
                                    <div class="flex gap-4 p-4 bg-red-50 rounded-lg border border-red-100">
                                        <svg class="w-8 h-8 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <h4 class="font-bold text-red-800 mb-1">Laporan Ditolak</h4>
                                            <p class="text-sm text-red-700">{{ $hazard->alasan_penolakan }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="text-xs text-gray-500 font-bold uppercase mb-1 block">Tindakan
                                                    Perbaikan</label>
                                                <div
                                                    class="p-3 bg-green-50 rounded border border-green-100 text-sm text-green-900 min-h-[80px]">
                                                    {{ $hazard->tindakan_perbaikan }}
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500 font-bold uppercase mb-1 block">Upaya
                                                    Terpilih</label>
                                                <div class="p-3 bg-white rounded border border-gray-200 text-sm min-h-[80px]">
                                                    @php
                                                        $upaya = is_array($hazard->upaya_penanggulangan) ? $hazard->upaya_penanggulangan : (empty($hazard->upaya_penanggulangan) ? [] : ['lain-lain' => $hazard->upaya_penanggulangan]);
                                                    @endphp
                                                    <div class="flex flex-wrap gap-2">
                                                        @forelse ($upaya as $key => $value)
                                                            @if(!empty($value))
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 rounded bg-indigo-50 text-indigo-700 text-xs font-medium border border-indigo-100">
                                                                    {{ ucfirst($key) }}: {{ $value }}
                                                                </span>
                                                            @endif
                                                        @empty
                                                            <span class="text-gray-400 italic">Tidak ada data.</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($hazard->status == 'selesai')
                                            <div
                                                class="flex items-center gap-2 text-green-700 bg-green-50 p-3 rounded-lg border border-green-200 justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-bold text-sm">Laporan Selesai & Ditutup pada
                                                    {{ \Carbon\Carbon::parse($hazard->report_selesai)->format('d M Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- RIGHT COLUMN: SIDEBAR (4/12) --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">

                    {{-- Foto Bukti (Awal) - Hanya tampil jika ada foto, atau jika belum Selesai --}}
                    @if($hazard->foto_bukti || $hazard->status != 'selesai')
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                                <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                    </svg>
                                    Bukti Temuan Awal
                                </h3>
                            </div>
                            <div class="p-4">
                                @if ($hazard->foto_bukti)
                                    <div
                                        class="group relative rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                        <img src="{{ route('files.public', ['path' => $hazard->foto_bukti]) }}"
                                            class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105 cursor-zoom-in"
                                            onclick="window.open(this.src, '_blank')"
                                            onerror="this.onerror=null; this.src='https://placehold.co/600x400/CCCCCC/666666?text=Foto+Bukti+Tidak+Ditemukan';"
                                            alt="Bukti Hazard">
                                        <div
                                            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors pointer-events-none">
                                        </div>
                                    </div>
                                    <p class="text-xs text-center text-gray-400 mt-2">Klik foto untuk melihat ukuran penuh
                                    </p>
                                @else
                                    <div
                                        class="h-48 flex flex-col items-center justify-center bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl text-gray-400">
                                        <svg class="w-12 h-12 mb-2 opacity-50 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span class="text-sm font-medium">Tidak ada foto dilampirkan</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Bukti Penyelesaian (Jika Selesai) --}}
                    @if($hazard->status == 'selesai' && $hazard->foto_bukti_penyelesaian)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-green-100">
                            <div class="px-6 py-4 border-b border-green-50 bg-green-50/50 text-green-800">
                                <h3 class="font-bold text-lg flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Bukti Penyelesaian
                                </h3>
                            </div>
                            <div class="p-4">
                                @php
                                    $filePath = $hazard->foto_bukti_penyelesaian;
                                    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                                    $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                                    // Ambil nama file asli (buang timestamp awalan if exists)
                                    $displayName = basename($filePath);
                                    if (strpos($displayName, '_') !== false) {
                                        $parts = explode('_', $displayName, 2);
                                        if (is_numeric($parts[0])) {
                                            $displayName = $parts[1];
                                        }
                                    }
                                @endphp

                                @if($isImg)
                                    <div
                                        class="group relative rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 border border-green-200">
                                        <img src="{{ route('files.public', ['path' => $filePath]) }}"
                                            class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105 cursor-zoom-in"
                                            onclick="window.open(this.src, '_blank')" alt="Bukti Penyelesaian">
                                        <div
                                            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors pointer-events-none">
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('files.public', ['path' => $filePath]) }}" target="_blank"
                                        class="flex flex-col items-center justify-center p-8 bg-green-50 border-2 border-dashed border-green-200 rounded-xl text-green-700 hover:bg-green-100 transition-colors group">
                                        <div class="relative">
                                            <svg class="w-16 h-16 mb-2 text-green-600 group-hover:scale-110 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span
                                                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/3 bg-white text-[9px] font-black px-1 rounded border border-green-200 uppercase">{{ $ext }}</span>
                                        </div>
                                        <span class="text-sm font-bold uppercase tracking-wider">LIHAT DOKUMEN</span>
                                        <span
                                            class="text-[10px] text-green-600/70 mt-1 truncate max-w-full italic font-medium">{{ $displayName }}</span>
                                    </a>
                                @endif
                                <p class="text-[10px] text-center text-gray-400 mt-2 italic">* Bukti yang diunggah saat
                                    verifikasi selesai</p>
                            </div>
                        </div>
                    @endif

                    {{-- CARD STATUS & RISK --}}
                    <div class="bg-white rounded-xl shadow-lg border border-indigo-50 overflow-hidden">
                        {{-- Status Header --}}
                        <div class="p-6 border-b border-gray-100 text-center bg-gray-50">
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Status
                                Saat Ini</span>
                            @php
                                $statusColors = [
                                    'menunggu validasi' => 'bg-yellow-100 text-yellow-800 border-yellow-200 ring-yellow-500',
                                    'diproses' => 'bg-blue-100 text-blue-800 border-blue-200 ring-blue-500',
                                    'selesai' => 'bg-green-100 text-green-800 border-green-200 ring-green-500',
                                    'ditolak' => 'bg-red-100 text-red-800 border-red-200 ring-red-500',
                                ];
                                $statusClass = $statusColors[$hazard->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span
                                class="inline-block px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wide border {{ $statusClass }}">
                                {{ $hazard->status }}
                            </span>
                        </div>

                        {{-- Risk Score (Only if validated) --}}
                        @if ($hazard->status != 'menunggu validasi' && $hazard->status != 'ditolak')
                            @php
                                $finalRiskScore = $hazard->final_tingkat_keparahan * $hazard->final_kemungkinan_terjadi;
                                $riskColor = $finalRiskScore <= 5 ? 'text-green-600' : ($finalRiskScore <= 10 ? 'text-yellow-600' : ($finalRiskScore <= 15 ? 'text-orange-600' : 'text-red-600'));
                                $riskBg = $finalRiskScore <= 5 ? 'bg-green-50' : ($finalRiskScore <= 10 ? 'bg-yellow-50' : ($finalRiskScore <= 15 ? 'bg-orange-50' : 'bg-red-50'));
                            @endphp
                            <div class="p-6 {{ $riskBg }}">
                                <div class="text-center">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Skor Risiko Final</span>
                                    <div class="text-5xl font-black {{ $riskColor }} mt-2">{{ $finalRiskScore }}</div>
                                    <div class="text-xs text-gray-600 mt-1 font-medium">
                                        Severity: {{ $hazard->final_tingkat_keparahan }} × Probability:
                                        {{ $hazard->final_kemungkinan_terjadi }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Action Buttons --}}
                        @if (!in_array($hazard->status, ['selesai', 'ditolak']))
                            <div class="p-4 bg-white border-t border-gray-100">
                                @if ($hazard->status == 'menunggu validasi')
                                    <div class="grid grid-cols-2 gap-3">
                                        <a href="{{ route('she.hazards.diprosesForm', $hazard) }}"
                                            class="col-span-2 flex justify-center items-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold text-sm transition shadow-md">
                                            Validasi Sekarang
                                        </a>
                                        <a href="{{ route('she.hazards.tolakForm', $hazard) }}"
                                            class="col-span-2 flex justify-center items-center px-4 py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-lg font-medium text-sm transition">
                                            Tolak Laporan
                                        </a>
                                    </div>
                                @elseif ($hazard->status == 'diproses')
                                    <a href="{{ route('she.hazards.selesaiForm', $hazard) }}"
                                        class="w-full flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold text-sm transition shadow-md">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7">
                                            </path>
                                        </svg>
                                        Tandai Selesai
                                    </a>
                                @elseif ($hazard->status == 'menunggu verifikasi')
                                    <div class="grid grid-cols-2 gap-3 w-full">
                                        <form action="{{ route('she.hazards.updateStatus', $hazard) }}" method="POST"
                                            class="w-full">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit"
                                                class="w-full flex justify-center items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold text-sm transition shadow-md"
                                                onclick="return confirm('Apakah Anda yakin verifikasi pekerjaan ini selesai?')">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Verifikasi Selesai
                                            </button>
                                        </form>
                                        <form action="{{ route('she.hazards.updateStatus', $hazard) }}" method="POST"
                                            class="w-full">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="diproses">
                                            <button type="submit"
                                                class="w-full flex justify-center items-center px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-semibold text-sm transition shadow-md"
                                                onclick="return confirm('Kembalikan ke PIC untuk revisi?')">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                                Revisi (Kembali ke PIC)
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- CARD TIMELINE --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                            <h3 class="text-sm font-bold text-gray-700">Linimasa Progres</h3>
                        </div>
                        <div class="p-5">
                            @php
                                // Reuse logic from original
                                $isSelesai = $hazard->status == 'selesai';
                                $isDitolak = $hazard->status == 'ditolak';
                                $steps = [];
                                $steps[] = ['title' => 'Laporan Dibuat', 'date' => $hazard->created_at, 'active' => true, 'icon' => 'check'];

                                if ($hazard->ditangani_pada) {
                                    $steps[] = [
                                        'title' => $isDitolak ? 'Ditolak SHE' : 'Divalidasi SHE',
                                        'date' => $hazard->ditangani_pada,
                                        'active' => true,
                                        'icon' => $isDitolak ? 'x' : 'check',
                                        'color' => $isDitolak ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600',
                                    ];
                                } else {
                                    $steps[] = ['title' => 'Validasi SHE', 'date' => null, 'active' => false, 'icon' => 'clock'];
                                }

                                if ($isSelesai) {
                                    $steps[] = ['title' => 'Selesai', 'date' => $hazard->report_selesai, 'active' => true, 'icon' => 'check', 'color' => 'bg-green-100 text-green-600'];
                                } elseif (!$isDitolak) {
                                    $steps[] = ['title' => 'Penyelesaian', 'date' => null, 'active' => false, 'icon' => 'clock'];
                                }
                            @endphp

                            <div class="relative pl-4 border-l-2 border-gray-200 space-y-8 my-2">
                                @foreach($steps as $step)
                                    <div class="relative">
                                        <div class="absolute -left-[21px] bg-white pt-1">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ $step['active'] ? ($step['color'] ?? 'bg-indigo-100 text-indigo-600 border-indigo-200') : 'bg-gray-50 text-gray-300 border-gray-200' }}">
                                                @if(($step['icon'] ?? '') == 'x')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                @elseif(($step['icon'] ?? '') == 'clock')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="pl-4">
                                            <h4
                                                class="text-sm font-bold {{ $step['active'] ? 'text-gray-800' : 'text-gray-400' }}">
                                                {{ $step['title'] }}
                                            </h4>
                                            @if($step['date'])
                                                <span
                                                    class="text-xs text-gray-500 block mt-0.5">{{ \Carbon\Carbon::parse($step['date'])->format('d M Y, H:i') }}</span>
                                            @else
                                                <span class="text-xs text-gray-400 italic block mt-0.5">Menunggu...</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Target Box --}}
                    @if($hazard->status == 'diproses' && $hazard->target_penyelesaian)
                        @php
                            $target = \Carbon\Carbon::parse($hazard->target_penyelesaian);
                            $diff = now()->diffInDays($target, false);
                            $isLate = $diff < 0;
                        @endphp
                        <div
                            class="rounded-xl border {{ $isLate ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200' }} p-4 text-center">
                            <span
                                class="text-xs font-bold uppercase {{ $isLate ? 'text-red-600' : 'text-blue-600' }}">Target
                                Penyelesaian</span>
                            <div class="font-bold text-gray-800 text-lg mt-1">{{ $target->format('d M Y') }}</div>
                            <div class="text-xs mt-1 {{ $isLate ? 'text-red-700 font-bold' : 'text-blue-700' }}">
                                {{ $isLate ? 'Terlambat ' . abs(round($diff)) . ' Hari' : round($diff) . ' Hari Lagi' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>