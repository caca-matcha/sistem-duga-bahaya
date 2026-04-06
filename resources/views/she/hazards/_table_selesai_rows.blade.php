@php
    $highlight = function ($text, $search) {
        if (empty($search))
            return e($text);
        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="highlight">$1</mark>', e($text));
    };
@endphp

@forelse ($hazardsSelesai as $hazard)
    <tr onclick="if(!event.target.closest('form') && event.target.type !== 'checkbox') window.location='{{ route('she.hazards.show', $hazard) }}'" class="hover:bg-gray-100 transition-colors cursor-pointer" :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
        <template x-if="selectionMode">
            <td class="p-4">
                <input type="checkbox" x-model="selectedHazards" value="{{ $hazard->id }}"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </td>
        </template>
        <td class="px-3 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-indigo-600">#{!! $highlight($hazard->id, $search ?? '') !!}</div>
            <div class="text-xs text-gray-500">{{ $hazard->tgl_observasi->translatedFormat('d M Y') }}</div>
        </td>
        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
            {!! $highlight($hazard->NPK ?? ($hazard->pelapor->npk ?? '-'), $search ?? '') !!}</td>
        <td class="px-3 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-gray-900">
                {!! $highlight($hazard->nama ?? ($hazard->pelapor->name ?? 'N/A'), $search ?? '') !!}
            </div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $hazard->pelapor->department ?? '-' }}</div>
        </td>
        <td class="px-3 py-4">
            <div class="text-sm text-gray-900 line-clamp-2 max-w-xs">
                {!! $highlight($hazard->deskripsi_bahaya ?? '-', $search ?? '') !!}</div>
        </td>
        <td class="px-3 py-4 whitespace-nowrap text-center">
            <div class="flex items-center justify-center gap-2">
                @php
                    $initialRisk = ($hazard->tingkat_keparahan ?? 0) * ($hazard->kemungkinan_terjadi ?? 0);
                    $finalRisk = $hazard->risk_score ?? 0;
                @endphp
                {{-- Before --}}
                <span
                    class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[11px] font-bold 
                                        {{ $initialRisk >= 15 ? 'bg-red-50 text-red-600 border border-red-100' : ($initialRisk >= 8 ? 'bg-yellow-50 text-yellow-600 border border-yellow-100' : 'bg-green-50 text-green-600 border border-green-100') }}"
                    title="Skor Awal (Before)">
                    {{ $initialRisk }}
                </span>

                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>

                {{-- After --}}
                <span
                    class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[11px] font-bold 
                                        {{ $finalRisk >= 15 ? 'bg-red-50 text-red-600 border border-red-100' : ($finalRisk >= 8 ? 'bg-yellow-50 text-yellow-600 border border-yellow-100' : 'bg-green-50 text-green-600 border border-green-100') }}"
                    title="Skor Akhir (After)">
                    {{ $finalRisk }}
                </span>
            </div>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            @if($hazard->status == 'selesai')
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                    <svg class="mr-1.5 h-3 w-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Selesai
                </span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                    <svg class="mr-1.5 h-3 w-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Ditolak
                </span>
            @endif
        </td>
        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $hazard->ditangani_pada ? \Carbon\Carbon::parse($hazard->ditangani_pada)->locale('id')->translatedFormat('d M Y') : '-' }}
        </td>
        <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex items-center justify-end space-x-2">

                <form action="{{ route('she.hazards.destroy', $hazard) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus Laporan">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        <td colspan="8" class="px-3 py-10 text-center text-sm text-gray-500 italic" x-bind:colspan="selectionMode ? 9 : 8">
            Belum ada riwayat laporan selesai.
        </td>
    </tr>
@endforelse
