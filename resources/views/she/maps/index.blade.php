<x-app-layout>
    @section('page-title', '')
    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-6 h-6 text-red-600" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M15 19l-6-2.1 -5.2 2.6c-.4.2-.9.1-1.2-.2C2.1 19 2 18.6 2 18.2V5.6c0-.4.2-.7.5-.9.3-.2.7-.3 1.1-.1L9 7.1l6-2.1 5.2 2.6c.4.2.9.1 1.2-.2.4-.3.5-.7.5-1.1v12.6c0 .4-.2.7-.5.9-.3.2-.7.3-1.1.1L15 19z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight capitalize leading-none">Maps
                            Management</h2>
                        <p class="text-gray-400 font-medium mt-1.5 tracking-tight uppercase text-[12px]">
                            Kelola layout peta risiko untuk pabrik dan gedung operasional.</p>
                    </div>
                </div>

                <div
                    class="hidden lg:flex items-center gap-4 px-5 py-2.5 bg-white/30 backdrop-blur-sm border border-white/20 rounded-2xl">
                    <div class="flex items-center gap-2">
                        <span class="flex h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Editor Active</span>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts Section -->
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 border border-green-200 shadow-sm"
                    role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error') || $errors->any())
                <div class="flex p-4 mb-4 text-red-800 rounded-lg bg-red-50 border border-red-200 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3">
                        <span class="font-bold text-sm">Terjadi Kesalahan!</span>
                        <ul class="mt-1 ml-4 list-disc list-inside text-xs">
                            @if(session('error'))
                            <li>{{ session('error') }}</li> @endif
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Section 1: Pabrik Maps (Multiple Maps) -->
            <section>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4">
                    <div>
                        <div
                            class="flex items-center gap-2.5 mb-1.5 bg-gray-100/50 w-fit px-3 py-1 rounded-xl border border-gray-200/30">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="text-sm font-black text-gray-500 uppercase tracking-widest">Layout Utama Pabrik
                            </h3>
                        </div>
                        <p class="text-[13px] text-gray-400 font-medium">Kelola layout peta risiko utama untuk berbagai
                            lokasi pabrik.</p>
                    </div>
                    <a href="{{ route('she.maps.create', ['type' => 'Pabrik']) }}"
                        class="inline-flex items-center px-6 py-3 bg-gray-900 border-b-4 border-gray-700 text-white text-xs font-black rounded-xl shadow-lg hover:bg-gray-800 hover:-translate-y-0.5 active:translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        TAMBAH PABRIK
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                    @if (!$existingPabrikMap)
                        <div class="p-10 text-center">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4 text-yellow-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Peta Pabrik</h4>
                            <p class="text-gray-500 max-w-sm mx-auto mb-6">Buat layout utama untuk pabrik. Anda dapat
                                membuat beberapa peta pabrik sesuai kebutuhan.</p>
                            <a href="{{ route('she.maps.create', ['type' => 'Pabrik']) }}"
                                class="inline-flex items-center px-6 py-3 bg-gray-900 text-white font-bold rounded-lg hover:bg-gray-800 transition shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Inisialisasi Peta Pabrik
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100 shadow-sm" id="pabrik-maps-container">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Map Detail
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Tipe</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Ukuran
                                            Grid</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Aksi
                                            Pintas
                                        </th>
                                    </tr>
                                </thead>
                                @forelse ($pabrikMaps as $pabrikMap)
                                    <tbody data-id="{{ $pabrikMap->id }}"
                                        class="sortable-bundle bg-white divide-y divide-gray-100 border-b border-gray-100 last:border-0 hover:bg-gray-50/30 transition-colors">
                                        <tr class="group">
                                            <td class="px-4 py-5 w-10">
                                                <div
                                                    class="cursor-move text-gray-300 hover:text-gray-500 transition-colors drag-handle">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 8h16M4 16h16" />
                                                    </svg>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-500 transition-colors border border-gray-100">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900 leading-tight">
                                                            {{ $pabrikMap->name }}
                                                        </div>
                                                        <div
                                                            class="text-[10px] font-black text-gray-300 uppercase tracking-widest mt-0.5">
                                                            ID: #MAP-0{{ $pabrikMap->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex flex-col gap-1">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-red-50 text-red-600 border border-red-100 w-fit">Pabrik</span>
                                                    @if($pabrikMap->is_primary)
                                                        <span
                                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-green-50 text-green-600 border border-green-100 shadow-sm animate-pulse w-fit"
                                                            title="Peta Utama (Aktif)">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <span
                                                                class="text-[9px] font-black uppercase tracking-widest">Utama</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <span
                                                    class="px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-xs font-black tracking-tighter border border-gray-100">{{ $pabrikMap->rows }}
                                                    <span class="text-gray-300 mx-0.5">×</span> {{ $pabrikMap->cols }}</span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end gap-1.5">
                                                    <form action="{{ route('she.maps.set-primary', $pabrikMap->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="inline-flex items-center p-2.5 rounded-xl border transition-all duration-200 shadow-sm {{ $pabrikMap->is_primary ? 'bg-red-600 border-red-600 text-white' : 'bg-white border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-100' }}"
                                                            title="{{ $pabrikMap->is_primary ? 'Peta Utama (Terpin)' : 'Jadikan Peta Utama' }}">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M16 11V4h1a1 1 0 0 0 0-2H7a1 1 0 0 0 0 2h1v7c0 2.2-1.8 4-4 4v2h7v5l1 1 1-1v-5h7v-2c-2.2 0-4-1.8-4-4z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('she.maps.show', $pabrikMap->id) }}"
                                                        class="inline-flex items-center p-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm"
                                                        title="Grid Editor">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('she.maps.edit', $pabrikMap->id) }}"
                                                        class="inline-flex items-center p-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm"
                                                        title="Edit Properties">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <div class="relative inline-block group/export">
                                                        <button
                                                            class="p-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl group-hover/export:bg-gray-50 shadow-sm transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                            </svg>
                                                        </button>
                                                        <div
                                                            class="absolute right-0 bottom-full mb-2 hidden group-hover/export:block bg-white border border-gray-100 rounded-2xl shadow-2xl z-20 w-48 overflow-hidden animate-in fade-in slide-in-from-bottom-2 duration-200">
                                                            <a href="{{ route('she.maps.export', $pabrikMap->id) }}"
                                                                class="flex items-center gap-3 px-4 py-3 text-xs hover:bg-blue-50 text-blue-700 font-bold transition-colors border-b border-gray-50">
                                                                <div
                                                                    class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2.5"
                                                                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                                    </svg>
                                                                </div>
                                                                JSON BACKUP
                                                            </a>
                                                            <a href="{{ route('she.maps.export-risk-excel', $pabrikMap->id) }}"
                                                                class="flex items-center gap-3 px-4 py-3 text-xs hover:bg-green-50 text-green-700 font-bold transition-colors">
                                                                <div
                                                                    class="w-6 h-6 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2.5"
                                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                </div>
                                                                RISK REPORT
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('she.maps.destroy', $pabrikMap->id) }}" method="POST"
                                                        onsubmit="return confirm('Hapus peta ini beserta seluruh data cell-nya?');"
                                                        class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2.5 bg-red-50 border border-red-100 text-red-400 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- Visual Preview for Pinned Map --}}
                                        @if ($pabrikMap->is_primary)
                                            <tr class="bg-gray-50/30">
                                                <td colspan="5" class="px-6 py-8">
                                                    <div class="flex items-center gap-8 pl-4">
                                                        <div
                                                            class="relative w-64 h-40 rounded-2xl overflow-hidden shadow-md border-2 border-white ring-1 ring-gray-200 bg-white group hover:shadow-xl transition-all duration-300">
                                                            @if($pabrikMap->background_image)
                                                                <img src="{{ route('files.public', ['path' => $pabrikMap->background_image]) }}"
                                                                    class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity duration-500" />
                                                            @else
                                                                <div class="w-full h-full bg-gray-50 flex items-center justify-center">
                                                                    <svg class="w-10 h-10 text-gray-200" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                </div>
                                                            @endif

                                                            {{-- Mini Grid Overlay with Colors --}}
                                                            <div class="absolute inset-0 grid gap-[1px] p-[1px]"
                                                                style="grid-template-rows: repeat({{ $pabrikMap->rows }}, 1fr); grid-template-columns: repeat({{ $pabrikMap->cols }}, 1fr);">
                                                                @php
                                                                    $cellMapPabrik = $pabrikMap->cells->keyBy(fn($c) => $c->row_index . '-' . $c->col_index);
                                                                @endphp
                                                                @for ($r = 0; $r < $pabrikMap->rows; $r++)
                                                                    @for ($c = 0; $c < $pabrikMap->cols; $c++)
                                                                        @php $pCell = $cellMapPabrik->get("$r-$c"); @endphp
                                                                        <div class="border-[0.5px] border-red-500/10 transition-colors duration-500"
                                                                            style="{{ $pCell && $pCell->risk_score > 0 ? 'background-color: ' . $pCell->zone_color . '99' : '' }}">
                                                                        </div>
                                                                    @endfor
                                                                @endfor
                                                            </div>

                                                            <div
                                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/10">
                                                                <a href="{{ route('she.maps.show', $pabrikMap->id) }}"
                                                                    class="px-4 py-2 bg-white text-[10px] font-black uppercase tracking-widest text-red-600 rounded-xl shadow-lg border border-red-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                                                    Buka Editor
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 max-w-lg">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <span
                                                                    class="flex h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                                                                <h4
                                                                    class="text-[11px] font-black text-red-600 uppercase tracking-[0.2em]">
                                                                    Peta Aktif Pabrik</h4>
                                                            </div>
                                                            <p class="text-sm text-gray-900 font-bold mb-1 leading-tight">
                                                                {{ $pabrikMap->name }}
                                                            </p>
                                                            <p class="text-[13px] text-gray-400 font-medium leading-relaxed">Peta
                                                                ini menyambungkan seluruh gedung operasional. Grid berukuran <span
                                                                    class="text-gray-900 font-bold">{{ $pabrikMap->rows }}x{{ $pabrikMap->cols }}</span>
                                                                digunakan untuk menempatkan titik gedung utama di dashboard.</p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                @empty
                                    <tbody class="bg-white">
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <p class="text-gray-400 text-sm">Tidak ditemukan peta pabrik.</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforelse
                            </table>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Section 2: Gedung Maps -->
            <section>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4 pt-4">
                    <div>
                        <div
                            class="flex items-center gap-2.5 mb-1.5 bg-red-50 w-fit px-3 py-1 rounded-xl border border-red-100">
                            <svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M3 10V4c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v16c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2v-6c0-1.1.9-2 2-2h14v2H5c-1.1 0-2 .9-2 2" />
                            </svg>
                            <h3 class="text-sm font-black text-red-700 uppercase tracking-widest">Daftar Peta Per Gedung
                            </h3>
                        </div>
                        <p class="text-[13px] text-gray-400 font-medium tracking-tight">Detail layout spesifik untuk
                            setiap
                            gedung operasional yang terdaftar.</p>
                    </div>
                    <a href="{{ route('she.maps.create', ['type' => 'Gedung']) }}"
                        class="inline-flex items-center px-6 py-3 bg-red-600 border-b-4 border-red-800 text-white text-xs font-black rounded-xl shadow-lg hover:bg-red-700 hover:-translate-y-0.5 active:translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        TAMBAH GEDUNG
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="gedung-maps-container">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama Peta
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Parent /
                                        Lokasi</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Grid
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            @forelse ($gedungMaps as $map)
                                <tbody data-id="{{ $map->id }}"
                                    class="sortable-bundle bg-white divide-y divide-gray-100 border-b border-gray-100 last:border-0 hover:bg-gray-50/30 transition-colors">
                                    <tr class="group">
                                        <td class="px-4 py-5 w-10">
                                            <div
                                                class="cursor-move text-gray-300 hover:text-gray-500 transition-colors drag-handle">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 8h16M4 16h16" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-2 h-2 rounded-full bg-red-400 shadow-[0_0_8px_rgba(248,113,113,0.5)] group-hover:scale-125 transition-transform">
                                                </div>
                                                <span
                                                    class="text-sm font-bold text-gray-800 leading-tight">{{ $map->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            @if($map->parent)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-gray-50 text-gray-500 border border-gray-100">
                                                    {{ $map->parent->name }}
                                                </span>
                                            @else
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-widest text-gray-300 italic">Global
                                                    Area</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span
                                                class="px-2 py-1 bg-gray-50 text-gray-500 rounded-lg text-xs font-black tracking-tighter border border-gray-100">{{ $map->rows }}
                                                <span class="text-gray-200 mx-0.5">×</span> {{ $map->cols }}</span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center gap-1.5 transition-opacity">
                                                <a href="{{ route('she.maps.show', $map->id) }}"
                                                    class="p-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200 shadow-sm"
                                                    title="Editor">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('she.maps.edit', $map->id) }}"
                                                    class="p-2.5 bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 rounded-xl shadow-sm transition-all duration-200"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('she.maps.destroy', $map->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus peta gedung ini?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2.5 bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:bg-red-50 hover:border-red-100 rounded-xl shadow-sm transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- Visual Preview for Pinned Gedung --}}
                                    @if($map->is_primary)
                                        <tr class="bg-gray-50/30">
                                            <td colspan="5" class="px-6 py-8">
                                                <div class="flex items-center gap-8 pl-4">
                                                    <div
                                                        class="relative w-64 h-40 rounded-2xl overflow-hidden shadow-md border-2 border-white ring-1 ring-gray-200 bg-white group hover:shadow-xl transition-all duration-300">
                                                        @if($map->background_image)
                                                            <img src="{{ route('files.public', ['path' => $map->background_image]) }}"
                                                                class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity duration-500" />
                                                        @else
                                                            <div class="w-full h-full bg-gray-50 flex items-center justify-center">
                                                                <svg class="w-10 h-10 text-gray-200" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif

                                                        {{-- Mini Grid Overlay with Colors --}}
                                                        <div class="absolute inset-0 grid gap-[1px] p-[1px]"
                                                            style="grid-template-rows: repeat({{ $map->rows }}, 1fr); grid-template-columns: repeat({{ $map->cols }}, 1fr);">
                                                            @php
                                                                $cellMapGedung = $map->cells->keyBy(fn($c) => $c->row_index . '-' . $c->col_index);
                                                            @endphp
                                                            @for ($r = 0; $r < $map->rows; $r++)
                                                                @for ($c = 0; $c < $map->cols; $c++)
                                                                    @php $gCell = $cellMapGedung->get("$r-$c"); @endphp
                                                                    <div class="border-[0.5px] border-red-500/10 transition-colors duration-500"
                                                                        style="{{ $gCell && $gCell->risk_score > 0 ? 'background-color: ' . $gCell->zone_color . '99' : '' }}">
                                                                    </div>
                                                                @endfor
                                                            @endfor
                                                        </div>

                                                        <div
                                                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/10">
                                                            <a href="{{ route('she.maps.show', $map->id) }}"
                                                                class="px-4 py-2 bg-white text-[10px] font-black uppercase tracking-widest text-red-600 rounded-xl shadow-lg border border-red-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                                                Buka Editor
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 max-w-lg">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <span
                                                                class="flex h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                                                            <h4
                                                                class="text-[11px] font-black text-red-600 uppercase tracking-[0.2em]">
                                                                Peta Aktif Gedung</h4>
                                                        </div>
                                                        <p class="text-sm text-gray-900 font-bold mb-1 leading-tight">
                                                            {{ $map->name }}
                                                        </p>
                                                        <p class="text-[13px] text-gray-400 font-medium leading-relaxed">Layout
                                                            operasional aktif untuk gedung ini. Grid <span
                                                                class="text-gray-900 font-bold">{{ $map->rows }}x{{ $map->cols }}</span>
                                                            digunakan untuk pemetaan area spesifik laporan bahaya.</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            @empty
                                <tbody class="bg-white">
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <p class="text-gray-400 text-sm">Tidak ditemukan peta gedung.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reorderMaps = (elId) => {
                const el = document.getElementById(elId);
                if (!el) return;

                new Sortable(el, {
                    animation: 150,
                    handle: '.drag-handle',
                    draggable: '.sortable-bundle',
                    ghostClass: 'bg-red-50',
                    onEnd: function () {
                        const ids = Array.from(el.querySelectorAll('.sortable-bundle')).map(item => item.dataset.id);

                        axios.post('{{ route("she.maps.reorder") }}', { ids: ids })
                            .then(response => {
                                console.log('Reorder success');
                            })
                            .catch(error => {
                                console.error('Reorder error', error);
                                window.location.reload();
                            });
                    }
                });
            };

            reorderMaps('pabrik-maps-container');
            reorderMaps('gedung-maps-container');
        });
    </script>
</x-app-layout>