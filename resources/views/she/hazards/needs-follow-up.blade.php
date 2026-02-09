<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Laporan Perlu Tindak Lanjut') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Laporan Terlambat (Overdue)</h3>
                </div>
                <div class="p-0">
                    @if ($overdueHazards->isEmpty())
                        <div class="p-4 text-center text-gray-500">Tidak ada laporan yang terlambat.</div>
                    @else
                        <ul class="divide-y divide-gray-50">
                            @foreach ($overdueHazards as $hazard)
                                <li>
                                    <a href="{{ route('she.hazards.show', $hazard) }}"
                                        class="p-4 hover:bg-red-50 transition-colors flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 line-clamp-1">
                                                {{ $hazard->deskripsi_bahaya }}</p>
                                            <p class="text-xs text-red-600 mt-1">
                                                ID: {{ $hazard->id }} &bull; Area: {{ $hazard->area_gedung }} &bull;
                                                Terlambat:
                                                {{ \Carbon\Carbon::parse($hazard->target_penyelesaian)->diffForHumans() }}
                                                ({{ \Carbon\Carbon::parse($hazard->target_penyelesaian)->format('d M Y') }})
                                            </p>
                                        </div>
                                        <svg class="w-5 h-5 text-red-400 flex-shrink-0 ml-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Laporan Akan Jatuh Tempo (Due Soon)</h3>
                </div>
                <div class="p-0">
                    @if ($dueSoonHazards->isEmpty())
                        <div class="p-4 text-center text-gray-500">Tidak ada laporan yang akan jatuh tempo.</div>
                    @else
                        <ul class="divide-y divide-gray-50">
                            @foreach ($dueSoonHazards as $hazard)
                                <li>
                                    <a href="{{ route('she.hazards.show', $hazard) }}"
                                        class="p-4 hover:bg-yellow-50 transition-colors flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 line-clamp-1">
                                                {{ $hazard->deskripsi_bahaya }}</p>
                                            <p class="text-xs text-yellow-600 mt-1">
                                                ID: {{ $hazard->id }} &bull; Area: {{ $hazard->area_gedung }} &bull;
                                                Jatuh Tempo:
                                                {{ \Carbon\Carbon::parse($hazard->target_penyelesaian)->diffForHumans() }}
                                                ({{ \Carbon\Carbon::parse($hazard->target_penyelesaian)->format('d M Y') }})
                                            </p>
                                        </div>
                                        <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 ml-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>