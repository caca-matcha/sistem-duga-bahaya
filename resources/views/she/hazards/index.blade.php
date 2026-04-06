<x-app-layout>
    @section('page-title', '')

    <style>
        mark.highlight {
            background-color: #fef08a;
            color: #854d0e;
            padding: 0 1px;
            border-radius: 2px;
            font-weight: 700;
        }
    </style>

    <!-- Header dengan Glassmorphism Effect -->
    <!-- Header Standardized -->
    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-6 h-6 text-red-600" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12 2.2c-.6 0-1.1.3-1.4.8L1.4 18.5c-.3.5-.3 1.1 0 1.6.3.5.8.8 1.4.8h18.3c.6 0 1.1-.3 1.4-.8.3-.5.3-1.1 0-1.6L13.4 3c-.3-.5-.8-.8-1.4-.8zM11 8h2v5h-2V8zm1 9c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight capitalize leading-none">
                            SHE Hazard Report</h2>
                        <p class="text-gray-400 font-medium mt-1.5 tracking-tight uppercase text-[12px]">
                            Monitoring keselamatan kerja & mitigasi risiko area.</p>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8" x-data="{ 
                    activeTab: new URLSearchParams(window.location.search).get('tab') || (window.location.hash ? window.location.hash.replace('#', '') : 'baru'),
                    selectionMode: false,
                    selectedHazards: [],

                    init() {
                        const form = document.getElementById('combinedFilterForm');
                        const searchInput = document.getElementById('search_input');
                        const monthSelect = form.querySelector('select[name=\'month\']');
                        const yearSelect = form.querySelector('select[name=\'year\']');
                        const contentArea = document.getElementById('hazard-content-area');

                        let debounceTimer;
                        
                        searchInput.addEventListener('keyup', () => {
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(() => this.fetchResults(), 500);
                        });

                        monthSelect.addEventListener('change', () => this.fetchResults());
                        yearSelect.addEventListener('change', () => this.fetchResults());
                        
                        // Delegated listener for pagination clicks
                        contentArea.addEventListener('click', (event) => {
                            const target = event.target.closest('nav[role=\'navigation\'] a');
                            if (target) {
                                event.preventDefault();
                                this.fetchResults(target.href);
                            }
                        });
                    },

                    fetchResults(url) {
                        const form = document.getElementById('combinedFilterForm');
                        const fetchUrl = url || `{{ route('she.hazards.index') }}?${new URLSearchParams(new FormData(form)).toString()}`;
                        
                        axios.get(fetchUrl, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(response => {
                            document.getElementById('tbody-baru').innerHTML = response.data.menunggu_validasi_html;
                            document.getElementById('pagination-baru').innerHTML = response.data.menunggu_validasi_pagination;
                            
                            document.getElementById('tbody-diproses').innerHTML = response.data.diproses_html;
                            document.getElementById('pagination-diproses').innerHTML = response.data.diproses_pagination;

                            document.getElementById('tbody-selesai').innerHTML = response.data.selesai_html;
                            document.getElementById('pagination-selesai').innerHTML = response.data.selesai_pagination;

                            document.getElementById('tbody-semua').innerHTML = response.data.semua_html;
                            document.getElementById('pagination-semua').innerHTML = response.data.semua_pagination;

                            const updateBadge = (id, count) => {
                                let badge = document.getElementById(id);
                                if (badge) {
                                    if (count > 0) {
                                        badge.innerText = count;
                                        badge.style.display = 'inline-block';
                                    } else {
                                        badge.style.display = 'none';
                                    }
                                }
                            };

                            updateBadge('badge-baru', response.data.count_menunggu_validasi);
                            updateBadge('badge-diproses', response.data.count_diproses);
                            updateBadge('badge-selesai', response.data.count_selesai);
                            updateBadge('badge-semua', response.data.count_semua);

                            window.history.pushState({}, '', fetchUrl);
                            this.selectedHazards = []; 
                            
                            // Scroll back to table top if it's a pagination click
                            if (url) {
                                document.getElementById('hazard-content-area').scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching filtered results:', error);
                        });
                    },

                    setTab(tab) {
                        this.activeTab = tab;
                        document.getElementById('activeTabInput').value = tab;
                        this.selectionMode = false;
                        this.selectedHazards = []; 
                        this.fetchResults();
                    },

                    toggleSelectAll(ids) {
                        const allSelectedOnPage = ids.every(id => this.selectedHazards.includes(id));
                        if (allSelectedOnPage) {
                            this.selectedHazards = this.selectedHazards.filter(id => !ids.includes(id));
                        } else {
                            ids.forEach(id => {
                                if (!this.selectedHazards.includes(id)) {
                                    this.selectedHazards.push(id);
                                }
                            });
                        }
                    },

                    enterSelectionMode() {
                        this.selectionMode = true;
                        this.selectedHazards = []; 
                    },

                    exitSelectionMode() {
                        this.selectionMode = false;
                        this.selectedHazards = []; 
                    },

                    get exportUrl() {
                        const baseUrl = '{{ route('she.hazards.exportExcelBulk') }}';
                        const params = new URLSearchParams();
                        this.selectedHazards.forEach(id => params.append('ids[]', id));
                        return `${baseUrl}?${params.toString()}`;
                    }
                 }" x-init="init()">
            <!-- Filter Section: Compact & Interactive (Sticky at top) -->
            <div class="sticky top-[64px] lg:top-[72px] z-30 bg-gray-50 pt-2 pb-2 transition-all duration-300">
                <form id="combinedFilterForm" action="{{ route('she.hazards.index') }}" method="GET">
                    <input type="hidden" name="tab" id="activeTabInput" x-model="activeTab">
                    <div
                        class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-stretch md:items-center gap-2">
                        <!-- Search Box -->
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search_input" value="{{ request('search') }}"
                                placeholder="Cari ID, Pelapor, atau Deskripsi..."
                                class="block w-full pl-10 pr-4 py-2 bg-gray-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl text-xs transition-all">
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Date Filters -->
                            <div class="flex items-center gap-2 bg-gray-50 p-1 rounded-xl border border-gray-100">
                                <select name="month"
                                    class="bg-transparent border-none text-xs font-medium focus:ring-0 cursor-pointer rounded-lg hover:bg-white transition-all px-2 pr-7 py-1.5">
                                    <option value="">Bulan</option>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}" @selected(request('month') == $month)>
                                            {{ \Carbon\Carbon::create()->month($month)->locale('id')->monthName }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="h-4 w-px bg-gray-300"></div>
                                <select name="year"
                                    class="bg-transparent border-none text-xs font-medium focus:ring-0 cursor-pointer rounded-lg hover:bg-white transition-all px-2 pr-7 py-1.5">
                                    <option value="">Tahun</option>
                                    @foreach (range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year - 2) as $year)
                                        <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2 ml-auto">
                                @if (request('month') || request('search'))
                                    <a href="{{ route('she.hazards.index') }}"
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                        title="Reset Filter">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                @endif

                                <button type="button"
                                    @click="selectionMode ? exitSelectionMode() : enterSelectionMode()"
                                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent rounded-xl font-bold text-xs text-white shadow-md transition-all duration-200"
                                    :class="selectionMode ? 'bg-gray-700 hover:bg-gray-800' : 'bg-green-600 hover:bg-green-700'">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        x-show="!selectionMode">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        x-show="selectionMode">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span x-show="!selectionMode">Ekspor</span>
                                    <span x-show="selectionMode">Batal</span>
                                </button>
                            </div>
                        </div>

                        <!-- Status Filter (Only for 'Semua' tab) - Moved below on mobile -->
                        <div x-show="activeTab === 'semua'" x-cloak
                            x-data="{ currentStatus: '{{ request('status', '') }}' }"
                            class="flex items-center gap-1 bg-gray-50 p-1 rounded-xl border border-gray-100 overflow-x-auto scrollbar-hide">
                            <input type="hidden" name="status" x-model="currentStatus">
                            <button type="button" @click="currentStatus = ''; fetchResults()"
                                :class="currentStatus === '' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-200/50'"
                                class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all whitespace-nowrap">Semua</button>
                            <button type="button" @click="currentStatus = 'menunggu'; fetchResults()"
                                :class="currentStatus === 'menunggu' ? 'bg-amber-500 text-white' : 'text-gray-500'"
                                class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all whitespace-nowrap">Menunggu</button>
                            <button type="button" @click="currentStatus = 'diproses'; fetchResults()"
                                :class="currentStatus === 'diproses' ? 'bg-blue-600 text-white' : 'text-gray-500'"
                                class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all whitespace-nowrap">Diproses</button>
                            <button type="button" @click="currentStatus = 'selesai'; fetchResults()"
                                :class="currentStatus === 'selesai' ? 'bg-emerald-600 text-white' : 'text-gray-500'"
                                class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all whitespace-nowrap">Selesai</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- MODERN NAVIGATION TABS (Sticky below Filters) -->
            <div class="sticky top-[104px] lg:top-[138px] z-20 bg-gray-50 pb-4 transition-all duration-300">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto scrollbar-hide">
                    <nav class="-mb-px flex space-x-6 sm:space-x-8 px-4 sm:px-6" aria-label="Tabs">
                        {{-- Tab Semua Laporan --}}
                        <button @click="setTab('semua')" :class="activeTab === 'semua' 
                            ? 'border-purple-500 text-purple-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-bold text-[10px] sm:text-xs uppercase tracking-tight transition-colors duration-200 whitespace-nowrap">
                            <svg :class="activeTab === 'semua' ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-500'"
                                class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Semua Laporan
                            <span id="badge-semua"
                                class="ml-2 bg-purple-100 text-purple-600 py-0.5 px-2.5 rounded-full text-[11px] font-black"
                                style="display: {{ $hazardsSemua->total() > 0 ? 'inline-block' : 'none' }}">
                                {{ $hazardsSemua->total() }}
                            </span>
                        </button>

                        {{-- Tab Baru --}}
                        <button @click="setTab('baru')" :class="activeTab === 'baru' 
                            ? 'border-indigo-500 text-indigo-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-bold text-[10px] sm:text-xs uppercase tracking-tight transition-colors duration-200 whitespace-nowrap">
                            <svg :class="activeTab === 'baru' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500'"
                                class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Laporan Baru
                            <span id="badge-baru"
                                class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2.5 rounded-full text-[11px] font-black"
                                style="display: {{ $hazardsMenungguValidasi->total() > 0 ? 'inline-block' : 'none' }}">
                                {{ $hazardsMenungguValidasi->total() }}
                            </span>
                        </button>

                        {{-- Tab Diproses --}}
                        <button @click="setTab('diproses')" :class="activeTab === 'diproses' 
                            ? 'border-blue-500 text-blue-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-bold text-[10px] sm:text-xs uppercase tracking-tight transition-colors duration-200 whitespace-nowrap">
                            <svg :class="activeTab === 'diproses' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'"
                                class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Sedang Diproses
                            <span id="badge-diproses" class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2 rounded-full text-[9px] font-black"
                                style="display: {{ $hazardsDiproses->total() > 0 ? 'inline-block' : 'none' }}">
                                {{ $hazardsDiproses->total() }}
                            </span>
                        </button>

                        {{-- Tab Selesai --}}
                        <button @click="setTab('selesai')" :class="activeTab === 'selesai' 
                            ? 'border-green-500 text-green-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-bold text-[10px] sm:text-xs uppercase tracking-tight transition-colors duration-200 whitespace-nowrap">
                            <svg :class="activeTab === 'selesai' ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500'"
                                class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat / Selesai
                            <span id="badge-selesai"
                                class="ml-2 bg-green-100 text-green-600 py-0.5 px-2.5 rounded-full text-[11px] font-black"
                                style="display: {{ $hazardsSelesai->total() > 0 ? 'inline-block' : 'none' }}">
                                {{ $hazardsSelesai->total() }}
                            </span>
                        </button>
                    </nav>
                </div>
            </div>

            {{-- Success Notification --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="relative z-10">
                <div id="hazard-content-area"
                    class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <!-- CONTENT AREA -->
                    <div class="p-6">

                        {{-- ================= TAB: SEMUA LAPORAN ================= --}}
                        <div x-show="activeTab === 'semua'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-800">Semua Laporan Masuk</h3>
                                <span class="text-xs text-gray-500">Menampilkan seluruh data</span>
                            </div>

                            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
                                <table class="min-w-full divide-y divide-gray-200 table-auto">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <template x-if="selectionMode">
                                                <th scope="col" class="p-4">
                                                    <input type="checkbox"
                                                        @click="toggleSelectAll({{ $hazardsSemua->pluck('id') }})"
                                                        :checked="selectedHazards.length === {{ $hazardsSemua->pluck('id')->count() }} && {{ $hazardsSemua->pluck('id')->count() }} > 0"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </th>
                                            </template>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID & Tanggal</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                NPK</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pelapor</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Deskripsi Bahaya</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Risiko</th>

                                        </tr>
                                    </thead>
                                    <tbody id="tbody-semua" class="bg-white divide-y divide-gray-200">
                                        @include('she.hazards._table_semua_rows', ['hazardsSemua' => $hazardsSemua])
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-semua">
                                {{ $hazardsSemua->fragment('hazard-content-area')->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                        {{-- ================= TAB: LAPORAN BARU ================= --}}
                        <div x-show="activeTab === 'baru'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-800">Menunggu Validasi SHE</h3>
                                <span class="text-xs text-gray-500">Perlu tindakan segera</span>
                            </div>


                            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
                                <table class="min-w-full divide-y divide-gray-200 table-auto">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <template x-if="selectionMode">
                                                <th scope="col" class="p-4">
                                                    <input type="checkbox"
                                                        @click="toggleSelectAll({{ $hazardsMenungguValidasi->pluck('id') }})"
                                                        :checked="selectedHazards.length === {{ $hazardsMenungguValidasi->pluck('id')->count() }} && {{ $hazardsMenungguValidasi->pluck('id')->count() }} > 0"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </th>
                                            </template>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID & Tanggal</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                NPK</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pelapor</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Deskripsi Singkat</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Risiko</th>

                                        </tr>
                                    </thead>
                                    <tbody id="tbody-baru" class="bg-white divide-y divide-gray-200">
                                        @include('she.hazards._table_menunggu_validasi_rows', ['hazardsMenungguValidasi' => $hazardsMenungguValidasi])
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-baru">
                                {{ $hazardsMenungguValidasi->fragment('hazard-content-area')->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                        {{-- ================= TAB: DIPROSES ================= --}}
                        <div x-show="activeTab === 'diproses'" x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-800">Sedang Dalam Penanganan</h3>
                            </div>

                            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
                                <table class="min-w-full divide-y divide-gray-200 table-auto">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <template x-if="selectionMode">
                                                <th scope="col" class="p-4">
                                                    <input type="checkbox"
                                                        @click="toggleSelectAll({{ $hazardsDiproses->pluck('id') }})"
                                                        :checked="selectedHazards.length === {{ $hazardsDiproses->pluck('id')->count() }} && {{ $hazardsDiproses->pluck('id')->count() }} > 0"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </th>
                                            </template>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID & Tanggal</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                NPK</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pelapor</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                PIC / Penanggung Jawab</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Deskripsi Bahaya</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Risiko</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-diproses" class="bg-white divide-y divide-gray-200">
                                        @include('she.hazards._table_diproses_rows', ['hazardsDiproses' => $hazardsDiproses])
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-diproses">
                                {{ $hazardsDiproses->fragment('hazard-content-area')->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                        {{-- ================= TAB: SELESAI / DITOLAK ================= --}}
                        <div x-show="activeTab === 'selesai'" x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-800">Riwayat Laporan Selesai</h3>
                            </div>

                            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-md">
                                <table class="min-w-full divide-y divide-gray-200 table-auto">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <template x-if="selectionMode">
                                                <th scope="col" class="p-4">
                                                    <input type="checkbox"
                                                        @click="toggleSelectAll({{ $hazardsSelesai->pluck('id') }})"
                                                        :checked="selectedHazards.length === {{ $hazardsSelesai->pluck('id')->count() }} && {{ $hazardsSelesai->pluck('id')->count() }} > 0"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </th>
                                            </template>
                                            <th
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID & Tanggal</th>
                                            <th
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                NPK</th>
                                            <th
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pelapor</th>
                                            <th
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Deskripsi Bahaya</th>
                                            <th
                                                class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Risiko (B/A)</th>
                                            <th
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Akhir</th>
                                            <th
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Waktu Penyelesaian</th>
                                            <th
                                                class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-selesai" class="bg-white divide-y divide-gray-200">
                                        @include('she.hazards._table_selesai_rows', ['hazardsSelesai' => $hazardsSelesai])
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-selesai">
                                {{ $hazardsSelesai->fragment('hazard-content-area')->links('vendor.pagination.custom') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

</x-app-layout>