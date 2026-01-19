@forelse ($hazardsMenungguValidasi as $hazard)
    <tr class="hover:bg-gray-50 transition-colors" :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
        <template x-if="selectionMode">
            <td class="p-4">
                <input type="checkbox" x-model="selectedHazards" value="{{ $hazard->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </td>
        </template>
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
            <a href="{{ route('she.hazards.show', $hazard) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                Review
                <svg class="ml-1.5 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="p-4 text-center text-sm text-gray-500 italic" x-bind:colspan="selectionMode ? 7 : 6">
            Tidak ada laporan baru yang menunggu validasi.
        </td>
    </tr>
@endforelse