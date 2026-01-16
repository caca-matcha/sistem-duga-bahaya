@forelse ($hazardsSelesai as $hazard)
    <tr class="hover:bg-gray-50" :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
        <td class="p-4">
            <input type="checkbox" x-model="selectedHazards" value="{{ $hazard->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </td>
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
            <div class="flex items-center justify-end space-x-2">
                <a href="{{ route('she.hazards.show', $hazard) }}" class="text-gray-400 hover:text-indigo-600 transition-colors" title="Lihat Detail">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                <form action="{{ route('she.hazards.destroy', $hazard) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus Laporan">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 italic">
            Belum ada riwayat laporan selesai.
        </td>
    </tr>
@endforelse