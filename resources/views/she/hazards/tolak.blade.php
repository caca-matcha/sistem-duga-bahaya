<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tolak Laporan Bahaya') . ' #' . $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Detail Laporan Ringkas -->
                    <div class="border-b pb-4 mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Laporan Bahaya: <span class="text-red-600">{{ $hazard->area_gedung }}</span></h3>
                        <p class="mt-1 text-sm text-gray-700">Oleh: {{ $hazard->pelapor->name ?? 'N/A' }} - ID Laporan: #{{ $hazard->id }}</p>
                    </div>

                    <!-- Peringatan Penolakan -->
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    Anda akan menolak laporan bahaya ini. Harap berikan alasan yang jelas.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="mb-4 text-gray-700 font-medium">Deskripsi Bahaya:</p>
                    <p class="mb-6 italic p-3 bg-gray-100 rounded-md border border-gray-200">{{ $hazard->deskripsi_bahaya }}</p>

                    <!-- Form Penolakan -->
                    <form method="POST" action="{{ route('she.hazards.tolak', $hazard) }}">
                        @csrf
                        @method('PUT') <!-- Gunakan PUT/PATCH untuk update status -->
                        
                        <div class="mb-6">
                            <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea 
                                name="alasan_penolakan" 
                                id="alasan_penolakan" 
                                rows="5" 
                                required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" 
                                placeholder="Jelaskan secara rinci mengapa laporan ini ditolak (misalnya: duplikat laporan, bukan bahaya SHE, informasi tidak valid, dll.)"
                            ></textarea>
                            @error('alasan_penolakan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end border-t pt-4">
                            <a href="{{ route('she.hazards.show', $hazard) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Tolak Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>