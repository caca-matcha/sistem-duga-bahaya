<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tolak Laporan Bahaya #{{ $hazard->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto py-8 px-8">
            <div class="bg-white shadow-xl overflow-hidden">
                <div class="p-6 text-gray-900">

                    {{-- INFORMASI LAPORAN --}}
                    <h3 class="text-xl font-bold mb-6 pb-2 border-b">
                        Informasi Laporan Awal
                    </h3>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 mb-8">
                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Pelapor</dt>
                            <dd class="text-sm mt-1">{{ $hazard->pelapor->name ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Departemen</dt>
                            <dd class="text-sm mt-1">{{ $hazard->dept }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Tanggal Observasi</dt>
                            <dd class="text-sm mt-1">
                                {{ \Carbon\Carbon::parse($hazard->tgl_observasi)->format('d M Y') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Area Gedung</dt>
                            <dd class="text-sm mt-1">{{ $hazard->area_gedung }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Area Type</dt>
                            <dd class="text-sm mt-1">{{ $hazard->area_type }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-semibold text-gray-500">Area Name</dt>
                            <dd class="text-sm mt-1">{{ $hazard->area_name }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-semibold text-gray-500">Area ID</dt>
                            <dd class="text-sm mt-1">{{ $hazard->area_id }}</dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-semibold text-gray-500">Deskripsi Bahaya</dt>
                            <dd class="text-sm mt-2 p-3 bg-gray-50 rounded-md border border-gray-200">
                                {{ $hazard->deskripsi_bahaya }}
                            </dd>
                        </div>
                    </dl>

                    {{-- ERROR MESSAGE --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM PENOLAKAN --}}
                    <form method="POST" action="{{ route('she.hazards.updateStatus', $hazard) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="ditolak">

                        <h3 class="text-xl font-bold mb-6 pb-2 border-b mt-10">
                            Alasan Penolakan
                        </h3>

                        <div class="mb-6">
                            <label for="alasan_penolakan" class="block font-medium text-sm text-gray-700">
                                Tuliskan alasan mengapa laporan ini ditolak:
                            </label>

                            <textarea id="alasan_penolakan"
                                      name="alasan_penolakan"
                                      rows="4"
                                      class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                      required>{{ old('alasan_penolakan') }}</textarea>

                            @error('alasan_penolakan')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex items-center justify-end space-x-3 mt-8">

                            <a href="{{ route('she.hazards.show', $hazard) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-m font-semibold text-gray-700 hover:bg-gray-50 transition duration-150">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-m font-semibold rounded-md hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150">
                                Tolak Laporan
                            </button>

                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
