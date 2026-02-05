<x-app-layout>
    @section('page-title', '')

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
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight capitalize leading-none">
                            SHE Hazard Report</h2>
                        <p class="text-gray-400 font-bold mt-1.5 tracking-tight uppercase tracking-widest text-[12px]">
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
        <div class="max-w-[96%] mx-auto sm:px-6 lg:px-8" x-data="{ 
                    activeTab: new URLSearchParams(window.location.search).get('tab') || (window.location.hash ? window.location.hash.replace('#', '') : 'baru'),
                    selectionMode: false,
                    selectedHazards: [],

                    init() {
                        const form = document.getElementById('combinedFilterForm');
                        const searchInput = document.getElementById('search_input');
                        const monthSelect = form.querySelector('select[name=\'month\']');
                        const yearSelect = form.querySelector('select[name=\'year\']');
                        const statusSelect = form.querySelector('select[name=\'status\']');
                        const contentArea = document.getElementById('hazard-content-area');

                        let debounceTimer;
                        
                        searchInput.addEventListener('keyup', () => {
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(() => this.fetchResults(), 500);
                        });

                        monthSelect.addEventListener('change', () => this.fetchResults());
                        yearSelect.addEventListener('change', () => this.fetchResults());
                        statusSelect.addEventListener('change', () => this.fetchResults());
                        
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

                            window.history.pushState({}, '', fetchUrl);
                            this.selectedHazards = []; 
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
            <div class="sticky top-[72px] z-30 bg-gray-50 pt-2 pb-2">
                <form id="combinedFilterForm" action="{{ route('she.hazards.index') }}" method="GET">
                    <input type="hidden" name="tab" id="activeTabInput" x-model="activeTab">
                    <div
                        class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center gap-2">
                        <!-- Search Box -->
                        <div class="relative flex-grow min-w-[300px]">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search_input" value="{{ request('search') }}"
                                placeholder="Cari ID, Pelapor, atau Deskripsi..."
                                class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl text-sm transition-all">
                        </div>

                        <!-- Date Filters -->
                        <div class="flex items-center gap-2 bg-gray-50 p-1 rounded-xl border border-gray-100">
                            <select name="month"
                                class="bg-transparent border-none text-sm font-medium focus:ring-0 cursor-pointer rounded-lg hover:bg-white transition-all px-3 pr-8 py-2">
                                <option value="">Semua Bulan</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" @selected(request('month') == $month)>
                                        {{ \Carbon\Carbon::create()->month($month)->locale('id')->monthName }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="h-4 w-px bg-gray-300"></div>
                            <select name="year"
                                class="bg-transparent border-none text-sm font-medium focus:ring-0 cursor-pointer rounded-lg hover:bg-white transition-all px-3 pr-8 py-2">
                                <option value="">Semua Tahun</option>
                                @foreach(range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year - 5) as $year)
                                    <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter (Clickable Badges - Only for 'Semua' tab) -->
                        <div x-show="activeTab === 'semua'" x-cloak
                            x-data="{ currentStatus: '{{ request('status', '') }}' }"
                            class="flex items-center gap-1 bg-gray-50 p-1 rounded-xl border border-gray-100">
                            <input type="hidden" name="status" x-model="currentStatus">

                            {{-- Semua --}}
                            <button type="button" @click="currentStatus = ''; fetchResults()"
                                :class="currentStatus === '' ? 'bg-indigo-600 text-white shadow-md ring-2 ring-indigo-100' : 'text-gray-500 hover:bg-gray-200/50'"
                                class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200">
                                Semua
                            </button>

                            {{-- Menunggu --}}
                            <button type="button" @click="currentStatus = 'menunggu'; fetchResults()"
                                :class="currentStatus === 'menunggu' ? 'bg-amber-500 text-white shadow-md ring-2 ring-amber-100 border-amber-500' : 'text-gray-500 hover:bg-gray-200/50'"
                                class="px-4 py-2 rounded-lg text-xs font-bold border border-transparent transition-all duration-200">
                                Menunggu
                            </button>

                            {{-- Diproses --}}
                            <button type="button" @click="currentStatus = 'diproses'; fetchResults()"
                                :class="currentStatus === 'diproses' ? 'bg-blue-600 text-white shadow-md ring-2 ring-blue-100 border-blue-600' : 'text-gray-500 hover:bg-gray-200/50'"
                                class="px-4 py-2 rounded-lg text-xs font-bold border border-transparent transition-all duration-200">
                                Diproses
                            </button>

                            {{-- Selesai --}}
                            <button type="button" @click="currentStatus = 'selesai'; fetchResults()"
                                :class="currentStatus === 'selesai' ? 'bg-emerald-600 text-white shadow-md ring-2 ring-emerald-100 border-emerald-600' : 'text-gray-500 hover:bg-gray-200/50'"
                                class="px-4 py-2 rounded-lg text-xs font-bold border border-transparent transition-all duration-200">
                                Selesai
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 ml-auto pr-1">
                            @if(request('month') || request('search'))
                                <a href="{{ route('she.hazards.index') }}"
                                    class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                    title="Reset Filter">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif

                            <a :href="exportUrl" x-show="selectionMode && selectedHazards.length > 0"
                                style="display: none;"
                                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Ekspor <span x-text="selectedHazards.length"></span> Laporan
                            </a>

                            <button type="button" @click="selectionMode ? exitSelectionMode() : enterSelectionMode()"
                                class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg transition-all duration-200"
                                :class="selectionMode ? 'bg-gray-700 hover:bg-gray-800 focus:ring-gray-300' : 'bg-green-600 hover:bg-green-700 focus:ring-green-300'">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    x-show="!selectionMode">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    x-show="selectionMode">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span x-show="!selectionMode">Ekspor Excel</span>
                                <span x-show="selectionMode">Batal</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- MODERN NAVIGATION TABS (Sticky below Filters) -->
            <div class="sticky top-[138px] z-20 bg-gray-50 pb-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 pt-2">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        {{-- Tab Semua Laporan --}}
                        <button @click="setTab('semua')" :class="activeTab === 'semua' 
                            ? 'border-purple-500 text-purple-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            <svg :class="activeTab === 'semua' ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-500'"
                                class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Semua Laporan
                        </button>

                        {{-- Tab Baru --}}
                        <button @click="setTab('baru')" :class="activeTab === 'baru' 
                            ? 'border-indigo-500 text-indigo-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            <svg :class="activeTab === 'baru' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500'"
                                class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Laporan Baru
                            @if($hazardsMenungguValidasi->count() > 0)
                                <span
                                    class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2.5 rounded-full text-xs font-bold md:inline-block hidden">
                                    {{ $hazardsMenungguValidasi->count() }}
                                </span>
                            @endif
                        </button>

                        {{-- Tab Diproses --}}
                        <button @click="setTab('diproses')" :class="activeTab === 'diproses' 
                            ? 'border-blue-500 text-blue-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            <svg :class="activeTab === 'diproses' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'"
                                class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Sedang Diproses
                            @if($hazardsDiproses->count() > 0)
                                <span
                                    class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs font-bold md:inline-block hidden">
                                    {{ $hazardsDiproses->count() }}
                                </span>
                            @endif
                        </button>

                        {{-- Tab Selesai --}}
                        <button @click="setTab('selesai')" :class="activeTab === 'selesai' 
                            ? 'border-green-500 text-green-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            <svg :class="activeTab === 'selesai' ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500'"
                                class="-ml-0.5 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat / Selesai
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
                                                Pelapor</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Risiko</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-semua" class="bg-white divide-y divide-gray-200">
                                        @include('she.hazards._table_semua_rows', ['hazardsSemua' => $hazardsSemua])
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-semua">
                                {{ $hazardsSemua->links('vendor.pagination.custom') }}
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
                                                Pelapor</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Deskripsi Singkat</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Risiko</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-baru" class="bg-white divide-y divide-gray-200">
                                        @forelse ($hazardsMenungguValidasi as $hazard)
                                            <tr class="hover:bg-gray-50 transition-colors"
                                                :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
                                                <template x-if="selectionMode">
                                                    <td class="p-4">
                                                        <input type="checkbox" x-model="selectedHazards"
                                                            value="{{ $hazard->id }}"
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    </td>
                                                </template>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-indigo-600">#{{ $hazard->id }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $hazard->tgl_observasi->format('d M Y') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $hazard->nama }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $hazard->dept }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900 line-clamp-2 max-w-xs">
                                                        {{ $hazard->deskripsi_bahaya }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                                                                                {{ $hazard->risk_score >= 15 ? 'bg-red-100 text-red-800' : ($hazard->risk_score >= 8 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                        {{ $hazard->risk_score }}
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $hazard->kategori_resiko }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('she.hazards.show', $hazard) }}"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                                        Review
                                                        <svg class="ml-1.5 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 italic"
                                                    x-bind:colspan="selectionMode ? 7 : 6">
                                                    Tidak ada laporan baru yang menunggu validasi.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-baru">
                                {{ $hazardsMenungguValidasi->links('vendor.pagination.custom') }}
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
                                                Pelapor</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                PIC / Penanggung Jawab</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Risiko</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-diproses" class="bg-white divide-y divide-gray-200">
                                        @forelse ($hazardsDiproses as $hazard)
                                            <tr class="hover:bg-gray-50"
                                                :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
                                                <template x-if="selectionMode">
                                                    <td class="p-4">
                                                        <input type="checkbox" x-model="selectedHazards"
                                                            value="{{ $hazard->id }}"
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    </td>
                                                </template>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-500">#{{ $hazard->id }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $hazard->tgl_observasi->format('d M Y') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $hazard->nama }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                                            {{ substr($hazard->ditanganiOleh?->name ?? '?', 0, 2) }}
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $hazard->ditanganiOleh?->name ?? 'Belum ditentukan' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">SHE Team</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <svg class="mr-1.5 h-2 w-2 text-blue-400" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Diproses
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="text-sm text-gray-900 font-bold">
                                                        {{ $hazard->risk_score }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('she.hazards.show', $hazard) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 font-semibold transition-colors">Lihat
                                                        Detail</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="px-6 py-10 text-center text-sm text-gray-500 italic"
                                                    x-bind:colspan="selectionMode ? 7 : 6">
                                                    Tidak ada laporan yang sedang diproses.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-diproses">
                                {{ $hazardsDiproses->links('vendor.pagination.custom') }}
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
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID & Tanggal</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pelapor</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Akhir</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Waktu Penyelesaian</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-selesai" class="bg-white divide-y divide-gray-200">
                                        @forelse ($hazardsSelesai as $hazard)
                                            <tr class="hover:bg-gray-50"
                                                :class="{'bg-indigo-50': selectedHazards.includes({{ $hazard->id }})}">
                                                <template x-if="selectionMode">
                                                    <td class="p-4">
                                                        <input type="checkbox" x-model="selectedHazards"
                                                            value="{{ $hazard->id }}"
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    </td>
                                                </template>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-500">#{{ $hazard->id }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $hazard->tgl_observasi->format('d M Y') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $hazard->nama }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($hazard->status == 'selesai')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                            <svg class="mr-1.5 h-3 w-3 text-green-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                            <svg class="mr-1.5 h-3 w-3 text-red-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Ditolak
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $hazard->ditangani_pada ? \Carbon\Carbon::parse($hazard->ditangani_pada)->format('d M Y') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <a href="{{ route('she.hazards.show', $hazard) }}"
                                                            class="text-gray-400 hover:text-indigo-600 transition-colors"
                                                            title="Lihat Detail">
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('she.hazards.destroy', $hazard) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini secara permanen?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-gray-400 hover:text-red-600 transition-colors"
                                                                title="Hapus Laporan">
                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="px-6 py-10 text-center text-sm text-gray-500 italic"
                                                    x-bind:colspan="selectionMode ? 6 : 5">
                                                    Belum ada riwayat laporan selesai.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div id="pagination-selesai">
                                {{ $hazardsSelesai->links('vendor.pagination.custom') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

</x-app-layout>