@forelse ($locations as $location)
    <tr class="hover:bg-red-50/20 transition-colors group">
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 font-bold text-xs">
                    {{ substr($location->name, 0, 2) }}
                </div>
                <span class="text-sm font-bold text-gray-800">{{ $location->name }}</span>
            </div>
        </td>
        <td class="px-6 py-4">
            <code class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-md font-mono">{{ $location->location_id_string }}</code>
        </td>
        <td class="px-6 py-4">
            @php
                $typeColors = [
                    'Gedung' => 'bg-purple-100 text-purple-700',
                    'Area' => 'bg-red-100 text-red-700',
                    'Room' => 'bg-green-100 text-green-700'
                ];
                $colorClass = $typeColors[$location->type] ?? 'bg-gray-100 text-gray-700';
            @endphp
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $colorClass }}">
                {{ $location->type }}
            </span>
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                {{ $location->map?->name ?? '-' }}
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] text-gray-500 border border-white">
                    {{ substr($location->creator->name ?? '?', 0, 1) }}
                </div>
                <span class="text-xs text-gray-600">{{ $location->creator->name ?? 'N/A' }}</span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex justify-end items-center space-x-1 transition-opacity"> 
                <a href="{{ route('she.locations.edit', $location) }}" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Edit Data">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('she.locations.destroy', $location) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-20 text-center">
            <div class="flex flex-col items-center">
                <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-gray-500 font-medium">Tidak ditemukan data lokasi yang tersedia.</p>
                <a href="{{ route('she.locations.create') }}" class="text-red-600 text-sm font-bold mt-2 hover:underline">Mulai tambahkan lokasi baru</a>
            </div>
        </td>
    </tr>
@endforelse
