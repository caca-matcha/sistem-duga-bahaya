@php
    $highlight = function ($text, $search) {
        if (empty($search))
            return e($text);
        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="highlight">$1</mark>', e($text));
    };
@endphp

@forelse ($hazardsDiproses as $hazard)
    <tr class="hover:bg-gray-50" :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
        <template x-if="selectionMode">
            <td class="p-4">
                <input type="checkbox" x-model="selectedHazards" value="{{ $hazard->id }}"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </td>
        </template>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-gray-900">#{!! $highlight($hazard->id, $search ?? '') !!}</div>
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
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div
                    class="h-8 w-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                    {{ substr($hazard->pic?->name ?? '?', 0, 2) }}
                </div>
                <div class="ml-3">
                    <div class="text-sm font-bold text-gray-900">{{ $hazard->pic?->name ?? 'Belum ditentukan' }}
                    </div>
                    <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-tight">PIC / Penanggung Jawab</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 line-clamp-2 max-w-xs">
                {!! $highlight($hazard->deskripsi_bahaya ?? '-', $search ?? '') !!}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                <svg class="mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3" />
                </svg>
                Diproses
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex flex-col items-center">
                <span
                    class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[11px] font-bold 
                                                                        {{ $hazard->risk_score >= 15 ? 'bg-red-50 text-red-600 border border-red-100' : ($hazard->risk_score >= 8 ? 'bg-yellow-50 text-yellow-600 border border-yellow-100' : 'bg-green-50 text-green-600 border border-green-100') }}">
                    {{ $hazard->risk_score }}
                </span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="{{ route('she.hazards.show', $hazard) }}"
                class="text-indigo-600 hover:text-indigo-900 font-semibold transition-colors">Lihat Detail</a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500 italic" x-bind:colspan="selectionMode ? 9 : 8">
            Tidak ada laporan yang sedang diproses.
        </td>
    </tr>
@endforelse