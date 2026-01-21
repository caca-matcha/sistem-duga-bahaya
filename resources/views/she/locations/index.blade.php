<x-app-layout>
    @section('page-title', '')
    <x-slot name="header"><div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
<div>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ __('Master Lokasi') }}
            </h2><p class="text-sm text-gray-500 mt-1">Kelola hierarki dan data lokasi operasional pabrik.</p>
</div>
</x-slot>

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        

        <!-- Main Content Card -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Lokasi</h3>
                    <span id="location-count" class="px-3 py-1 text-xs font-bold text-red-800 bg-red-100 rounded-full">{{ $locations->count() }}</span>
                </div>
                
                <!-- Actions Container -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                    <!-- Search Bar -->
                    <div class="relative flex-1 sm:flex-initial sm:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" id="location-search-input" name="search" placeholder="Cari lokasi..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-500 transition-all">
                    </div>
                    
                    <!-- Import/Export Buttons -->
                    <div class="flex gap-2">
                        <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center px-3 md:px-4 py-2.5 bg-green-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-green-200 hover:bg-green-700 focus:ring-4 focus:ring-green-100 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="hidden sm:inline">Import</span>
                        </button>
                        <a href="{{ route('she.locations.export') }}" class="inline-flex items-center px-3 md:px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-blue-200 hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                            <span class="hidden sm:inline">Export</span>
                        </a>
                        <a href="{{ route('she.locations.create') }}" class="inline-flex items-center px-3 md:px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-red-200 hover:bg-red-700 focus:ring-4 focus:ring-red-100 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="hidden sm:inline">Tambah</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Nama Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">ID / Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Tipe</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mapping</th>
               
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Dibuat Oleh</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="location-table-body" class="divide-y divide-gray-100">
                        @forelse ($locations as $location)
                            <tr class="hover:bg-red-50/20 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 font-bold text-xs">
                                            {{ substr($location->name, 0, 2) }}
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">{{ $location->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-md font-mono">{{ $location->location_id_string }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'Gedung' => 'bg-purple-100 text-purple-700',
                                            'Area' => 'bg-red-100 text-red-700',
                                            'Room' => 'bg-green-100 text-green-700'
                                        ];
                                        $colorClass = $typeColors[$location->type] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $colorClass }}">
                                        {{ $location->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                        {{ $location->map->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] text-gray-500 border border-white">
                                            {{ substr($location->creator->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-xs text-gray-600">{{ $location->creator->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center space-x-1 transition-opacity"> 
                                        <a href="{{ route('she.locations.edit', $location) }}" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Edit Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('she.locations.destroy', $location) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <p class="text-gray-500 font-medium">Belum ada data lokasi yang tersedia.</p>
                                        <a href="{{ route('she.locations.create') }}" class="text-red-600 text-sm font-bold mt-2 hover:underline">Mulai tambahkan lokasi baru</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Simple Pagination Styling Proxy (Jika ada) -->
            @if(method_exists($locations, 'links'))
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                    {{ $locations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Import Data Lokasi</h3>
            <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('she.locations.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <!-- Download Template -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-blue-900 mb-2">Download Template Terlebih Dahulu</p>
                            <p class="text-xs text-blue-700 mb-3">Template sudah dilengkapi dengan instruksi dan contoh data yang benar.</p>
                            <a href="{{ route('she.locations.template') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Download Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih File Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 p-2.5">
                    <p class="text-xs text-gray-500 mt-2">Format: .xlsx atau .xls (Maksimal 2MB)</p>
                </div>

                <!-- Warning -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-yellow-900">Perhatian!</p>
                            <p class="text-xs text-yellow-700 mt-1">Pastikan data sudah sesuai dengan template. Data yang tidak valid akan dilewati.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition">
                    Import Data
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('location-search-input');
        const tableBody = document.getElementById('location-table-body');
        const locationCount = document.getElementById('location-count');
        let debounceTimer;

        function fetchLocations() {
            const query = searchInput.value;
            const url = `{{ route('she.locations.index') }}?search=${encodeURIComponent(query)}`;

            axios.get(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                tableBody.innerHTML = response.data.html;
                locationCount.textContent = response.data.total;
                // Update URL for bookmarking, etc.
                window.history.pushState({ path: url }, '', url);
            })
            .catch(error => {
                console.error('Error fetching locations:', error);
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-10">Gagal memuat data. Silakan coba lagi.</td></tr>';
            });
        }

        searchInput.addEventListener('keyup', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchLocations, 300); // 300ms delay
        });
    });
</script>
@endpush

</x-app-layout>