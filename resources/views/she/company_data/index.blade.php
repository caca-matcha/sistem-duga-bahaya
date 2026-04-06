<x-app-layout>
    <x-slot name="header">
        <div class="relative py-2">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100/50">
                        <svg class="w-6 h-6 text-red-600" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight capitalize leading-none">
                            Data Perusahaan</h2>
                        <p class="text-gray-400 font-medium mt-1.5 tracking-tight uppercase text-[12px]">
                            Informasi identitas dan kontak unit bisnis.</p>
                    </div>
                </div>
            </div>
            <div
                class="absolute -bottom-4 left-0 w-32 h-1 bg-gradient-to-r from-red-600 to-red-400 rounded-full opacity-50">
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Status Messages -->
                @if(session('warning'))
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-700 font-bold">Peringatan</p>
                                <p class="text-sm text-amber-600 mt-0.5">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-rose-50 border-l-4 border-rose-400 p-4 rounded-r-xl shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-rose-700 font-bold">Error</p>
                                <p class="text-sm text-rose-600 mt-0.5">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($debug_mode) && $debug_mode)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-xl shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-blue-700 font-black uppercase tracking-widest">Mode Pengembangan</p>
                                <p class="text-sm text-blue-600 mt-0.5">Menampilkan data dummy untuk keperluan pratinjau antarmuka.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Company Cards Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @forelse($companies as $company)
                        <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500">
                            <div class="p-8">
                                <div class="flex items-start justify-between mb-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-200 group-hover:scale-110 transition-transform duration-500">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-black text-gray-900 leading-none mb-2">{{ $company['company_name'] ?? 'Perusahaan Tanpa Nama' }}</h3>
                                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-[0.15em] border border-red-100">
                                                {{ $company['company_code'] ?? 'KODE-MISSING' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Alamat</p>
                                                <p class="text-sm font-bold text-gray-600 leading-relaxed">{{ $company['address'] ?? '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Telepon</p>
                                                <p class="text-sm font-bold text-gray-600">{{ $company['phone'] ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email</p>
                                                <p class="text-sm font-bold text-gray-600">{{ $company['email'] ?? '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h18" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Website</p>
                                                <a href="http://{{ $company['website'] ?? '#' }}" target="_blank"
                                                    class="text-sm font-black text-red-600 hover:text-red-700 transition flex items-center gap-1 group/link">
                                                    {{ $company['website'] ?? '-' }}
                                                    <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-8 py-3 bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-t border-gray-100 flex items-center justify-between">
                                <span>Tersinkronisasi dengan Sistem Pusat</span>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                                    Aktif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white rounded-[32px] p-20 border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-6 font-black text-4xl">?</div>
                            <h3 class="text-xl font-black text-gray-900 mb-2">Tidak Ada Data</h3>
                            <p class="text-gray-500 max-w-sm">Data perusahaan belum tersedia atau belum dikonfigurasi melalui integrasi API.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>