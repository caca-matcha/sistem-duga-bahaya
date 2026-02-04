@php $currentMapId = 'start'; @endphp
@forelse ($locations as $location)
    @if ($location->map_id !== $currentMapId)
        @php $currentMapId = $location->map_id; @endphp
        <tr class="bg-gradient-to-r from-gray-50/95 to-white border-y border-gray-100 no-drag">
            <td colspan="7" class="px-5 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-1 h-4 bg-red-600 rounded-full"></div>
                            <div class="w-1 h-1 bg-red-600/20 rounded-full"></div>
                        </div>
                        <div class="flex items-center gap-3.5">
                            <div class="relative group/building">
                                <div
                                    class="absolute -inset-1 bg-red-500/10 rounded-xl blur-sm opacity-0 group-hover/building:opacity-100 transition duration-500">
                                </div>
                                <div
                                    class="relative p-2 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-[10px] font-black text-red-500/70 uppercase tracking-[0.2em] leading-none mb-1">Building
                                    Area</span>
                                <span class="text-[16px] font-black text-gray-900 uppercase tracking-tight leading-none">
                                    {{ $location->map?->name ?? 'TANPA MAPPING' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($location->map_id)
                        <a href="{{ route('she.locations.create', ['map_id' => $location->map_id]) }}"
                            class="inline-flex items-center gap-2.5 px-4 py-2 bg-white border border-gray-200 rounded-xl text-[11px] font-black text-red-600 uppercase tracking-widest hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm active:scale-95 group/btn">
                            <svg class="w-4 h-4 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Di Sini
                        </a>
                    @endif
                </div>
            </td>
        </tr>
    @endif
    <tr class="hover:bg-red-50/[0.03] transition-colors group cursor-default" data-id="{{ $location->id }}"
        data-map-id="{{ $location->map_id }}">
        <td class="px-3 py-2 align-middle">
            <div class="flex items-center gap-1.5">
                <!-- Drag Handle -->
                <div
                    class="drag-handle p-1 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg cursor-grab active:cursor-grabbing transition-all opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-12a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z">
                        </path>
                    </svg>
                </div>
                <div>
                    <div class="text-base font-bold text-gray-900 leading-tight group-hover:text-red-600 transition-colors">
                        {{ $location->name }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-3 py-2 align-middle w-1">
            <span
                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-black bg-white text-gray-600 uppercase border border-gray-200 tracking-tighter shadow-sm group-hover:border-red-100 group-hover:text-red-700 transition-colors whitespace-nowrap">
                {{ $location->type }}
            </span>
        </td>
        <td class="px-3 py-2 align-middle">
            <span
                class="text-sm font-mono font-bold text-gray-500 bg-gray-50/50 px-2 py-1 rounded-lg border border-gray-100 group-hover:bg-red-50/50 group-hover:border-red-100 transition-colors">
                {{ $location->location_id_string }}
            </span>
        </td>
        <td class="px-3 py-2 align-middle whitespace-nowrap">
            <div
                class="flex items-center gap-1.5 text-sm text-gray-500 font-semibold group-hover:text-gray-700 transition-colors">
                <div
                    class="w-5 h-5 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-400 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                {{ $location->map?->name ?? '-' }}
            </div>
        </td>
        <td class="px-3 py-2 align-middle w-1">
            <div class="flex items-center gap-1.5 whitespace-nowrap">
                <div
                    class="w-7 h-7 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center text-gray-500 text-[10px] font-black uppercase ring-2 ring-gray-50 group-hover:ring-red-50 transition-all">
                    {{ substr($location->creator->name ?? '?', 0, 1) }}
                </div>
                <span class="text-sm text-gray-500 font-bold group-hover:text-gray-700 transition-colors">
                    {{ $location->creator->name ?? 'Sistem' }}
                </span>
            </div>
        </td>
        <td class="px-3 py-2 align-middle whitespace-nowrap text-right text-sm font-medium">
            <div class="flex justify-end items-center space-x-2">
                <a href="{{ route('she.locations.edit', $location) }}"
                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all hover:shadow-sm"
                    title="Edit Data">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form action="{{ route('she.locations.destroy', $location) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all hover:shadow-sm"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-20 text-center">
            <div class="flex flex-col items-center">
                <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-gray-500 font-medium">Tidak ditemukan data lokasi yang tersedia.</p>
                <a href="{{ route('she.locations.create') }}"
                    class="text-red-600 text-sm font-bold mt-2 hover:underline">Mulai tambahkan lokasi baru</a>
            </div>
        </td>
    </tr>
@endforelse