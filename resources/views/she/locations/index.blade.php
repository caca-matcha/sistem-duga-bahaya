<x-app-layout>
    @section('page-title', '')
    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight capitalize leading-none">
                            Master Lokasi</h2>
                        <p class="text-gray-400 font-bold mt-1.5 tracking-tight uppercase tracking-widest text-[12px]">
                            Daftar area dan titik pantau bahaya di seluruh fasilitas.</p>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-[96%] mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Dynamic Controls & Filters -->

            <!-- Main Content Card -->
            <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
                <div
                    class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-bold text-gray-800">Daftar Lokasi</h3>
                        <span id="location-count"
                            class="px-3 py-1 text-xs font-bold text-red-800 bg-red-100 rounded-full">{{ $locations->count() }}</span>
                    </div>

                    <!-- Actions Container -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                        <!-- Building Filter -->
                        <div class="relative flex-1 sm:flex-initial sm:w-48">
                            <select id="location-map-filter"
                                class="w-full pl-4 pr-10 py-2 bg-gray-50 border-none rounded-xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 transition-all appearance-none cursor-pointer">
                                <option value="">🏢 Semua Gedung</option>
                                @foreach($maps as $map)
                                    <option value="{{ $map->id }}">{{ $map->name }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Search Bar -->
                        <div class="relative flex-1 sm:flex-initial sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" id="location-search-input" name="search" placeholder="Cari lokasi..."
                                class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-500 transition-all">
                        </div>

                        <!-- Import/Export/Guide Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <button onclick="document.getElementById('guideModal').classList.remove('hidden')"
                                class="inline-flex items-center px-3 md:px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl font-bold text-sm text-gray-700 hover:bg-gray-200 focus:ring-4 focus:ring-gray-100 transition-all duration-200"
                                title="Panduan Penggunaan">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="hidden sm:inline">Panduan</span>
                            </button>
                            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                                class="inline-flex items-center px-3 md:px-4 py-2.5 bg-green-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-green-200 hover:bg-green-700 focus:ring-4 focus:ring-green-100 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="hidden sm:inline">Import</span>
                            </button>
                            <a href="{{ route('she.locations.export') }}"
                                class="inline-flex items-center px-3 md:px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-blue-200 hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                </svg>
                                <span class="hidden sm:inline">Export</span>
                            </a>
                            <a href="{{ route('she.locations.create') }}"
                                class="inline-flex items-center px-3 md:px-4 py-2.5 bg-red-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-red-200 hover:bg-red-700 focus:ring-4 focus:ring-red-100 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="hidden sm:inline">Tambah</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th
                                    class="px-3 py-2.5 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                    NAMA LOKASI</th>
                                <th
                                    class="px-3 py-2.5 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 w-1">
                                    TIPE</th>
                                <th
                                    class="px-3 py-2.5 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                    ID / KODE</th>
                                <th
                                    class="px-3 py-2.5 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 whitespace-nowrap">
                                    MAPPING</th>
                                <th
                                    class="px-3 py-2.5 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 w-1">
                                    DIBUAT OLEH</th>
                                <th
                                    class="px-3 py-2.5 text-right text-xs font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                    AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="location-table-body" class="divide-y divide-gray-100">
                            @include('she.locations._table_rows')
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
    <div id="importModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Import Data Lokasi</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('she.locations.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <!-- Maintenance Note -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-900 leading-none mb-1.5">Tips Tambah Data</p>
                                <p class="text-xs text-blue-700 leading-relaxed">Gunakan file hasil <b>Export</b> untuk
                                    menambah data baru di baris paling bawah agar tidak perlu input ulang data lama.</p>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih File Excel (dari Export)</label>
                        <input type="file" name="file" accept=".xlsx,.xls" required
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 p-2.5">
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition">
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Guide Modal -->
    <div id="guideModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Panduan Import & Export Data</h3>
                </div>
                <button onclick="document.getElementById('guideModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[70vh] space-y-8">
                <!-- Step Content -->
                <div class="space-y-8">
                    <!-- Step 1: Filter & Cari -->
                    <div class="relative pl-12">
                        <div
                            class="absolute left-0 top-0 w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center font-bold shadow-lg shadow-red-100">
                            1</div>
                        <h4 class="font-bold text-gray-900 mb-2">Gunakan Filter & Pencarian</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Cari data lebih cepat dengan memilih <b>Gedung</b> pada dropdown filter atau ketik nama
                            lokasi di kotak pencarian.
                        </p>
                    </div>

                    <!-- Step 2: Export & Edit -->
                    <div class="relative pl-12">
                        <div
                            class="absolute left-0 top-0 w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center font-bold shadow-lg shadow-red-100">
                            2</div>
                        <h4 class="font-bold text-gray-900 mb-2">Export & Kolom "ID" Baru</h4>
                        <p class="text-sm text-gray-600 leading-relaxed mb-3">
                            Klik <span class="text-blue-600 font-bold uppercase text-xs">Export</span> untuk mengunduh
                            Excel. Anda akan melihat kolom <b>ID</b> (berwarna abu-abu).
                        </p>
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-800 italic">
                            <b>💡 Tips Aman:</b> Kolom <b>ID</b> adalah "jangkar". Selama ID ini tidak diubah, Anda
                            bebas mengubah <i>Nama Lokasi</i> atau <i>Location ID String</i> (misal: GA ke GB) tanpa
                            takut data duplikat.
                        </div>
                    </div>

                    <!-- Step 3: Import -->
                    <div class="relative pl-12">
                        <div
                            class="absolute left-0 top-0 w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center font-bold shadow-lg shadow-red-100">
                            3</div>
                        <h4 class="font-bold text-gray-900 mb-2">Aturan Tambah & Hapus</h4>
                        <ul class="text-sm text-gray-600 space-y-2 list-disc ml-4">
                            <li><b>Tambah Baru</b>: Kosongkan kolom <b>ID</b> jika ingin menambah lokasi baru via Excel.
                            </li>
                            <li><b>Hapus Permanent</b>: Menghapus baris di Excel <b>TIDAK</b> menghapus data di sistem.
                                Gunakan tombol
                                <span
                                    class="inline-flex items-center justify-center p-1.5 bg-red-50 text-red-600 rounded-lg border border-red-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </span> di tabel web.
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Column Explanation -->
                <div class="pt-6 border-t border-gray-100">
                    <h4 class="text-xs font-extrabold text-gray-800 uppercase tracking-widest mb-4">Penjelasan Kolom
                        Excel</h4>
                    <div class="overflow-hidden border border-gray-200 rounded-xl">
                        <table class="min-w-full text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-gray-600 border-b border-r">Kolom</th>
                                    <th class="px-4 py-3 text-left font-bold text-gray-600 border-b">Aturan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-800 border-r bg-gray-50/50">ID</td>
                                    <td class="px-4 py-3 text-gray-500">JANGAN DIUBAH untuk data lama. Kosongkan hanya
                                        untuk data BARU.</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-800 border-r">Nama Lokasi</td>
                                    <td class="px-4 py-3 text-gray-600">Nama yang tampil (Contoh: Warehouse A).</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-800 border-r">Location ID String</td>
                                    <td class="px-4 py-3 text-gray-600 italic">Kode unik (Contoh: WH_A).</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-800 border-r">Nama Map (Gedung)</td>
                                    <td class="px-4 py-3 text-gray-600">Sesuai nama Gedung/Map di sistem.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="button" onclick="document.getElementById('guideModal').classList.add('hidden')"
                    class="px-5 py-2.5 bg-gray-800 text-white rounded-xl font-bold hover:bg-gray-900 transition shadow-lg shadow-gray-200">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('location-search-input');
                const mapFilter = document.getElementById('location-map-filter');
                const tableBody = document.getElementById('location-table-body');
                const locationCount = document.getElementById('location-count');
                let debounceTimer;

                // Initialize Sortable
                let sortable = new Sortable(tableBody, {
                    handle: '.drag-handle',
                    animation: 150,
                    draggable: 'tr:not(.no-drag)',
                    filter: '.no-drag',
                    ghostClass: 'bg-red-50',
                    onEnd: function () {
                        updateServerOrder();
                    }
                });

                function updateServerOrder() {
                    const rows = Array.from(tableBody.querySelectorAll('tr[data-id]'));
                    const locations = rows.map((row, index) => ({
                        id: row.dataset.id,
                        display_order: index + 1
                    }));

                    axios.patch('{{ route('she.api.locations.reorder') }}', { locations })
                        .catch(error => {
                            console.error('Error updating order:', error);
                            alert('Gagal menyimpan urutan lokasi.');
                        });
                }

                function fetchLocations() {
                    const query = searchInput.value;
                    const mapId = mapFilter.value;
                    const url = `{{ route('she.locations.index') }}?search=${encodeURIComponent(query)}&map_id=${mapId}`;

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
                            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-10 text-red-500 font-medium font-mono text-xs">⚠️ Gagal memuat data. Silakan coba lagi.</td></tr>';
                        });
                }

                searchInput.addEventListener('keyup', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(fetchLocations, 300); // 300ms delay
                });

                mapFilter.addEventListener('change', function () {
                    fetchLocations();
                });
            });
        </script>
    @endpush

</x-app-layout>