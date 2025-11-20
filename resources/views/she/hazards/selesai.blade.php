<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Selesaikan Laporan Bahaya') . ' #' . $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Laporan: {{ $hazard->deskripsi_bahaya }}</h3>
                    <p class="mb-6">Oleh: {{ $hazard->pelapor->name ?? 'N/A' }} - Area: {{ $hazard->area_gedung }}</p>

                    <p class="mb-4">Apakah Anda yakin ingin menandai laporan ini sebagai "Selesai"?</p>
                    <p class="text-sm text-gray-600 mb-6">Status laporan akan diperbarui menjadi 'selesai' dan tindakan akan dicatat.</p>

                    <form method="POST" action="{{ route('she.hazards.selesai', $hazard) }}">
                        @csrf
                        {{-- Future: Add fields for before-after photos, notes, etc. --}}

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('she.hazards.show', $hazard) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Tandai Selesai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
