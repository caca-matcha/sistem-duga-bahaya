@forelse ($hazardsDiproses as $hazard)
    <tr class="hover:bg-gray-50" :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
        <template x-if="selectionMode">
            <td class="p-4">
                <input type="checkbox" x-model="selectedHazards" value="{{ $hazard->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </td>
        </template>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500">#{{ $hazard->id }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $hazard->nama }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                    {{ substr($hazard->ditanganiOleh?->name ?? '?', 0, 2) }}
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ $hazard->ditanganiOleh?->name ?? 'Belum ditentukan' }}</div>
                    <div class="text-xs text-gray-500">SHE Team</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <svg class="mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3" />
                </svg>
                Diproses
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="text-sm text-gray-900 font-bold">{{ $hazard->risk_score }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="{{ route('she.hazards.show', $hazard) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold transition-colors">Lihat Detail</a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500 italic" x-bind:colspan="selectionMode ? 8 : 7">
            Tidak ada laporan yang sedang diproses.
        </td>
    </tr>
@endforelse