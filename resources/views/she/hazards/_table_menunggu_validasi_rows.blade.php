@php
    $highlight = function ($text, $search) {
        if (empty($search))
            return e($text);
        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="highlight">$1</mark>', e($text));
    };
@endphp

@forelse ($hazardsMenungguValidasi as $hazard)
    <tr onclick="if(event.target.type !== 'checkbox') window.location='{{ route('she.hazards.show', $hazard) }}'" class="hover:bg-gray-100 transition-colors cursor-pointer" :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
        <template x-if="selectionMode">
            <td class="p-4">
                <input type="checkbox" x-model="selectedHazards" value="{{ $hazard->id }}"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </td>
        </template>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-indigo-600">#{!! $highlight($hazard->id, $search ?? '') !!}</div>
            <div class="text-xs text-gray-500">{{ $hazard->tgl_observasi->translatedFormat('d M Y') }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {!! $highlight($hazard->NPK ?? ($hazard->pelapor->npk ?? '-'), $search ?? '') !!}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-gray-900">
                {!! $highlight($hazard->nama ?? ($hazard->pelapor->name ?? 'N/A'), $search ?? '') !!}
            </div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $hazard->pelapor->department ?? '-' }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 line-clamp-2 max-w-xs">
                {!! $highlight($hazard->deskripsi_bahaya ?? '-', $search ?? '') !!}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex flex-col items-center">
                <span
                    class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[11px] font-bold 
                                                            {{ $hazard->risk_score >= 15 ? 'bg-red-50 text-red-600 border border-red-100' : ($hazard->risk_score >= 8 ? 'bg-yellow-50 text-yellow-600 border border-yellow-100' : 'bg-green-50 text-green-600 border border-green-100') }}">
                    {{ $hazard->risk_score }}
                </span>
                <div class="text-[10px] text-gray-500 mt-1.5 font-medium leading-none">{{ $hazard->kategori_resiko }}</div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="p-4 text-center text-sm text-gray-500 italic" x-bind:colspan="selectionMode ? 6 : 5">
            Tidak ada laporan baru yang menunggu validasi.
        </td>
    </tr>
@endforelse