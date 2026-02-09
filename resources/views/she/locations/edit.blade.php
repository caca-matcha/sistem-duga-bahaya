<x-app-layout>
    @section('page-title', '')
    <x-slot name="header">
        <div class="flex items-center gap-3 py-1">
            <a href="{{ route('she.locations.index') }}"
                class="group inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-100 hover:bg-red-50 transition-all duration-200 shadow-sm"
                title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="flex items-center gap-2 text-sm font-bold uppercase tracking-[0.2em] text-gray-400">
                <span>Location</span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <h2 class="text-gray-900 font-black tracking-tight tracking-normal capitalize text-xl">
                    Edit Lokasi
                </h2>
            </div>
        </div>
    </x-slot>


    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                <div class="p-12">
                    <!-- Header Form -->
                    <div
                        class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-100 pb-8">
                        <div>
                            <div
                                class="flex items-center gap-3 mb-2 bg-gray-50 w-fit px-4 py-1.5 rounded-xl border border-gray-100">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em]">Informasi Lokasi
                                </h3>
                            </div>
                            <p class="text-sm text-gray-400 font-medium">Perbarui detail identitas dan mapping lokasi:
                                <span class="font-semibold text-red-600">{{ $location->name }}</span>
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('she.locations.update', $location) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lokasi</label>
                            <input type="text" name="name" id="name"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                value="{{ old('name', $location->name) }}" required autofocus
                                placeholder="Masukkan nama lokasi">
                            @error('name')
                                <p class="text-red-500 text-xs mt-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Lokasi</label>
                                <div
                                    class="mt-1 flex items-center px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 sm:text-sm cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Area
                                </div>
                                <input type="hidden" name="type" value="Area">
                            </div>

                            <div>
                                <label for="map_id" class="block text-sm font-semibold text-gray-700 mb-1">Induk
                                    Peta/Gedung</label>
                                <select name="map_id" id="map_id"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                    required>
                                    <option value="">-- Pilih Peta/Gedung --</option>
                                    @foreach ($maps as $map)
                                        <option value="{{ $map->id }}" @selected(old('map_id', $location->map_id) == $map->id)>
                                            {{ $map->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('map_id')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="location_id_string" class="block text-sm font-semibold text-gray-700 mb-1">ID
                                Lokasi (Unik)</label>
                            <input type="text" name="location_id_string" id="location_id_string"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm transition duration-150"
                                value="{{ old('location_id_string', $location->location_id_string) }}" required>

                            <div class="mt-2 bg-red-50 border-l-4 border-red-400 p-3">
                                <p class="text-xs text-red-700 leading-relaxed">
                                    <strong>Saran Format:</strong> <code
                                        class="bg-red-100 px-1 rounded text-red-800">NAMA_LOKASI</code>_<code
                                        class="bg-red-100 px-1 rounded text-red-800">KODE_GEDUNG</code>
                                    <br>Contoh: <span class="italic text-red-600 font-bold uppercase">KANTIN_GA</span>.
                                    <br>Penamaan yang konsisten memudahkan pencarian dan identifikasi area dalam sistem
                                    Hazard Report.
                                </p>
                            </div>

                            @error('location_id_string')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PIC & Leader Assignment -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100 mt-6">
                            <!-- PIC (Person in Charge) -->
                            <div>
                                <label for="pic_id" class="block text-sm font-semibold text-gray-700 mb-1">
                                    PIC (Penanggung Jawab Area)
                                </label>
                                <select name="pic_id" id="pic_id" class="tom-select-search">
                                    <option value="">-- Pilih PIC --</option>
                                    @foreach ($users ?? [] as $user)
                                        <option value="{{ $user->id }}" @selected(old('pic_id', $location->pic_id) == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1">PIC akan menerima notifikasi dan tugas tindak
                                    lanjut hazard di area ini.</p>
                                @error('pic_id')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Leader -->
                            <div>
                                <label for="leader_id" class="block text-sm font-semibold text-gray-700 mb-1">
                                    Leader (Atasan PIC)
                                </label>
                                <select name="leader_id" id="leader_id" class="tom-select-search">
                                    <option value="">-- Pilih Leader --</option>
                                    @foreach ($users ?? [] as $user)
                                        <option value="{{ $user->id }}" @selected(old('leader_id', $location->leader_id) == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1">Leader akan menerima notifikasi sebagai
                                    CC/supervisor.</p>
                                @error('leader_id')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                            <a href="{{ route('she.locations.index') }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-5 py-2.5 bg-red-600 border border-transparent rounded-lg font-bold text-sm text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <style>
            /* Custom Tom Select Styling - Premium Look */
            .ts-wrapper {
                border-radius: 0.75rem !important;
            }

            .ts-control {
                background: linear-gradient(to bottom, #ffffff, #f9fafb) !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 0.75rem !important;
                padding: 0.625rem 1rem !important;
                font-size: 0.875rem !important;
                font-weight: 500 !important;
                min-height: 44px !important;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
                transition: all 0.2s ease !important;
            }

            .ts-control:hover {
                border-color: #ef4444 !important;
            }

            .ts-wrapper.focus .ts-control {
                border-color: #ef4444 !important;
                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
                background: #ffffff !important;
            }

            .ts-control input {
                font-size: 0.875rem !important;
            }

            .ts-control .item {
                background: transparent !important;
                color: #111827 !important;
            }

            .ts-dropdown {
                border-radius: 0.75rem !important;
                border: 2px solid #e5e7eb !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15) !important;
                margin-top: 4px !important;
                overflow: hidden !important;
            }

            .ts-dropdown .ts-dropdown-content {
                max-height: 250px !important;
                padding: 0.5rem !important;
            }

            .ts-dropdown .option {
                padding: 0.625rem 1rem !important;
                border-radius: 0.5rem !important;
                margin-bottom: 2px !important;
                font-size: 0.875rem !important;
                transition: all 0.15s ease !important;
            }

            .ts-dropdown .option:hover {
                background-color: #fef2f2 !important;
            }

            .ts-dropdown .option.active {
                background: linear-gradient(135deg, #ef4444, #dc2626) !important;
                color: #ffffff !important;
                font-weight: 600 !important;
            }

            .ts-dropdown .no-results {
                padding: 1rem !important;
                text-align: center !important;
                color: #6b7280 !important;
            }

            .ts-control input::placeholder {
                color: #9ca3af !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.tom-select-search').forEach(function (el) {
                    new TomSelect(el, {
                        allowEmptyOption: true,
                        placeholder: el.options[0]?.text || 'Pilih...',
                        searchField: ['text'],
                        sortField: { field: 'text', direction: 'asc' },
                        render: {
                            no_results: function (data, escape) {
                                return '<div class="no-results">Tidak ditemukan: <strong>' + escape(data.input) + '</strong></div>';
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>